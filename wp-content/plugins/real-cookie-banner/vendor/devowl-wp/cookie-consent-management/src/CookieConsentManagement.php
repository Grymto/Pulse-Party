<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent\Consent;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend\AbstractRevisionPersistance;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend\Frontend;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend\Revision;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\Settings;
/**
 * Main consent management class.
 * @internal
 */
class CookieConsentManagement
{
    /**
     * See `Settings`.
     *
     * @var Settings
     */
    private $settings;
    /**
     * See `Frontend`.
     *
     * @var Frontend
     */
    private $frontend;
    /**
     * See `Revision`.
     *
     * @var Revision
     */
    private $revison;
    /**
     * C'tor.
     *
     * @param Settings $settings
     * @param AbstractRevisionPersistance $revisionPersistence
     * @codeCoverageIgnore
     */
    public function __construct($settings, $revisionPersistence)
    {
        $this->settings = $settings;
        $this->settings->setCookieConsentManagement($this);
        $this->frontend = new Frontend($this);
        $this->revison = new Revision($this, $revisionPersistence);
    }
    /**
     * Start a new `Consent` session.
     */
    public function startConsent()
    {
        return new Consent($this);
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getSettings()
    {
        return $this->settings;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getFrontend()
    {
        return $this->frontend;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRevision()
    {
        return $this->revison;
    }
}
