<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use Perfmatters\CSS;
/**
 * Perfmatters.
 *
 * @see https://perfmatters.io/
 * @codeCoverageIgnore
 * @internal
 */
class PerfmattersCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'perfmatters';
    // Documented in AbstractCache
    public function isActive()
    {
        // Perfmatters supports caches since the introduction of "Remove Unused CSS", so we need to check if the class
        // with the method exists
        return \method_exists(CSS::class, 'clear_used_css');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return CSS::clear_used_css();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        \add_filter('perfmatters_delay_js_exclusions', function ($excluded) use($excludeAssets) {
            $path = $excludeAssets->getAllUrlPath('js');
            return \array_merge($excluded, $path, ['data-skip-lazy-load']);
        });
        \add_filter('perfmatters_rucss_excluded_stylesheets', function ($excluded) use($excludeAssets) {
            $path = $excludeAssets->getAllUrlPath('css');
            return \array_merge($excluded, $path);
        });
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Perfmatters';
    }
}
