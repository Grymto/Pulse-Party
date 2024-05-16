<?php

namespace DevOwl\RealCookieBanner\overrides\interfce\settings;

use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
interface IOverrideTCF
{
    /**
     * Initially add PRO-only options.
     */
    public function overrideEnableOptionsAutoload();
    /**
     * Register PRO-only options.
     */
    public function overrideRegister();
    /**
     * If we need to update the GVL (scheduled), let's do this.
     */
    public function probablyUpdateGvl();
    /**
     * Update the GVL `vendor-list` and persist to database.
     *
     * @param boolean $force Skip `isActive` check and download immediate
     * @return true|WP_Error
     */
    public function updateGvl($force = \false);
    /**
     * Completely clear all database tables for GVL.
     */
    public function clearGvl();
    /**
     * Check when the compatibility got enabled the first time.
     *
     * @return string|null
     */
    public function getFirstAcceptedTime();
    /**
     * Check when the compatibility got enabled.
     *
     * @return string|null
     */
    public function getAcceptedTime();
    /**
     * Check when the GVL got downloaded at latest.
     *
     * @return string|null
     */
    public function getGvlDownloadTime();
}
