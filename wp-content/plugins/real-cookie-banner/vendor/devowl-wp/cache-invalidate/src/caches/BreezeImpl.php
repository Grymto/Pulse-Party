<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use Breeze_Admin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * Breeze.
 *
 * @see https://wordpress.org/plugins/breeze/
 * @codeCoverageIgnore
 * @internal
 */
class BreezeImpl extends AbstractCache
{
    const IDENTIFIER = 'breeze';
    // Documented in AbstractCache
    public function isActive()
    {
        return \class_exists(Breeze_Admin::class);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        $admin = $this->getAdminInstance();
        if ($admin !== null) {
            return $admin->breeze_clear_all_cache();
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
        // This does currently not work cause excluding files in Breeze works, but it also
        // extracts all inline scripts (e.g. `wp_localize_script`), too. So, e.g. Real Cookie Banner
        // does no longer work because the `var realCookieBanner` is expected in the headers
        /*if (!is_admin()) {
                    add_filter('(site)?_option_breeze_advanced_settings', function ($value) use ($excludeAssets) {
                        $value = is_array($value)
                            ? $value
                            : [
                                'breeze-exclude-js' => [],
                                'breeze-exclude-css' => []
                            ];
        
                        foreach (['css', 'js'] as $type) {
                            foreach ($excludeAssets->getDependencies($type) as $dep) {
                                $value['breeze-exclude-' . $type][] .= $dep->src;
                            }
                        }
        
                        return $value;
                    });
                }*/
    }
    // Documented in AbstractCache
    public function failureExcludeAssets()
    {
        // See above for more information
        return \true;
    }
    /**
     * Get the admin instance so we can safely clear the cache.
     *
     * @return Breeze_Admin|null
     */
    public function getAdminInstance()
    {
        global $wp_filter;
        if (isset($wp_filter['breeze_clear_all_cache'])) {
            $callbacks = $wp_filter['breeze_clear_all_cache']->callbacks;
            if (isset($callbacks['10'])) {
                foreach ($callbacks['10'] as $callback) {
                    if (\is_array($callback['function'])) {
                        $callee = $callback['function'][0];
                        if (\gettype($callee) === 'object' && \get_class($callee) === Breeze_Admin::class) {
                            return $callee;
                        }
                    }
                }
            }
        }
        return null;
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Breeze';
    }
}
