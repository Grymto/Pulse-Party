<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use Borlabs\Cache\Frontend\Garbage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * Borlabs Cache.
 *
 * @see https://de.borlabs.io/borlabs-cache/
 * @codeCoverageIgnore
 * @internal
 */
class BorlabsCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'borlabs-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(Garbage::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        Garbage::getInstance()->clearStylesPreCacheFiles();
        return Garbage::getInstance()->clearCache();
    }
    // Documented in AbstractCache
    public function failureExcludeAssets()
    {
        // Borlabs supports such a feature, but does not allow to exclude by URL
        return \true;
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Borlabs Cache';
    }
}
