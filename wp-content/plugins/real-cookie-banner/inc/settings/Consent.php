<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractConsent;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\Consent as LiteConsent;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideConsent;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Settings > Consent.
 * @internal
 */
class Consent extends AbstractConsent implements IOverrideConsent
{
    use LiteConsent;
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    const SETTING_ACCEPT_ALL_FOR_BOTS = RCB_OPT_PREFIX . '-accept-all-for-bots';
    const SETTING_RESPECT_DO_NOT_TRACK = RCB_OPT_PREFIX . '-respect-do-not-track';
    const SETTING_COOKIE_DURATION = RCB_OPT_PREFIX . '-cookie-duration';
    const SETTING_COOKIE_VERSION = RCB_OPT_PREFIX . '-cookie-version';
    const SETTING_FAILED_CONSENT_DOCUMENTATION_HANDLING = RCB_OPT_PREFIX . '-failed-consent-documentation-handling';
    const SETTING_SAVE_IP = RCB_OPT_PREFIX . '-save-ip';
    const SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES = RCB_OPT_PREFIX . '-data-processing-in-unsafe-countries';
    const SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES_SAFE_COUNTRIES = RCB_OPT_PREFIX . '-data-processing-in-unsafe-countries-safe-countries';
    const SETTING_AGE_NOTICE = RCB_OPT_PREFIX . '-age-notice';
    const SETTING_AGE_NOTICE_AGE_LIMIT = RCB_OPT_PREFIX . '-age-notice-age-limit';
    const SETTING_LIST_SERVICES_NOTICE = RCB_OPT_PREFIX . '-list-services-notice';
    const SETTING_CONSENT_DURATION = RCB_OPT_PREFIX . '-consent-duration';
    const DEFAULT_ACCEPT_ALL_FOR_BOTS = \true;
    const DEFAULT_RESPECT_DO_NOT_TRACK = \false;
    const DEFAULT_COOKIE_DURATION = 365;
    const DEFAULT_FAILED_CONSENT_DOCUMENTATION_HANDLING = self::FAILED_CONSENT_DOCUMENTATION_HANDLING_ESSENTIALS_ONLY;
    const DEFAULT_SAVE_IP = \false;
    const DEFAULT_DATA_PROCESSING_IN_UNSAFE_COUNTRIES = \false;
    const DEFAULT_DATA_PROCESSING_IN_UNSAFE_COUNTRIES_SAFE_COUNTRIES = 'GDPR,ADEQUACY';
    const DEFAULT_AGE_NOTICE = \true;
    const DEFAULT_AGE_NOTICE_AGE_LIMIT = 'INHERIT';
    const DEFAULT_LIST_SERVICES_NOTICE = \true;
    const DEFAULT_CONSENT_DURATION = 120;
    const TRANSIENT_SCHEDULE_CONSENTS_DELETION = RCB_OPT_PREFIX . '-schedule-consents-deletion';
    /**
     * Singleton instance.
     *
     * @var Consent
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
        Utils::enableOptionAutoload(self::SETTING_ACCEPT_ALL_FOR_BOTS, self::DEFAULT_ACCEPT_ALL_FOR_BOTS, 'boolval');
        Utils::enableOptionAutoload(self::SETTING_RESPECT_DO_NOT_TRACK, self::DEFAULT_RESPECT_DO_NOT_TRACK, 'boolval');
        Utils::enableOptionAutoload(self::SETTING_COOKIE_DURATION, self::DEFAULT_COOKIE_DURATION, 'intval');
        Utils::enableOptionAutoload(self::SETTING_COOKIE_VERSION, self::DEFAULT_COOKIE_VERSION, 'intval');
        Utils::enableOptionAutoload(self::SETTING_FAILED_CONSENT_DOCUMENTATION_HANDLING, self::DEFAULT_FAILED_CONSENT_DOCUMENTATION_HANDLING);
        Utils::enableOptionAutoload(self::SETTING_SAVE_IP, self::DEFAULT_SAVE_IP, 'boolval');
        Utils::enableOptionAutoload(self::SETTING_AGE_NOTICE, self::DEFAULT_AGE_NOTICE, 'boolval');
        Utils::enableOptionAutoload(self::SETTING_AGE_NOTICE_AGE_LIMIT, self::DEFAULT_AGE_NOTICE_AGE_LIMIT);
        Utils::enableOptionAutoload(self::SETTING_LIST_SERVICES_NOTICE, self::DEFAULT_LIST_SERVICES_NOTICE, 'boolval');
        Utils::enableOptionAutoload(self::SETTING_CONSENT_DURATION, self::DEFAULT_CONSENT_DURATION, 'intval');
        $this->overrideEnableOptionsAutoload();
    }
    /**
     * Register settings.
     */
    public function register()
    {
        \register_setting(self::OPTION_GROUP, self::SETTING_ACCEPT_ALL_FOR_BOTS, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_RESPECT_DO_NOT_TRACK, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_COOKIE_DURATION, ['type' => 'number', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_COOKIE_VERSION, ['type' => 'number', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_FAILED_CONSENT_DOCUMENTATION_HANDLING, ['type' => 'boolean', 'show_in_rest' => ['schema' => ['type' => 'string', 'enum' => self::FAILED_CONSENT_DOCUMENTATION_HANDLINGS]]]);
        \register_setting(self::OPTION_GROUP, self::SETTING_SAVE_IP, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_AGE_NOTICE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_AGE_NOTICE_AGE_LIMIT, ['type' => 'boolean', 'show_in_rest' => ['schema' => ['type' => 'string', 'enum' => \array_keys(self::AGE_NOTICE_COUNTRY_AGE_MAP)]]]);
        \register_setting(self::OPTION_GROUP, self::SETTING_LIST_SERVICES_NOTICE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_CONSENT_DURATION, ['type' => 'number', 'show_in_rest' => \true]);
        $this->overrideRegister();
    }
    // Documented in AbstractConsent
    public function isAcceptAllForBots()
    {
        return \get_option(self::SETTING_ACCEPT_ALL_FOR_BOTS);
    }
    // Documented in AbstractConsent
    public function isRespectDoNotTrack()
    {
        return \get_option(self::SETTING_RESPECT_DO_NOT_TRACK);
    }
    // Documented in AbstractConsent
    public function getFailedConsentDocumentationHandling()
    {
        return \get_option(self::SETTING_FAILED_CONSENT_DOCUMENTATION_HANDLING);
    }
    // Documented in AbstractConsent
    public function isSaveIpEnabled()
    {
        return \get_option(self::SETTING_SAVE_IP);
    }
    // Documented in AbstractConsent
    public function isAgeNoticeEnabled()
    {
        return \get_option(self::SETTING_AGE_NOTICE);
    }
    // Documented in AbstractConsent
    public function getAgeNoticeAgeLimitRaw()
    {
        return \get_option(self::SETTING_AGE_NOTICE_AGE_LIMIT);
    }
    // Documented in AbstractConsent
    public function isListServicesNoticeEnabled()
    {
        return \get_option(self::SETTING_LIST_SERVICES_NOTICE);
    }
    // Documented in AbstractConsent
    public function getCookieDuration()
    {
        return \get_option(self::SETTING_COOKIE_DURATION);
    }
    // Documented in AbstractConsent
    public function getCookieVersion()
    {
        return \get_option(self::SETTING_COOKIE_VERSION);
    }
    // Documented in AbstractConsent
    public function getConsentDuration()
    {
        return \get_option(self::SETTING_CONSENT_DURATION);
    }
    // Documented in AbstractConsent
    public function setCookieVersion($version)
    {
        \update_option(self::SETTING_COOKIE_VERSION, $version);
    }
    /**
     * The cookie duration may not be greater than 365 days.
     *
     * @param mixed $value
     * @since 1.10
     */
    public function option_cookie_duration($value)
    {
        // Use `is_numeric` as it can be a string
        if (\is_numeric($value) && \intval($value) > 365) {
            return 365;
        }
        return $value;
    }
    /**
     * The consent duration may not be greater than 120 months.
     *
     * @param mixed $value
     */
    public function option_consent_duration($value)
    {
        // Use `is_numeric` as it can be a string
        if (\is_numeric($value) && \intval($value) > 120) {
            return 120;
        }
        return $value;
    }
    /**
     *  Delete transient when settings are updated
     */
    public function update_option_consent_transient_deletion()
    {
        \delete_transient(self::TRANSIENT_SCHEDULE_CONSENTS_DELETION);
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\Consent() : self::$me;
    }
}
