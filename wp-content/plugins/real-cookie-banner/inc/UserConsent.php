<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview\ImagePreview;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\view\blocker\WordPressImagePreviewCache;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\view\Blocker;
use DevOwl\RealCookieBanner\view\blocker\Plugin;
use DevOwl\RealCookieBanner\view\shortcode\LinkShortcode;
use WP_Error;
use WP_HTTP_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle consents of users.
 * @internal
 */
class UserConsent
{
    use UtilsProvider;
    const TABLE_NAME = 'consent';
    const CLICKABLE_BUTTONS = ['none', 'main_all', 'main_essential', 'main_close_icon', 'main_custom', 'ind_all', 'ind_essential', 'ind_close_icon', 'ind_custom', LinkShortcode::BUTTON_CLICKED_IDENTIFIER, Blocker::BUTTON_CLICKED_IDENTIFIER];
    const BY_CRITERIA_RESULT_TYPE_JSON_DECODE = 'jsonDecode';
    const BY_CRITERIA_RESULT_TYPE_COUNT = 'count';
    const BY_CRITERIA_RESULT_TYPE_SQL_QUERY = 'sqlQuery';
    /**
     * Singleton instance.
     *
     * @var UserConsent
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Delete all available user consents with revisions and stats.
     *
     * @return boolean|array Array with deleted counts of the database tables
     */
    public function purge()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        $table_name_revision = $this->getTableName(Revision::TABLE_NAME);
        $table_name_revision_independent = $this->getTableName(Revision::TABLE_NAME_INDEPENDENT);
        $table_name_stats_terms = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_TERMS);
        $table_name_stats_buttons_clicked = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_BUTTONS_CLICKED);
        $table_name_stats_custom_bypass = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_CUSTOM_BYPASS);
        // The latest revision should not be deleted
        $revisionHash = Revision::getInstance()->getRevision()->getEnsuredCurrentHash();
        // phpcs:disable WordPress.DB
        $consent = $wpdb->query("DELETE FROM {$table_name}");
        $revision = $wpdb->query($wpdb->prepare("DELETE FROM {$table_name_revision} WHERE `hash` != %s", $revisionHash));
        $revision_independent = $wpdb->query("DELETE FROM {$table_name_revision_independent}");
        $stats_terms = $wpdb->query("DELETE FROM {$table_name_stats_terms}");
        $stats_buttons_clicked = $wpdb->query("DELETE FROM {$table_name_stats_buttons_clicked}");
        $stats_custom_bypass = $wpdb->query("DELETE FROM {$table_name_stats_custom_bypass}");
        // phpcs:enable WordPress.DB
        return ['consent' => $consent, 'revision' => $revision, 'revision_independent' => $revision_independent, 'stats_terms' => $stats_terms, 'stats_buttons_clicked' => $stats_buttons_clicked, 'stats_custom_bypass' => $stats_custom_bypass];
    }
    /**
     * Check if there are truncated IPs saved in the current consents list and return the count of found rows.
     */
    public function getTruncatedIpsCount()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        // phpcs:disable WordPress.DB
        $count = $wpdb->get_var("SELECT COUNT(1) FROM {$table_name} WHERE ipv4 = 0 AND ipv6 IS NULL");
        // phpcs:enable WordPress.DB
        return \intval($count);
    }
    /**
     * Fetch user consents by criteria.
     *
     * @param array $args 'uuid', 'uuids', 'ip', 'exactIp', 'offset', 'perPage', 'from', 'to', 'pure_referer', 'context'
     * @param string $returnType
     */
    public function byCriteria($args, $returnType = self::BY_CRITERIA_RESULT_TYPE_JSON_DECODE)
    {
        global $wpdb;
        // Parse arguments
        $args = \array_merge([
            // LIMIT
            'offset' => 0,
            'perPage' => 10,
            'revisionJson' => \false,
            // Filters
            'uuid' => '',
            'uuids' => null,
            'ip' => '',
            'exactIp' => \true,
            'from' => '',
            'to' => '',
            'sinceSeconds' => 0,
            'pure_referer' => '',
            'context' => null,
        ], $args);
        $revisionJson = \boolval($args['revisionJson']);
        $ip = $args['ip'];
        $exactIp = \boolval($args['exactIp']);
        $uuid = $args['uuid'];
        $uuids = $args['uuids'];
        $limitOffset = $args['offset'];
        $perPage = $args['perPage'];
        $sinceSeconds = \intval($args['sinceSeconds']);
        $from = $args['from'];
        $to = $args['to'];
        $pure_referer = $args['pure_referer'];
        $context = $args['context'];
        // Prepare parameters
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        $table_name_revision = $this->getTableName(Revision::TABLE_NAME);
        $table_name_revision_independent = $this->getTableName(Revision::TABLE_NAME_INDEPENDENT);
        // Build JOIN's
        $joins = ['INNER JOIN ' . $table_name_revision . ' AS rev ON rev.hash = c.revision', 'INNER JOIN ' . $table_name_revision_independent . ' AS revc ON revc.hash = c.revision_independent'];
        // Build WHERE statement for filtering
        // If you add a new filter, keep in mind to add the column to the index `filters` of `wp_rcb_consent`.
        // The following properties are not part of the index as they are currently used rarely:
        //  IPs, UUID, Pure Referer
        $where = [];
        if (!empty($uuid)) {
            $where[] = $wpdb->prepare('(cs.uuid = %s)', $uuid);
        } elseif (\is_array($uuids)) {
            $where[] = \sprintf('uuid IN (%s)', \join(', ', \array_map(function ($uuid) use($wpdb) {
                return $wpdb->prepare('%s', $uuid);
            }, $uuids)));
            // Usually, `uuid` should be `UNIQUE` index in database and be fast, but at the moment, due to
            // backwards-compatibility it isn't. So, use `LIMIT` to stop at when found x entries.
            $perPage = \count($uuids) > 100 ? 100 : \count($uuids);
        }
        if (!empty($ip)) {
            $ips = \DevOwl\RealCookieBanner\IpHandler::getInstance()->persistIp($ip, $exactIp);
            if ($ips['ipv4'] === \false || $ips['ipv6'] === \false) {
                return new WP_Error('invalid_ip', \__('Invalid IP address. Please insert a valid IPv4 or IPv6 address.', RCB_TD));
            }
            $whereIp = [];
            foreach ($ips as $key => $value) {
                if (!empty($value)) {
                    // phpcs:disable WordPress.DB
                    $whereIp[] = $wpdb->prepare('cs.' . $key . ' = %s', $value);
                    // phpcs:enable WordPress.DB
                }
            }
            if (\count($whereIp) > 0) {
                $where[] = \sprintf('(%s)', \join(' OR ', $whereIp));
            }
        }
        if (!empty($pure_referer)) {
            $where[] = $wpdb->prepare('cs.pure_referer = %s', $pure_referer);
        }
        if ($sinceSeconds > 0) {
            $where[] = $wpdb->prepare('cs.created > (NOW() - INTERVAL %d SECOND)', $sinceSeconds);
        } elseif (!empty($from) && !empty($to)) {
            $where[] = $wpdb->prepare('cs.created BETWEEN %s AND %s', $from, $to);
        } elseif (!empty($from)) {
            $where[] = $wpdb->prepare('cs.created >= %s', $from);
        } elseif (!empty($to)) {
            $where[] = $wpdb->prepare('cs.created <= %s', $to);
        }
        if (!empty($context)) {
            $where[] = $wpdb->prepare('cs.context = %s', $context);
        } else {
            // Force `SELECT` statement to use at least one index-possible column to try to boost performance
            // This is especially useful for `COUNT` statements
            $where[] = '(context = "" OR context <> "")';
        }
        $fields = ['c.id', 'c.plugin_version', 'c.design_version', 'c.ipv4', 'c.ipv6', 'c.ipv4_hash', 'c.ipv6_hash', 'c.uuid', 'c.previous_decision', 'c.decision', 'c.created', 'c.created_client_time', 'c.blocker', 'c.blocker_thumbnail', 'c.dnt', 'c.custom_bypass', 'c.user_country', 'c.button_clicked', 'c.context', 'c.viewport_width', 'c.viewport_height', 'c.referer as viewed_page', 'c.url_imprint', 'c.url_privacy_policy', 'c.forwarded', 'c.forwarded_blocker', 'c.previous_tcf_string', 'c.tcf_string', 'c.previous_gcm_consent', 'c.gcm_consent', 'c.recorder', 'c.ui_view'];
        if ($returnType === self::BY_CRITERIA_RESULT_TYPE_COUNT) {
            $sql = \sprintf('SELECT COUNT(1) AS cnt FROM %s AS cs WHERE %s', $table_name, \join(' AND ', $where));
        } else {
            if ($revisionJson) {
                $fields[] = 'rev.json_revision AS revision';
                $fields[] = 'revc.json_revision AS revision_independent';
            }
            $fields[] = 'rev.hash AS revision_hash';
            $fields[] = 'revc.hash AS revision_independent_hash';
            // Read data
            // phpcs:disable WordPress.DB
            $sql = \sprintf('SELECT %s FROM (SELECT cs.id FROM %s AS cs WHERE %s ORDER BY cs.created DESC LIMIT %d, %d) AS cids %s ORDER BY c.created DESC', \join(',', $fields), $table_name, \join(' AND ', $where), $limitOffset, $perPage, \join(' ', \array_merge([
                // Due to `ORDERBY` and `INNER JOIN` optimization use a subquery for the filtering
                // and paging to boost performance.
                'INNER JOIN ' . $table_name . ' AS c ON c.id = cids.id',
            ], $joins)));
        }
        if ($returnType === self::BY_CRITERIA_RESULT_TYPE_SQL_QUERY) {
            return $sql;
        }
        $results = $wpdb->get_results($sql);
        // phpcs:enable WordPress.DB
        if ($returnType === self::BY_CRITERIA_RESULT_TYPE_COUNT) {
            return \intval($results[0]->cnt);
        }
        $this->castReadRows($results, $returnType === self::BY_CRITERIA_RESULT_TYPE_JSON_DECODE);
        return $results;
    }
    /**
     * Cast read rows from database to correct types.
     *
     * @param object[] $results
     * @param boolean $jsonDecode Pass `false` if you do not want to decode data like `revision` or `decision` to real objects (useful for CSV exports)
     */
    public function castReadRows(&$results, $jsonDecode = \true)
    {
        global $wpdb;
        $table_name_blocker_thumbnails = $this->getTableName(Plugin::TABLE_NAME_BLOCKER_THUMBNAILS);
        $revisionHashes = [];
        foreach ($results as &$row) {
            $row->id = \intval($row->id);
            $row->design_version = \intval($row->design_version);
            $row->ipv4 = $row->ipv4 === '0' ? null : $row->ipv4;
            $row->context = empty($row->context) ? '' : Revision::getInstance()->translateContextVariablesString($row->context);
            if ($jsonDecode) {
                $row->previous_decision = \json_decode($row->previous_decision, ARRAY_A);
                $row->previous_decision = \count($row->previous_decision) > 0 ? $row->previous_decision : null;
                $row->decision = \json_decode($row->decision, ARRAY_A);
                if ($row->previous_gcm_consent !== null) {
                    $row->previous_gcm_consent = \json_decode($row->previous_gcm_consent, ARRAY_A);
                }
                if ($row->gcm_consent !== null) {
                    $row->gcm_consent = \json_decode($row->gcm_consent, ARRAY_A);
                }
                // Only populate decision_labels if we also decode the decision
                $revisionHashes[] = $row->revision_hash;
                if (\property_exists($row, 'revision')) {
                    $row->revision = \json_decode($row->revision, ARRAY_A);
                    $row->revision_independent = \json_decode($row->revision_independent, ARRAY_A);
                }
            }
            $row->blocker = $row->blocker === null ? null : \intval($row->blocker);
            $row->blocker_thumbnail = $row->blocker_thumbnail === null ? null : \intval($row->blocker_thumbnail);
            $row->dnt = $row->dnt === '1';
            $row->created = \mysql2date('c', $row->created, \false);
            $row->created_client_time = \mysql2date('c', $row->created_client_time, \false);
            $row->viewport_width = \intval($row->viewport_width);
            $row->viewport_height = \intval($row->viewport_height);
            $row->forwarded = $row->forwarded === null ? null : \intval($row->forwarded);
            $row->forwarded_blocker = $row->forwarded_blocker === '1';
            if ($row->ipv4 !== null) {
                $row->ipv4 = \long2ip($row->ipv4);
            }
            if ($row->ipv6 !== null) {
                $row->ipv6 = \inet_ntop($row->ipv6);
            }
        }
        // Populate blocker_thumbnails as object instead of the ID itself
        $blockerThumbnailIds = \array_values(\array_unique(\array_filter(\array_column($results, 'blocker_thumbnail'))));
        $blockerThumbnails = [];
        $imagePreviewPlugins = \DevOwl\RealCookieBanner\Core::getInstance()->getBlocker()->getHeadlessContentBlocker()->getPluginsByClassName(ImagePreview::class) ?? [];
        if (\count($blockerThumbnailIds) && \count($imagePreviewPlugins) > 0) {
            /**
             * Plugin.
             *
             * @var WordPressImagePreviewCache
             */
            $imagePreviewCache = $imagePreviewPlugins[0]->getCache();
            // phpcs:disable WordPress.DB.PreparedSQL
            $readBlockerThumbnailsQueryResult = $wpdb->get_results("SELECT id, embed_id, file_md5, embed_url, cache_filename, title, width, height FROM {$table_name_blocker_thumbnails} WHERE id IN (" . \join(',', $blockerThumbnailIds) . ')', ARRAY_A);
            // phpcs:enable WordPress.DB.PreparedSQL
            foreach ($readBlockerThumbnailsQueryResult as $readBlockerThumbnail) {
                $blockerThumbnails[$readBlockerThumbnail['id']] = \array_merge($readBlockerThumbnail, ['id' => \intval($readBlockerThumbnail['id']), 'width' => \intval($readBlockerThumbnail['width']), 'height' => \intval($readBlockerThumbnail['height']), 'url' => $imagePreviewCache->getPrefixUrl() . $readBlockerThumbnail['cache_filename']]);
            }
        }
        foreach ($results as &$row) {
            if ($row->blocker_thumbnail > 0) {
                $thumbnailId = $row->blocker_thumbnail;
                $row->blocker_thumbnail = $blockerThumbnails[$thumbnailId] ?? null;
                if (!$jsonDecode && $row->blocker_thumbnail !== null) {
                    $row->blocker_thumbnail = \json_encode($row->blocker_thumbnail);
                }
            }
        }
        // Populate decision_labels so we can show the decision in table
        if (\count($revisionHashes)) {
            $revisionHashes = Revision::getInstance()->getByHash($revisionHashes);
            // Iterate all table items
            foreach ($results as &$row) {
                $decision = $row->decision;
                $labels = [];
                $groups = $revisionHashes[$row->revision_hash]['groups'];
                // Iterate all decision groups
                foreach ($decision as $groupId => $cookies) {
                    $cookiesCount = \count($cookies);
                    if ($cookiesCount > 0) {
                        // Iterate all available revision groups to find the decision group
                        foreach ($groups as $group) {
                            if ($group['id'] === \intval($groupId)) {
                                $name = $group['name'];
                                $itemsCount = \count($group['items']);
                                $labels[] = $name . ($cookiesCount !== $itemsCount ? \sprintf(' (%d / %d)', $cookiesCount, $itemsCount) : '');
                                break;
                            }
                        }
                    }
                }
                $row->decision_labels = $labels;
            }
        }
        return $revisionHashes;
    }
    /**
     * Get the total count of current consents.
     */
    public function getCount()
    {
        return $this->byCriteria([], \DevOwl\RealCookieBanner\UserConsent::BY_CRITERIA_RESULT_TYPE_COUNT);
    }
    /**
     * Get all referer across all consents.
     *
     * @return string[]
     */
    public function getReferer()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        return $wpdb->get_col("SELECT DISTINCT(pure_referer) FROM {$table_name} WHERE pure_referer <> ''");
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Get all persisted contexts so they can be used e. g. to query statistics.
     *
     * @return string[]
     */
    public function getPersistedContexts()
    {
        global $wpdb;
        $result = [];
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $contexts = $wpdb->get_col("SELECT DISTINCT(context) FROM {$table_name}");
        // phpcs:enable WordPress.DB.PreparedSQL
        foreach ($contexts as $context) {
            $result[$context] = Revision::getInstance()->translateContextVariablesString($context);
        }
        return $result;
    }
    /**
     * Settings got updated in "Settings" tab, lets reschedule deletion of consents.
     *
     * @param WP_HTTP_Response $response
     */
    public function settings_updated($response)
    {
        $this->scheduleDeletionOfConsents();
        return $response;
    }
    /**
     * Delete consents older than set duration time
     */
    public function scheduleDeletionOfConsents()
    {
        $currentTime = \current_time('mysql');
        $lastDeletion = \get_transient(Consent::TRANSIENT_SCHEDULE_CONSENTS_DELETION);
        if ($lastDeletion === \false) {
            \set_transient(Consent::TRANSIENT_SCHEDULE_CONSENTS_DELETION, $currentTime, DAY_IN_SECONDS);
            $this->deleteConsentsByConsentDurationPeriod();
        }
    }
    /**
     * Executing query for deletion consents
     */
    private function deleteConsentsByConsentDurationPeriod()
    {
        global $wpdb;
        $consent = Consent::getInstance();
        $consentDuration = $consent->getConsentDuration();
        $endDate = \gmdate('Y-m-d', \strtotime('-' . $consentDuration . ' months'));
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        $table_name_revision = $this->getTableName(Revision::TABLE_NAME);
        $table_name_revision_independent = $this->getTableName(Revision::TABLE_NAME_INDEPENDENT);
        // phpcs:disable WordPress.DB
        $deleted = $wpdb->query($wpdb->prepare("DELETE FROM `{$table_name}` WHERE `created` < %s", $endDate));
        if ($deleted > 0) {
            $wpdb->query("DELETE ri1 FROM {$table_name_revision} ri1\n                LEFT JOIN (\n                    SELECT DISTINCT(ri2.hash) FROM {$table_name_revision} ri2\n                    INNER JOIN {$table_name} c ON ri2.hash = c.revision\n                ) existing\n                ON ri1.hash = existing.hash\n                WHERE existing.hash IS NULL");
            $wpdb->query("DELETE ri1 FROM {$table_name_revision_independent} ri1\n                LEFT JOIN (\n                    SELECT DISTINCT(ri2.hash) FROM {$table_name_revision_independent} ri2\n                    INNER JOIN {$table_name} c ON ri2.hash = c.revision_independent\n                ) existing\n                ON ri1.hash = existing.hash\n                WHERE existing.hash IS NULL");
        }
        // phpcs:enable WordPress.DB
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\UserConsent() : self::$me;
    }
}
