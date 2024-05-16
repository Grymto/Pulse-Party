<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue;

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
        return 'REAL_QUEUE';
    }
    /**
     * Make sure the REAL_QUEUE constants are available.
     */
    public static function setupConstants()
    {
        if (\defined('REAL_QUEUE_SLUG')) {
            return;
        }
        \define('REAL_QUEUE_SLUG', 'real-queue');
        \define('REAL_QUEUE_ROOT_SLUG', 'devowl-wp');
        \define('REAL_QUEUE_TD', REAL_QUEUE_ROOT_SLUG . '-' . REAL_QUEUE_SLUG);
        \define('REAL_QUEUE_SLUG_CAMELCASE', \lcfirst(\str_replace('-', '', \ucwords(REAL_QUEUE_SLUG, '-'))));
        \define('REAL_QUEUE_VERSION', \filemtime(__FILE__));
        // as we do serve assets through the consumer plugin we can safely use file modified time
        \define('REAL_QUEUE_DB_PREFIX', 'real_queue');
        \define('REAL_QUEUE_OPT_PREFIX', 'real-queue');
    }
}
