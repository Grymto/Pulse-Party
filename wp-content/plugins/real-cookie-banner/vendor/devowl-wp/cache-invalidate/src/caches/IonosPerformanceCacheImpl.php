<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use Ionos\Performance\Caching\Caching;
use Ionos\Performance\Manager;
/**
 * IONOS Performance plugin.
 *
 * @codeCoverageIgnore
 * @internal
 */
class IonosPerformanceCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'ionos-performance';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(Manager::class) || \class_exists(Caching::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        if (\class_exists(Manager::class)) {
            // v1
            return Manager::flush_total_cache(\true);
        } elseif (\class_exists(Caching::class)) {
            // v2
            return Caching::flush_total_cache();
        }
        return null;
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'IONOS Performance';
    }
}
