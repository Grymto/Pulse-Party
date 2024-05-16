<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf\AbstractGvlPersistance;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf\VendorConfiguration;
/**
 * Abstract implementation of the settings for the TCF compatibility.
 * @internal
 */
abstract class AbstractTcf extends BaseSettings
{
    const SCOPE_OF_CONSENT_SERVICE = 'service-specific';
    const ALLOWED_SCOPE_OF_CONSENT = [self::SCOPE_OF_CONSENT_SERVICE];
    /**
     * Check if compatibility is enabled.
     *
     * @return boolean
     */
    public abstract function isActive();
    /**
     * Get scope of consent.
     *
     * @return string Can be `service`
     */
    public abstract function getScopeOfConsent();
    /**
     * Get the list of created TCF vendor configurations.
     *
     * @return VendorConfiguration[]
     */
    public abstract function getVendorConfigurations();
    /**
     * Get GVL persistence class.
     *
     * @return AbstractGvlPersistance
     */
    public abstract function getGvl();
    /**
     * Changes to the Global Vendor List are published weekly at 5:00 PM Central European Time on Thursdays.
     */
    public static function getNextUpdateTime()
    {
        return \strtotime('next thursday 4:00 PM');
        // convert CET to UTC (+01:00)
    }
    /**
     * Sanitize a 4-letter language code to 2-letter language code as it is the only
     * one which is currently supported by TCF.
     *
     * @param string $language
     */
    public static function fourLetterLanguageCodeToTwoLetterCode($language)
    {
        return \strtolower(\explode('-', \explode('_', $language)[0])[0]);
    }
}
