<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use Hummingbird\WP_Hummingbird;
/**
 * Hummingbird.
 *
 * @see https://wordpress.org/plugins/hummingbird-performance/
 * @see https://premium.wpmudev.org/docs/api-plugin-development/hummingbird-api-docs/#action-wphb_clear_page_cache
 * @codeCoverageIgnore
 * @internal
 */
class HummingbirdImpl extends AbstractCache
{
    const IDENTIFIER = 'hummingbird-performance';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(WP_Hummingbird::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        \do_action('wphb_clear_page_cache');
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     * @see https://gist.github.com/igmoweb/fe669ba5f63f7ac2cab6ffc4330efa67
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['wphb_minify_resource', 'wphb_combine_resource'] as $filter) {
            \add_filter($filter, function ($minify_or_combine, $handle, $type) use($excludeAssets) {
                $excludedHandles = $excludeAssets->getHandles()[$type === 'scripts' ? 'js' : 'css'];
                if (\in_array($handle, $excludedHandles, \true)) {
                    return \false;
                }
                return $minify_or_combine;
            }, 10, 3);
        }
        \add_filter('wphb_minification_display_enqueued_file', function ($display, $item, $type) use($excludeAssets) {
            $excludedHandles = $excludeAssets->getHandles()[$type === 'scripts' ? 'js' : 'css'];
            if (\in_array($item['handle'], $excludedHandles, \true)) {
                return \false;
            }
            return $display;
        }, 10, 3);
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Hummingbird';
    }
}
