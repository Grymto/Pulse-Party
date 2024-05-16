<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * WP Super Cache.
 *
 * @see https://wordpress.org/plugins/wp-super-cache/
 * @see https://odd.blog/wp-super-cache-developers/
 * @codeCoverageIgnore
 * @internal
 */
class WpSuperCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-super-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('wp_cache_clean_cache');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        global $file_prefix, $supercachedir;
        // In some cases the cache dir is not yet filled as global variable, let's fix this
        if (empty($supercachedir) && \function_exists('get_supercache_dir')) {
            $supercachedir = \get_supercache_dir();
        }
        return \wp_cache_clean_cache($file_prefix);
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Super Cache';
    }
}
