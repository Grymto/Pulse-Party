<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Debloat.
 *
 * @see https://wordpress.org/plugins/debloat/
 * @codeCoverageIgnore
 * @internal
 */
class DebloatImpl extends AbstractCache
{
    const IDENTIFIER = 'debloat';
    // Documented in AbstractCache
    public function isActive()
    {
        return \defined('DEBLOAT_PLUGIN_FILE');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \do_action('debloat/empty_caches');
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['debloat/optimize_css_excludes', 'debloat/remove_css_excludes'] as $filter) {
            \add_filter($filter, function ($exclude) use($excludeAssets) {
                $path = $excludeAssets->getAllUrlPath('css');
                return \array_merge($exclude, $path);
            });
        }
        foreach (['debloat/defer_js_excludes', 'debloat/delay_js_excludes'] as $filter) {
            \add_filter($filter, function ($exclude) use($excludeAssets) {
                $path = $excludeAssets->getAllUrlPath('js');
                return \array_merge($exclude, $path);
            });
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Debloat';
    }
}
