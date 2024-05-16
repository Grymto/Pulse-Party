<?php

namespace DevOwl\RealCookieBanner\overrides\interfce\settings;

use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
interface IOverrideCountryBypass
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
     * If we need to update the location database (scheduled), let's do this.
     */
    public function probablyUpdateDatabase();
    /**
     * Update the IP database and persist to our own database.
     *
     * @param boolean $force Skip `isActive` check and download immediate
     * @return true|WP_Error
     */
    public function updateDatabase($force = \false);
    /**
     * Completely clear all database tables.
     */
    public function clearDatabase();
    /**
     * Check when the database got downloaded at latest.
     *
     * @return string|null
     */
    public function getDatabaseDownloadTime();
}
