<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\scanner\Persist;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\AbstractStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ExpireOption;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Helper functionality for common storage operations for a given consumer.
 * @internal
 */
class StorageHelper
{
    use UtilsProvider;
    const TABLE_NAME = 'template';
    const TRANSIENT_CACHE_KEY = RCB_OPT_PREFIX . '-%s-templates-%s';
    const TRANSIENT_CACHE_EXPIRE = 24 * 60 * 60;
    const TYPE_SERVICE = 'service';
    const TYPE_BLOCKER = 'blocker';
    private $storage;
    /**
     * Cache of templates as they can be time-consuming to generate.
     * So, they are recalculated each x hours.
     *
     * @var ExpireOption
     */
    private $expireOption;
    /**
     * C'tor.
     *
     * @param AbstractStorage $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    /**
     * Retrieve all templates (`field=null`) or by criteria.
     *
     * @param string $field
     * @param mixed $value
     * @param string $middlewareRoutine Can be `before` or `after`
     * @param boolean|string $forceInvalidate
     * @return AbstractTemplate[]|false
     */
    public function retrieveBy($field = null, $value = null, $middlewareRoutine = 'after', $forceInvalidate = \false)
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME);
        $type = $this->getType();
        $context = $this->getContext();
        $middlewareRoutine = \in_array($middlewareRoutine, ['before', 'after'], \true) ? $middlewareRoutine : 'after';
        $where = [];
        $joins = [];
        $where[] = $wpdb->prepare('t.type = %s', $type);
        $where[] = $wpdb->prepare('t.context = %s', $context);
        $where['is_outdated'] = 't.is_outdated = 0';
        if ($field === 'identifier') {
            if (\is_array($value)) {
                $where[] = \count($value) > 0 ? \sprintf('t.identifier IN (%s)', \join(',', \array_map(function ($identifier) use($wpdb) {
                    return $wpdb->prepare('%s', $identifier);
                }, $value))) : '1 = 0';
            } else {
                $where[] = $wpdb->prepare('t.identifier = %s', $value);
            }
        } elseif ($field === 'scanned' && $value) {
            $table_name_scan = $this->getTableName(Persist::TABLE_NAME);
            $where[] = $wpdb->prepare('t.is_disabled = %d', 0);
            // phpcs:disable WordPress.DB
            $where[] = $wpdb->prepare("((t.type = %s AND EXISTS(SELECT 1 FROM {$table_name_scan} WHERE preset = t.identifier)) OR t.is_recommended = 1)", self::TYPE_BLOCKER);
            // phpcs:enable WordPress.DB
        } elseif ($field === 'recommended' && $value) {
            $where[] = $wpdb->prepare('t.is_hidden = %d', 0);
            $where[] = $wpdb->prepare('t.is_disabled = %d', 0);
            $where[] = $wpdb->prepare('t.is_recommended = %d', $value);
        } elseif ($field === 'versions' && \is_array($value) && \count($value) > 0) {
            $identifier = $value[0];
            $versions = \array_slice($value, 1);
            unset($where['is_outdated']);
            $where[] = $wpdb->prepare('t.identifier = %s', $identifier);
            $where[] = \sprintf('t.version IN (%s)', \join(',', \array_map(function ($version) use($wpdb) {
                return $wpdb->prepare('%d', $version);
            }, $versions)));
        } elseif (\is_string($field)) {
            return [];
        }
        // phpcs:disable WordPress.DB
        $result = $wpdb->get_results(\sprintf("SELECT t2.versions, %s AS response, is_invalidate_needed\n                    FROM {$table_name} t %s\n                    LEFT JOIN (\n                      SELECT identifier, context, GROUP_CONCAT(DISTINCT version) AS versions\n                      FROM {$table_name}\n                      GROUP BY identifier, context\n                    ) t2 ON t.identifier = t2.identifier AND t.context = t2.context\n                    WHERE %s\n                    ORDER BY headline ASC, version DESC", $middlewareRoutine . '_middleware', \join(' AND ', $joins), \join(' AND ', $where)), ARRAY_A);
        // phpcs:enable WordPress.DB
        if ($result === null) {
            // Error happened, do not try to refetch from data sources
            return [];
        }
        if ($type === self::TYPE_SERVICE && $field === null && \count($result) === 0 && $middlewareRoutine === 'after' && $forceInvalidate !== 'never') {
            // No results in database but there must be at least one, refetch from data source
            // Force also the fetch from service cloud via API (e.g. new language in WPML added)
            $this->getExpireOption()->delete();
            return \false;
        }
        foreach ($result as &$row) {
            if ($middlewareRoutine === 'after' && \intval($row['is_invalidate_needed']) > 0 && $forceInvalidate !== 'never') {
                // So, in this state we know that we should recalculate middlewares.
                return \false;
            }
            $versions = \array_filter(\array_map('intval', \explode(',', $row['versions'])));
            $row = $this->fromArray(\json_decode($row['response'], ARRAY_A));
            if ($field !== 'versions') {
                $row->consumerData['versions'] = $versions;
            }
        }
        return $result;
    }
    /**
     * Convert JSON to `ServiceTemplate` or `BlockerTemplate` depending on current type.
     *
     * @param array $array
     */
    public function fromArray($array)
    {
        $consumer = $this->storage->getConsumer();
        $row = $this->getType() === self::TYPE_BLOCKER ? new BlockerTemplate($consumer) : new ServiceTemplate($consumer);
        $row->fromArray($array);
        return $row;
    }
    /**
     * Persist a set of templates in chunks into the database.
     *
     * @param AbstractTemplate[] $templates
     * @param closre $otherMetaKeysResolver
     */
    public function persist($templates, $otherMetaKeysResolver = null)
    {
        global $wpdb;
        /**
         * Chunks.
         *
         * @var AbstractTemplate[][]
         */
        $chunks = \array_chunk($templates, \DevOwl\RealCookieBanner\templates\TemplateConsumers::PERSIST_CHUNK_SIZE_TEMPLATES);
        $table_name = $this->getTableName(self::TABLE_NAME);
        $inserted = 0;
        $context = $this->getContext();
        $type = $this->getType();
        $persistedIdentifierInSql = [];
        foreach ($chunks as $chunk) {
            $values = [];
            foreach ($chunk as $template) {
                $otherMeta = \is_callable($otherMetaKeysResolver) ? $otherMetaKeysResolver($template) : [];
                $values[] = \str_ireplace("'NULL'", 'NULL', $wpdb->prepare('(%s, %s, %s, %d, %s,
                            %s, %s, %s,
                            %d, %d, %d, %d, %d, %d, %d,
                            %s, %s, %s, %s, %s, %s)', $template->identifier, $context, $type, $template->version, \mysql2date('c', \gmdate('Y-m-d H:i:s', $template->createdAt), \false), $template->headline ?? '', $template->subHeadline ?? '', $template->logoUrl ?? '', 0, $template->consumerData['isDisabled'] ? 1 : 0, 0, $template->consumerData['isUntranslated'] ?? \false, $template->isHidden ? 1 : 0, $template->consumerData['isRecommended'] ? 1 : 0, isset($template->consumerData['isCloud']) && $template->consumerData['isCloud'] ? 1 : 0, $template->tier, \count($template->consumerData['tags']) > 0 ? \json_encode($template->consumerData['tags']) : \json_encode((object) []), \json_encode($template->getBeforeMiddleware()), \json_encode(AbstractTemplate::toArray($template)), \count($otherMeta) > 0 ? \json_encode($otherMeta) : \json_encode((object) []), \count($template->successorOfIdentifierInfo) > 0 ? \json_encode($template->successorOfIdentifierInfo) : 'NULL'));
                $persistedIdentifierInSql[] = $wpdb->prepare('%s', $template->identifier);
            }
            // phpcs:disable WordPress.DB.PreparedSQL
            $result = $wpdb->query(\sprintf('INSERT INTO %s (
                        `identifier`, `context`, `type`, `version`, `created_at`,
                        `headline`, `sub_headline`, `logo_url`,
                        `is_outdated`, `is_disabled`, `is_invalidate_needed`, `is_untranslated`, `is_hidden`, `is_recommended`, `is_cloud`,
                        `tier`, `tags`, `before_middleware`, `after_middleware`, `other_meta`, `successor_of_identifiers`
                    )
                    VALUES %s ON DUPLICATE KEY UPDATE
                        `version` = VALUES(`version`),
                        `created_at` = VALUES(`created_at`),
                        `headline` = VALUES(`headline`),
                        `sub_headline` = VALUES(`sub_headline`),
                        `logo_url` = VALUES(`logo_url`),
                        `is_disabled` = VALUES(`is_disabled`),
                        `is_invalidate_needed` = VALUES(`is_invalidate_needed`),
                        `is_untranslated` = VALUES(`is_untranslated`),
                        `is_hidden` = VALUES(`is_hidden`),
                        `is_recommended` = VALUES(`is_recommended`),
                        `is_cloud` = VALUES(`is_cloud`),
                        `tier` = VALUES(`tier`),
                        `tags` = VALUES(`tags`),
                        `before_middleware` = VALUES(`before_middleware`),
                        `after_middleware` = VALUES(`after_middleware`),
                        `other_meta` = VALUES(`other_meta`),
                        `successor_of_identifiers` = VALUES(`successor_of_identifiers`)', $table_name, \join(',', $values)));
            // phpcs:enable WordPress.DB.PreparedSQL
            // When $result is zero, the query did not fail but no new row where added, we need to respect `ON DUPLICATE KEY UPDATE`
            $inserted += $result === \false ? 0 : \count($values);
        }
        $this->updateOutdated();
        // Delete no-longer known templates
        if (\count($persistedIdentifierInSql) > 0) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query($wpdb->prepare("DELETE FROM {$table_name} WHERE context = %s AND type = %s AND identifier NOT IN (" . \join(',', $persistedIdentifierInSql) . ')', $context, $type));
            // phpcs:enable WordPress.DB.PreparedSQL
        }
        return $inserted;
    }
    /**
     * Update all `is_outdated` for all known services.
     */
    public function updateOutdated()
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query("UPDATE {$table_name} t1\n            INNER JOIN {$table_name} t2\n            ON t1.identifier = t2.identifier AND t1.context = t2.context AND t1.type = t2.type\n            SET t1.is_outdated = 1\n            WHERE t1.version < t2.version AND t1.is_outdated = 0");
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    // Documented in AbstractStorage
    public function shouldInvalidate()
    {
        $option = $this->getExpireOption();
        $cache = $option->get();
        return $cache !== $this->getCacheInvalidateKey() && $option->set($this->getCacheInvalidateKey());
    }
    /**
     * Get type as string from template type class.
     */
    public function getType()
    {
        $typeClass = $this->storage->getConsumer()->getTypeClass();
        return $typeClass === ServiceTemplate::class ? self::TYPE_SERVICE : self::TYPE_BLOCKER;
    }
    /**
     * Get the `ExpireOption` instance.
     */
    public function getExpireOption()
    {
        return $this->expireOption === null ? $this->expireOption = new ExpireOption(\sprintf(self::TRANSIENT_CACHE_KEY, $this->getType(), $this->getContext()), \false, self::TRANSIENT_CACHE_EXPIRE) : $this->expireOption;
    }
    /**
     * Automatically invalidate storage when Real Cookie Banner verion changes.
     */
    public function getCacheInvalidateKey()
    {
        return $this->storage->getConsumer()->getVariableResolver()->resolveRequired('cache.invalidate.key');
    }
    /**
     * Get context from variable resolver.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->storage->getConsumer()->getVariableResolver()->resolveRequired('context');
    }
}
