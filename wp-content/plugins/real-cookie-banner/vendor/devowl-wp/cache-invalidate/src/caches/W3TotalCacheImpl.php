<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * W3 Total Cache.
 *
 * @see https://wordpress.org/plugins/w3-total-cache/
 * @see http://hookr.io/plugins/w3-total-cache/0.9.5.1/hooks/#index=g
 * @codeCoverageIgnore
 * @internal
 */
class W3TotalCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'w3-total-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('w3tc_pgcache_flush');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \w3tc_pgcache_flush();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('w3tc_minify_%s_do_tag_minification', $type), function ($do_minify, $script_tag, $file) use($excludeAssets, $type) {
                if (empty($file)) {
                    return $do_minify;
                }
                foreach ($excludeAssets->getAllUrlPath($type) as $path) {
                    if ($path === $file) {
                        return \false;
                    }
                }
                return $do_minify;
            }, 10, 3);
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'W3 Total Cache';
    }
}
