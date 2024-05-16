<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use comet_cache;
/**
 * Comet Cache.
 *
 * @see https://wordpress.org/plugins/comet-cache/
 * @see https://cometcache.com/kb-article/clearing-the-cache-dynamically/
 * @codeCoverageIgnore
 * @internal
 */
class CometCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'comet-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(comet_cache::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return comet_cache::clear();
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Comet Cache';
    }
}
