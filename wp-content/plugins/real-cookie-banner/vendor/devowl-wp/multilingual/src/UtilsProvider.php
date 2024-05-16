<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Base;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Let our package act as own "plugin".
 * @internal
 */
trait UtilsProvider
{
    use Base;
    /**
     * Get the prefix of this package so we can utils package natively.
     *
     * @return string
     */
    public function getPluginConstantPrefix()
    {
        self::setupConstants();
        return 'DEVOWL_MULTILINGUAL';
    }
    /**
     * Make sure the DEVOWL_MULTILINGUAL constants are available.
     */
    public static function setupConstants()
    {
        if (\defined('DEVOWL_MULTILINGUAL_SLUG')) {
            return;
        }
        \define('DEVOWL_MULTILINGUAL_SLUG', 'multilingual');
        \define('DEVOWL_MULTILINGUAL_ROOT_SLUG', 'devowl-wp');
        \define('DEVOWL_MULTILINGUAL_TD', DEVOWL_MULTILINGUAL_ROOT_SLUG . '-' . DEVOWL_MULTILINGUAL_SLUG);
        \define('DEVOWL_MULTILINGUAL_SLUG_CAMELCASE', \lcfirst(\str_replace('-', '', \ucwords(DEVOWL_MULTILINGUAL_SLUG, '-'))));
        \define('DEVOWL_MULTILINGUAL_VERSION', \filemtime(__FILE__));
        // as we do serve assets through the consumer plugin we can safely use file modified time
        \define('DEVOWL_MULTILINGUAL_OPT_PREFIX', 'devowl-multilingual');
    }
}
