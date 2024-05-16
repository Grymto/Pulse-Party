<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Custom cache implementation through a filter. See filter `DevOwl/CacheInvalidate/Custom` for more information.
 *
 * @codeCoverageIgnore
 * @internal
 */
class CustomCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'custom-cache';
    // Documented in AbstractCache
    public function isActive()
    {
        return !empty($this->label());
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        /**
         * The cache invalidation took place, clear also a custom cache implemented through this filter.
         * To make this filter work and mark the cache as "active" you also need to make use of the `DevOwl/CacheInvalidate/Custom/Label`
         * filter.
         *
         * @hook DevOwl/CacheInvalidate/Custom
         * @param {array} $result
         * @return {array}
         * @example <caption>Clear a remote cache</caption>
         * <?php
         * add_filter( 'DevOwl/CacheInvalidate/Custom', function ( $result )  {
         *     $result['my-custom-cache'] = wp_remote_get('https://example.com/purgeall');
         *     return $result;
         * });
         *
         * add_filter( 'DevOwl/CacheInvalidate/Custom/Label', function ( $labels )  {
         *     $labels[] = 'My custom cache';
         *     return $labels;
         * });
         */
        return \apply_filters('DevOwl/CacheInvalidate/Custom', []);
    }
    // Documented in AbstractCache
    public function label()
    {
        /**
         * The cache invalidation took place, clear also a custom cache implemented through this filter.
         *
         * @hook DevOwl/CacheInvalidate/Custom/Label
         * @param {string[]} $labels
         * @return {string[]}
         */
        $labels = \apply_filters('DevOwl/CacheInvalidate/Custom/Label', []);
        return \count($labels) > 0 ? \join(', ', $labels) : '';
    }
}
