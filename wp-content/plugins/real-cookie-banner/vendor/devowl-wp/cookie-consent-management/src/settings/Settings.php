<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\CookieConsentManagement;
/**
 * A collection of all available settings.
 * @internal
 */
class Settings
{
    /**
     * See `AbstractGeneral`.
     *
     * @var AbstractGeneral
     */
    private $general;
    /**
     * See `AbstractConsent`.
     *
     * @var AbstractConsent
     */
    private $consent;
    /**
     * See `AbstractCountryBypass`.
     *
     * @var AbstractCountryBypass
     */
    private $countryBypass;
    /**
     * See `AbstractTcf`.
     *
     * @var AbstractTcf
     */
    private $tcf;
    /**
     * See `AbstractMultisite`.
     *
     * @var AbstractMultisite
     */
    private $multisite;
    /**
     * See `AbstractGoogleConsentMode`.
     *
     * @var AbstractGoogleConsentMode
     */
    private $googelConsentMode;
    /**
     * See `CookieConsentManagement`.
     *
     * @var CookieConsentManagement
     */
    private $cookieConsentManagement;
    /**
     * C'tor.
     *
     * @param AbstractGeneral $general
     * @param AbstractConsent $consent
     * @param AbstractCountryBypass $countryBypass
     * @param AbstractTcf $tcf
     * @param AbstractMultisite $multisite
     * @param AbstractGoogleConsentMode $googleConsentMode
     */
    public function __construct($general, $consent, $countryBypass, $tcf, $multisite, $googleConsentMode)
    {
        $this->general = $general;
        $this->consent = $consent;
        $this->countryBypass = $countryBypass;
        $this->tcf = $tcf;
        $this->multisite = $multisite;
        $this->googelConsentMode = $googleConsentMode;
        $this->general->setSettings($this);
        $this->consent->setSettings($this);
        $this->countryBypass->setSettings($this);
        $this->tcf->setSettings($this);
        $this->multisite->setSettings($this);
        $this->googelConsentMode->setSettings($this);
    }
    /**
     * Setter.
     *
     * @param CookieConsentManagement $cookieConsentManagement
     * @codeCoverageIgnore
     */
    public function setCookieConsentManagement($cookieConsentManagement)
    {
        $this->cookieConsentManagement = $cookieConsentManagement;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCookieConsentManagement()
    {
        return $this->cookieConsentManagement;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getGeneral()
    {
        return $this->general;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsent()
    {
        return $this->consent;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCountryBypass()
    {
        return $this->countryBypass;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTcf()
    {
        return $this->tcf;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMultisite()
    {
        return $this->multisite;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getGoogleConsentMode()
    {
        return $this->googelConsentMode;
    }
}
