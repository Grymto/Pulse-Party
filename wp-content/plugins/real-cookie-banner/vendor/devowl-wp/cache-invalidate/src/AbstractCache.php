<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate;

/**
 * Implement a cache mechanism / plugin.
 * @internal
 */
abstract class AbstractCache
{
    /**
     * Check if the caching mechanism / plugin is active and available.
     *
     * @return boolean
     */
    public abstract function isActive();
    /**
     * Trigger a cache invalidation.
     *
     * @return mixed
     */
    public abstract function invalidate();
    /**
     * Returns `true` if the plugin does not support excluding assets but has the feature in general.
     * Returns `void` if the does not have this feature or when excluding is supported.
     *
     * @return void|boolean
     */
    public function failureExcludeAssets()
    {
        // Silence is golden.
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     * @return void|false Returns `false` if the plugin does not support excluding assets but has the feature in general
     */
    public function excludeAssetsHook($excludeAssets)
    {
        // Silence is golden.
    }
    /**
     * Similar to `excludeAssetsHook`, but instead of using hooks you can use `CacheInvalidator#getExcludeHtmlAttributesString()`
     * to append to your HTML markup so it gets ignored by the cache plugin.
     *
     * @return false|string
     */
    public function excludeHtmlAttribute()
    {
        return \false;
    }
    /**
     * Get the label.
     */
    public abstract function label();
    /**
     * Add defined `excludeHtmlAttribute` as HTML attribute to all affected Script/Link tags.
     *
     * @param ExcludeAssets $excludeAssets
     */
    protected function addExcludeHtmlAttributeLoaderTagFilter($excludeAssets)
    {
        $excludeAttribute = $this->excludeHtmlAttribute();
        if (!\is_string($excludeAttribute)) {
            return;
        }
        foreach (['js', 'css'] as $type) {
            $typeTag = $type === 'js' ? 'script' : 'style';
            \add_filter(\sprintf('%s_loader_tag', $typeTag), function ($tag, $handle) use($type, $excludeAssets, $typeTag, $excludeAttribute) {
                $handles = $excludeAssets->getHandles()[$type];
                if (\in_array($handle, $handles, \true) && \strpos($tag, $excludeAttribute) === \false) {
                    return \str_replace(\sprintf('<%s ', $typeTag), \sprintf('<%s %s ', $typeTag, $excludeAttribute), $tag);
                }
                return $tag;
            }, 10, 2);
        }
    }
    /**
     * Add `skip-rucss` as HTML attribute to all affected link tags.
     *
     * @param ExcludeAssets $excludeAssets
     */
    protected function addExcludeStyleTagFromRucssProcessAttributeTagFilter($excludeAssets)
    {
        \add_filter('style_loader_tag', function ($tag, $handle) use($excludeAssets) {
            $handles = $excludeAssets->getHandles()['css'];
            if (\in_array($handle, $handles, \true) && \strpos($tag, ExcludeAssets::EXCLUDE_STYLE_TAG_FROM_RUCSS_PROCESS_ATTRIBUTE) === \false) {
                return \str_replace('<link ', \sprintf('<link %s ', ExcludeAssets::EXCLUDE_STYLE_TAG_FROM_RUCSS_PROCESS_ATTRIBUTE), $tag);
            }
            return $tag;
        }, 10, 2);
    }
}
