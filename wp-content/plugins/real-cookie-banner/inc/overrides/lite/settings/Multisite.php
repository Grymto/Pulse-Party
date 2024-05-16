<?php

namespace DevOwl\RealCookieBanner\lite\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractMultisite;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait Multisite
{
    // Documented in IOverrideGeneral
    public function overrideEnableOptionsAutoload()
    {
        // Silence is golden.
    }
    // Documented in IOverrideMultisite
    public function overrideRegister()
    {
        // Silence is golden.
    }
    // Documented in AbstractMultisite
    public function isConsentForwarding()
    {
        return \false;
    }
    // Documented in AbstractMultisite
    public function getForwardTo()
    {
        return [];
    }
    // Documented in AbstractMultisite
    public function getCrossDomains()
    {
        return [];
    }
    // Documented in AbstractMultisite
    public function getConfiguredEndpoints()
    {
        return [];
    }
    // Documented in AbstractMultisite
    public function getAvailableEndpoints($filter = AbstractMultisite::ENDPOINT_FILTER_ALL)
    {
        return [];
    }
}
