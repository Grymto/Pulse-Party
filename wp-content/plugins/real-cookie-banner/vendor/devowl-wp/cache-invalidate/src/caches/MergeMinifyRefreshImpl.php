<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use MergeMinifyRefresh;
/**
 * Merge + Minify + Refresh
 *
 * @see https://wordpress.org/plugins/merge-minify-refresh/
 * @codeCoverageIgnore
 * @internal
 */
class MergeMinifyRefreshImpl extends AbstractCache
{
    const IDENTIFIER = 'merge-minify-refresh';
    // Documented in AbstractCache
    public function isActive()
    {
        global $mergeminifyrefresh;
        return \class_exists(MergeMinifyRefresh::class) && isset($mergeminifyrefresh);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        global $mergeminifyrefresh;
        return $mergeminifyrefresh->purgeAll();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('mmr_ignored_%s_sources', $type), function ($excluded) use($excludeAssets, $type) {
                $path = $excludeAssets->getAllUrls($type);
                return \array_merge($excluded, $path);
            }, 10, 3);
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Merge + Minify + Refresh';
    }
}
