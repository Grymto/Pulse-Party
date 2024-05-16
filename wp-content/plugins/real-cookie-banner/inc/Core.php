<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\CookieConsentManagement;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\Settings;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\Core as CustomizeCore;
use DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset\AnonymousAssetBuilder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\ScriptInlineMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractLanguagePlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core as RealQueue;
use DevOwl\RealCookieBanner\base\Core as BaseCore;
use DevOwl\RealCookieBanner\comp\ComingSoonPlugins;
use DevOwl\RealCookieBanner\comp\DatabaseUpgrades;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileMigrationMajor2;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileMigrationMajor3;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileMigrationMajor4;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileTcfV2IllegalUsage;
use DevOwl\RealCookieBanner\comp\TemplatesPluginIntegrations;
use DevOwl\RealCookieBanner\comp\ThirdPartyNotices;
use DevOwl\RealCookieBanner\lite\Core as LiteCore;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\lite\tcf\TcfVendorListNormalizer;
use DevOwl\RealCookieBanner\overrides\interfce\IOverrideCore;
use DevOwl\RealCookieBanner\rest\Templates;
use DevOwl\RealCookieBanner\rest\Config;
use DevOwl\RealCookieBanner\rest\Import;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\view\Banner;
use DevOwl\RealCookieBanner\view\ConfigPage;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\settings\Multisite;
use DevOwl\RealCookieBanner\rest\Consent as RestConsent;
use DevOwl\RealCookieBanner\rest\Stats as RestStats;
use DevOwl\RealCookieBanner\rest\Scanner as RestScanner;
use DevOwl\RealCookieBanner\scanner\AutomaticScanStarter;
use DevOwl\RealCookieBanner\scanner\Scanner;
use DevOwl\RealCookieBanner\settings\BannerLink;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\GoogleConsentMode;
use DevOwl\RealCookieBanner\settings\Reset;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\Blocker as ViewBlocker;
use DevOwl\RealCookieBanner\view\checklist\ActivateBanner;
use DevOwl\RealCookieBanner\view\checklist\AddCookie;
use DevOwl\RealCookieBanner\view\checklist\SaveSettings;
use DevOwl\RealCookieBanner\view\shortcode\LinkShortcode;
use DevOwl\RealCookieBanner\view\shortcode\HistoryUuidsShortcode;
use DevOwl\RealCookieBanner\view\Scanner as ViewScanner;
use DevOwl\RealCookieBanner\view\navmenu\NavMenuLinks;
use DevOwl\RealCookieBanner\view\Notices;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealUtils\Core as RealUtilsCore;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\FixInvalidJsonInDb;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ServiceNoStore;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Singleton core class which handles the main system for plugin. It includes
 * registering of the autoload, all hooks (actions & filters) (see BaseCore class).
 * @internal
 */
