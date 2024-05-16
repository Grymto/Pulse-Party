<?php

namespace DevOwl\RealCookieBanner\comp;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Provide notices for third-party plugins.
 * @internal
 */
class ThirdPartyNotices
{
    use UtilsProvider;
    const PIXEL_MANAGER_ACTIVATE_PRO_FEATURES_QUERY_ARG = 'wgact-show-pro-features';
    const PIXEL_MANAGER_OPTION_NAME = 'wgact_plugin_options';
    const PIXEL_YOUR_SITE_OPTION_NAME = 'pys_core';
    const PIXEL_YOUR_SITE_OPTION_ARRAY_NAME = 'gdpr_real_cookie_banner_integration_enabled';
    const SLUG_PIXEL_MANAGER_FOR_WOOCOMMERCE_FREE = 'woocommerce-google-adwords-conversion-tracking-tag/wgact.php';
    const SLUG_PIXEL_MANAGER_FOR_WOOCOMMERCE_PRO = 'pixel-manager-pro-for-woocommerce/wgact.php';
    const SLUG_PIXEL_YOUR_SITE_PRO = 'pixelyoursite-pro/pixelyoursite-pro.php';
    const SLUG_PIXEL_YOUR_SITE_FREE = 'pixelyoursite/pixelyoursite.php';
    /**
     * Singleton instance.
     *
     * @var ThirdPartyNotices
     */
    private static $me = null;
    /**
     * Register hooks in `init` action.
     */
    public function init()
    {
        \add_action('admin_notices', [$this, 'admin_notices_pixel_manager_for_woocommerce']);
        \add_action('admin_notices', [$this, 'admin_notices_pixel_your_site']);
    }
    /**
     * Show a notice when Pixel Manager for WooCommerce is active.
     */
    public function admin_notices_pixel_manager_for_woocommerce()
    {
        $configPage = Core::getInstance()->getConfigPage();
        if (!$configPage->isVisible()) {
            return;
        }
        $isFreeVersion = \is_plugin_active(self::SLUG_PIXEL_MANAGER_FOR_WOOCOMMERCE_FREE);
        if ($isFreeVersion || \is_plugin_active(self::SLUG_PIXEL_MANAGER_FOR_WOOCOMMERCE_PRO)) {
            $option = \get_option(self::PIXEL_MANAGER_OPTION_NAME);
            if (\is_array($option)) {
                $showNotice = isset($option['shop']['cookie_consent_mgmt']['explicit_consent']) ? \intval($option['shop']['cookie_consent_mgmt']['explicit_consent']) < 1 : \true;
                if ($showNotice) {
                    echo \sprintf('<div class="notice notice-warning"><p>%s</p></div>', \sprintf(
                        // translators:
                        \__('<strong>Pixel Manager for WooCommerce</strong> embed trackers by default without waiting for consent. Please activate "Enable Explicit Consent Mode" under <a href="%1$s"><i>WooCommerce > Pixel Manager > Advanced > Cookie Consent Management</i></a> to embed trackers only after consent. In addition, since you are using Real Cookie Banner, you need to enable all the suggested service templates in <a href="%2$s"><i>Cookies > Scanner</i></a> for the trackers embedded with "Pixel Manager for WooCommerce".', RCB_TD),
                        \admin_url('admin.php?page=wpm&section=advanced&subsection=cookie-consent-mgmt&' . self::PIXEL_MANAGER_ACTIVATE_PRO_FEATURES_QUERY_ARG),
                        $configPage->getUrl() . '#/scanner'
                    ) . ($isFreeVersion ? '<br /><br />' . \__('Pixel Manager for WooCommerce allows to check consents only in its PRO version (paid version). Therefore, you won\'t see the "Enable Explicit Consent Mode" option.', RCB_TD) : ''));
                }
            }
        }
    }
    /**
     * Show a notice when Pixel Your Site is active.
     */
    public function admin_notices_pixel_your_site()
    {
        $configPage = Core::getInstance()->getConfigPage();
        if (!$configPage->isVisible()) {
            return;
        }
        if (\is_plugin_active(self::SLUG_PIXEL_YOUR_SITE_FREE) || \is_plugin_active(self::SLUG_PIXEL_YOUR_SITE_PRO)) {
            $option = \get_option(self::PIXEL_YOUR_SITE_OPTION_NAME);
            if (\is_array($option)) {
                $showNotice = !($option[self::PIXEL_YOUR_SITE_OPTION_ARRAY_NAME] ?? \false);
                if ($showNotice) {
                    echo \sprintf('<div class="notice notice-warning"><p>%s</p></div>', \sprintf(
                        // translators:
                        \__('<strong>Pixel Your Site</strong> embed trackers by default without waiting for consent. Please activate the Real Cookie Banner integration under <a href="%1$s"><i>PixelYourSite > Consent</i></a> to embed trackers only after consent. In addition, since you are using Real Cookie Banner, you need to enable all the suggested service templates in <a href="%2$s"><i>Cookies > Scanner</i></a> for the trackers embedded with "PixelYourSite".', RCB_TD),
                        \admin_url('admin.php?page=pixelyoursite&tab=gdpr'),
                        $configPage->getUrl() . '#/scanner'
                    ));
                }
            }
        }
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\comp\ThirdPartyNotices() : self::$me;
    }
}
