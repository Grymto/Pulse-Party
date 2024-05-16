<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\BlockableScanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\ScannableBlockable;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Cache;
use DevOwl\RealCookieBanner\comp\ComingSoonPlugins;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\templates\StorageHelper;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\Blocker as BlockerView;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\blockable\BlockerPostType;
use DevOwl\RealCookieBanner\view\Scanner as ViewScanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Job;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils as UtilsUtils;
use stdClass;
use WP_User;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * This isn't really a "cookie" scanner, it is a scanner which detects external URLs,
 * scripts, iframes for the current page request. Additionally, it can automatically
 * detect usable content blocker templates which we can recommend to the user.
 * @internal
 */
class Scanner
{
    use UtilsProvider;
    const QUERY_ARG_TOKEN = 'rcb-scan';
    const QUERY_ARG_JOB_ID = 'rcb-scan-job';
    const REAL_QUEUE_TYPE = 'rcb-scan';
    /**
     * See `findByRobots.txt`: This simulates to be the current blog to be public
     * so the `robots.txt` exposes a sitemap and also activates the sitemap.
     */
    const QUERY_ARG_FORCE_SITEMAP = 'rcb-force-sitemap';
    private $query;
    private $onChangeDetection;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        $this->query = new \DevOwl\RealCookieBanner\scanner\Query();
        $this->onChangeDetection = new \DevOwl\RealCookieBanner\scanner\OnChangeDetection($this);
    }
    /**
     * Force to enable the content blocker even when the content blocker is deactivated.
     *
     * @param boolean $enabled
     */
    public function force_blocker_enabled($enabled)
    {
        return isset($_GET[self::QUERY_ARG_TOKEN]) && Core::getInstance()->getRealQueue()->currentUserAllowedToQuery() ? \true : $enabled;
    }
    /**
     * All templates and external URLs got catched, let's persist them to database.
     */
    public function teardown()
    {
        // In unlicensed state we cannot show scanner results as we do not have any templates available
        if (!Core::getInstance()->isLicenseActive()) {
            return;
        }
        $query = $this->getQuery();
        // Get Job for this scan process
        $jobId = \intval($_GET[self::QUERY_ARG_JOB_ID] ?? 0);
        $job = $jobId > 0 ? Core::getInstance()->getRealQueue()->getQuery()->fetchById($jobId) : null;
        // Memorize all found templates and external URL hosts so we can diff on them
        list($beforeTemplates, $beforeExternalHosts) = $query->getScannedCombinedResults();
        // Delete all known scan-entries for the current URL
        $deleteSourceUrls = [self::getCurrentSourceUrl()];
        if ($job !== null) {
            // Original URL (e.g. redirects)
            $deleteSourceUrls[] = $job->data['url'];
        }
        $query->removeSourceUrls($deleteSourceUrls);
        /**
         * `BlockableScanner`.
         *
         * @var BlockableScanner
         */
        $contentBlockerScanner = Core::getInstance()->getBlocker()->getHeadlessContentBlocker()->getPluginsByClassName(BlockableScanner::class)[0];
        $contentBlockerScanner->filterFalsePositives();
        $scanEntries = $contentBlockerScanner->flushResults();
        // Persist scan entries to database but only when the current URL is not a `/wp-admin` URL.
        // When can this happen? This can happen, when a Custom Post Type is configured `public`
        // but it has a custom implementation that the preview URL redirects back to the edit screen.
        if (!\is_admin()) {
            $persist = new \DevOwl\RealCookieBanner\scanner\Persist($scanEntries);
            $persist->persist();
            list($afterTemplates, $afterExternalHosts) = $query->getScannedCombinedResults();
            $this->doActionAddedRemoved($scanEntries, $beforeTemplates, $beforeExternalHosts, $afterTemplates, $afterExternalHosts);
        }
        // Print result as HTML comment
        \printf('<!--rcb-scan:%s-->', \json_encode($scanEntries));
        // Get real-queue options so we can use this page request instead of an additional `/status` request
        $realQueueParams = $_GET[self::QUERY_ARG_TOKEN] ?? null;
        if ($realQueueParams !== null) {
            $realQueueParams = UtilsUtils::isJson(\base64_decode(\trim($realQueueParams, '-')));
            if (\is_array($realQueueParams)) {
                \printf('
    <!--real-queue-status:%s-->', \json_encode(Core::getInstance()->getRealQueue()->getRestQuery()->buildStatus($realQueueParams)));
            }
        }
        // Mark Job as succeed
        if ($job !== null) {
            $job->passthruClientResult(\true, null);
        }
        $this->probablyInvalidateCacheAfterJobExecution($job);
    }
    /**
     * Try to invalidate some caches after every scan process. The cache is invalidated
     * in the following cases:
     *
     * - Scan process was done without job (e.g. calling website with `?rcb-scan`)
     * - Scan process was done for a single website (e.g. after saving a post)
     * - Scan process is within a complete website then, then the following situation is implemented:
     *   Depending on the count of scanned entries, every x. job within the complete website scan
     *   invalidated the cache.
     *
     * @param Job $job
     */
    protected function probablyInvalidateCacheAfterJobExecution($job)
    {
        $doInvalidate = \false;
        if ($job !== null) {
            $isCompleteWebsiteScan = !empty($job->group_position);
            if ($isCompleteWebsiteScan) {
                $scannedEntriesCount = $this->getQuery()->getScannedEntriesCount();
                $invalidateScannedExternalUrlsAfter = 1;
                if ($scannedEntriesCount > 50000) {
                    $invalidateScannedExternalUrlsAfter = 15;
                } elseif ($scannedEntriesCount > 20000) {
                    $invalidateScannedExternalUrlsAfter = 10;
                } elseif ($scannedEntriesCount > 10000) {
                    $invalidateScannedExternalUrlsAfter = 5;
                } elseif ($scannedEntriesCount > 5000) {
                    $invalidateScannedExternalUrlsAfter = 3;
                } elseif ($scannedEntriesCount > 1000) {
                    $invalidateScannedExternalUrlsAfter = 2;
                }
                if ($job->group_position % $invalidateScannedExternalUrlsAfter === 0 || $job->group_position === $job->group_total) {
                    $doInvalidate = \true;
                }
            } else {
                $doInvalidate = \true;
            }
        } else {
            $doInvalidate = \true;
        }
        if ($doInvalidate) {
            \delete_transient(ViewScanner::TRANSIENT_SERVICES_FOR_NOTICE);
            \delete_transient(\DevOwl\RealCookieBanner\scanner\Query::TRANSIENT_SCANNED_EXTERNAL_URLS);
        }
        return $doInvalidate;
    }
    /**
     * `do_action` when a result from the scanner got removed or added.
     *
     * @param ScanEntry[] $results
     * @param string[] $beforeTemplates
     * @param string[] $beforeExternalHosts
     * @param string[] $afterTemplates
     * @param string[] $afterExternalHosts
     */
    protected function doActionAddedRemoved($results, $beforeTemplates, $beforeExternalHosts, $afterTemplates, $afterExternalHosts)
    {
        $changed = \false;
        $addedTemplates = [];
        $removeTemplates = [];
        $addedExternalHosts = [];
        $removedExternalHosts = [];
        // Check if new template / external URL host was found
        foreach ($afterTemplates as $afterTemplate) {
            if (!\in_array($afterTemplate, $beforeTemplates, \true)) {
                /**
                 * A new template was found in the scanner.
                 *
                 * @param {string} $template Example: "google-fonts"
                 * @param {ScanEntry[]} $scanEntries See [ScanEntry](../php/classes/DevOwl-HeadlessContentBlocker-plugins-scanner-ScanEntry.html) class
                 * @hook RCB/Scanner/Preset/Found
                 * @see [`RCB/Scanner/Result/Updated`](./RCB_Scanner_Result_Updated.html)
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/Preset/Found', $afterTemplate, $results);
                $changed = \true;
                $addedTemplates[] = $afterTemplate;
            }
        }
        foreach ($afterExternalHosts as $afterExternalHost) {
            if (!\in_array($afterExternalHost, $beforeExternalHosts, \true)) {
                /**
                 * A new external host was found for the scanner.
                 *
                 * @param {string} $host Example: external.example.com
                 * @param {ScanEntry[]} $scanEntries See [ScanEntry](../php/classes/DevOwl-HeadlessContentBlocker-plugins-scanner-ScanEntry.html) class
                 * @hook RCB/Scanner/ExternalHost/Found
                 * @see [`RCB/Scanner/Result/Updated`](./RCB_Scanner_Result_Updated.html)
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/ExternalHost/Found', $afterExternalHost, $results);
                $changed = \true;
                $addedExternalHosts[] = $afterExternalHost;
            }
        }
        // Check if template / external URL host got removed
        foreach ($beforeTemplates as $beforeTemplate) {
            if (!\in_array($beforeTemplate, $afterTemplates, \true)) {
                /**
                 * A template was removed from the scanner results as it is no longer found on your site.
                 *
                 * @param {string} $template Example: "google-fonts"
                 * @param {ScanEntry[]} $scanEntries See [ScanEntry](../php/classes/DevOwl-HeadlessContentBlocker-plugins-scanner-ScanEntry.html) class
                 * @hook RCB/Scanner/Preset/Removed
                 * @see [`RCB/Scanner/Result/Updated`](./RCB_Scanner_Result_Updated.html)
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/Preset/Removed', $beforeTemplate, $results);
                $changed = \true;
                $removeTemplates[] = $beforeTemplate;
            }
        }
        foreach ($beforeExternalHosts as $beforeExternalHost) {
            if (!\in_array($beforeExternalHost, $afterExternalHosts, \true)) {
                /**
                 * An external host was removed from the scanner results as it is no longer found on your site.
                 *
                 * @param {string} $host Example: "external.example.com"
                 * @param {ScanEntry[]} $scanEntries See [ScanEntry](../php/classes/DevOwl-HeadlessContentBlocker-plugins-scanner-ScanEntry.html) class
                 * @hook RCB/Scanner/ExternalHost/Removed
                 * @see [`RCB/Scanner/Result/Updated`](./RCB_Scanner_Result_Updated.html)
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/ExternalHost/Removed', $beforeExternalHost, $results);
                $changed = \true;
                $removedExternalHosts[] = $beforeExternalHost;
            }
        }
        if ($changed) {
            /**
             * New items (templates and external hosts) got added or removed from the scanner result.
             *
             * Related filters:
             *
             * - [`RCB/Scanner/ExternalHost/Found`](./RCB_Scanner_ExternalHost_Found.html)
             * - [`RCB/Scanner/ExternalHost/Removed`](./RCB_Scanner_ExternalHost_Removed.html)
             * - [`RCB/Scanner/Preset/Found`](./RCB_Scanner_Preset_Found.html)
             * - [`RCB/Scanner/Preset/Removed`](./RCB_Scanner_Preset_Removed.html)
             *
             * @param {string[]} $addedTemplates
             * @param {string[]} $addedExternalHosts
             * @param {string[]} $removeTemplates
             * @param {string[]} $removedExternalHosts
             * @param {ScanEntry[]} $scanEntries See [ScanEntry](../php/classes/DevOwl-HeadlessContentBlocker-plugins-scanner-ScanEntry.html) class
             * @hook RCB/Scanner/Result/Updated
             * @since 2.6.0
             */
            \do_action('RCB/Scanner/Result/Updated', $addedTemplates, $addedExternalHosts, $removeTemplates, $removedExternalHosts, $results);
        }
    }
    /**
     * Add all known and non-disabled content blocker templates.
     *
     * @param AbstractBlockable[] $blockables
     * @param HeadlessContentBlocker $headlessContentBlocker
     */
    public function resolve_blockables($blockables, $headlessContentBlocker)
    {
        global $wpdb;
        // Remove all known blockables because we want to show all found services (and label them with "Already created")
        foreach ($blockables as $key => $blockable) {
            if ($blockable instanceof BlockerPostType) {
                unset($blockables[$key]);
            }
        }
        $table_name = $this->getTableName(StorageHelper::TABLE_NAME);
        // phpcs:disable WordPress.DB
        $blockers = $wpdb->get_results($wpdb->prepare("SELECT identifier, other_meta\n                    FROM {$table_name}\n                    WHERE context = %s AND `type` = %s AND is_disabled = 0 AND is_outdated = 0", TemplateConsumers::getContext(), StorageHelper::TYPE_BLOCKER), ARRAY_A);
        // phpcs:enable WordPress.DB
        foreach ($blockers as $blocker) {
            $identifier = $blocker['identifier'];
            $otherMeta = \json_decode($blocker['other_meta'], ARRAY_A);
            $extendsIdentifier = $otherMeta['extendsIdentifier'] ?? null;
            $rules = $otherMeta['rules'] ?? [];
            $ruleGroups = $otherMeta['ruleGroups'] ?? [];
            if (\count($rules) > 0) {
                $blockables[] = new ScannableBlockable($headlessContentBlocker, $identifier, $extendsIdentifier, $rules, $ruleGroups);
            }
        }
        return $blockables;
    }
    /**
     * Check if the current page request should be scanned.
     */
    public function isActive()
    {
        return isset($_GET[self::QUERY_ARG_TOKEN]) && Core::getInstance()->getRealQueue()->currentUserAllowedToQuery();
    }
    /**
     * Force sitemaps in
     */
    public function probablyForceSitemaps()
    {
        if (!isset($_GET[\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_FORCE_SITEMAP])) {
            return;
        }
        $cbEnableSitemaps = function ($val) {
            return \function_exists('wp_get_current_user') && \current_user_can(Core::MANAGE_MIN_CAPABILITY) ? \true : $val;
        };
        $cbStylesheetUrl = function ($url) {
            return \add_query_arg([self::QUERY_ARG_FORCE_SITEMAP => 1], $url);
        };
        \add_filter('pre_option_blog_public', $cbEnableSitemaps, 999);
        \add_filter('wp_sitemaps_enabled', $cbEnableSitemaps, 999);
        \add_filter('wp_sitemaps_index_entry', function ($entry) {
            if (isset($entry['loc'])) {
                $entry['loc'] = \add_query_arg([self::QUERY_ARG_FORCE_SITEMAP => 1], $entry['loc']);
            }
            return $entry;
        }, \PHP_INT_MAX);
        \add_filter('wp_sitemaps_stylesheet_index_url', $cbStylesheetUrl, \PHP_INT_MAX);
        \add_filter('wp_sitemaps_stylesheet_url', $cbStylesheetUrl, \PHP_INT_MAX);
    }
    /**
     * Add URLs to the queue so they get scanned.
     *
     * @param string[] $urls
     * @param boolean $purgeUnused  If `true`, the difference of the previous scanned URLs gets
     *                              automatically purged if they do no longer exist in the URLs (pass only if you have the complete sitemap!)
     */
    public function addUrlsToQueue($urls, $purgeUnused = \false)
    {
        global $wpdb;
        if ($purgeUnused) {
            $urls = \array_values(\array_merge(ComingSoonPlugins::getInstance()->getComputedUrlsForSitemap(), $urls));
        }
        // In unlicensed state we cannot start the scanner as we do not have any templates available
        if (!Core::getInstance()->isLicenseActive()) {
            return 0;
        }
        $queue = Core::getInstance()->getRealQueue();
        $persist = $queue->getPersist();
        $query = $queue->getQuery();
        $persist->startTransaction();
        // Only sitemap crawlers should be grouped
        if ($purgeUnused) {
            $persist->startGroup();
        }
        foreach ($urls as $url) {
            if (\filter_var($url, \FILTER_VALIDATE_URL)) {
                // Check if this URL belongs to our current installation with a loose "contains" check
                // from the original home URL as external URLs could never be scanned due to CORS errors.
                $homeUrl = Utils::getOriginalHomeUrl();
                $scheme = \parse_url($homeUrl, \PHP_URL_SCHEME) ?? '';
                $homeUrl = \trim(\substr($homeUrl, \strlen($scheme)), ':/');
                if (\stripos($url, $homeUrl) === \false) {
                    continue;
                }
                // Check if a Job with this URL already exists
                if (!$purgeUnused) {
                    $found = $query->read(['type' => 'all', 'dataContains' => \json_encode($url)]);
                    if (\count($found) > 0) {
                        continue;
                    }
                }
                $job = new Job($queue);
                $job->worker = Job::WORKER_CLIENT;
                $job->type = self::REAL_QUEUE_TYPE;
                $job->data = new stdClass();
                $job->data->url = $url;
                $job->retries = 3;
                $persist->addJob($job);
            }
        }
        if ($purgeUnused) {
            // This is a complete sitemap
            $this->purgeUnused($urls);
            Cache::getInstance()->invalidate();
            // Update URLs in existing scanner results to avoid issues when a website got cloned (e.g. `source_url_hash` represents the old URL)
            $table_name = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
            // phpcs:disable WordPress.DB
            $wpdb->query("UPDATE IGNORE {$table_name} SET source_url_hash = MD5(source_url)");
            $wpdb->query("DELETE FROM {$table_name} WHERE source_url_hash <> MD5(source_url)");
            // phpcs:enable WordPress.DB
        }
        return $persist->commit();
    }
    /**
     * Read a group of all known site URLs and delete them if they no longer exist in the passed URLs.
     *
     * @param string[] $urls
     */
    public function purgeUnused($urls)
    {
        $knownUrls = $this->getQuery()->getScannedSourceUrls();
        $deleted = \array_values(\array_diff($knownUrls, $urls));
        $this->getQuery()->removeSourceUrls($deleted);
        return \count($deleted);
    }
    /**
     * Get human-readable label for RCB queue jobs.
     *
     * @param string $label
     * @param string $originalType
     */
    public function real_queue_job_label($label, $originalType)
    {
        switch ($originalType) {
            case self::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner: Scan of your pages', RCB_TD);
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner: Automatic scan of complete website', RCB_TD);
            default:
                return $label;
        }
    }
    /**
     * Get actions for RCB queue jobs.
     *
     * @param array[] $actions
     * @param string $type
     */
    public function real_queue_job_actions($actions, $type)
    {
        switch ($type) {
            case self::REAL_QUEUE_TYPE:
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                $actions[] = ['url' => \__('https://devowl.io/support/', RCB_TD), 'linkText' => \__('Contact support', RCB_TD)];
                $actions[] = ['action' => 'delete', 'linkText' => \__('Cancel scan', RCB_TD)];
                $actions[] = ['action' => 'skip', 'linkText' => \__('Skip failed pages', RCB_TD)];
                break;
            default:
        }
        return $actions;
    }
    /**
     * Get human-readable description for a RCB queue jobs.
     *
     * @param string $description
     * @param string $type
     * @param int[] $remaining
     */
    public function real_queue_error_description($description, $type, $remaining)
    {
        switch ($type) {
            case self::REAL_QUEUE_TYPE:
                return \sprintf(
                    // translators:
                    \_n('%1$d pages failed to be scanned.', '%1$d pages failed to be scanned.', $remaining['failure'], RCB_TD),
                    $remaining['failure']
                );
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner tried to automatically scan your entire website for services and external URLs.', RCB_TD);
            default:
                return $description;
        }
    }
    /**
     * Remove some capabilities and roles from the current user for the running page request.
     * For example, some Google Analytics plugins do only print out the analytics code when
     * not `manage_options` (e.g. WooCommerce Google Analytics).
     *
     * @param array $caps
     * @see https://regex101.com/r/3U3miS/1
     */
    public function reduceCurrentUserPermissions()
    {
        global $current_user;
        \add_action('user_has_cap', function ($caps) {
            $caps['administrator'] = \false;
            $caps['manage_options'] = \false;
            if (isset($caps['manage_woocommerce'])) {
                $caps['manage_woocommerce'] = \false;
            }
            return $caps;
        });
        if ($current_user instanceof WP_User && \count($current_user->roles) > 0) {
            $current_user->roles = \array_filter($current_user->roles, function ($role) {
                return !\in_array($role, ['administrator'], \true);
            });
            // We never should write back roles back to database!
            // In general, never update a user meta as it is not needed while scanning a site.
            \add_filter('update_user_metadata', '__return_false', \PHP_INT_MAX);
            \add_filter('add_user_metadata', '__return_false', \PHP_INT_MAX);
        }
        // [Plugin Comp] WoodMart theme (bypass "You do not have access" for `?cms_block=` URLs)
        \add_filter('woodmart_html_block_access', function () {
            return 'read';
        });
        // [Plugin Comp] RankMath
        \add_filter('rank_math/analytics/gtag_exclude_loggedin_roles', function () {
            return [];
        });
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getQuery()
    {
        return $this->query;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getOnChangeDetection()
    {
        return $this->onChangeDetection;
    }
    /**
     * New instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\scanner\Scanner();
    }
    /**
     * Get the current source URL usable for a newly created `ScanEntry`. It gets escaped for database use with
     * the help of `esc_url_raw`.
     */
    public static function getCurrentSourceUrl()
    {
        $result = \remove_query_arg(\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_TOKEN, Utils::getRequestUrl());
        $result = \remove_query_arg(\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_JOB_ID, $result);
        $result = \remove_query_arg(BlockerView::FORCE_TIME_COMMENT_QUERY_ARG, $result);
        return $result;
    }
}
