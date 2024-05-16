<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\Blocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\ServiceGroup;
/**
 * Abstract implementation of the settings for general consent management (e.g. is cookie banner active?).
 * @internal
 */
abstract class AbstractGeneral extends BaseSettings
{
    const TERRITORIAL_LEGAL_BASIS_GDPR = 'gdpr-eprivacy';
    const TERRITORIAL_LEGAL_BASIS_DSG_SWITZERLAND = 'dsg-switzerland';
    const LEGAL_BASIS_ALLOWED = [self::TERRITORIAL_LEGAL_BASIS_GDPR, self::TERRITORIAL_LEGAL_BASIS_DSG_SWITZERLAND];
    /**
     * Is the banner active?
     *
     * @return boolean
     */
    public abstract function isBannerActive();
    /**
     * Is the content blocker active?
     *
     * @return boolean
     */
    public abstract function isBlockerActive();
    /**
     * Get configured legal basis.
     *
     * @return string[]
     */
    public abstract function getTerritorialLegalBasis();
    /**
     * Get configured operator country.
     *
     * @return string
     */
    public abstract function getOperatorCountry();
    /**
     * Get configured operator contact address.
     *
     * @return string
     */
    public abstract function getOperatorContactAddress();
    /**
     * Get configured operator contact phone.
     *
     * @return string
     */
    public abstract function getOperatorContactPhone();
    /**
     * Get configured operator contact email.
     *
     * @return string
     */
    public abstract function getOperatorContactEmail();
    /**
     * Get the operator contact form page ID.
     *
     * @return int
     */
    public abstract function getOperatorContactFormId();
    /**
     * Get the operator contact form page URL.
     *
     * @param mixed $default
     * @return mixed
     */
    public abstract function getOperatorContactFormUrl($default = \false);
    /**
     * Get an array of hidden page ids (not imprint and privacy policy, there are own options!).
     *
     * @return int[]
     */
    public abstract function getAdditionalPageHideIds();
    /**
     * Get the option "Load services after consent via".
     *
     * @return string
     */
    public abstract function getSetCookiesViaManager();
    /**
     * Get the list of service groups and their services.
     *
     * @return ServiceGroup[]
     */
    public abstract function getServiceGroups();
    /**
     * Get the list of content blockers.
     *
     * @return Blocker[]
     */
    public abstract function getBlocker();
    /**
     * Get the list of banner links which should be shown in cookie banner and content blocker.
     *
     * @return BannerLink[]
     */
    public abstract function getBannerLinks();
    /**
     * Get the list of languages in which this website is reachable. This is also used for the language switcher.
     *
     * @return Language[]
     */
    public abstract function getLanguages();
    /**
     * Get the essential service groups.
     */
    public function getEssentialServiceGroup()
    {
        foreach ($this->getServiceGroups() as $group) {
            if ($group->isEssential()) {
                return $group;
            }
        }
        return null;
    }
}
