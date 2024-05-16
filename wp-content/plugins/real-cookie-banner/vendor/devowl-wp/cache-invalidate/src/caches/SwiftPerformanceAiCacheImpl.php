<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use Swift3_Cache;
/**
 * Swift Performance AI (v3).
 *
 * @see https://swiftperformance.io/
 * @codeCoverageIgnore
 * @internal
 */
class SwiftPerformanceAiCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'swift-performance-ai';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(Swift3_Cache::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return Swift3_Cache::invalidate_object(null, -1);
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        \add_filter('swift3_skip_js_optimization', function ($result, $tag) use($excludeAssets) {
            if (!$result && isset($tag->attributes['data-dont-merge'])) {
                return \true;
            }
            if (!$result && isset($tag->attributes['src'])) {
                $src = $tag->attributes['src'];
                foreach ($excludeAssets->getAllUrlPath('js') as $path) {
                    if (\strpos($src, $path) !== \false) {
                        return \true;
                    }
                }
            }
            return $result;
        }, 10, 2);
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Swift Performance AI';
    }
}
