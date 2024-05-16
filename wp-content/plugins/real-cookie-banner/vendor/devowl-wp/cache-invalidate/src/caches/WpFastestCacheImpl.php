<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use stdClass;
/**
 * WP Fastest Cache.
 *
 * @see https://wordpress.org/plugins/wp-fastest-cache/
 * @see https://www.wpfastestcache.com/tutorial/delete-the-cache-by-calling-the-function/
 * @codeCoverageIgnore
 * @internal
 */
class WpFastestCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-fastest-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('wpfc_clear_all_cache');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \wpfc_clear_all_cache();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        /**
         * This is currently not yet implemented cause WP Fastest Cache is loading the option
         * before our assets are enqueued and we can not safely query `WP_Scripts`.
         */
        /*if (!is_admin()) {
                    add_filter('option_WpFastestCacheExclude', function ($value) use ($excludeAssets) {
                        $value = json_decode(is_string($value) ? $value : '[]', ARRAY_A);
        
                        foreach (['css', 'js'] as $type) {
                            foreach ($excludeAssets->getAllUrlPath($type) as $path) {
                                error_log($path);
                                $new_rule = new stdClass();
                                $new_rule->prefix = 'contain';
                                $new_rule->content = $path;
                                $new_rule->type = $type;
                                $value[] = $new_rule;
                            }
                        }
        
                        return json_encode($value);
                    });
                }*/
    }
    // Documented in AbstractCache
    public function failureExcludeAssets()
    {
        // See above for more information
        return \true;
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Fastest Cache';
    }
}
