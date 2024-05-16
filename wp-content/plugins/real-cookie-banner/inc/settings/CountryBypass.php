<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractCountryBypass;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\CountryBypass as LiteCountryBypass;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideCountryBypass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Bypass the cookie banner for a specific set of countries.
 * @internal
 */
class CountryBypass extends AbstractCountryBypass implements IOverrideCountryBypass
{
    use LiteCountryBypass;
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    /**
     * Name for the custom bypass saved in the database.
     */
    const CUSTOM_BYPASS = 'geolocation';
    const SETTING_COUNTRY_BYPASS_ACTIVE = RCB_OPT_PREFIX . '-country-bypass';
    const SETTING_COUNTRY_BYPASS_COUNTRIES = RCB_OPT_PREFIX . '-country-bypass-countries';
    const SETTING_COUNTRY_BYPASS_TYPE = RCB_OPT_PREFIX . '-country-bypass-type';
    const SETTING_COUNTRY_BYPASS_DB_DOWNLOAD_TIME = RCB_OPT_PREFIX . '-country-bypass-db-download-time';
    // This option should not be visible in any REST service, it is only used via `get_option` and `update_option`
    const OPTION_COUNTRY_DB_NEXT_DOWNLOAD_TIME = RCB_OPT_PREFIX . '-country-db-next-download-time';
    const DEFAULT_COUNTRY_BYPASS_ACTIVE = \false;
    const DEFAULT_COUNTRY_BYPASS_COUNTRIES = 'GDPR,CCPA,GB,CH';
    // use the predefined lists of below
    const DEFAULT_COUNTRY_BYPASS_TYPE = self::TYPE_ALL;
    const DEFAULT_COUNTRY_BYPASS_DB_DOWNLOAD_TIME = '';
    /**
     * Singleton instance.
     *
     * @var CountryBypass
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
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     * @return CountryBypass
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\CountryBypass() : self::$me;
    }
}
