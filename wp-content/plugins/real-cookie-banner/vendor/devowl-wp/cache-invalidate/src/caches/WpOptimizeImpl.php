<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use WP_Optimize;
/**
 * WP Optimize.
 *
 * @see https://wordpress.org/plugins/wp-optimize/
 * @see https://getwpo.com/faqs/#Full-cache-purge-
 * @codeCoverageIgnore
 * @internal
 */
class WpOptimizeImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-optimize';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(WP_Optimize::class) && \defined('WPO_PLUGIN_MAIN_PATH');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        if (\class_exists('WP_Optimize')) {
            return \WP_Optimize()->get_page_cache()->purge();
        } else {
            return \false;
        }
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['js'] as $type) {
            \add_filter(\sprintf('wp-optimize-minify-default-exclusions', $type), function ($excluded) use($excludeAssets, $type) {
                $path = $excludeAssets->getAllUrlPath($type);
                return \array_merge($excluded, $path);
            });
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Optimize';
    }
}
