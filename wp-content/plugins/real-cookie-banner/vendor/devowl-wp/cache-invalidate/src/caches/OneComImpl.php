<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use VCachingOC;
/**
 * One.com Performance Cache.
 *
 * @codeCoverageIgnore
 * @internal
 */
class OneComImpl extends AbstractCache
{
    const IDENTIFIER = 'one-com';
    // Documented in AbstractCache
    public function isActive()
    {
        global $vcaching;
        return \class_exists(VCachingOC::class) && isset($vcaching);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        global $vcaching;
        /**
         * Var.
         *
         * @var VCachingOC
         */
        $vcaching = $vcaching;
        return $vcaching->purge_cache();
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'One.com Performance Cache';
    }
}
