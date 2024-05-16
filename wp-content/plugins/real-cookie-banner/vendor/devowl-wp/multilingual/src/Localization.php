<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\PackageLocalization;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Package localization for `multilingual` package.
 * @internal
 */
class Localization extends PackageLocalization
{
    /**
     * C'tor.
     */
    protected function __construct()
    {
        parent::__construct(DEVOWL_MULTILINGUAL_ROOT_SLUG, \dirname(__DIR__));
    }
    /**
     * Create instance.
     *
     * @codeCoverageIgnore
     */
    public static function instanceThis()
    {
        return new Localization();
    }
}
