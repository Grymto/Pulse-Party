<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * WP Fastest Cache.
 *
 * @see https://wordpress.org/plugins/cache-enabler/
 * @see https://www.keycdn.com/support/wordpress-cache-enabler-plugin
 * @codeCoverageIgnore
 * @internal
 */
class CacheEnablerImpl extends AbstractCache
{
    const IDENTIFIER = 'cache-enabler';
    // Documented in AbstractCache
    public function isActive()
    {
        return \has_action('ce_clear_cache');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        \do_action('ce_clear_cache');
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Cache Enabler';
    }
}
