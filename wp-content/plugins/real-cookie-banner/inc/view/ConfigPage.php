<?php

namespace DevOwl\RealCookieBanner\view;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\Utils;
use WP_Admin_Bar;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Creates a WordPress backend menu page to configure the cookie banner.
 * @internal
 */
class ConfigPage
{
    use UtilsProvider;
    const COMPONENT_ID = RCB_SLUG . '-component';
    const CHECKLIST_OVERDUE = '+1 week';
    const ADMIN_BAR_TOP_LEVEL_NODE_ID = 'rcb-top-node';
    const TRANSIENT_SERVICES_WITH_EMPTY_PRIVACY_POLICY = RCB_OPT_PREFIX . '-services-with-empty-privacy-policy';
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Creates an admin notice for preinstalled environments.
     */
    public function admin_notices_preinstalled_environment()
    {
        $hasInteractedWithFormOnce = Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->hasInteractedWithFormOnce();
        if ($this->isVisible() || !Utils::isPreinstalledEnvironment() || $hasInteractedWithFormOnce) {
            return;
        }
        echo \sprintf('<div class="notice notice-warning"><p>%s &bull; <a href="%s">%s</a></p></div>', \__('A cookie banner for your website is installed. With it, you can integrate e.g. <strong>Google Analytics, Google Maps or YouTube videos</strong> legally compliant. You just have to configure it!', RCB_TD), $this->getUrl(), \__('Start now!', RCB_TD));
    }
    /**
     * Creates an admin notice in the settings page if an ad-blocker got detected.
     */
    public function admin_notices_ad_blocker()
    {
        if (!$this->isVisible()) {
            return;
        }
        echo \sprintf('<div id="rcb-ad-block-checker-notice" class="notice notice-warning hidden"><p>%s</p></div>
<script type="text/javascript" src="%s"></script>
<script>(function(notice) {
    document.addEventListener("DOMContentLoaded", function() {
        if (!document.getElementById("ZSCFlsPIDL95U1QFvkFvGKQu3R9YAh0")) {
            notice.classList.remove("hidden");
        }
    });
})(document.getElementById("rcb-ad-block-checker-notice"))</script>', \__('<strong>Adblocker detected</strong>: You may use an adblocker in your browser. This can block parts of the settings of the Real Cookie Banner and thus lead to errors. <strong>Please deactivate your adblocker for your own website!</strong>', RCB_TD), \plugins_url('public/images/detect-adblock/doubleserve.js', RCB_FILE));
    }
    /**
     * Add new menu page.
     */
    public function admin_menu()
    {
        $checklistOverdue = \DevOwl\RealCookieBanner\view\Checklist::getInstance()->isOverdue(self::CHECKLIST_OVERDUE) && !$this->isVisible();
        /**
         * Allow to show a `!` exclamation mark in the "Cookies" menu item.
         *
         * @hook RCB/Menu/ConfigPage/Attention
         * @param {boolean} $hints
         * @return {boolean}
         * @since 2.0.0
         */
        $checklistOverdue = \apply_filters('RCB/Menu/ConfigPage/Attention', $checklistOverdue);
        $pluginName = $this->getCore()->getPluginData()['Name'];
        \add_menu_page($pluginName, \__('Cookies', RCB_TD) . '&nbsp;' . ($checklistOverdue ? ' <span class="awaiting-mod" id="rcb-checklist-overdue" style="margin-right: 5px;">!</span>' : '') . '<span class="awaiting-mod" id="rcb-scanner-status" style="display:none;"></span>', Core::MANAGE_MIN_CAPABILITY, self::COMPONENT_ID, [$this, 'render_component_library'], self::getIconAsSvgBase64($this->isVisible() ? '#FFFFFF' : '#858585'));
    }
    /**
     * Show a "Settings" link in plugins list.
     *
     * @param string[] $actions
     * @return string[]
     */
    public function plugin_action_links($actions)
    {
        $actions[] = \sprintf('<a href="%s">%s</a>', $this->getUrl(), \__('Settings'));
        return $actions;
    }
    /**
     * Render the content of the menu page.
     */
    public function render_component_library()
    {
        echo '<div id="' . self::COMPONENT_ID . '" class="wrap"></div>';
    }
    /**
     * Check if a given page string is this config page or from the current page `pagenow`.
     *
     * @param string $hook_suffix The current admin page (admin_enqueue_scripts)
     */
    public function isVisible($hook_suffix = \false)
    {
        return $hook_suffix === 'settings_page_' . self::COMPONENT_ID || isset($_GET['page'], $GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'admin.php' && $_GET['page'] === self::COMPONENT_ID;
    }
    /**
     * Get the URL of this page.
     */
    public function getUrl()
    {
        return \admin_url('admin.php?page=' . self::COMPONENT_ID);
    }
    /**
     * Create the top menu admin bar for "Real Cookie Banner" and return the `parent` id
     * so you can add additional drop down menus.
     */
    public function ensureAdminBarTopLevelNode()
    {
        /**
         * Var.
         *
         * @var WP_Admin_Bar
         */
        global $wp_admin_bar;
        if (!$wp_admin_bar->get_node(self::ADMIN_BAR_TOP_LEVEL_NODE_ID)) {
            $iconDefault = self::getIconAsSvgSpan();
            $iconWhite = self::getIconAsSvgSpan('white', 'display:none;');
            $wp_admin_bar->add_menu(['id' => self::ADMIN_BAR_TOP_LEVEL_NODE_ID, 'title' => $iconDefault . $iconWhite . \__('Cookies', RCB_TD), 'href' => $this->getUrl()]);
            // Add a "Settings" node to the end of the menu
            \add_action('wp_before_admin_bar_render', function () use($wp_admin_bar) {
                $wp_admin_bar->add_menu(['parent' => self::ADMIN_BAR_TOP_LEVEL_NODE_ID, 'id' => 'rcb-settings', 'title' => \__('Settings', RCB_TD), 'href' => $this->getUrl()]);
                $enabledLabel = \sprintf('<span style="color:#3cb63c;">&#11044;</span> %s', \__('Enabled', RCB_TD));
                $disabledLabel = \sprintf('<span style="color:#b63c3c;">&#11044;</span> %s', \__('Disabled', RCB_TD));
                $isBannerActive = General::getInstance()->isBannerActive();
                $wp_admin_bar->add_menu(['parent' => self::ADMIN_BAR_TOP_LEVEL_NODE_ID, 'id' => 'rcb-cookie-banner-active', 'title' => \sprintf('%s: %s', \__('Cookie Banner', RCB_TD), $isBannerActive ? $enabledLabel : $disabledLabel)]);
                $wp_admin_bar->add_menu(['parent' => self::ADMIN_BAR_TOP_LEVEL_NODE_ID, 'id' => 'rcb-content-blocker-active', 'title' => \sprintf('%s: %s', \__('Content Blocker', RCB_TD), $isBannerActive && General::getInstance()->isBlockerActive() ? $enabledLabel : $disabledLabel)]);
            }, 9);
        }
        return self::ADMIN_BAR_TOP_LEVEL_NODE_ID;
    }
    /**
     * Get the Real Cookie Banner logo as SVG icon in base64 format.
     *
     * @param string $color
     */
    public static function getIconAsSvgBase64($color = '#9ea3a8')
    {
        return 'data:image/svg+xml;base64,' . \base64_encode(\sprintf(
            '<svg viewBox="0 0 200.46 194.85" xmlns="http://www.w3.org/2000/svg"><g fill="%s"><path d="m184.61 75.42a21.48 21.48 0 0 1 -32.49-9.84 25 25 0 0 1 -4.27.38 24.31 24.31 0 0 1 -20.95-11.96h-.1l-.35.15c-.27.1-.53.2-.82.29l-.35.11c-.41.11-.8.2-1.19.27l-6.28 1.18a12.12 12.12 0 0 1 -4.76-.12 10.93 10.93 0 0 1 -8.51-9 11.38 11.38 0 0 1 -.08-1.31v-7.65a10.52 10.52 0 0 1 .09-1.34c0-.12 0-.22 0-.32.06-.37.14-.72.23-1.06l.07-.25a11.66 11.66 0 0 1 .47-1.24l.11-.23a10.46 10.46 0 0 1 .54-1 1.17 1.17 0 0 1 .08-.13 10.89 10.89 0 0 1 1.71-2.05l.08-.07a11.54 11.54 0 0 1 6.39-2.9l4.61-.57a12.25 12.25 0 0 1 1.48-.09 11.88 11.88 0 0 1 4.07.71 21.22 21.22 0 0 1 -5.11-13.83 20.84 20.84 0 0 1 .25-3.28 93.94 93.94 0 1 0 65.05 65.14zm-75.39-50.68a1.27 1.27 0 1 1 -1.22 1.26 1.26 1.26 0 0 1 1.22-1.26zm-56 20.33.73-.42a3.38 3.38 0 0 1 4.62 1.24l5.43 9.42a3.39 3.39 0 0 1 -1.24 4.62l-.73.41a3.38 3.38 0 0 1 -4.62-1.23l-5.43-9.42a3.38 3.38 0 0 1 1.19-4.62zm-33.64 34.21a8.38 8.38 0 0 1 -1.37-.87 8.89 8.89 0 0 1 -1.13-1.08 7.1 7.1 0 0 1 -1.43-2.64 6.25 6.25 0 0 1 -.17-.72 6.1 6.1 0 0 1 -.06-1.48 5.87 5.87 0 0 1 .32-1.49l3.36-9.5c.06-.17.13-.34.2-.5l.07-.14c.06-.13.13-.25.2-.37l.07-.12.29-.42a1.47 1.47 0 0 1 .1-.13 3.23 3.23 0 0 1 .23-.27l.15-.17.23-.22.29-.26.21-.16a1.44 1.44 0 0 1 .25-.18l.15-.1a6.09 6.09 0 0 1 1-.56h.05a7.46 7.46 0 0 1 1.71-.48 8.08 8.08 0 0 1 3 .1l6 1.33c3.83.85 6.51 4.21 6.12 7.67l-1.06 9.59a5.89 5.89 0 0 1 -2.36 4.08l-.09.06a5.07 5.07 0 0 1 -.54.36l-.1.06-.31.16-.21.1-.43.18-.29.1-.25.08-.5.13h-.21l-.55.08h-.12-.55-.15c-.21 0-.42 0-.63 0h-.14l-.68-.08-8.25-1.35a8.22 8.22 0 0 1 -2.42-.79zm6.08 29.07a1.79 1.79 0 1 1 -1.78 1.79 1.79 1.79 0 0 1 1.78-1.79zm10.91 47.89a2.1 2.1 0 1 1 2.1-2.1 2.11 2.11 0 0 1 -2.1 2.1zm2.27-31.14-9.42 5.44a3.38 3.38 0 0 1 -4.62-1.24l-.42-.73a3.39 3.39 0 0 1 1.24-4.57l9.38-5.48a3.38 3.38 0 0 1 4.62 1.23l.42.73a3.38 3.38 0 0 1 -1.2 4.62zm3.85-81.59a2.1 2.1 0 1 1 2.1-2.1 2.1 2.1 0 0 1 -2.1 2.1zm2.61 47.82-.06-.08a2.75 2.75 0 0 1 -.17-.29 2.39 2.39 0 0 1 -.15-.37s0 0 0-.06a3.57 3.57 0 0 1 -.1-.35 3.53 3.53 0 0 1 -.06-.86 4 4 0 0 1 .17-.89 4.06 4.06 0 0 1 .68-1.43l2-2.4a3.75 3.75 0 0 1 4.85-1l4.08 2.64a3 3 0 0 1 1.32 2v.38a3.82 3.82 0 0 1 -.06.82l-.09.35v.11l-.11.27a.5.5 0 0 1 0 .13l-.13.26-.06.12c-.08.12-.15.24-.24.36l-2.5 3.42a4.19 4.19 0 0 1 -1.67 1.39l-.37.16a3.61 3.61 0 0 1 -1.89.16 2.37 2.37 0 0 1 -.36-.09 2.92 2.92 0 0 1 -1.28-.78l-3.54-3.66a3.76 3.76 0 0 1 -.26-.31zm22.44 66a5.84 5.84 0 0 1 -.2.59 5.52 5.52 0 0 1 -.26.55 5.11 5.11 0 0 1 -.75 1.06 5.29 5.29 0 0 1 -.41.41l-.13.11c-.12.1-.24.2-.37.29l-.18.13c-.13.08-.26.17-.4.24l-.18.11c-.2.1-.4.2-.62.29l-6.24 2.54a6.65 6.65 0 0 1 -2.63.51 5.47 5.47 0 0 1 -5.46-4l-1.74-7.92c0-.14-.05-.29-.07-.43a.57.57 0 0 1 0-.13c0-.11 0-.22 0-.33s0-.07 0-.11 0-.28 0-.41a.68.68 0 0 1 0-.14 2.7 2.7 0 0 1 0-.28v-.17l.06-.26.09-.29c0-.07 0-.14.07-.2a1.6 1.6 0 0 1 .1-.23l.06-.14a5.5 5.5 0 0 1 .47-.81 5.92 5.92 0 0 1 1-1.05 6.6 6.6 0 0 1 2.1-1.21l4.64-1.62c3-1 6.23.12 7.43 2.65l3.33 7a4.74 4.74 0 0 1 .29 3.23zm4.26-19.15a2.1 2.1 0 1 1 2.1-2.1 2.1 2.1 0 0 1 -2.1 2.1zm4.64-25a4.64 4.64 0 1 1 4.64-4.64 4.64 4.64 0 0 1 -4.67 4.66zm5.36-75.34-.44-.71a3.37 3.37 0 0 1 1.06-4.66l9.2-5.8a3.38 3.38 0 0 1 4.66 1.06l.45.71a3.38 3.38 0 0 1 -1.06 4.66l-9.2 5.8a3.39 3.39 0 0 1 -4.67-1.06zm14.26 19.46a2.1 2.1 0 1 1 -2.1-2.1 2.1 2.1 0 0 1 2.1 2.1zm6.7 66.09c-1 3.33-4.53 5.81-8.8 5.81s-7.84-2.48-8.79-5.81a1.4 1.4 0 0 1 1.34-1.78h14.9a1.4 1.4 0 0 1 1.39 1.78zm13.28 44.61-.72.42a3.37 3.37 0 0 1 -4.62-1.24l-5.44-9.41a3.38 3.38 0 0 1 1.24-4.62l.72-.42a3.37 3.37 0 0 1 4.62 1.24l5.44 9.41a3.37 3.37 0 0 1 -1.24 4.62zm-9.41-59.41a4.64 4.64 0 1 1 4.64 4.64 4.64 4.64 0 0 1 -4.64-4.67zm11.45-24.87-14.91 3.08a3.8 3.8 0 1 1 -1.54-7.44l14.91-3.09a3.8 3.8 0 1 1 1.54 7.45zm8.91 65.89a2.11 2.11 0 1 1 2.1-2.1 2.1 2.1 0 0 1 -2.1 2.07zm41.1-43.79a2.11 2.11 0 1 1 -2.1-2.1 2.1 2.1 0 0 1 2.1 2.07zm-20.81-10.16a4.85 4.85 0 0 1 .23-1.17 5.41 5.41 0 0 1 .95-1.76l2.57-3.15a4.93 4.93 0 0 1 6.38-1.36l5.39 3.46a3.91 3.91 0 0 1 1.73 2.65 4 4 0 0 1 .06.5 5.65 5.65 0 0 1 -.07 1.08 3.9 3.9 0 0 1 -.12.46s0 .09 0 .14-.09.24-.14.37l-.07.16c-.05.12-.11.23-.17.35l-.08.15c-.1.16-.2.32-.31.48l-3.29 4.49a5.42 5.42 0 0 1 -1.19 1.2 4.61 4.61 0 0 1 -6.06-.17l-4.65-4.83a3.19 3.19 0 0 1 -.33-.4.44.44 0 0 1 -.08-.11 3 3 0 0 1 -.23-.38v-.06c-.07-.15-.14-.31-.2-.48a.19.19 0 0 1 0-.08 4 4 0 0 1 -.13-.46.43.43 0 0 0 0 0 4.74 4.74 0 0 1 -.19-1.08zm12.39 49.41v.06c0 .23-.07.47-.12.71a7.18 7.18 0 0 1 -.53 1.47v.06c-.11.2-.22.4-.35.59l-.12.18a4.84 4.84 0 0 1 -.33.46c-.06.06-.11.13-.16.19s-.26.29-.39.42l-.17.18c-.2.18-.41.36-.63.53l-6.4 4.94c-3.57 2.75-8.63 2-10.49-1.51l-4.53-8.6a2.86 2.86 0 0 1 -.22-.47l-.06-.14c0-.13-.09-.26-.13-.38v-.13c0-.16-.08-.32-.11-.48a1 1 0 0 1 0-.16c0-.12 0-.23-.05-.34s0-.14 0-.21 0-.21 0-.32 0-.24 0-.37 0-.17 0-.25 0-.2 0-.3v-.17a6.05 6.05 0 0 1 .29-1.09v-.05a7.61 7.61 0 0 1 .78-1.53 8 8 0 0 1 2-2.07l4.85-3.29c3.11-2.16 7.24-1.86 9.44.68l6.08 7a5.65 5.65 0 0 1 1.35 4.39zm10-17.17-8.76-6.45a3.39 3.39 0 0 1 -.72-4.73l.5-.67a3.37 3.37 0 0 1 4.73-.72l8.75 6.45a3.37 3.37 0 0 1 .72 4.73l-.49.67a3.4 3.4 0 0 1 -4.74.72z"/><path d="m164.19 9.72a93.78 93.78 0 0 0 -30.58-9.72 21.82 21.82 0 0 0 .2 2.89 21.28 21.28 0 0 0 9.52 14.93c7.41-1.24 16.32-2.02 20.86-8.1z"/><path d="m170.31 39.08a6.18 6.18 0 0 0 3.51.65c.76-3.81.89-7.47-.88-10.65a24 24 0 0 0 -4.92-5.7 2.4 2.4 0 0 0 -4 1.6 16 16 0 0 0 6.29 14.1z"/><path d="m166 54.77a5 5 0 0 0 -1.23-1.77 9.24 9.24 0 0 0 -9.17-2.3 3.32 3.32 0 0 0 -1.43 5.34 15.32 15.32 0 0 0 10.44 5.61 1.48 1.48 0 0 0 1.56-1.58 41.12 41.12 0 0 1 -.17-5.3z"/><path d="m190.34 36a52.71 52.71 0 0 0 -9 5.92 8.59 8.59 0 0 1 6.31 6.32l1 4.09a8.16 8.16 0 0 1 -1.19 6.45v.08c-.09.12-.19.24-.28.37a27.26 27.26 0 0 0 3.11-.07 18.35 18.35 0 0 0 10.17-4.16 80.3 80.3 0 0 0 -10.12-19z"/><path d="m181 56.85.24-.09h.11l.23-.11h.08l.27-.17a3.35 3.35 0 0 0 .52-.47c.07-.09.14-.17.2-.26a2.63 2.63 0 0 0 .38-2l-1-4.09a3.24 3.24 0 0 0 -3.63-2.26l-2.65.33a3.6 3.6 0 0 0 -1.25.4 2.94 2.94 0 0 0 -.64.45 3.23 3.23 0 0 0 -.48.56l-.15.28s0 0 0 0a2 2 0 0 0 -.12.31l-.06.28s0 .06 0 .09a1.87 1.87 0 0 0 0 .34v4.4a2.89 2.89 0 0 0 2.1 2.67 3.58 3.58 0 0 0 1.79.13l3.6-.63.36-.08z"/><path d="m124.36 49 .19-.08.4-.2.14-.08a3.58 3.58 0 0 0 .46-.29 5.66 5.66 0 0 0 .92-.83 5.07 5.07 0 0 0 .35-.46 4.46 4.46 0 0 0 .68-3.56l-1.67-7.13a5.66 5.66 0 0 0 -6.34-3.94l-4.62.57a5.92 5.92 0 0 0 -3.28 1.48 5.46 5.46 0 0 0 -.83 1s0 0 0 .06a5.42 5.42 0 0 0 -.26.48.3.3 0 0 0 0 .08 3.83 3.83 0 0 0 -.22.56.19.19 0 0 1 0 .07c0 .17-.08.33-.11.5a.77.77 0 0 0 0 .15 3 3 0 0 0 0 .6v7.66a3.77 3.77 0 0 0 0 .58 5.27 5.27 0 0 0 4.2 4.25 6.36 6.36 0 0 0 2.54.06l6.27-1.1.63-.14.19-.06z"/></g></svg>',
            // > Also the fill attribute for paths must be set, but choose some neutral start color, because the re-coloring happens on page load (so you might see your original fill for a second...)
            // https://stackoverflow.com/questions/30620226/custom-svg-admin-menu-icon-in-wordpress#comment71922520_42265057
            $color
        ));
    }
    /**
     * Get the Real Cookie Banner logo as SVG icon in base64 format within a `<span>`.
     *
     * @param string $color
     * @param string $additionalStyle
     */
    public static function getIconAsSvgSpan($color = '#9ea3a8', $additionalStyle = '')
    {
        return $icon = \sprintf('<span class="custom-icon" style="float:left;width:22px !important;height:22px !important;margin: 5px 5px 0 !important;background-image:url(\'%s\');%s"></span>', self::getIconAsSvgBase64($color), $additionalStyle);
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\view\ConfigPage();
    }
}
