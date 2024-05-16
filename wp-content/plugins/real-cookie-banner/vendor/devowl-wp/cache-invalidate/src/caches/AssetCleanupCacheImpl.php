<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
/**
 * Asset CleanUp.
 *
 * @see https://wordpress.org/plugins/wp-asset-clean-up/
 * @codeCoverageIgnore
 * @internal
 */
class AssetCleanupCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'asset-clean-up';
    // Documented in AbstractCache
    public function isActive()
    {
        //return defined('WPACU_PLUGIN_VERSION'); -> Use `WPACU_PLUGIN_FILE` as this reflects the real state
        // if the plugin is loaded e.g. invalidation works as expected.
        return \defined('WPACU_PLUGIN_FILE');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        // Cache invalidation does not work via REST API requests as this is stricly
        // implemented in the plugin: https://plugins.trac.wordpress.org/browser/wp-asset-clean-up/trunk/early-triggers.php#L663
        return OptimizeCommon::clearCache(\false);
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        $this->addExcludeHtmlAttributeLoaderTagFilter($excludeAssets);
    }
    // Documented in AbstractCache
    public function excludeHtmlAttribute()
    {
        return 'data-wpacu-skip';
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Asset CleanUp: Page Speed Booster';
    }
}
