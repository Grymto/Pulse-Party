<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use RaidboxesNginxCacheFunctions;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Raidboxes Hosting cache (uses NGINX cache under the hood).
 *
 * @see https://raidboxes.io/
 * @codeCoverageIgnore
 * @internal
 */
class RaidboxesImpl extends AbstractCache
{
    const IDENTIFIER = 'raidboxes';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(RaidboxesNginxCacheFunctions::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        $fn = new RaidboxesNginxCacheFunctions();
        return $fn->purge_cache();
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Raidboxes Cache';
    }
}
