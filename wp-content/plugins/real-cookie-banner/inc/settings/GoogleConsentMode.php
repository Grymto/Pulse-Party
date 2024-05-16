<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractGoogleConsentMode;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\GoogleConsentMode as LiteGoogleConsentMode;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideGoogleConsentMode;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Consent Mode settings.
 * @internal
 */
class GoogleConsentMode extends AbstractGoogleConsentMode implements IOverrideGoogleConsentMode
{
    use LiteGoogleConsentMode;
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    const SETTING_GCM_ENABLED = RCB_OPT_PREFIX . '-gcm';
    const SETTING_GCM_SHOW_RECOMMONDATIONS_WITHOUT_CONSENT = RCB_OPT_PREFIX . '-gcm-recommandations';
    const SETTING_GCM_ADDITIONAL_URL_PARAMETERS = RCB_OPT_PREFIX . '-gcm-additional-url-parameters';
    const SETTING_GCM_REDACT_DATA_WITHOUT_CONSENT = RCB_OPT_PREFIX . '-gcm-redact-without-consent';
    const SETTING_GCM_LIST_PURPOSES = RCB_OPT_PREFIX . '-gcm-list-purposes';
    const DEFAULT_GCM_ENABLED = \false;
    const DEFAULT_GCM_GTM_INTEGRATION = \false;
    const DEFAULT_GCM_SHOW_RECOMMONDATIONS_WITHOUT_CONSENT = \false;
    const DEFAULT_GCM_ADDITIONAL_URL_PARAMETERS = \false;
    const DEFAULT_GCM_REDACT_DATA_WITHOUT_CONSENT = \true;
    const DEFAULT_GCM_LIST_PURPOSES = \true;
    /**
     * Singleton instance.
     *
     * @var GoogleConsentMode
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
     * @return GoogleConsentMode
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\GoogleConsentMode() : self::$me;
    }
}
