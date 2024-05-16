<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use autoptimizeCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * Autoptimize.
 *
 * @see https://wordpress.org/plugins/autoptimize/
 * @see https://www.davidkehr.com/wordpress-autoptimize-cache-automatisch-loeschen/
 * @codeCoverageIgnore
 * @internal
 */
class AutoptimizeCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'autoptimize';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(autoptimizeCache::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return autoptimizeCache::clearall();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('autoptimize_filter_%s_exclude', $type), function ($excluded) use($excludeAssets, $type) {
                $path = $excludeAssets->getAllUrlPath($type);
                return \count($path) > 0 ? $excluded . ',' . \join(',', $path) : $excluded;
            });
            // Avoid `autoptimize_single_379333ab543cd90a92c06d9165fda5dd.js' files
            \add_filter(\sprintf('autoptimize_filter_%s_consider_minified', $type), function ($considerMinified) use($excludeAssets, $type) {
                $considerMinified = \is_array($considerMinified) ? $considerMinified : [];
                $path = $excludeAssets->getAllUrlPath($type);
                return \array_merge($considerMinified, $path);
            });
        }
    }
    // Documented in AbstractCache
    public function excludeHtmlAttribute()
    {
        return 'data-noptimize';
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Autoptimize';
    }
}
