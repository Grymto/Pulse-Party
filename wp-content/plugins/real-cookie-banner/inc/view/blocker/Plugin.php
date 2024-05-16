<?php

namespace DevOwl\RealCookieBanner\view\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\ScriptInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\StyleInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\StyleInlineAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\ScriptInlineMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\SelectorSyntaxMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\StyleInlineAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\StyleInlineMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\AdditionalAttributesBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\AttributeJsonBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\Autoplay;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\CustomElementBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\DoNotBlockScriptTextTemplates;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\Image;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview\ImagePreview;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\RemoveAlwaysCSSClasses;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\LinkBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\LinkRelBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\ReattachDom;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\ScriptInlineJsonBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\Confirm;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\DelegateClick;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\ForceVisual;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\JQueryHijackEach;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\KeepAttributes;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\MatchesUrl;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\Style;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\TransformAttribute;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction\VisualParent;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\tcf\TcfForwardGdprStringInUrl;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\lite\view\blocker\WordPressImagePreviewCache;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\CSSList\Document;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Real Cookie Banner plugin for `HeadlessContentBlocker`.
 * @internal
 */
class Plugin extends AbstractPlugin
{
    use UtilsProvider;
    const TABLE_NAME_BLOCKER_THUMBNAILS = 'blocker_thumbnails';
    private $wpContentDir;
    // Documented in AbstractPlugin
    public function init()
    {
        $this->wpContentDir = \basename(\constant('WP_CONTENT_DIR'));
        $cb = $this->getHeadlessContentBlocker();
        $cb->addTagAttributeMap([
            // [Plugin Comp] https://wordpress.org/plugins/presto-player/
            'presto-player',
        ], [
            // [Plugin Comp] JetElements for Elementor
            'data-lazy-load',
            // [Plugin Comp] Revolution Slider
            'data-lazyload',
        ]);
        $cb->addSelectorSyntaxMap([
            // [Plugin Comp] https://themenectar.com/salient/
            'a[href][class*="nectar_video_lightbox"]',
            // [Plugin Comp] Elementor media carousel
            'a[href][data-elementor-lightbox-video]',
            // [Plugin Comp] https://promo-theme.com/
            'a[data-popup-json]',
            // [Plugin Comp] Kadence Blocks
            'a[href][class*="kadence-video-popup-link":delegateClick()]',
        ]);
        /**
         * `<div>` elements are expensive in Regexp cause there a lot of them, let's assume only a
         * set of attributes to get a match. For example, WP Rockets' lazy loading technology modifies
         * iFrames and YouTube embeds.
         *
         * @see https://git.io/JLQSy
         */
        $cb->addTagAttributeMap(['div'], [
            // [Plugin Comp] WP Rocket
            'data-src',
            'data-lazy-src',
            // [Theme Comp] FloThemes
            'data-flo-video-embed-embed-code',
            // [Plugin Comp] JetElements for Elementor
            'style',
            // [Theme Comp] Themify
            'data-url',
            // [Theme Comp] https://themeforest.net/item/norebro-creative-multipurpose-wordpress-theme/20834703
            'data-video-module',
            // [Plugin Comp] OptimizePress page builder
            'data-op3-src',
            // [Plugin Comp] Multiview in Divi (e.g. Desktop / mobile / tablet)
            'data-et-multi-view',
            // [Plugin Comp] Avia Slider / Enfold
            'data-original_url',
            // [Plugin Comp] Avada
            'data-image',
        ], 'expensiveDiv');
        $cb->addTagAttributeMap(['iframe'], [
            // [Plugin Comp] WP Rocket
            'data-src',
            'data-lazy-src',
            // [Plugin Comp] Avada Fusion video shortcode
            'data-orig-src',
        ], 'iframeLazyLoad');
        $cb->addKeepAlwaysAttributes([
            'rel',
            // [Theme Comp] FloThemes
            'data-flo-video-embed-embed-code',
        ]);
        $cb->addKeepAlwaysAttributesIfClass([
            // [Plugin Comp] Ultimate Video (WP Bakery Page Builder)
            'ultv-video__play' => ['data-src'],
            // [Plugin Comp] Elementor Video Widget
            'elementor-widget-video' => ['data-settings'],
            // If you include two Podigee players, the 1st script podigee-podcast-player.js
            // will be executed directly when unblocking, and will also process the 2nd script
            // right away. At this point of the 1st unblocking, data-configuration of the 2nd script
            // is not yet unblocked consent-original-data-configuration. Podigee throws a
            // corresponding error here.
            // 2x `<p>
            //    <script class="podigee-podcast-player"
            //          src="https://player.podigee-cdn.net/podcast-player/javascripts/podigee-podcast-player.js"
            //          data-configuration="https://bundesrechtsanwaltskammer.podigee.io/31-folge_30/embed?context=external"
            //    ></script>
            // </p>`
            'podigee-podcast-player' => ['data-configuration'],
            // [Plugin Comp] https://themeforest.net/item/attornix-lawyer-wordpress-theme/24032543 (controlled by jQuery hijack to gMap plugin)
            'cmsmasters_google_map' => ['class'],
            // [Plugin Comp] Impreza (WP Bakery Page Builder)
            'w-video' => ['class'],
            'w-map' => ['class'],
            // [Plugin Comp] OnePress (controlled by jQuery hijack of `jQuery.each`)
            'onepress-map' => ['class'],
            // [Plugin Comp] https://themenectar.com/salient/ (controlled by jQuery hijack of `jQuery.fn.magnificPopup`)
            'nectar_video_lightbox' => ['class'],
            // [Plugin Comp] https://themeforest.net/item/sober-woocommerce-wordpress-theme/18332889 (controlled by jQuery hijack of `jQuery.each`)
            'sober-map' => ['class'],
            // [Plugin Comp] https://wordpress.org/plugins/bold-page-builder/
            'bt_bb_google_maps_map' => ['class'],
            // [Plugin Comp] Kadence Blocks
            'kadence-video-popup-link' => ['class', 'href'],
        ]);
        $cb->addVisualParentIfClass([
            // [Theme Comp] FloThemes
            'flo-video-embed__screen' => 2,
            // [Plugin Comp] Ultimate Video (WP Bakery Page Builder)
            'ultv-video__play' => 2,
            // [Plugin Comp] Elementor
            'elementor-widget' => 'children:.elementor-widget-container',
            // [Plugin Comp] Thrive Architect
            'thrv_responsive_video' => 'children:iframe',
            // [Plugin Comp] Ultimate Addons for Elementor
            'uael-video__play' => '.elementor-widget-container',
            // [Plugin Comp] WP Grid Builder
            'wpgb-map-facet' => '.wpgb-facet',
            // [Plugin Comp] tagDiv Composer
            'td_wrapper_playlist_player_youtube' => 1,
            // [Plugin Comp] https://wordpress.org/plugins/play-ht/
            'playht-iframe-player' => 1,
            // [Plugin Comp] https://themenectar.com/salient/
            'nectar_video_lightbox' => 2,
            // [Plugin Comp] https://wordpress.org/plugins/bold-page-builder/
            'bt_bb_google_maps_map' => 1,
        ]);
        $cb->addSkipInlineScriptVariableAssignments([
            '_wpCustomizeSettings',
            // [Plugin Comp] Divi
            'et_animation_data',
            'et_link_options_data',
            // [Plugin Comp] https://wordpress.org/plugins/groovy-menu-free/
            'groovyMenuSettings',
            // [Plugin Comp] https://wordpress.org/plugins/meow-lightbox/
            'mwl_data',
            // [Plugin Comp] https://wpadvancedads.com/
            'advads_tracking_ads',
            // [Plugin Comp] https://wordpress.org/plugins/podcast-player/
            'podcastPlayerData',
            // [Plugin Comp] FacetWP
            'FWP_JSON',
            // [Plugin Comp] RankMath
            'rankMath',
            // [Plugin Comp] https://wordpress.org/plugins/wp-google-maps/
            'WPGMZA_localized_data',
            // [Plugin Comp] Elementor (https://regex101.com/r/zeph0t/1)
            '/var elementor\\w+Config\\s*=/m',
            // [Plugin Comp] https://givewp.com/addons/stripe-gateway/
            'give_stripe_vars',
            // [Plugin Comp] https://woocommerce.com/products/point-of-sale-for-woocommerce/
            '/window\\.wc_pos_params\\s*=/m',
            // [Plugin Comp] https://wordpress.org/plugins/woocommerce-google-adwords-conversion-tracking-tag/
            '/window\\.wpmDataLayer\\s*=/m',
            // [Plugin Comp] https://woocommerce.com/de-de/products/point-of-sale-for-woocommerce/
            '/window\\.AppData\\s*=/m',
            // [Plugin Comp] https://woocommerce.com/de-de/products/woocommerce-gutenberg-products-block/
            'wcSettings',
            // [Plugin Comp] https://wordpress.org/plugins/ghostkit/
            'ghostkitVariables',
            // [Plugin Comp] FloTheme (https://regex101.com/r/YeTlqt/1)
            '/var flo_/m',
            // [Plugin Comp] SeoPress
            'SEOPRESS_DATA',
            // [Plugin Comp] https://codecanyon.net/item/superfly-responsive-wordpress-menu-plugin/8012790
            '/var SFM_template/m',
            // [Plugin Comp] Surecart
            '/window\\.surecartComponents\\s*=/m',
            '/window\\.SureCartAffiliatesConfig\\s*=/m',
            // [Plugin Comp] wl-api-connector
            'valuationConfig',
            // [Plugin Comp] Slider Revolution
            '/window\\.SR7\\s*\\?\\?=/m',
        ]);
        $cb->setInlineStyleDummyUrlPath(\plugins_url('public/images/', RCB_FILE));
        /**
         * DoNotBlockScriptTextTemplates.
         *
         * @var DoNotBlockScriptTextTemplates
         */
        $doNotBlockScriptTextTemplates = $cb->addPlugin(DoNotBlockScriptTextTemplates::class);
        // [Plugin Comp] https://www.elegantthemes.com/gallery/extra/
        $doNotBlockScriptTextTemplates->addExclusionIfMatches('script[class*="widget-video-item-"]');
        // Other plugins
        $cb->addPlugin(Autoplay::class);
        $cb->addPlugin(\DevOwl\RealCookieBanner\view\blocker\PluginAutoplay::class);
        $cb->addPlugin(\DevOwl\RealCookieBanner\view\blocker\ElementorProActionsPlugin::class);
        $cb->addPlugin(MatchesUrl::class);
        $cb->addPlugin(ForceVisual::class);
        $cb->addPlugin(JQueryHijackEach::class);
        $cb->addPlugin(VisualParent::class);
        $cb->addPlugin(KeepAttributes::class);
        $cb->addPlugin(Style::class);
        $cb->addPlugin(DelegateClick::class);
        $cb->addPlugin(TransformAttribute::class);
        $cb->addPlugin(Confirm::class);
        /**
         * Plugin.
         *
         * @var AttributeJsonBlocker
         */
        $attributeJsonBlocker = $cb->addPlugin(AttributeJsonBlocker::class);
        $attributeJsonBlocker->addAttributes([
            // [Plugin Comp] Multiview in Divi (e.g. Desktop / mobile / tablet)
            'data-et-multi-view',
            // [Plugin Comp] https://promo-theme.com/
            'data-popup-json',
        ]);
        /**
         * Plugin.
         *
         * @var RemoveAlwaysCSSClasses
         */
        $removeAlwaysCssClasses = $cb->addPlugin(RemoveAlwaysCSSClasses::class);
        $removeAlwaysCssClasses->addClassNames(RemoveAlwaysCSSClasses::KNOWN_LAZY_LOADED_CLASSES);
        $removeAlwaysCssClasses->addClassNames([
            // [Plugin Comp] https://wpbeaveraddons.com/demo/video/
            'pp-video-iframe',
            // [Plugin Comp] Bricks Builder
            'bricks-lazy-hidden',
        ]);
        $cb->addPlugin(CustomElementBlocker::class);
        $cb->addPlugin(ReattachDom::class);
        AdditionalAttributesBlocker::defaults($cb);
        /**
         * Plugin.
         *
         * @var LinkRelBlocker
         */
        $linkRelBlockerPlugin = $cb->addPlugin(LinkRelBlocker::class);
        /**
         * Plugin.
         *
         * @var ScriptInlineJsonBlocker
         */
        $scriptInlineJsonBlocker = $cb->addPlugin(ScriptInlineJsonBlocker::class);
        $scriptInlineJsonBlocker->addSchema('wp.i18n.setLocaleData', '/(wp\\.i18n\\.setLocaleData\\(\\s*localeData,\\s*domain\\s*\\);\\s*}\\s*\\)\\s*\\(\\s*"[^"]+",\\s*)(.*)(\\)\\s*;\\s*<\\/script>)/m', '</script>');
        $scriptInlineJsonBlocker->addSchema('jetMenuMobileWidgetRenderData', '/(window\\.jetMenuMobileWidgetRenderData[^=]+=)(.*)(;)$/m');
        // [Plugin Comp] https://wordpress.org/plugins/ays-chatgpt-assistant/
        $scriptInlineJsonBlocker->addSchema('AysChatGPTChatSettings', '/(var\\s*AysChatGPTChatSettings[^=]+=)(.*)(;)$/ms');
        /**
         * Legal opinion: With `dns-prefetch`, only the DNS server specified by the website visitor
         * is requested and not, for example, Google Fonts. Consequently, data is only passed
         * on to servers that can be expected by the visitor and it is in his interest for the
         * website to load as quickly as possible. Consequently, we assume that there is a
         * legitimate interest of the website visitor (not website operator, as he has no advantage)
         * according to Art. 6 para. 1. lit. f DSGVO.
         */
        $linkRelBlockerPlugin->setDoNotTouch(['dns-prefetch']);
        $cb->addPlugin(Image::class);
        // This class only exists in PRO Version
        if ($this->isPro()) {
            $imagePreviewCache = WordPressImagePreviewCache::create();
            if ($imagePreviewCache !== \false) {
                /**
                 * Plugin.
                 *
                 * @var ImagePreview
                 */
                $imagePreviewPlugin = $cb->addPlugin(ImagePreview::class);
                $imagePreviewPlugin->setCache($imagePreviewCache);
            }
        }
        /**
         * Plugin.
         *
         * @var LinkBlocker
         */
        $linkBlockerPlugin = $cb->addPlugin(LinkBlocker::class);
        $linkBlockerPlugin->addBlockIfClass([
            // [Plugin Comp] https://wordpress.org/plugins/foobox-image-lightbox/
            'foobox',
        ]);
        if ($this->isPro() && TCF::getInstance()->isActive()) {
            /**
             * Plugin.
             *
             * @var TcfForwardGdprStringInUrl
             */
            $tcfForwardGdprStringInUrl = $cb->addPlugin(TcfForwardGdprStringInUrl::class);
            $vendorConfigurations = TCF::getInstance()->getVendorConfigurations();
            foreach ($vendorConfigurations as $vendorConfiguration) {
                $vendor = $vendorConfiguration->getVendor();
                if (isset($vendor['deviceStorageDisclosure']) && isset($vendor['deviceStorageDisclosure']['domains'])) {
                    $tcfForwardGdprStringInUrl->addVendorDisclosureDomains($vendorConfiguration->getId(), $vendor['deviceStorageDisclosure']['domains']);
                }
            }
        }
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function beforeMatch($matcher, $match)
    {
        /**
         * Allow to block multiple matches in content blocker.
         *
         * @hook RCB/Blocker/IsBlocked/AllowMultiple
         * @param {boolean} $allowMultiple
         * @return {boolean}
         * @since 2.6.0
         * @deprecated Do no longer use this!
         * @ignore
         */
        $allowMultiple = \apply_filters('RCB/Blocker/IsBlocked/AllowMultiple', \false);
        $this->getHeadlessContentBlocker()->setAllowMultipleBlockerResults($allowMultiple);
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            /**
             * A tag got blocked, e. g. `iframe`. We can now modify the attributes again to add an additional attribute to
             * the blocked content.
             *
             * @hook RCB/Blocker/HTMLAttributes
             * @param {array} $attributes
             * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {string} $newLinkAttribute
             * @param {string} $linkAttribute
             * @param {string} $link
             * @return {array}
             */
            $attributes = \apply_filters('RCB/Blocker/HTMLAttributes', $match->getAttributes(), $result, $result->getData('newLinkAttribute'), $match->getLinkAttribute(), $match->getLink());
            $match->setAttributes($attributes);
        } elseif ($match instanceof ScriptInlineMatcher) {
            /**
             * Var.
             *
             * @var ScriptInlineMatch
             */
            $match = $match;
            /**
             * An inline script got blocked, e. g. `iframe`. We can now modify the attributes again to add an additional attribute to
             * the blocked script. Do not forget to hook into the frontend and transform the modified attributes!
             *
             * @hook RCB/Blocker/InlineScript/HTMLAttributes
             * @param {array} $attributes
             * @param {array} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {string} $script
             * @return {array}
             */
            $attributes = \apply_filters('RCB/Blocker/InlineScript/HTMLAttributes', $match->getAttributes(), $result, $match->getAttribute(Constants::HTML_ATTRIBUTE_INLINE));
            $match->setAttributes($attributes);
        }
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        // Never try to block or scan other "blocker" scripts
        if (\in_array($match->getAttribute('id'), [
            // [Plugin Comp] OMGF
            'omgf-pro-remove-async-google-fonts',
        ], \true)) {
            $result->disableBlocking();
        }
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            /**
             * Check if a given tag, link attribute and link is blocked.
             *
             * @hook RCB/Blocker/IsBlocked
             * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {string} $linkAttribute
             * @param {string} $link
             * @return {BlockedResult}
             */
            $result = \apply_filters('RCB/Blocker/IsBlocked', $result, $match->getLinkAttribute(), $match->getLink());
        } elseif ($matcher instanceof ScriptInlineMatcher) {
            /**
             * Var.
             *
             * @var ScriptInlineMatch
             */
            $match = $match;
            /**
             * Check if a given inline script is blocked.
             *
             * @hook RCB/Blocker/InlineScript/IsBlocked
             * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {string} $script
             * @return {BlockedResult}
             */
            $result = \apply_filters('RCB/Blocker/InlineScript/IsBlocked', $result, $match->getScript());
        } elseif ($matcher instanceof StyleInlineMatcher) {
            /**
             * Var.
             *
             * @var StyleInlineMatch
             */
            $match = $match;
            /**
             * Check if a given inline style is blocked.
             *
             * @hook RCB/Blocker/InlineStyle/IsBlocked
             * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {string} $style
             * @return {BlockedResult}
             */
            $result = \apply_filters('RCB/Blocker/InlineStyle/IsBlocked', $result, $match->getStyle());
        } elseif ($matcher instanceof SelectorSyntaxMatcher) {
            /**
             * Check if a element blocked by custom element blocking (Selector Syntax) is blocked.
             *
             * @hook RCB/Blocker/SelectorSyntax/IsBlocked
             * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
             * @param {SelectorSyntaxMatch} $match
             * @return {BlockedResult}
             * @since 2.6.0
             */
            $result = \apply_filters('RCB/Blocker/SelectorSyntax/IsBlocked', $result, $match);
        }
        return $result;
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param string[] $keepAttributes
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     * @return string[]
     */
    public function keepAlwaysAttributes($keepAttributes, $matcher, $match)
    {
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            /**
             * In some cases we need to keep the attributes as original instead of prefix it with `consent-original-`.
             * Keep in mind, that no external data should be loaded if the attribute is set!
             *
             * @hook RCB/Blocker/KeepAttributes
             * @param {string[]} $keepAttributes
             * @param {string} $tag
             * @param {array} $attributes
             * @param {string} $linkAttribute
             * @param {string} $link
             * @return {string[]}
             * @since 1.5.0
             */
            $keepAttributes = \apply_filters('RCB/Blocker/KeepAttributes', $keepAttributes, $match->getTag(), $match->getAttributes(), $match->getLinkAttribute(), $match->getLink());
        }
        return $keepAttributes;
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param string[] $names
     * @param ScriptInlineMatcher $matcher
     * @param ScriptInlineMatch $match
     * @return string[]
     */
    public function skipInlineScriptVariableAssignment($names, $matcher, $match)
    {
        if (Core::getInstance()->getScanner()->isActive()) {
            // While scanning, we want to report all external URLs also in localized variables
            // as we want to catch them and reported via support. Afterwards, add via `addSkipInlineScriptVariableAssignments`.
            $names[] = 'DO_NOT_COMPUTE';
        }
        /**
         * Check if a given inline script is blocked by a localized variable name (e.g. `wp_localize_script`).
         *
         * @hook RCB/Blocker/InlineScript/AvoidBlockByLocalizedVariable
         * @param {string[]} $variables
         * @param {string} $script
         * @return {string[]}
         * @since 1.14.1
         */
        return \apply_filters('RCB/Blocker/InlineScript/AvoidBlockByLocalizedVariable', $names, $match->getScript());
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param boolean $extract
     * @param StyleInlineMatcher|StyleInlineAttributeMatcher $matcher
     * @param StyleInlineMatch|StyleInlineAttributeMatch $match
     * @return boolean
     */
    public function inlineStyleShouldBeExtracted($extract, $matcher, $match)
    {
        /**
         * Determine, if the current inline style should be split into two inline styles. One inline style
         * with only CSS rules without blocked URLs and the second one with only CSS rules with blocked URLs.
         *
         * @hook RCB/Blocker/InlineStyle/Extract
         * @param {boolean} $extract
         * @param {string} $style
         * @param {array} $attributes
         * @return {boolean}
         * @since 1.13.2
         */
        return \apply_filters('RCB/Blocker/InlineStyle/Extract', \true, $match->getStyle(), $match->getAttributes());
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param Document $document
     * @param Document $extractedDocument
     * @param StyleInlineMatcher|StyleInlineAttributeMatcher $matcher
     * @param StyleInlineMatch|StyleInlineAttributeMatch $match
     * @return boolean
     */
    public function inlineStyleModifyDocuments($document, $extractedDocument, $matcher, $match)
    {
        /**
         * An inline style got blocked. We can now modify the rules again with the help of `\Sabberworm\CSS\CSSList\Document`.
         *
         * @hook RCB/Blocker/InlineStyle/Document
         * @param {Document} $document `\Sabberworm\CSS\CSSList\Document`
         * @param {Document} $extractedDocument `\Sabberworm\CSS\CSSList\Document`
         * @param {array} $attributes
         * @param {AbstractBlockable[]} $blockables
         * @param {string} $style
         * @see https://github.com/sabberworm/PHP-CSS-Parser
         * @since 1.13.2
         */
        \do_action('RCB/Blocker/InlineStyle/Document', $document, $extractedDocument, $match->getAttributes(), $this->getHeadlessContentBlocker()->getBlockables(), $match->getStyle());
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param string $url
     * @param StyleInlineMatcher|StyleInlineAttributeMatcher $matcher
     * @param StyleInlineMatch|StyleInlineAttributeMatch $match
     * @return boolean
     */
    public function inlineStyleBlockRule($result, $url, $matcher, $match)
    {
        /**
         * Check if a given inline CSS rule is blocked.
         *
         * @hook RCB/Blocker/InlineStyle/Rule/IsBlocked
         * @param {BlockedResult} $isBlocked Since 3.0.0 this is an instance of [BlockedResult](../php/classes/DevOwl-HeadlessContentBlocker-BlockedResult.html)
         * @param {string} $url
         * @return {BlockedResult}
         * @since 1.13.2
         */
        return \apply_filters('RCB/Blocker/InlineStyle/Rule/IsBlocked', $result, $url);
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param boolean|string|number $visualParent
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     * @return boolean|string|number
     */
    public function visualParent($visualParent, $matcher, $match)
    {
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            // [Plugin Comp] https://wordpress.org/plugins/wp-youtube-lyte/
            if ($match->hasAttribute('id') && Utils::startsWith($match->getAttribute('id'), 'lyte_')) {
                $visualParent = 2;
            }
            /**
             * A tag got blocked, e. g. `iframe`. We can now determine the "Visual Parent". The visual parent is the
             * closest parent where the content blocker should be placed to. The visual parent can be configured as follow:
             *
             *- `false` = Use original element
             * - `true` = Use parent element
             * - `number` = Go upwards x element (parentElement)
             * - `string` = Go upwards until parentElement matches a selector
             * - `string` = Starting with `children:` you can `querySelector` down to create the visual parent for a children (since 2.0.4)
             *
             * @hook RCB/Blocker/VisualParent
             * @param {boolean|string|number} $useVisualParent
             * @param {string} $tag
             * @param {array} $attributes
             * @return {boolean|string|number}
             * @since 1.5.0
             */
            $visualParent = \apply_filters('RCB/Blocker/VisualParent', $visualParent, $match->getTag(), $match->getAttributes());
        }
        return $visualParent;
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param string $html
     */
    public function modifyHtmlAfterProcessing($html)
    {
        /**
         * Modify HTML content for content blockers. This is called directly after the core
         * content blocker has done its job for common HTML tags (iframe, scripts, ... ) and
         * inline scripts.
         *
         * @hook RCB/Blocker/HTML
         * @param {string} $html
         * @return {string}
         */
        return \apply_filters('RCB/Blocker/HTML', $html);
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param string $expression
     * @param AbstractBlockable $blockable
     */
    public function blockableStringExpression($expression, $blockable)
    {
        // Modify `wp-content/{themes,plugins}` to the configured folder
        $expression = \str_replace(['wp-content/themes', 'wp-content/plugins'], [$this->wpContentDir . '/themes', $this->wpContentDir . '/plugins'], $expression);
        return $expression;
    }
}
