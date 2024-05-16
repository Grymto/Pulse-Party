<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * NitroPack.
 *
 * @see https://wordpress.org/plugins/nitropack/
 * @codeCoverageIgnore
 * @internal
 */
class NitroPackImpl extends AbstractCache
{
    const IDENTIFIER = 'nitropack';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('nitropack_sdk_purge');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \nitropack_sdk_purge();
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
    public function label()
    {
        return 'NitroPack';
    }
    // Documented in AbstractCache
    public function excludeHtmlAttribute()
    {
        return 'nitro-exclude';
    }
}
