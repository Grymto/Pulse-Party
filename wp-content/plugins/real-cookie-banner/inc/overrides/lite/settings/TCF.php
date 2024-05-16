<?php

namespace DevOwl\RealCookieBanner\lite\settings;

use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait TCF
{
    // Documented in IOverrideGeneral
    public function overrideEnableOptionsAutoload()
    {
        // Silence is golden.
    }
    // Documented in IOverrideTCF
    public function overrideRegister()
    {
        // Silence is golden.
    }
    // Documented in IOverrideTCF
    public function probablyUpdateGvl()
    {
        // Silence is golden.
    }
    // Documented in IOverrideTCF
    public function updateGvl($force = \false)
    {
        return new WP_Error('rcb_update_gvl_needs_pro', \__('This functionality is only available in the PRO version.', RCB_TD), ['status' => 500]);
    }
    /**
     * Completely clear all database tables for GVL.
     */
    public function clearGvl()
    {
        // Silence is golden.
    }
    // Documented in IOverrideTCF
    public function clear()
    {
        // Silence is golden.
    }
    // Documented in AbstractTcf
    public function isActive()
    {
        return \false;
    }
    // Documented in IOverrideTCF
    public function getFirstAcceptedTime()
    {
        return '';
    }
    // Documented in IOverrideTCF
    public function getAcceptedTime()
    {
        return '';
    }
    // Documented in IOverrideTCF
    public function getGvlDownloadTime()
    {
        return '';
    }
    // Documented in AbstractTcf
    public function getScopeOfConsent()
    {
        return null;
    }
    // Documented in AbstractTcf
    public function getVendorConfigurations()
    {
        return [];
    }
    // Documented in AbstractTcf
    public function getGvl()
    {
        return null;
    }
}
