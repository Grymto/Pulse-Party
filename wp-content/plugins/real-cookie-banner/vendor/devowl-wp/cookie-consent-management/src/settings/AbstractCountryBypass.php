<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent\Consent;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent\Transaction;
/**
 * Abstract implementation of the settings for country bypass settings.
 * @internal
 */
abstract class AbstractCountryBypass extends BaseSettings
{
    const TYPE_ALL = 'all';
    const TYPE_ESSENTIALS = 'essentials';
    /**
     * A list of predefined lists for e.g. `GDPR` or `CCPA`.
     */
    const PREDEFINED_COUNTRY_LISTS = [
        // EU: https://reciprocitylabs.com/resources/what-countries-are-covered-by-gdpr/
        // EEA: https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Glossary:European_Economic_Area_(EEA)
        'GDPR' => ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IS', 'IT', 'LI', 'LV', 'LT', 'LU', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE'],
        'CCPA' => ['US'],
    ];
    /**
     * Get the country for the passed IP address.
     *
     * @param string $ipAddress
     * @return string
     */
    public abstract function lookupCountryCode($ipAddress);
    /**
     * Check if compatibility is enabled.
     *
     * @return boolean
     */
    public abstract function isActive();
    /**
     * Get the list of countries where the banner should be shown.
     *
     * @return string[]
     */
    public abstract function getCountriesRaw();
    /**
     * Get the type for the Country Bypass. Can be `all` or `essentials` (see class constants).
     *
     * @return string
     */
    public abstract function getType();
    /**
     * Get the list of countries where the banner should be shown, expanded with predefined lists (ISO 3166-1 alpha2).
     *
     * @return string[]
     */
    public function getCountries()
    {
        $result = [];
        // Expand predefined lists
        foreach ($this->getCountriesRaw() as $code) {
            if (\strlen($code) !== 2) {
                $predefinedList = self::PREDEFINED_COUNTRY_LISTS[$code] ?? [];
                $result = \array_merge($result, $predefinedList);
            } else {
                $result[] = $code;
            }
        }
        return $result;
    }
    /**
     * If Country Bypass is active and the requested IP address does match our settings, we can prepare a `Tansaction`
     * instance which we can instantly use to `Consent#commit` so the user does not see any cookie banner (see also
     * the term "Predecision Gateway").
     *
     * @param Consent $consent The current consent
     * @param Transaction $transaction A prepared transaction which has `userAgent`, `ipAddress` filled
     */
    public function probablyCreateTransaction($consent, $transaction)
    {
        $isLighthouse = \preg_match('/chrome-lighthouse/i', $transaction->userAgent);
        if ($this->isActive() && !$isLighthouse) {
            // Lookup for the current country and do not show banner if it is outside our defined countries
            $countries = $this->getCountries();
            $countryCode = $this->lookupCountryCode($transaction->ipAddress);
            if (!\is_string($countryCode) || \in_array(\strtoupper($countryCode), $countries, \true)) {
                // Skip custom bypass
                return \false;
            }
            $transaction->buttonClicked = 'none';
            $transaction->customBypass = 'geolocation';
            // The GDPR does not apply here, so we do not need to set a TCF string
            $transaction->tcfString = null;
            // Country bypassing does not need a GCM consent as this is configured through the `region` attribute in `gtag`
            $transaction->gcmConsent = null;
            // Create decision for this bypass
            $type = $this->getType();
            if (empty($consent->getUuid())) {
                // No previous consent, so create it from the given type
                $transaction->decision = $consent->sanitizeDecision($type);
            } else {
                // There is a previous consent, modify it
                $transaction->decision = $consent->optInOrOptOutExistingDecision($consent->getDecision(), $type, $type === 'all' ? 'optOut' : 'optIn');
            }
            return \true;
        }
        return \false;
    }
    /**
     * Changes to the country database are published daily, but we do this only once a week.
     */
    public static function getNextUpdateTime()
    {
        return \strtotime('next sunday 11:59 PM');
    }
}
