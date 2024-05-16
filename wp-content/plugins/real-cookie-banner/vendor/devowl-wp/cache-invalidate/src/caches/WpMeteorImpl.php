<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * WP Rocket.
 *
 * @see https://wordpress.org/plugins/wp-meteor/
 * @codeCoverageIgnore
 * @internal
 */
class WpMeteorImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-meteor';
    // Documented in AbstractCache
    public function isActive()
    {
        return \defined('WPMETEOR_VERSION');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        // Currently, the plugin does not have a cache and optimize on-the-fly
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
        return 'data-wpmeteor-nooptimize="true"';
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Meteor';
    }
}
