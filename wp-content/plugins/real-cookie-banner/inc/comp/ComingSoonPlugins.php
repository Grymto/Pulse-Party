<?php

namespace DevOwl\RealCookieBanner\comp;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Utils;
use WP_Post;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils as UtilsUtils;
use OCUC_Themes;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Provide native integrations to known plugins which provide "Coming soon" or "Maintenance" functionality.
 * @internal
 */
class ComingSoonPlugins
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var ComingSoonPlugins
     */
    private static $me = null;
    /**
     * Register hooks in `init` action.
     */
    public function init()
    {
        // "Maintenance" plugin
        \add_action('load_custom_style', [$this, 'maintenance_load_custom_style'], 0);
        \add_action('after_main_container', [Core::getInstance()->getBanner(), 'wp_footer']);
        \add_action('update_option_maintenance_options', [$this, 'update_option_maintenance_options']);
        // CMP – Coming Soon & Maintenance Plugin by NiteoThemes
        \add_action('cmp-before-header-scripts', [$this, 'cmp_before_header_scripts']);
        \add_action('cmp_footer', [Core::getInstance()->getBanner(), 'wp_footer']);
        \add_action('cmp_save_settings', [$this, 'cmp_save_settings']);
        // WP Maintenance Mode & Coming Soon
        \add_action('update_option_wpmm_settings', [$this, 'update_option_wpmm_settings']);
        // one.com maintenance plugin
        \add_action('update_option_onecom_under_construction_info', [$this, 'addHomeUrlToQueue']);
    }
    /**
     * Register actions or modify the plugin in that way, that the current request does not get blocked.
     */
    public function bypass()
    {
        // [Plugin comp] https://wordpress.org/plugins/ultimate-member/
        if (\function_exists('UM')) {
            \remove_action('template_redirect', [\UM()->logout(), 'logout_page'], 10000);
        }
        // [Plugin comp] https://wordpress.org/plugins/cmp-coming-soon-maintenance/
        \add_filter('pre_option_niteoCS_status', function () {
            return '0';
        });
        // [Plugin comp] https://wordpress.org/plugins/under-construction-page/
        \add_filter('ucp_is_construction_mode_enabled', '__return_false');
        // [Plugin comp] https://wordpress.org/plugins/wp-staging/
        \add_filter('wpstg.frontend.showLoginForm', '__return_true');
        // [Plugin Comp] one.com maintenance plugin
        UtilsUtils::suspenseHook('template_redirect', function ($function) {
            return \is_array($function) && $function[1] === 'under_construction' && $function[0] instanceof OCUC_Themes;
        })->suspense();
    }
    /**
     * The URLs to "Coming soon" preview pages so they can be used e.g. for scanning them.
     *
     * @return string[]
     */
    public function getComputedUrlsForSitemap()
    {
        $urls = [];
        $urls[] = $this->getPreviewUrlForMaintenancePlugin();
        $urls[] = $this->getPreviewUrlForCmpPlugin();
        $arr = $this->getPreviewUrlForSeedProdPlugin();
        if ($arr !== null) {
            $urls = \array_values(\array_merge($urls, $arr));
        }
        // Not needed as this page needs to be a public page
        // $urls[] = $this->getPreviewUrlForWPMaintenanceModePlugin();
        return \array_filter($urls);
    }
    /**
     * Enqueue the cookie banner on 'Maintenance' plugin . With this hook, we are in the `<head` section .
     *
     * @see https://wordpress.org/plugins/maintenance/
     */
    public function maintenance_load_custom_style()
    {
        global $wp_scripts;
        $assets = Core::getInstance()->getAssets();
        $assets->enqueue_scripts_and_styles(Constants::ASSETS_TYPE_FRONTEND);
        // The plugin does not automatically print the head, instead it uses `do_items` explicitly
        $wp_scripts->do_items($assets->handleBanner);
        \add_action('load_custom_scripts', function () use($wp_scripts, $assets) {
            // With this action, we are in the footer (end of `</body>`)
            $wp_scripts->do_items($assets->handleBlocker);
        });
    }
    /**
     * Enqueue the cookie banner on 'CMP – Coming Soon & Maintenance Plugin by NiteoThemes' plugin . With this hook, we are in the `<head` section .
     *
     * @see https://wordpress.org/plugins/cmp-coming-soon-maintenance/
     */
    public function cmp_before_header_scripts()
    {
        global $wp_scripts;
        $assets = Core::getInstance()->getAssets();
        $assets->enqueue_scripts_and_styles(Constants::ASSETS_TYPE_FRONTEND);
        // The plugin does not automatically print the head, instead it uses `do_items` explicitly
        $wp_scripts->do_items($assets->handleBanner);
        \add_action('cmp-before-footer-scripts', function () use($wp_scripts, $assets) {
            // With this action, we are in the footer (end of `</body>`)
            $wp_scripts->do_items($assets->handleBlocker);
        });
    }
    /**
     * Add the home URL to the scanner when the maintenance plugin does not provide a "preview" URL.
     */
    public function addHomeUrlToQueue()
    {
        Core::getInstance()->getScanner()->addUrlsToQueue([\home_url()]);
    }
    /**
     * When updating the "Maintenance" plugin options, add the preview to the scanner.
     *
     * @see https://wordpress.org/plugins/maintenance/
     */
    public function update_option_maintenance_options()
    {
        Core::getInstance()->getScanner()->addUrlsToQueue([$this->getPreviewUrlForMaintenancePlugin()]);
    }
    /**
     * When updating the "WP Maintenance Mode & Coming Soon" plugin options, add the preview to the scanner.
     *
     * @see https://wordpress.org/plugins/wp-maintenance-mode/
     */
    public function update_option_wpmm_settings()
    {
        Core::getInstance()->getScanner()->addUrlsToQueue([$this->getPreviewUrlForWPMaintenanceModePlugin()]);
    }
    /**
     * When updating the "CMP – Coming Soon & Maintenance Plugin by NiteoThemes" plugin options, add the preview to the scanner.
     *
     * @see https://wordpress.org/plugins/cmp-coming-soon-maintenance/
     */
    public function cmp_save_settings()
    {
        Core::getInstance()->getScanner()->addUrlsToQueue([$this->getPreviewUrlForCmpPlugin()]);
    }
    /**
     * Get preview URL for "Maintenance" plugin.
     *
     * @see https://wordpress.org/plugins/maintenance/
     */
    protected function getPreviewUrlForMaintenancePlugin()
    {
        return \is_plugin_active('maintenance/maintenance.php') ? \home_url('?maintenance-preview') : null;
    }
    /**
     * Get preview URL for "CMP – Coming Soon & Maintenance Plugin by NiteoThemes" plugin.
     *
     * @see https://wordpress.org/plugins/cmp-coming-soon-maintenance/
     */
    protected function getPreviewUrlForCmpPlugin()
    {
        return \is_plugin_active('cmp-coming-soon-maintenance/niteo-cmp.php') ? \home_url('?cmp_preview=true') : null;
    }
    /**
     * Get preview URL for "Website Builder by SeedProd — Theme Builder, Landing Page Builder, Coming Soon Page, Maintenance Mode" plugin.
     *
     * @see https://wordpress.org/plugins/coming-soon/
     */
    protected function getPreviewUrlForSeedProdPlugin()
    {
        if (\is_plugin_active('coming-soon/coming-soon.php')) {
            $posts = \get_posts(['post_type' => 'seedprod', 'post_status' => 'any', 'numberposts' => -1, 'nopaging' => \true]);
            return \array_values(\array_map([Utils::class, 'getPreviewUrl'], $posts));
        }
        return null;
    }
    /**
     * Get preview URL for "WP Maintenance Mode & Coming Soon" plugin.
     *
     * @see https://wordpress.org/plugins/wp-maintenance-mode/
     */
    protected function getPreviewUrlForWPMaintenanceModePlugin()
    {
        if (\is_plugin_active('wp-maintenance-mode/wp-maintenance-mode.php')) {
            $options = \get_option('wpmm_settings');
            if (\is_array($options) && isset($options['design'])) {
                $pageId = $options['design']['page_id'] ?? 0;
                $pageId = \intval($pageId);
                if ($pageId > 0) {
                    $post = \get_post($pageId);
                    if ($post instanceof WP_Post) {
                        return Utils::getPreviewUrl($post);
                    }
                }
            }
        }
        return null;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\comp\ComingSoonPlugins() : self::$me;
    }
}
