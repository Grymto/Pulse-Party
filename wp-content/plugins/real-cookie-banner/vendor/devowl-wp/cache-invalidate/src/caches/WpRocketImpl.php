<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * WP Rocket.
 *
 * @see https://wp-rocket.me/
 * @see https://docs.wp-rocket.me/article/92-rocketcleandomain
 * @codeCoverageIgnore
 * @internal
 */
class WpRocketImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-rocket';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('rocket_clean_domain');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \rocket_clean_domain();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        // Exclude `<style skip-rucss>` tags from being added to the RUCSS process
        $this->addExcludeStyleTagFromRucssProcessAttributeTagFilter($excludeAssets);
        \add_filter('rocket_rucss_skip_styles_with_attr', function ($skipped_attr) {
            $skipped_attr[] = ExcludeAssets::EXCLUDE_STYLE_TAG_FROM_RUCSS_PROCESS_ATTRIBUTE;
            return $skipped_attr;
        });
        foreach (['rocket_exclude_%s', 'rocket_exclude_defer_%s', 'rocket_delay_%s_exclusions'] as $filter) {
            foreach (['js', 'css'] as $type) {
                \add_filter(\sprintf($filter, $type), function ($excluded) use($excludeAssets, $type) {
                    $path = $excludeAssets->getAllUrlPath($type);
                    $handles = $excludeAssets->getHandles()[$type];
                    return \array_merge($excluded, $path, $handles);
                });
            }
        }
        /**
         * Safelisting a CSS file in WP Rocket's UI: This does not prevent the file from being removed from the HTML. Instead,
         * it allows the "Remove Unused CSS" feature to add the entire content of the CSS file to the "Used CSS" styles.
         *
         * Preventing CSS Removal: The only method to ensure a CSS file is not removed from the HTML is by using a specific
         * helper plugin called "WP Rocket | Exclude CSS files from Remove Unused CSS". This requires modifying the helper
         * plugin by specifying the CSS file you wish to exclude.
         *
         * Using rocket_rucss_skip_styles_with_attr Filter: This filter instructs WP Rocket to skip adding the content of
         * specified stylesheets to the "Used CSS", but it does not prevent the stylesheet from being removed from the HTML.
         *
         * Effect of Adding skip-rucss to Stylesheets: Adding this attribute does not have the intended effect because it
         * does not prevent the removal of the stylesheet from the HTML.
         *
         * Using rocket_rucss_external_exclusions Filter: To prevent a stylesheet from being removed from the HTML, this filter
         * should be used alongside rocket_rucss_skip_styles_with_attr. This combination ensures the stylesheet remains in the HTML.
         */
        \add_filter('rocket_rucss_external_exclusions', function ($excluded) use($excludeAssets) {
            $path = $excludeAssets->getAllUrlPath('css');
            return \array_merge($excluded, $path);
        });
        // @deprecated Does no longer work as this filter is applied before the `wp_enqueue_script` filter
        // and therefore the handles of the excluded assets are no longer know. We need to use the `skip-rucss`
        // attribute for this now.
        \add_filter('rocket_rucss_safelist', function ($safeList) use($excludeAssets) {
            return \array_values(\array_merge($safeList, $excludeAssets->getAllUrlPath('css')));
        });
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Rocket';
    }
}
