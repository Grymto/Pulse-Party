<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use autoptimizeCache;
/**
 * Cloudflare plugin.
 *
 * @see https://de.wordpress.org/plugins/cloudflare/
 * @codeCoverageIgnore
 * @internal
 */
class CloudflareImpl extends AbstractCache
{
    const IDENTIFIER = 'cloudflare';
    // Documented in AbstractCache
    public function isActive()
    {
        return \is_plugin_active('cloudflare/cloudflare.php');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        // Manually call action as Autopimize is compatible with the plugin itself:
        // https://github.com/cloudflare/Cloudflare-WordPress/blob/10dd1ef1f7c0060c96d198b9b4883a09b7c7b7a8/cloudflare.loader.php#L66
        // and the Cloudflare plugin does not provide an API function to clear the cache.
        if (\class_exists(autoptimizeCache::class)) {
            // Silence is golden. Cache clearing is done through `AutopimizeCacheImpl`
        } else {
            \do_action('autoptimize_action_cachepurged');
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Cloudflare';
    }
}
