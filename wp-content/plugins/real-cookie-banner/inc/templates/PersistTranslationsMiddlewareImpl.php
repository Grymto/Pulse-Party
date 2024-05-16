<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\translations\PersistTranslationsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Pool middleware for both service and blocker templates and persist translations to `wp_rcb_template_translation`.
 * @internal
 */
class PersistTranslationsMiddlewareImpl extends PersistTranslationsMiddleware
{
    use UtilsProvider;
    const TABLE_NAME = 'template_translation';
    const CACHE_GROUP = RCB_OPT_PREFIX . '-template-translation';
    /**
     * Override this functionality and misuse this hook to also download the latest GVL from the backend.
     *
     * @param ServiceCloudConsumer[] $consumers
     * @param AbstractTemplate[] $typeClassToAllTemplates
     */
    public function afterPersistTemplatesWithinPool($consumers, &$typeClassToAllTemplates)
    {
        parent::afterPersistTemplatesWithinPool($consumers, $typeClassToAllTemplates);
        if ($this->isPro()) {
            TCF::getInstance()->updateGvl();
        }
    }
    // Documented in PersistTranslationsMiddleware
    public function fetchEnglishTemplates($identifiers, $consumer)
    {
        return \DevOwl\RealCookieBanner\templates\TemplateConsumers::getInstance()->getPool('en_US')->getConsumer($consumer->getTypeClass())->retrieveBy('identifier', $identifiers);
    }
    // Documented in PersistTranslationsMiddleware
    public function persistTranslations($translations, $consumer)
    {
        global $wpdb;
        $sqlValues = [];
        $context = $consumer->getVariableResolver()->resolveRequired('context');
        $table_name = $this->getTableName(self::TABLE_NAME);
        foreach ($translations as $translation) {
            // Currently, we only hold a simple table with language, source_content, target_content
            // as all the other stuff like identifier, version and so on is not relevant as at translation
            // time we do not know for which template we need to read translations (like a simple POT file).
            // Example: In TranslatePress/Weglot we never know the context.
            $sqlValues[] = $wpdb->prepare('(%s, %s, %s, %s, %s)', $context, \md5($translation->getEnglishValue()), \md5($translation->getTranslatedValue()), $translation->getEnglishValue(), $translation->getTranslatedValue());
        }
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query(\sprintf('INSERT INTO %s (
                    `target_language`, `source_content_hash`, `target_content_hash`, `source_content`, `target_content`
                )
                VALUES %s ON DUPLICATE KEY UPDATE
                    `target_content_hash` = VALUES(`target_content_hash`),
                    `source_content` = VALUES(`source_content`),
                    `target_content` = VALUES(`target_content`)', $table_name, \join(',', $sqlValues)));
        // phpcs:enable WordPress.DB.PreparedSQL
        // Write `0` to object cache as `null` and `false` could be considered as "do not write"
        // and `wp_cache_flush_group()` is only available WP >= 6.1 and not for all cache implementations.
        \wp_cache_set($context, '0', self::CACHE_GROUP);
    }
}
