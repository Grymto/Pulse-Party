<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\CacheInvalidator;
use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Automatically clear frontend cache when essential things got changed:
 *
 * - Settings
 * - Customize
 * - New consent revision
 * @internal
 */
class Cache
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var Cache
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
     * When customize got updated, refresh cache.
     *
     * @param array $response
     */
    public function customize_updated($response)
    {
        $response['invalidate_cache'] = $this->invalidate();
        return $response;
    }
    /**
     * When settings got updated, refresh cache.
     *
     * @param WP_HTTP_Response $response
     */
    public function settings_updated($response)
    {
        $response->data['invalidate_cache'] = $this->invalidate();
        \DevOwl\RealCookieBanner\Core::getInstance()->getNotices()->recalculatePostDependingNotices();
        return $response;
    }
    /**
     * When a new consent got requested, refresh cache.
     *
     * @param array $result
     */
    public function revision_hash($result)
    {
        $result['invalidate_cache'] = $this->invalidate();
        return $result;
    }
    /**
     * Invalidate the cache with the help of `cache-invalidate` package.
     */
    public function invalidate()
    {
        return CacheInvalidator::getInstance()->invalidate();
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\Cache() : self::$me;
    }
}
