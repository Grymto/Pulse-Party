<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle try.devowl.io specific settings, e. g. hide template attributes.
 * @internal
 */
class DemoEnvironment
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var DemoEnvironment
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Checks if the current running WordPress instance is configured as sandbox
     * because then some configurations are not allowed to change.
     */
    public function isDemoEnv()
    {
        return \defined('MATTHIASWEB_DEMO') && \constant('MATTHIASWEB_DEMO');
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\DemoEnvironment() : self::$me;
    }
}
