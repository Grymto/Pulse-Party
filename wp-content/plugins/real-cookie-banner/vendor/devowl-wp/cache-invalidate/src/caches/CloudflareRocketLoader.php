<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * Cloudflare Rocket Loader.
 *
 * @see https://developers.cloudflare.com/speed/optimization/content/rocket-loader/
 * @codeCoverageIgnore
 * @internal
 */
class CloudflareRocketLoader extends AbstractCache
{
    const IDENTIFIER = 'cloudflare-rocket-loader';
    // Documented in AbstractCache
    public function isActive()
    {
        // Currently, there is no way to detect this.
        return \true;
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        // Nothing to do.
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
        return 'data-cfasync="false"';
    }
    // Documented in AbstractCache
    public function label()
    {
        // This should never be shown as active.
        return '';
    }
}
