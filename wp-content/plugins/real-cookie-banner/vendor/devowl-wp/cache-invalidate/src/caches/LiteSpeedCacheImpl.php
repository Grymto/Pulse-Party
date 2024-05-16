<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use LiteSpeed\Purge;
/**
 * LiteSpeed Cache
 *
 * @see https://wordpress.org/plugins/litespeed-cache/
 * @see https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:api#hooks
 * @codeCoverageIgnore
 * @internal
 */
class LiteSpeedCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'litespeed-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(Purge::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return Purge::purge_all('@devowl-wp/cache-invalidate');
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js', 'css'] as $type) {
            \add_filter(\sprintf('litespeed_optimize_%s_excludes', $type), function ($excluded) use($excludeAssets, $type) {
                $path = $excludeAssets->getAllUrlPath($type);
                return \array_merge($excluded, $path);
            }, 10, 3);
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'LiteSpeed Cache';
    }
}
