<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use TFCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Themify cache.
 *
 * @see https://themify.me/
 * @codeCoverageIgnore
 * @internal
 */
class ThemifyImpl extends AbstractCache
{
    const IDENTIFIER = 'themify';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(TFCache::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return TFCache::remove_cache('blog');
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Themify Cache';
    }
}