class Core extends BaseCore implements IOverrideCore
{
    use LiteCore;
    use CustomizeCore;
    /**
     * Add the minimum required capability so a user can manage cookies to all users,
     * which also have the `manage_options` capability.
     */
    const ADD_MANAGE_MIN_CAPABILITY_TO_ALL_USERS_WITH = 'manage_options';
    /**
     * The minimal required capability so a user can manage cookies.
     */
    const MANAGE_MIN_CAPABILITY = 'manage_real_cookie_banner';
    /**
     * Singleton instance.
     *
     * @var Core
     */
    private static $me = null;
    /**
     * The config page.
     *
     * @var ConfigPage
     */
    private $configPage;
    /**
     * The banner.
     *
     * @var Banner
     */
    private $banner;
    /**
     * The blocker.
     *
     * @var ViewBlocker
     */
    private $blocker;
    /**
     * An unique id for this page request. This is e. g. needed for unique overlay id.
     *
     * @var string
     */
    private $pageRequestUuid4;
    /**
     * See AbstractLanguagePlugin.
     *
     * @var AbstractLanguagePlugin
     */
    private $compLanguage;
    /**
     * See AdInitiator.
     *
     * @var AdInitiator
     */
    private $adInitiator;
    /**
     * See RpmInitiator.
     *
     * @var RpmInitiator
     */
    private $rpmInitiator;
    /**
     * See AnonymousAssetBuilder.
     *
     * @var AnonymousAssetBuilder
     */
    private $anonymousAssetBuilder;
    /**
     * See TcfVendorListNormalizer.
     *
     * @var TcfVendorListNormalizer
     */
    private $tcfVendorListNormalizer;
    /**
     * See ExcludeAssets.
     *
     * @var ExcludeAssets
     */
    private $excludeAssets;
    /**
     * See Notices.
     *
     * @var Notices
     */
    private $notices;
    /**
     * See Scanner.
     *
     * @var Scanner
     */
    private $scanner;
    /**
     * See RealQueue.
     *
     * @var RealQueue
     */
    private $realQueue;
    /**
     * See CookieConsentManagement.
     *
     * @var CookieConsentManagement
     */
    private $cookieConsentManagement;
    /**
     * Application core constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        // This line is needed so all singleton instances are correctly configured with `@devowl-wp/cookie-consent-management`
        // e.g. by setting the `Settings` instance.
        $this->getCookieConsentManagement();
        // The Uuid4 must start with a non-number character to work with CSS selectors
        $this->pageRequestUuid4 = 'a' . \wp_generate_uuid4();
        $this->blocker = ViewBlocker::instance();
        $this->scanner = Scanner::instance();
        $this->realQueue = new RealQueue($this);
        $this->configPage = ConfigPage::instance();
        $this->banner = Banner::instance();
        $this->notices = new Notices($this);
        $automaticScanStarter = AutomaticScanStarter::instance();
        $templatesPluginIntegrations = TemplatesPluginIntegrations::getInstance();
        $this->realQueue->addCapability(self::MANAGE_MIN_CAPABILITY);
        // Create multilingual instance
        $path = \untrailingslashit(\plugin_dir_path(RCB_FILE)) . $this->getPluginData('DomainPath');
        $mo = \trailingslashit($path) . RCB_TD . '-%s.mo';
        $this->compLanguage = AbstractLanguagePlugin::determineImplementation(RCB_TD, $mo, \DevOwl\RealCookieBanner\Localization::class);
        \DevOwl\RealCookieBanner\Localization::enableWordPressDotOrgLanguagePacksDownload(RCB_SLUG, RCB_SLUG_LITE);
        // Enable `no-store` for our relevant WP REST API endpoints
        ServiceNoStore::hook('/' . Service::getNamespace($this));
        ServiceNoStore::hook('/wp/v2/rcb-');
        // Custom Post Types
        // Fix technicalDefinitions hosts modified by migration tools
        (new FixInvalidJsonInDb())->fixMetadataBySingleMetaKey(Cookie::META_NAME_TECHNICAL_DEFINITIONS);
        // Official Consent API
        \add_filter('Consent/Block/HTML', [$this->getBlocker(), 'replace']);
        \add_action('init', [$templatesPluginIntegrations, 'init'], 0);
        \add_action('init', [ComingSoonPlugins::getInstance(), 'init'], 11);
        \add_action('init', [ThirdPartyNotices::getInstance(), 'init']);
        \add_action('init', [$this, 'registerPostTypes'], 0);
        // E.g. WooCommerce does not know at 10 priority about the custom post types
        \add_action('RCB/Templates/FalsePositivePlugin', [$templatesPluginIntegrations, 'templates_plugin_false_positives'], 10, 4);
        \add_action('activated_plugin', [$this->getActivator(), 'anyPluginToggledState']);
        \add_action('deactivated_plugin', [$this->getActivator(), 'anyPluginToggledState']);
        \add_action('activated_plugin', [$this->getNotices(), 'anyPluginToggledState'], 10, 2);
        \add_action('deactivated_plugin', [$this->getNotices(), 'anyPluginToggledState'], 10, 2);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_scanner_rerun_after_plugin_toggle']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_service_using_template_which_got_deleted']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_tcf_too_much_vendors']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notices_services_with_empty_privacy_policy']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notices_check_saving_consent_via_rest_api_endpoint_working']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_services_with_updated_templates']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_services_with_successor_templates']);
        \add_action('admin_notices', [$this->getNotices(), 'admin_notice_services_with_google_consent_mode_adjustments']);
        \add_action('admin_init', [$automaticScanStarter, 'probablyAddClientJob']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [$automaticScanStarter, 'probablyAddClientJob']);
        \add_action('admin_init', [$this, 'registerSettings'], 1);
        \add_action('rest_api_init', [$this, 'registerSettings'], 1);
        \add_action('plugins_loaded', [\DevOwl\RealCookieBanner\Localization::class, 'multilingual'], 1);
        \add_action('wp', [$this->getAssets(), 'createHashedAssets']);
        \add_action('login_init', [$this->getAssets(), 'createHashedAssets']);
        \add_action('plugins_loaded', [$this->getBlocker(), 'registerOutputBuffer'], ViewBlocker::OB_START_PLUGINS_LOADED_PRIORITY);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [TCF::getInstance(), 'new_version_installation']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [new DatabaseUpgrades(), 'apply']);
        \add_filter('RCB/Blocker/Enabled', [$this->getScanner(), 'force_blocker_enabled']);
        \add_filter('customize_save_response', [$this, 'customize_save_response'], 10, 1);
        \add_filter('option_' . Consent::SETTING_COOKIE_DURATION, [Consent::getInstance(), 'option_cookie_duration']);
        \add_filter('option_' . Consent::SETTING_CONSENT_DURATION, [Consent::getInstance(), 'option_consent_duration']);
        \add_action('update_option_' . Consent::SETTING_CONSENT_DURATION, [Consent::getInstance(), 'update_option_consent_transient_deletion'], 10, 2);
        \add_action('update_option_' . General::SETTING_BANNER_ACTIVE, [$this->getNotices(), 'update_option_banner_active']);
        \add_filter('pre_update_option', function ($value, $optionName, $oldValue) {
            // See https://wordpress.stackexchange.com/a/330290/83335
            if ($value !== $oldValue && \in_array($optionName, [General::SETTING_SET_COOKIES_VIA_MANAGER, GoogleConsentMode::SETTING_GCM_ENABLED], \true)) {
                TemplateConsumers::getInstance()->forceRecalculation();
            }
            return $value;
        }, 10, 3);
        // Cache relevant hooks
        \add_filter('RCB/Customize/Updated', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'customize_updated']);
        \add_filter('RCB/Settings/Updated', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'settings_updated']);
        \add_filter('RCB/Settings/Updated', [SaveSettings::class, 'settings_updated']);
        \add_filter('RCB/Settings/Updated', [ActivateBanner::class, 'settings_updated'], 10, 2);
        \add_filter('RCB/Settings/Updated', [\DevOwl\RealCookieBanner\UserConsent::getInstance(), 'settings_updated']);
        \add_filter('RCB/Revision/Hash', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'revision_hash']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [\DevOwl\RealCookieBanner\Cache::getInstance(), 'invalidate']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [TemplateConsumers::getInstance(), 'licenseStatusChanged']);
        // Compatibility hooks (Blocker)
        \add_filter('ez_buffered_final_content', [$this->getBlocker(), 'replace'], 1);
        // Ezoic CDN compatibility
        \add_filter('rocket_buffer', [$this->getBlocker(), 'replace'], 1);
        // WP Rocket Lazy loading compatibility
        \add_filter('wp-meteor-frontend-rewrite', [$this->getBlocker(), 'replace']);
        // WP Meteor
        \add_filter('ionos_performancemodify_output', [$this->getBlocker(), 'replace']);
        // IONOS Performance plugin
        \add_filter('ionos_performance_modify_output', [$this->getBlocker(), 'replace']);
        // IONOS Performance plugin (v2)
        \add_filter('facetwp_facet_html', [$this->getBlocker(), 'replace']);
        // Facet WP facets
        \add_filter('wp_grid_builder/async/render_response', [$this->getBlocker(), 'replace']);
        // WP Grid builder refresh ajax action
        \add_filter('autoptimize_filter_html_before_minify', [$this->getBlocker(), 'replace']);
        // Autoptimize
        \add_filter('autoptimize_filter_css_exclude', [$this->getBlocker(), 'autoptimize_filter_css_exclude']);
        \add_filter('avf_exclude_assets', [$this->getBlocker(), 'avf_exclude_assets']);
        \add_filter('litespeed_buffer_before', [$this->getBlocker(), 'replace'], 1);
        // LiteSpeed Cache
        \add_filter('litespeed_ccss_url', [$this->getBlocker(), 'modifyUrlToSkipContentBlocker']);
        // LiteSpeed Cache Critical CSS
        \add_filter('litespeed_ucss_url', [$this->getBlocker(), 'modifyUrlToSkipContentBlocker']);
        // LiteSpeed Cache Unique CSS
        \add_filter('us_grid_listing_post', [$this->getBlocker(), 'replace']);
        // Impreza Lazy Loading posts
        \add_filter('fl_builder_render_js', [ScriptInlineMatcher::class, 'makeInlineScriptSkippable']);
        // Beaver Builder and inline scripts (https://docs.wpbeaverbuilder.com/beaver-builder/developer/how-to-tips/load-css-and-javascript-inline/)
        \add_action('shutdown', function () {
            // [Theme Comp] Themify
            if (\has_action('shutdown', ['TFCache', 'tf_cache_end']) !== \false) {
                echo $this->getBlocker()->replace(\ob_get_clean());
            }
        }, 0);
        \add_action('init', [$this->getBlocker(), 'registerOutputBuffer'], 11);
        // SiteGround Optimizer (https://wordpress.org/support/topic/allow-to-modify-html-in-output-buffer/)
        \add_action('shutdown', [$this->getBlocker(), 'closeOutputBuffer'], 11);
        // "
        \add_shortcode(LinkShortcode::TAG, [LinkShortcode::class, 'render']);
        \add_shortcode(HistoryUuidsShortcode::TAG, [HistoryUuidsShortcode::class, 'render']);
        \add_shortcode(HistoryUuidsShortcode::DEPRECATED_TAG, [HistoryUuidsShortcode::class, 'render']);
        $this->adInitiator = new \DevOwl\RealCookieBanner\AdInitiator();
        $this->adInitiator->start();
        $this->rpmInitiator = new \DevOwl\RealCookieBanner\RpmInitiator();
        $this->rpmInitiator->start();
        $this->anonymousAssetBuilder = new AnonymousAssetBuilder($this->getTableName(AnonymousAssetBuilder::TABLE_NAME), RCB_OPT_PREFIX, \trailingslashit(RCB_PATH) . $this->getAssets()->getPublicFolder());
        if ($this->isPro()) {
            $this->tcfVendorListNormalizer = new TcfVendorListNormalizer(RCB_DB_PREFIX, Service::getExternalContainerUrl('rcb') . '1.0.0/tcf/gvl/', $this->getCompLanguage());
        }
        $this->getScanner()->probablyForceSitemaps();
        $this->overrideConstructFreemium();
        $this->overrideConstruct();
    }
    /**
     * Define constants which relies on i18n localization loaded.
     */
    public function i18n()
    {
        parent::i18n();
        // Internal hook; see CU-jbayae
        $args = \apply_filters('RCB/Misc/ProUrlArgs', ['partner' => null, 'aid' => null]);
        // Check if affiliate Link is allowed (only when privacy policy got accepted)
        $isLicensed = !empty($this->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getCode());
        if (!$isLicensed) {
            $args['aid'] = null;
        }
        $translatedUrl = \__('https://devowl.io/go/real-cookie-banner?source=rcb-lite', RCB_TD);
        $translatedUrl = \add_query_arg($args, $translatedUrl);
        \define('RCB_PRO_VERSION', $translatedUrl);
    }
    /**
     * Register settings.
     */
    public function registerSettings()
    {
        General::getInstance()->register();
        Consent::getInstance()->register();
        Multisite::getInstance()->register();
        CountryBypass::getInstance()->register();
        TCF::getInstance()->register();
        GoogleConsentMode::getInstance()->register();
        $this->overrideRegisterSettings();
    }
    /**
     * Register post types and custom taxonomies.
     */
    public function registerPostTypes()
    {
        Cookie::getInstance()->register();
        Blocker::getInstance()->register();
        CookieGroup::getInstance()->register();
        BannerLink::getInstance()->register();
        $this->overrideRegisterPostTypes();
    }
    /**
     * The init function is fired even the init hook of WordPress. If possible
     * it should register all hooks to have them in one place.
     */
    public function init()
    {
        $this->excludeAssets = new ExcludeAssets($this);
        $templatesService = Templates::instance();
        $configService = Config::instance();
        $viewScanner = ViewScanner::instance();
        $restScanner = RestScanner::instance();
        $navMenuLinks = NavMenuLinks::instance();
        $scanner = $this->getScanner();
        $scannerQuery = $scanner->getQuery();
        $scannerOnChangeDetection = $scanner->getOnChangeDetection();
        // Register all your hooks here
        \add_action('rest_api_init', [$templatesService, 'rest_api_init']);
        \add_action('rest_api_init', [$configService, 'rest_api_init']);
        \add_action('rest_api_init', [RestConsent::instance(), 'rest_api_init']);
        \add_action('rest_api_init', [RestStats::instance(), 'rest_api_init']);
        \add_action('rest_api_init', [$restScanner, 'rest_api_init']);
        \add_action('rest_api_init', [Import::instance(), 'rest_api_init']);
        \add_action('admin_enqueue_scripts', [$this->getAssets(), 'admin_enqueue_scripts']);
        \add_action('wp_enqueue_scripts', [$this->getAssets(), 'wp_enqueue_scripts'], 0);
        \add_action('login_enqueue_scripts', [$this->getAssets(), 'login_enqueue_scripts'], 0);
        \add_action('DevOwl/RealQueue/Job/Label', [$scanner, 'real_queue_job_label'], 10, 3);
        \add_action('DevOwl/RealQueue/Job/Actions', [$scanner, 'real_queue_job_actions'], 10, 2);
        \add_action('DevOwl/RealQueue/Error/Description', [$scanner, 'real_queue_error_description'], 10, 3);
        \add_action('DevOwl/RealQueue/EnqueueScripts', [$this->getAssets(), 'real_queue_enqueue_scripts']);
        \add_action('DevOwl/RealQueue/Rest/Status/AdditionalData/rcb-scan-list', [$restScanner, 'real_queue_additional_data_list']);
        \add_action('DevOwl/RealQueue/Rest/Status/AdditionalData/rcb-scan-notice', [$restScanner, 'real_queue_additional_data_notice']);
        \add_action('admin_menu', [$this->getConfigPage(), 'admin_menu']);
        \add_filter('plugin_action_links_' . \plugin_basename(RCB_FILE), [$this->getConfigPage(), 'plugin_action_links'], 10, 2);
        \add_action('customize_register', [$this->getBanner()->getCustomize(), 'customize_register']);
        \add_action('wp_body_open', [$this->getBanner(), 'wp_body_open']);
        \add_action('wp_footer', [$this->getBanner(), 'wp_footer']);
        \add_action('login_footer', [$this->getBanner(), 'wp_footer']);
        \add_action('admin_bar_menu', [$this->getBanner(), 'admin_bar_menu'], 100);
        \add_action('admin_bar_menu', [$viewScanner, 'admin_bar_menu'], 100);
        \add_action('save_post_' . Cookie::CPT_NAME, [AddCookie::class, 'save_post'], 10, 3);
        \add_action('save_post_' . Blocker::CPT_NAME, [Blocker::getInstance(), 'save_post'], 10, 3);
        \add_action('save_post_' . Blocker::CPT_NAME, [\DevOwl\RealCookieBanner\Cache::getInstance(), 'invalidate']);
        \add_action('save_post_page', [BannerLink::getInstance(), 'save_post_page']);
        \add_action('delete_' . CookieGroup::TAXONOMY_NAME, [CookieGroup::getInstance(), 'deleted'], 10, 4);
        \add_action('edited_' . CookieGroup::TAXONOMY_NAME, [\DevOwl\RealCookieBanner\Stats::getInstance(), 'edited_group']);
        \add_action('deleted_post', [Blocker::getInstance(), 'deleted_post']);
        \add_action('admin_notices', [$this->getConfigPage(), 'admin_notices_preinstalled_environment']);
        \add_action('admin_notices', [$this->getConfigPage(), 'admin_notices_ad_blocker']);
        \add_action('posts_where', [\DevOwl\RealCookieBanner\Utils::class, 'posts_where_find_in_set'], 10, 2);
        \add_action('post_updated', [$scannerOnChangeDetection, 'post_updated'], 10, 3);
        \add_action('save_post', [$scannerOnChangeDetection, 'save_post'], 10, 2);
        \add_action('delete_post', [$scannerOnChangeDetection, 'delete_post'], 10);
        \add_action('wp_trash_post', [$scannerOnChangeDetection, 'wp_trash_post']);
        \add_action('untrash_post', [$scannerOnChangeDetection, 'wp_trash_post']);
        \add_action('wp_nav_menu_item_custom_fields', [$navMenuLinks, 'wp_nav_menu_item_custom_fields'], 10, 2);
        \add_action('wp_update_nav_menu_item', [$navMenuLinks, 'wp_update_nav_menu_item'], 10, 3);
        \add_action('admin_head-nav-menus.php', [$navMenuLinks, 'admin_head']);
        \add_action('customize_controls_head', [$navMenuLinks, 'customize_controls_head']);
        \add_action('delete_post', [General::getInstance(), 'delete_post']);
        \add_action('wp_trash_post', [General::getInstance(), 'delete_post']);
        \add_action('delete_post', [BannerLink::getInstance(), 'delete_post']);
        \add_action('wp_trash_post', [BannerLink::getInstance(), 'delete_post']);
        \add_action('update_option_wp_page_for_privacy_policy', [BannerLink::getInstance(), 'update_option_wp_page_for_privacy_policy'], 10, 2);
        \add_action('updated_post_meta', [BannerLink::getInstance(), 'added_or_updated_post_meta'], 10, 4);
        \add_action('added_post_meta', [BannerLink::getInstance(), 'added_or_updated_post_meta'], 10, 4);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileMigrationMajor2(), 'actions']);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileMigrationMajor3(), 'actions']);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileMigrationMajor4(), 'actions']);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileTcfV2IllegalUsage(), 'actions']);
        \add_action('added_post_meta', [$this->getNotices(), 'added_post_meta_data_processing_in_unsafe_countries'], 10, 4);
        \add_filter('update_post_metadata', [$this->getNotices(), 'update_post_meta_data_processing_in_unsafe_countries'], 10, 4);
        \add_filter('nav_menu_link_attributes', [$navMenuLinks, 'nav_menu_link_attributes'], 10, 2);
        \add_filter('wp_setup_nav_menu_item', [$navMenuLinks, 'wp_setup_nav_menu_item']);
        \add_filter('customize_nav_menu_available_item_types', [$navMenuLinks, 'register_customize_nav_menu_item_types']);
        \add_filter('customize_nav_menu_available_items', [$navMenuLinks, 'register_customize_nav_menu_items'], 10, 4);
        \add_filter('RCB/Revision/Current', [$navMenuLinks, 'revisionCurrent']);
        \add_filter('RCB/Revision/Current', [TemplateConsumers::getInstance(), 'revisionCurrent']);
        \add_filter('oembed_result', [$this->getBlocker(), 'modifyOEmbedHtmlToKeepOriginalUrl'], 10, 2);
        \add_filter('embed_oembed_html', [$this->getBlocker(), 'modifyOEmbedHtmlToKeepOriginalUrl'], 10, 2);
        \add_filter('RCB/Hints', [$this->getAssets(), 'hints_dashboard_tile_predefined_links'], 100);
        \add_filter('rest_post_dispatch', [$configService, 'rest_post_dispatch'], 10, 3);
        \add_filter('rest_prepare_' . Cookie::CPT_NAME, [$templatesService, 'rest_prepare_templates'], 10, 2);
        \add_filter('rest_' . Cookie::CPT_NAME . '_item_schema', [Cookie::getInstance(), 'rest_item_schema']);
        \add_filter('rest_prepare_' . Blocker::CPT_NAME, [$templatesService, 'rest_prepare_templates'], 10, 2);
        \add_filter('rest_prepare_' . Cookie::CPT_NAME, [$this->getCompLanguage(), 'rest_prepare_post'], 10, 3);
        \add_filter('rest_prepare_' . Blocker::CPT_NAME, [$this->getCompLanguage(), 'rest_prepare_post'], 10, 3);
        \add_filter('rest_prepare_' . BannerLink::CPT_NAME, [$this->getCompLanguage(), 'rest_prepare_post'], 10, 3);
        if ($this->isPro()) {
            \add_filter('rest_prepare_' . TcfVendorConfiguration::CPT_NAME, [$this->getCompLanguage(), 'rest_prepare_post'], 10, 3);
        }
        \add_filter('rest_prepare_' . CookieGroup::TAXONOMY_NAME, [$this->getCompLanguage(), 'rest_prepare_taxonomy'], 10, 3);
        \add_filter('RCB/Revision/Current', [$scannerQuery, 'revisionCurrent']);
        // Multilingual
        \add_filter('rest_' . Cookie::CPT_NAME . '_query', [Hooks::getInstance(), 'rest_query']);
        \add_filter('rest_' . Blocker::CPT_NAME . '_query', [Hooks::getInstance(), 'rest_query']);
        \add_filter('rest_' . BannerLink::CPT_NAME . '_query', [Hooks::getInstance(), 'rest_query']);
        \add_action('rest_api_init', [Hooks::getInstance(), 'rest_api_init'], 1);
        \add_filter('RCB/Hints', [Hooks::getInstance(), 'hints']);
        \add_filter('RCB/Query/Arguments', [Hooks::getInstance(), 'queryArguments']);
        \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . Blocker::META_NAME_SERVICES, [Hooks::getInstance(), 'copy_blocker_connected_services_meta'], 10, 5);
        \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . Blocker::META_NAME_TCF_VENDORS, [Hooks::getInstance(), 'copy_blocker_connected_services_meta'], 10, 5);
        \add_filter('RCB/Revision/Context', [Hooks::getInstance(), 'context']);
        \add_filter('RCB/Revision/Context/Translate', [Hooks::getInstance(), 'contextTranslate']);
        \add_filter('RCB/Revision/Hash', [Hooks::getInstance(), 'revisionHash']);
        \add_filter('RCB/Revision/NeedsRetrigger', [Hooks::getInstance(), 'revisionNeedsRetrigger']);
        $this->getBanner()->getCustomize()->enableOptionsAutoload();
        General::getInstance()->enableOptionsAutoload();
        Consent::getInstance()->enableOptionsAutoload();
        Multisite::getInstance()->enableOptionsAutoload();
        TCF::getInstance()->enableOptionsAutoload();
        GoogleConsentMode::getInstance()->enableOptionsAutoload();
        CountryBypass::getInstance()->enableOptionsAutoload();
        if ($scanner->isActive()) {
            $scanner->reduceCurrentUserPermissions();
            ComingSoonPlugins::getInstance()->bypass();
            \add_action('shutdown', [$scanner, 'teardown']);
            \add_filter('show_admin_bar', '__return_false');
            \add_filter('RCB/Blocker/ResolveBlockables', [$scanner, 'resolve_blockables'], 50, 2);
        }
        // If country bypass is active, add the filter so the frontend fetches the WP REST API and modify revision
        if (CountryBypass::getInstance()->isActive()) {
            \add_filter('RCB/Consent/DynamicPreDecision', [CountryBypass::getInstance(), 'dynamicPredecision'], 10, 2);
        }
        $this->overrideInitCustomize();
        $this->overrideInit();
        // Allow to reset all available data and recreated
        if (isset($_GET['rcb-reset-all']) && \current_user_can('activate_plugins')) {
            \check_admin_referer('rcb-reset-all');
            Reset::getInstance()->all(isset($_GET['reset-consents']));
            \wp_safe_redirect($this->getConfigPage()->getUrl());
            exit;
        }
        // Create default content
        if ($this->getConfigPage()->isVisible() && (\get_site_option(\DevOwl\RealCookieBanner\Activator::OPTION_NAME_NEEDS_DEFAULT_CONTENT) || CookieGroup::getInstance()->getEssentialGroup() === null || \count(Hooks::getInstance()->getLanguagesWithoutEssentialGroup()) > 0)) {
            $this->getActivator()->addInitialContent();
        }
        // If we reached next thursday, update the GVL automatically
        TCF::getInstance()->probablyUpdateGvl();
        // If we reached next sunday, update the country database automatically
        CountryBypass::getInstance()->probablyUpdateDatabase();
    }
    /**
     * Check if any plugin specific setting got changed in customize.
     *
     * @param array $response
     */
    public function customize_save_response($response)
    {
        if (AbstractCustomizePanel::gotUpdated($response, RCB_OPT_PREFIX)) {
            /**
             * Real Cookie Banner (Content Blocker, banner) got updated in the Customize.
             *
             * @hook RCB/Customize/Updated
             * @param {array} $response
             * @return {array}
             */
            $response = \apply_filters('RCB/Customize/Updated', $response);
        }
        return $response;
    }
    /**
     * See filter RCB/Query/Arguments.
     *
     * @param array $arguments
     * @param string $context
     */
    public function queryArguments($arguments, $context)
    {
        /**
         * Modify arguments to `get_posts` and `get_terms`. This can be especially useful for WPML / PolyLang.
         *
         * @hook RCB/Query/Arguments
         * @param {array} $arguments
         * @param {string} $context
         * @return {array}
         */
        return \apply_filters('RCB/Query/Arguments', \array_merge(['suppress_filters' => \false], $arguments), $context);
    }
    /**
     * Return the base URL to assets specially for Real Cookie Banner.
     *
     * @param string $path
     */
    public function getBaseAssetsUrl($path)
    {
        return RealUtilsCore::getInstance()->getBaseAssetsUrl('wp-real-cookie-banner/' . $path);
    }
    /**
     * Get config page.
     *
     * @codeCoverageIgnore
     */
    public function getConfigPage()
    {
        return $this->configPage;
    }
    /**
     * Get banner.
     *
     * @codeCoverageIgnore
     */
    public function getBanner()
    {
        return $this->banner;
    }
    /**
     * Get blocker.
     *
     * @codeCoverageIgnore
     */
    public function getBlocker()
    {
        return $this->blocker;
    }
    /**
     * Get request uuid 4.
     *
     * @codeCoverageIgnore
     */
    public function getPageRequestUuid4()
    {
        return $this->pageRequestUuid4;
    }
    /**
     * Get compatibility language class.
     *
     * @codeCoverageIgnore
     */
    public function getCompLanguage()
    {
        return $this->compLanguage;
    }
    /**
     * Get ad initiator from `real-utils`.
     *
     * @codeCoverageIgnore
     */
    public function getAdInitiator()
    {
        return $this->adInitiator;
    }
    /**
     * Get ad initiator from `real-product-manager-wp-client`.
     *
     * @codeCoverageIgnore
     */
    public function getRpmInitiator()
    {
        return $this->rpmInitiator;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAnonymousAssetBuilder()
    {
        return $this->anonymousAssetBuilder;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTcfVendorListNormalizer()
    {
        return $this->tcfVendorListNormalizer;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExcludeAssets()
    {
        return $this->excludeAssets;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getNotices()
    {
        return $this->notices;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getScanner()
    {
        return $this->scanner;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRealQueue()
    {
        return $this->realQueue;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCookieConsentManagement()
    {
        if ($this->cookieConsentManagement === null) {
            $settings = new Settings(General::getInstance(), Consent::getInstance(), CountryBypass::getInstance(), TCF::getInstance(), Multisite::getInstance(), GoogleConsentMode::getInstance());
            $this->cookieConsentManagement = new CookieConsentManagement($settings, Revision::getInstance());
        }
        return $this->cookieConsentManagement;
    }
    /**
     * Check if a license is active.
     */
    public function isLicenseActive()
    {
        return !empty(\DevOwl\RealCookieBanner\Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getCode());
    }
    /**
     * Get singleton core class.
     *
     * @return Core
     */
    public static function getInstance()
    {
        return !isset(self::$me) ? self::$me = new \DevOwl\RealCookieBanner\Core() : self::$me;
    }
}
/**
 * See API docs.
 *
 * @api {get} /real-cookie-banner/v1/plugin Get plugin information
 * @apiHeader {string} X-WP-Nonce
 * @apiName GetPlugin
 * @apiGroup Plugin
 *
 * @apiSuccessExample {json} Success-Response:
 * {
 *     Name: "My plugin",
 *     PluginURI: "https://example.com/my-plugin",
 *     Version: "0.1.0",
 *     Description: "This plugin is doing something.",
 *     Author: "<a href="https://example.com">John Smith</a>",
 *     AuthorURI: "https://example.com",
 *     TextDomain: "my-plugin",
 *     DomainPath: "/languages",
 *     Network: false,
 *     Title: "<a href="https://example.com">My plugin</a>",
 *     AuthorName: "John Smith"
 * }
 * @apiVersion 1.0.0
 */
