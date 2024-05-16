<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractMultisite;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\Multisite as LiteMultisite;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideMultisite;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Consent Forwarding settings.
 * @internal
 */
class Multisite extends AbstractMultisite implements IOverrideMultisite
{
    use LiteMultisite;
    use UtilsProvider;
    const FORWARDING_QUERY_BLOG_ID = 'blog';
    const OPTION_GROUP = 'options';
    const SETTING_CONSENT_FORWARDING = RCB_OPT_PREFIX . '-consent-forwarding';
    const SETTING_FORWARD_TO = RCB_OPT_PREFIX . '-forward-to';
    const SETTING_CROSS_DOMAINS = RCB_OPT_PREFIX . '-cross-domains';
    const DEFAULT_CONSENT_FORWARDING = \false;
    const DEFAULT_FORWARD_TO = '';
    const DEFAULT_CROSS_DOMAINS = '';
    /**
     * Singleton instance.
     *
     * @var Multisite
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
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        $this->overrideEnableOptionsAutoload();
    }
    /**
     * Register settings.
     */
    public function register()
    {
        $this->overrideRegister();
    }
    // Documented in AbstractMultisite
    public function getEnvironmentHost()
    {
        return \parse_url(\home_url(), \PHP_URL_HOST);
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     * @return Multisite
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\Multisite() : self::$me;
    }
}
