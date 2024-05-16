<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use function OPDashboard\clear_all_cache;
/**
 * OptimizePress
 *
 * @see https://optimizepress.com/
 * @codeCoverageIgnore
 * @internal
 */
class OptimizePressImpl extends AbstractCache
{
    const IDENTIFIER = 'optimizepress';
    // Documented in AbstractCache
    public function isActive()
    {
        return \defined('OPD_VERSION');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return clear_all_cache();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach ([['script', 'js'], ['style', 'css']] as $row) {
            list($type, $mime) = $row;
            \add_filter(\sprintf('op3_%s_is_allowed_in_blank_template', $type), function ($allowed, $handle) use($mime, $excludeAssets) {
                $handles = $excludeAssets->getHandles();
                if (!$allowed && \in_array($handle, $handles[$mime], \true)) {
                    return \true;
                }
                return $allowed;
            }, 10, 2);
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'OptimizePress';
    }
}
