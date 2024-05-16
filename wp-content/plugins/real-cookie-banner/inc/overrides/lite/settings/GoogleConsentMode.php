<?php

namespace DevOwl\RealCookieBanner\lite\settings;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait GoogleConsentMode
{
    // Documented in IOverrideGeneral
    public function overrideEnableOptionsAutoload()
    {
        // Silence is golden.
    }
    // Documented in IOverrideGoogleConsentMode
    public function overrideRegister()
    {
        // Silence is golden.
    }
    // Documented in AbstractGoogleConsentMode
    public function isEnabled()
    {
        return \false;
    }
    // Documented in AbstractGoogleConsentMode
    public function isShowRecommandationsWithoutConsent()
    {
        return \false;
    }
    // Documented in AbstractGoogleConsentMode
    public function isCollectAdditionalDataViaUrlParameters()
    {
        return \false;
    }
    // Documented in AbstractGoogleConsentMode
    public function isRedactAdsDataWithoutConsent()
    {
        return \false;
    }
    // Documented in AbstractGoogleConsentMode
    public function isListPurposes()
    {
        return \false;
    }
}
