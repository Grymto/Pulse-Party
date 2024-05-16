<?php

namespace DevOwl\RealCookieBanner\view;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview\ImagePreview;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\BlockableScanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\ScriptInlineExtractExternalUrl;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Blocker as SettingsBlocker;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\blockable\BlockerPostType;
use DevOwl\RealCookieBanner\view\blocker\Plugin;
use WP_Query;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Block common HTML tags!
 * @internal
 */
class Blocker
{
    use UtilsProvider;
    const BUTTON_CLICKED_IDENTIFIER = 'unblock';
    /**
     * If a given class of the `parentElement` is given, set the visual parent. This is needed for
     * some page builder and theme compatibilities. This is only used on client-side (see `findVisualParent`).
     */
    const SET_VISUAL_PARENT_IF_CLASS_OF_PARENT = [
        // [Plugin Comp] Divi Builder
        'et_pb_video_box' => 1,
        // [Theme Comp] Astra Theme (Gutenberg Block)
        'ast-oembed-container' => 1,
        // [Plugin Comp] WP Bakery
        'wpb_video_wrapper' => 1,
        // [Plugin Comp] GoodLayers page builder
        'gdlr-core-pbf-background-video' => '.gdlr-core-pbf-background-wrap',
    ];
    /**
     * Before trying to create a visual content blocker, check if the node is inside a given container and
     * if it is, wait until this container is visible. For example, you are providing a sidebar with blocked
     * content, you need to pass the selector for this sidebar.
     */
    const DEPENDANT_VISIBILITY_CONTAINERS = [
        '[role="tabpanel"]',
        // [Plugin Comp] https://wordpress.org/plugins/essential-addons-for-elementor-lite/
        '.eael-tab-content-item',
        // [Plugin Comp] https://de.wordpress.org/plugins/wp-contact-slider/
        '.wpcs_content_inner',
        // [Plugin Comp] OptimizePress
        '.op3-contenttoggleitem-content',
        // [Plugin Comp] Popup Maker
        '.pum-overlay',
        // [Plugin Comp] Elementor Pro Popups
        '[data-elementor-type="popup"]',
        // [Plugin Comp] https://ultimateblocks.com/content-toggle-accordion-block/
        '.wp-block-ub-content-toggle-accordion-content-wrap',
        // [Plugin Comp] Impreza
        '.w-popup-wrap',
        // [Plugin Comp] Oxygen Builder
        '.oxy-lightbox_inner[data-inner-content=true]',
        '.oxy-pro-accordion_body',
        '.oxy-tab-content',
        // [Plugin Comp] https://wordpress.org/plugins/kadence-blocks/
        '.kt-accordion-panel',
        // [Plugin Comp] WP Bakery Tabs
        '.vc_tta-panel-body',
        // [Plugin Comp] Magnific popup
        '.mfp-hide',
        // [Plugin Comp] Thrive Architect lightbox
        'div[id^="tve_thrive_lightbox_"]',
    ];
    const OB_START_PLUGINS_LOADED_PRIORITY = (\PHP_INT_MAX - 1) * -1;
    /**
     * Force to output the needed computing time at the end of the page for debug purposes.
     */
    const FORCE_TIME_COMMENT_QUERY_ARG = 'rcb-calc-time';
    /**
     * A list of MD5 hashes of HTML strings which got successfully processed. This allows
     * you to run `registerOutputBuffer` multiple times.
     */
    private $processedOutputBufferHtmlHashes = [];
    /**
     * See `HeadlessContentBlocker`
     *
     * @var HeadlessContentBlocker
     */
    private $headlessContentBlocker;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Get `HeadlessContentBlocker` instance.
     */
    public function getHeadlessContentBlocker()
    {
        if ($this->headlessContentBlocker === null) {
            $headlessContentBlocker = new HeadlessContentBlocker();
            $isScanning = Core::getInstance()->getScanner()->isActive();
            if ($isScanning) {
                // This plugin needs to be available before our custom hooks fired in `Plugin`
                $headlessContentBlocker->addPlugin(ScriptInlineExtractExternalUrl::class);
            }
            // This is our custom Real Cookie Banner plugin (runs hooks, adds standard plugins, adds theme / plugin compatibilities, ...)
            $headlessContentBlocker->addPlugin(Plugin::class);
            if ($isScanning) {
                /**
                 * This plugin needs to be available after our custom hooks fired in `Plugin`
                 *
                 * @var BlockableScanner
                 */
                $scanner = $headlessContentBlocker->addPlugin(BlockableScanner::class);
                $scanner->excludeHostByUrl(\home_url());
            }
            $headlessContentBlocker->addBlockables($this->createBlockables($headlessContentBlocker));
            $headlessContentBlocker->setup();
            $this->headlessContentBlocker = $headlessContentBlocker;
        }
        return $this->headlessContentBlocker;
    }
    /**
     * Apply the content blocker attributes to the output buffer when it is enabled.
     *
     * You can start this output buffer multiple times as it is safe to avoid execution of same
     * strings multiple times for the headless content blocker (e.g. multiple WordPress hook lifecycle).
     */
    public function registerOutputBuffer()
    {
        if ($this->isEnabled()) {
            \ob_start([$this, 'ob_start']);
        }
    }
    /**
     * Close a output buffer. This is not necessarily needed as PHP automatically closes them, but in some
     * cases it is needed to make the modified content available to previously read output buffers in e.g.
     * earlier WordPress hook lifecycle.
     */
    public function closeOutputBuffer()
    {
        if (\ob_get_length()) {
            \ob_end_flush();
        }
    }
    /**
     * Event for ob_start.
     *
     * @param string $response
     */
    public function ob_start($response)
    {
        if (Utils::isDownload()) {
            return $response;
        }
        $start = \microtime(\true);
        // Measure replace time
        if (\in_array(\md5($response), $this->processedOutputBufferHtmlHashes, \true)) {
            // This buffer was already processed...
            return $response;
        } else {
            /**
             * Block content in a given HTML string. This is a Consent API filter and can be consumed
             * by third-party plugin and theme developers. See example for usage.
             *
             * @hook Consent/Block/HTML
             * @param {string} $html
             * @return {string}
             * @example <caption>Block content of a given HTML string</caption>
             * $output = apply_filters('Consent/Block/HTML', '<iframe src="https://player.vimeo.com/..." />');
             */
            $newResponse = \apply_filters('Consent/Block/HTML', $response);
        }
        $time_elapsed_secs = \microtime(\true) - $start;
        $htmlEndComment = '<!--rcb-cb:' . \json_encode(['replace-time' => $time_elapsed_secs]) . '-->';
        $newResponse = ($newResponse === null ? $response : $newResponse) . (isset($_GET[self::FORCE_TIME_COMMENT_QUERY_ARG]) ? $htmlEndComment : '');
        $this->processedOutputBufferHtmlHashes[] = \md5($newResponse);
        return $newResponse;
    }
    /**
     * Apply content blockers to a given HTML. It also supports JSON output.
     *
     * If you want to use this functionality in your plugin, please use the filter `Consent/Block/HTML` instead!
     *
     * @param string $html
     */
    public function replace($html)
    {
        if (!$this->isEnabled()) {
            return $html;
        }
        return $this->getHeadlessContentBlocker()->modifyAny($html);
    }
    /**
     * Get all available blockables.
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     * @return AbstractBlockable[]
     */
    protected function createBlockables($headlessContentBlocker)
    {
        $blockables = [];
        $blockers = SettingsBlocker::getInstance()->getOrdered();
        foreach ($blockers as &$blocker) {
            // Ignore blockers with no connected cookies
            if (\count($blocker->metas[SettingsBlocker::META_NAME_SERVICES]) + \count($blocker->metas[SettingsBlocker::META_NAME_TCF_VENDORS]) === 0) {
                continue;
            }
            $blockables[] = new BlockerPostType($headlessContentBlocker, $blocker);
        }
        /**
         * Allows you to add, modify or remove existing `AbstractBlockable` instances. For usual,
         * they get generated of published Content Blocker post types records. This allows you
         * to block for example by custom criteria (services, TCF vendor, ...).
         *
         * **Note**: This hook is called only once, cause the result is cached for performance reasons!
         *
         * @hook RCB/Blocker/ResolveBlockables
         * @param {AbstractBlockable[]} $blockables
         * @param {HeadlessContentBlocker} $headlessContentBlocker
         * @return {AbstractBlockable[]}
         * @ignore
         * @since 2.6.0
         */
        return \apply_filters('RCB/Blocker/ResolveBlockables', $blockables, $headlessContentBlocker);
    }
    /**
     * Check if content blocker is enabled on the current request.
     */
    protected function isEnabled()
    {
        global $wp_query;
        $isEnabled = (Utils::isFrontend() || $this->isAdminAjaxAction()) && General::getInstance()->isBannerActive() && General::getInstance()->isBlockerActive() && !\is_customize_preview() && !$this->isCurrentRequestException();
        // Disable content blocker for AMP pages completely as it needs a AMP-specific consent management system
        if (\function_exists('amp_is_request') && (\current_action() === 'wp' || \did_action('wp')) && \amp_is_request()) {
            $isEnabled = \false;
        }
        // [Plugin Comp] https://wordpress.org/plugins/dhl-for-woocommerce/
        if ($wp_query instanceof WP_Query && isset($wp_query->query_vars['dhl_download_label'])) {
            return \false;
        }
        /**
         * Allows you to force the content blocker take action. This is especially
         * useful if you want to use the blocker functionality for custom mechanism
         * like Scanner.
         *
         * @hook RCB/Blocker/Enabled
         * @param {boolean} $isEnabled
         * @return {boolean}
         * @since 2.6.0
         */
        return \apply_filters('RCB/Blocker/Enabled', $isEnabled);
    }
    /**
     * Check if the current request should not load any blocking mechanism depending
     * on a special condition.
     *
     */
    protected function isCurrentRequestException()
    {
        return isset($_GET['callback']) && $_GET['callback'] === 'map-iframe' || isset($_GET['lease']) && \preg_match('/^[a-f0-9]{32}$/i', $_GET['lease']);
    }
    /**
     * Allows to modify content within a `admin-ajax.php` action.
     */
    protected function isAdminAjaxAction()
    {
        /**
         * Run the content blocker over `admin-ajax.php` responses.
         *
         * @hook RCB/Blocker/AdminAjaxActions
         * @param {string[]} $actions
         * @return {string[]}
         * @since 3.4.11
         */
        $actions = \apply_filters('RCB/Blocker/AdminAjaxActions', [
            // [Plugin Comp] https://wordpress.org/plugins/modern-events-calendar-lite/
            'mec_load_single_page',
            // [Plugin Comp] https://wordpress.org/plugins/wpdiscuz/
            'wpdLoadMoreComments',
            'wpdAddComment',
            'wpdSorting',
            // [Plugin Comp] Elementor
            'e_elementor_popup',
            // [Plugin Comp] https://crocoblock.com/plugins/jetsmartfilters/
            'jet_smart_filters',
            // [Plugin Comp] https://www.buddyboss.com/
            'activity_filter',
            // [Plugin Comp] Elementor
            'loadmore_elementor_portfolio',
            // [Plugin Comp] https://knowledgebase.unitedthemes.com/docs/how-to-set-up-your-portfolio/
            'ut_get_portfolio_post_content',
            // [Plugin Comp] https://core.pixfort.com/
            'pix_get_popup_content',
        ]);
        return \wp_doing_ajax() && isset($_REQUEST['action']) && \in_array($_REQUEST['action'], $actions, \true);
    }
    /**
     * Exclude blocked styles from autoptimize inline aggregation.
     *
     * @param string $exclusions
     * @see https://github.com/futtta/autoptimize/pull/386#issuecomment-1156622026
     */
    public function autoptimize_filter_css_exclude($exclusions)
    {
        return \sprintf('%s, %s-href-%s', $exclusions, Constants::HTML_ATTRIBUTE_CAPTURE_PREFIX, Constants::HTML_ATTRIBUTE_CAPTURE_SUFFIX);
    }
    /**
     * Exclude blocked styles and scripts
     *
     * @param array $assets
     */
    public function avf_exclude_assets($assets)
    {
        $blockedHandles = $this->getHeadlessContentBlocker()->getBlockableRulesStartingWith('avf_exclude_assets:', \true);
        foreach ($blockedHandles as $handle) {
            $assets['js'][] = $handle;
            $assets['css'][] = $handle;
        }
        return $assets;
    }
    /**
     * Modify any URL and add a query argument to skip the content blocker mechanism.
     *
     * @param string $url
     */
    public function modifyUrlToSkipContentBlocker($url)
    {
        return \add_query_arg(
            // Use the `fl_builder` argument which is covered by `Utils::isPageBuilder()`
            ['fl_builder' => '1'],
            $url
        );
    }
    /**
     * Modify the HTML of an oEmbed HTML and keep the original pasted URL as attribute
     * so our headless content blocker can generate an image preview from the original URL.
     *
     * @param string $html
     * @param string $url
     * @see https://wordpress.stackexchange.com/q/353313/83335
     * @see https://regex101.com/r/r1n1ZY/1
     */
    public function modifyOEmbedHtmlToKeepOriginalUrl($html, $url)
    {
        if (\strpos($html, ImagePreview::HTML_ATTRIBUTE_TO_FETCH_URL_FROM) === \false && \filter_var($url, \FILTER_VALIDATE_URL) && Utils::startsWith($url, 'http')) {
            return \preg_replace('/^(<[A-Za-z-]+)/m', \sprintf('$1 %s="%s"', ImagePreview::HTML_ATTRIBUTE_TO_FETCH_URL_FROM, \esc_attr($url)), $html, 1);
        }
        return $html;
    }
    /**
     * New instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\view\Blocker();
    }
}
