<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use function PoweredCache\Utils\powered_cache_flush;
/**
 * Autoptimize.
 *
 * @see https://wordpress.org/plugins/powered-cache/
 * @codeCoverageIgnore
 * @internal
 */
class PoweredCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'poweredcache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \defined('POWERED_CACHE_VERSION');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return powered_cache_flush();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('powered_cache_fo_%s_do_concat', $type), function ($doConcat, $handle) use($excludeAssets, $type) {
                $handles = $excludeAssets->getHandles();
                if ($doConcat && \in_array($handle, $handles[$type], \true)) {
                    return \false;
                }
                return $doConcat;
            }, 10, 2);
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'PoweredCache';
    }
}
