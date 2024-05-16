<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\Scanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Query scan results.
 * @internal
 */
class Query
{
    use UtilsProvider;
    const MIME_TYPE_JAVASCRIPT = 'text/javascript';
    const MIME_TYPE_CSS = 'text/css';
    const MIME_TYPE_HTML = 'text/html';
    const TRANSIENT_SCANNED_EXTERNAL_URLS = RCB_OPT_PREFIX . '-scanned-external-urls';
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        // Silence is golden.
    }
    /**
     * Get markup by ID. This also returns the mime type used for the blocked content.
     * This can also be `null` when the scanned element is only blocked by URL (e.g. not
     * by inline script, style or `SelectorSyntaxBlocker`).
     *
     * @param int|int[] $ids
     */
    public function getMarkup($ids)
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        $table_name_markup = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME_MARKUP);
        $multiple = \is_array($ids);
        $ids = \array_map('intval', $multiple ? $ids : [$ids]);
        $where = \sprintf('s.id IN (%s)', \join(',', $ids));
        $sql = \sprintf("SELECT\n                s.id,\n                s.tag,\n                CASE\n                    WHEN m.markup LIKE '<%%' THEN '%s'\n                    WHEN s.tag = 'script' THEN '%s'\n                    WHEN s.tag = 'style' THEN '%s'\n                    ELSE '%s'\n                END AS mime,\n                m.markup,\n                m.markup_hash\n            FROM {$table_name} s\n            INNER JOIN {$table_name_markup} m\n                ON s.markup_hash = m.markup_hash\n            WHERE {$where}", self::MIME_TYPE_HTML, self::MIME_TYPE_JAVASCRIPT, self::MIME_TYPE_CSS, self::MIME_TYPE_HTML);
        // phpcs:disable WordPress.DB.PreparedSQL
        $result = $multiple ? $wpdb->get_results($sql, ARRAY_A) : $wpdb->get_row($sql, ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        // Cast
        if ($multiple) {
            foreach ($result as &$row) {
                $row['id'] = \intval($row['id']);
            }
        } else {
            $result['id'] = \intval($result['id']);
        }
        return $result;
    }
    /**
     * Get a count of all scanned entries.
     *
     * @return int
     */
    public function getScannedEntriesCount()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $count = $wpdb->get_var("SELECT COUNT(1) FROM {$table_name}");
        // phpcs:enable WordPress.DB.PreparedSQL
        return $count;
    }
    /**
     * Get all (known) scanned pages.
     */
    public function getScannedSourceUrls()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_col("SELECT source_url FROM {$table_name} GROUP BY source_url");
        // phpcs:enable WordPress.DB.PreparedSQL
        return $rows;
    }
    /**
     * Remove scanned entries by source urls. This can be useful if you know a page got deleted.
     *
     * @param string[] $urls
     * @return int
     */
    public function removeSourceUrls($urls)
    {
        global $wpdb;
        if (\count($urls) > 0) {
            $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
            $urls = \array_map(function ($url) use($wpdb) {
                return $wpdb->prepare('%s', \md5($url));
            }, $urls);
            $sqlIn = \join(',', $urls);
            \delete_transient(Scanner::TRANSIENT_SERVICES_FOR_NOTICE);
            \delete_transient(\DevOwl\RealCookieBanner\scanner\Query::TRANSIENT_SCANNED_EXTERNAL_URLS);
            // phpcs:disable WordPress.DB.PreparedSQL
            return $wpdb->query("DELETE FROM {$table_name} WHERE source_url_hash IN ({$sqlIn})");
            // phpcs:enable WordPress.DB.PreparedSQL
        }
        return 0;
    }
    /**
     * Get a list of found templates and external URL hosts.
     */
    public function getScannedCombinedResults()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $result = $wpdb->get_row("SELECT\n                GROUP_CONCAT(DISTINCT IF(preset <> '', preset, NULL) ORDER BY preset) AS templates,\n                GROUP_CONCAT(DISTINCT IF(preset = '', blocked_url_host, NULL) ORDER BY blocked_url_host) AS externalHosts\n            FROM {$table_name}", ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        return [\explode(',', $result['templates'] ?? ''), \explode(',', $result['externalHosts'] ?? '')];
    }
    /**
     * Get a list of scanned templates.
     *
     * @param boolean|string $forceTemplatesInvalidate See `ServiceCloudConsumer#retrieveBy` parameters
     */
    public function getScannedTemplates($forceTemplatesInvalidate = \false)
    {
        /**
         * Templates.
         *
         * @var (ServiceTemplate|BlockerTemplate)[]
         */
        $templates = [];
        $blockerTemplates = TemplateConsumers::getCurrentBlockerConsumer()->retrieveBy('scanned', \true, $forceTemplatesInvalidate);
        $recommendedServiceTemplates = TemplateConsumers::getCurrentServiceConsumer()->retrieveBy('recommended', 1);
        foreach ($recommendedServiceTemplates as $recommendedServiceTemplate) {
            $templates[] = $recommendedServiceTemplate;
        }
        // For hidden content blockers, use the service template
        $hiddenIdentifiers = [];
        foreach ($blockerTemplates as $blocker) {
            if ($blocker->isHidden) {
                $hiddenIdentifiers[] = $blocker->identifier;
            } else {
                $templates[] = $blocker;
            }
        }
        if (\count($hiddenIdentifiers)) {
            $serviceTemplates = TemplateConsumers::getCurrentServiceConsumer()->retrieveBy('identifier', $hiddenIdentifiers);
            $templates = \array_merge($templates, $serviceTemplates);
        }
        // Sort by headline
        \usort($templates, function ($a, $b) {
            return \strcmp($a->headline, $b->headline);
        });
        /**
         * Make the identifier as key of the object.
         *
         * @var (ServiceTemplate|BlockerTemplate)[]
         */
        $result = [];
        foreach ($templates as $key => $tmpl) {
            $result[$tmpl->identifier] = $tmpl;
            unset($templates[$key]);
        }
        return $result;
    }
    /**
     * Get scanned templates with details about the amount of found times, and the last scanned.
     * This does not return template-instances, for this you need to use `getScannedTemplates`.
     */
    public function getScannedTemplateStats()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results("SELECT\n                preset,\n                COUNT(1) AS foundCount,\n                COUNT(DISTINCT source_url_hash) AS foundOnSitesCount,\n                MAX(created) AS lastScanned\n            FROM {$table_name} scan\n            WHERE preset <> ''\n            GROUP BY preset\n            ORDER BY preset", ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        $result = [];
        foreach ($rows as $row) {
            $templateIdentifier = $row['preset'];
            // Cast result
            $result[$templateIdentifier] = ['foundCount' => \intval($row['foundCount']), 'foundOnSitesCount' => \intval($row['foundOnSitesCount']), 'lastScanned' => \mysql2date('c', $row['lastScanned'], \false)];
        }
        return $result;
    }
    /**
     * Get scanned external URLs aggregated by host.
     *
     * Attention: This query is cached to a transient as it is very expensive! Use
     * `delete_transient(Query::TRANSIENT_SCANNED_EXTERNAL_URLS)` to invalidate the cache.
     *
     * @return array
     */
    public function getScannedExternalUrls()
    {
        global $wpdb;
        $value = \get_transient(self::TRANSIENT_SCANNED_EXTERNAL_URLS);
        if ($value !== \false) {
            return $value;
        }
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        $expressions = $this->getExistingUrlExpressions();
        $expressionSql = $this->transformExpressionsToMySQL($expressions, 'blocked_url');
        $ignored = Core::getInstance()->getNotices()->getScannerIgnored()['hosts'];
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results("SELECT\n                blocked_url_host AS host,\n                COUNT(1) AS foundCount,\n                COUNT(DISTINCT source_url) AS foundOnSitesCount,\n                SUM(IF({$expressionSql}, 1, 0)) AS blockedCount,\n                MAX(created) AS lastScanned,\n                GROUP_CONCAT(DISTINCT tag) AS tags\n            FROM {$table_name} scan\n            WHERE blocked_url_hash IS NOT NULL\n                AND preset = ''\n            GROUP BY blocked_url_host\n            ORDER BY 1", ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        // Cast properties
        $result = [];
        foreach ($rows as &$row) {
            $host = $row['host'];
            // Cast result
            $result[$host] = ['host' => $host, 'foundCount' => \intval($row['foundCount']), 'foundOnSitesCount' => \intval($row['foundOnSitesCount']), 'blockedCount' => \intval($row['blockedCount']), 'ignored' => \in_array($host, $ignored, \true), 'lastScanned' => \mysql2date('c', $row['lastScanned'], \false), 'tags' => \explode(',', $row['tags'])];
        }
        \set_transient(self::TRANSIENT_SCANNED_EXTERNAL_URLS, $result, 2 * DAY_IN_SECONDS);
        return $result;
    }
    /**
     * Get scanned external URLs by host. This includes source page and complete
     * blocked URL instead of only host.
     *
     * @param string $by Can be `host` or `template`
     * @param string $value
     */
    public function getAllScannedExternalUrlsBy($by, $value)
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        $expressions = $this->getExistingUrlExpressions();
        $expressionSql = $this->transformExpressionsToMySQL($expressions, 'blocked_url');
        // phpcs:disable WordPress.DB.PreparedSQL
        $sql = "SELECT id,\n                blocked_url AS blockedUrl,\n                IF(markup_hash <> '' , 1, 0) AS markup,\n                source_url AS sourceUrl,\n                IF({$expressionSql}, 1, 0) AS blocked,\n                created AS lastScanned,\n                tag\n            FROM {$table_name} scan\n            WHERE " . ($by === 'host' ? $wpdb->prepare("blocked_url_host = %s AND preset = ''", $value) : $wpdb->prepare('preset = %s', $value)) . ' ORDER BY blocked_url';
        $rows = $wpdb->get_results($sql, ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        // Collect markup ids so we can run the content blocker on the HTML
        $checkMarkup = [];
        // Cast properties
        $result = [];
        foreach ($rows as &$row) {
            $id = \intval($row['id']);
            // Cast result
            $row = ['id' => $id, 'blockedUrl' => $row['blockedUrl'], 'markup' => \intval($row['markup']) > 0, 'sourceUrl' => $row['sourceUrl'], 'blocked' => \intval($row['blocked']) > 0, 'lastScanned' => \mysql2date('c', $row['lastScanned'], \false), 'tag' => $row['tag']];
            $result[] = $row;
            if ($row['markup'] && !$row['blocked'] && (empty($row['blockedUrl']) || \in_array($row['tag'], ['link'], \true))) {
                $checkMarkup[] = $row['id'];
            }
        }
        if (\count($checkMarkup)) {
            $this->checkIfMarkupIsBlocked($checkMarkup, $result);
        }
        return $result;
    }
    /**
     * Check server-side via PHP if the markup got blocked by an existing content blocker.
     *
     * @param int[] $ids
     * @param array $result
     */
    protected function checkIfMarkupIsBlocked($ids, &$result)
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        $table_name_markup = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME_MARKUP);
        \add_filter('RCB/Blocker/Enabled', '__return_true');
        // We need to ensure to not block too many memory so lets get distinct MD5 hashes
        // of the markups and afterwards check it instead of getting markups directly by our record IDs
        $sql = \sprintf("SELECT\n                s.tag,\n                CASE\n                    WHEN m.markup LIKE '<%%' THEN '%s'\n                    WHEN s.tag = 'script' THEN '%s'\n                    WHEN s.tag = 'style' THEN '%s'\n                    ELSE '%s'\n                END AS mime,\n                m.markup,\n                m.markup_hash,\n                GROUP_CONCAT(s.id) AS ids\n            FROM {$table_name} s\n            INNER JOIN {$table_name_markup} m\n                ON s.markup_hash = m.markup_hash\n            WHERE s.id IN (%s)\n            GROUP BY markup_hash", self::MIME_TYPE_HTML, self::MIME_TYPE_JAVASCRIPT, self::MIME_TYPE_CSS, self::MIME_TYPE_HTML, \join(',', $ids));
        // phpcs:disable WordPress.DB.PreparedSQL
        $distinctMarkups = $wpdb->get_results($sql, ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        foreach ($distinctMarkups as $distinctMarkup) {
            if ($distinctMarkup['mime'] !== self::MIME_TYPE_HTML) {
                $distinctMarkup['markup'] = \sprintf('<%1$s>%2$s</%1$s>', $distinctMarkup['tag'], $distinctMarkup['markup']);
            }
            $distinctMarkup['markup'] = \apply_filters('Consent/Block/HTML', $distinctMarkup['markup']);
            $isBlocked = \strpos($distinctMarkup['markup'], Constants::HTML_ATTRIBUTE_COOKIE_IDS . '=') !== \false;
            if ($isBlocked) {
                $recordIds = \array_map('intval', \explode(',', $distinctMarkup['ids']));
                foreach ($result as &$row) {
                    if (\in_array($row['id'], $recordIds, \true)) {
                        $row['blocked'] = \true;
                    }
                }
            }
        }
        \remove_filter('RCB/Blocker/Enabled', '__return_true');
    }
    /**
     * Provide a count for all scan results.
     *
     * @param array $revision
     */
    public function revisionCurrent($revision)
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $counts = $wpdb->get_col("SELECT COUNT(1) FROM (SELECT COUNT(1) FROM {$table_name} WHERE preset <> '' GROUP BY preset) t\n        UNION ALL SELECT COUNT(1) FROM (SELECT COUNT(1) FROM {$table_name} WHERE blocked_url_host IS NOT NULL AND preset = '' GROUP BY blocked_url_host) t");
        // phpcs:enable WordPress.DB.PreparedSQL
        $revision['all_scanner_result_templates_count'] = \intval($counts[0]);
        $revision['all_scanner_result_external_urls_count'] = \intval($counts[1]);
        return $revision;
    }
    /**
     * Calculate all existing expressions for blocked URLs.
     */
    protected function getExistingUrlExpressions()
    {
        global $wpdb;
        // Get all existing rules from existing posts
        // phpcs:disable WordPress.DB.PreparedSQL
        $rules = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM {$wpdb->postmeta} pm\n            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id\n            WHERE p.post_type = %s AND pm.meta_key = %s", Blocker::CPT_NAME, Blocker::META_NAME_RULES));
        // phpcs:enable WordPress.DB.PreparedSQL
        // The rules is multiline (coming from textarea), let's split into an array
        $rulesArray = [];
        foreach ($rules as $rule) {
            $rulesArray = \array_merge($rulesArray, \explode("\n", $rule));
        }
        $urlExpressions = [];
        foreach (\array_filter($rulesArray) as $rule) {
            // Filter out custom element expressions
            if (SelectorSyntaxFinder::fromExpression($rule) === \false) {
                $urlExpressions[] = $rule;
            }
        }
        return $urlExpressions;
    }
    /**
     * Transform a set of expression strings to a valid SQL statement. The regular
     * expressions are combined with `OR` so you can check e.g. an URL column if it matches.
     *
     * @param string[] $expressions
     * @param string $column
     */
    protected function transformExpressionsToMySQL($expressions, $column)
    {
        global $wpdb;
        $result = [];
        foreach ($expressions as $expression) {
            $result[] = \sprintf('%s LIKE %s', $column, $wpdb->prepare('%s', \str_replace('*', '%', $expression)));
        }
        return \count($result) > 0 ? \join(' OR ', $result) : '1=0';
    }
}
