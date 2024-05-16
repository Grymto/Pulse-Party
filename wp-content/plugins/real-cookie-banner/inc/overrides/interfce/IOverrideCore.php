<?php

namespace DevOwl\RealCookieBanner\overrides\interfce;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Freemium\ICore;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
interface IOverrideCore extends ICore
{
    /**
     * Extend constructor.
     */
    public function overrideConstruct();
    /**
     * Register additional settings.
     */
    public function overrideRegisterSettings();
    /**
     * Register additional custom post types and taxonmies.
     */
    public function overrideRegisterPostTypes();
    /**
     * Additional init.
     */
    public function overrideInit();
}
