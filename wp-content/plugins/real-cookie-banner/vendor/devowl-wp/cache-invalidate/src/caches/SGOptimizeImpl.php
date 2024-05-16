<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * SG Optimize.
 *
 * @see https://wordpress.org/plugins/sg-cachepress/
 * @codeCoverageIgnore
 * @internal
 */
class SGOptimizeImpl extends AbstractCache
{
    const IDENTIFIER = 'sg-cachepress';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('sg_cachepress_purge_cache');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \sg_cachepress_purge_cache();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        // Exclude from combine
        foreach (['javascript', 'css'] as $type) {
            \add_filter(\sprintf('sgo_%s_combine_exclude', $type), function ($exclude_list) use($excludeAssets, $type) {
                $useType = $type === 'javascript' ? 'js' : 'css';
                $exclude_list = \array_merge($exclude_list, $excludeAssets->getHandles()[$useType]);
                return $exclude_list;
            });
        }
        // Exclude from minify
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('sgo_%s_minify_exclude', $type), function ($exclude_list) use($excludeAssets, $type) {
                $exclude_list = \array_merge($exclude_list, $excludeAssets->getHandles()[$type]);
                return $exclude_list;
            });
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'SG Optimize';
    }
}
