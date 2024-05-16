<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\comp\TemplatesPluginIntegrations;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Set `recommended` through a hook / filter for both service and blocker templates.
 * @internal
 */
class RecommendedHooksMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        $recommended = $template->consumerData['isRecommended'] ?? \false;
        if (!$recommended) {
            $templatePluginIntegrations = TemplatesPluginIntegrations::getInstance();
            $legacyPresetObj = ['id' => $template->identifier];
            if ($template instanceof ServiceTemplate) {
                $recommended = $templatePluginIntegrations->templates_cookies_recommended($recommended, $template->identifier);
                /**
                 * Show a cookie service template in the scanner, or hide it depending on a given condition.
                 *
                 * @hook RCB/Presets/Cookies/Recommended
                 * @param {boolean} $recommended
                 * @param {array} $template The template passed as reference
                 * @returns {boolean}
                 * @since 2.11.1
                 * @deprecated Use `RCB/Templates/Recommended` instead!
                 */
                $recommended = \apply_filters('RCB/Presets/Cookies/Recommended', $recommended, $legacyPresetObj);
            } elseif ($template instanceof BlockerTemplate) {
                $recommended = $templatePluginIntegrations->templates_blocker_recommended($recommended, $template->identifier);
                /**
                 * Show a content blocker template in the scanner, or hide it depending on a given condition.
                 *
                 * @hook RCB/Presets/Blocker/Recommended
                 * @param {boolean} $recommended
                 * @param {array} $template The template passed as reference
                 * @returns {callable[]}
                 * @since 2.11.1
                 * @deprecated Use `RCB/Templates/Recommended` instead!
                 */
                $recommended = \apply_filters('RCB/Presets/Blocker/Recommended', $recommended, $legacyPresetObj);
            }
            /**
             * This filter is only intended for developers, which addresses the following scenario:
             *
             * - You are working on a plugin, which embeds e.g. Google Fonts through a custom script which cannot be blocked by our content blocker.
             * - You now want to build an integration to Real Cookie Banner, your users should be able to get consent for Google Fonts as comfortable as possible.
             *
             * **Note**: Google Fonts is only used as an example here to better illustrate the integration. This works with all integrations which need to change the
             * way how Real Cookie Banner recommends services in the Service Scanner.
             *
             * ### Filter usage
             *
             * If you are a plugin developer and you embed e.g. Google Fonts via a custom JavaScript, the service will probably not be
             * found via our scanner because we **do not parse** custom scripts of plugins and check if they dynamically embed content in any way.
             * Learn more about it here: [What does the service scanner find (and what not)?](https://devowl.io/knowledge-base/real-cookie-banner-service-scanner/).
             *
             * You could use the following code snippet to recommend Google Fonts to your user base:
             *
             * ```php
             * add_filter('RCB/Templates/Recommended', function($recommended, $template) {
             *   if (!$recommended &&
             *      // Recommend the service template instead of content blocker template
             *      property_exists($template, 'technicalDefinitions') &&
             *      $template->identifier === "google-fonts" &&
             *      // google_fonts_embed_active() is a example function which checks if Google Fonts embed is active in your plugin
             *      google_fonts_embed_active()) {
             *      return true;
             *   }
             *
             *   return $recommended;
             * }, 10, 2);
             * ```
             *
             * *Where do I find this `google-fonts` identifier?* This identifier is unique per template and should definitely be used for matching.
             * You will find the respective identifier when you create the service in Real Cookie Banner, in the form in the field "Unique Identifier".
             *
             * ### The filter is not fired, why?
             *
             * Since the calculation of the templates can be compute-intensive, this is cached of course. Basically, the template cache is emptied every day, and when you activate or deactivate a plugin.
             * If you provide an option in your plugin that disables Google Fonts inclusion, for example, this will not immediately clear the template cache and perform the calculation again.
             *
             * Should you technically save the option via WordPress' official [Options API](https://developer.wordpress.org/plugins/settings/options-api/), you can use the following snippet to
             * automatically clear the template cache with our [`wp_rcb_invalidate_templates_cache(true) `](../php/namespaces/default.html#function_wp_rcb_invalidate_templates_cache) API together with
             * the [`update_option_{$option}`](https://developer.wordpress.org/reference/hooks/update_option_option/) hook:
             *
             * ```php
             * <?php
             * // Replace my_plugin_is_google_fonts_active with your option name
             * add_action('update_option_my_plugin_is_google_fonts_active', function() {
             *   wp_rcb_invalidate_templates_cache();
             * });
             *
             * // Listen also to add_option as it is called for the first time when option is not
             * // available in update_option
             * add_action('add_option_my_plugin_is_google_fonts_active', function() {
             *   wp_rcb_invalidate_templates_cache();
             * });
             * ```
             *
             * ### But this does actually not block Google Fonts?
             *
             * Since you used a custom script to load Google Fonts, we cannot block this with our blocker mechanism until consent is given.
             * But don't worry, we also provide corresponding APIs to "wait" for a consent in your JavaScript code and load Google Fonts accordingly afterward:
             *
             * - [`window.consentApi.consent()`](../js/functions/frontend_packages_cookie_consent_web_client.consent.html)
             * - [`window.consentApi.consentSync()`](../js/functions/frontend_packages_cookie_consent_web_client.consentSync.html)
             *
             * Both `consent()` and `consentSync()` allows to wait for a consent for a given service. It allows multiple mechanism to identify the service (e.g.
             * technical definition, HTTP cookie names, ...) but we **highly recommend** to use the unique identifier:
             *
             * ```js
             * (window.consentApi?.consent("google-fonts") || Promise.resolve()).then(() => {
             *     console.log("Consent for Google Fonts given, load them...!");
             * });
             * ```
             *
             * @hook RCB/Templates/Recommended
             * @param {boolean} $recommended
             * @param {ServiceTemplate|BlockerTemplate} $template See [templates](../php/namespaces/devowl-servicecloudconsumer-templates.html)
             * @returns {boolean}
             * @since 3.16.0
             */
            $recommended = \apply_filters('RCB/Templates/Recommended', $recommended, $template);
            // Run plugin integrations
            if ($template instanceof ServiceTemplate) {
                $integration = \DevOwl\RealCookieBanner\templates\ServiceTemplateTechnicalHandlingIntegration::applyFilters($template->identifier);
                if ($integration->isIntegrated()) {
                    $template->consumerData['technicalHandlingIntegration'] = ['name' => $integration->getPluginName(), 'codeOptIn' => $integration->getCodeOptIn(), 'codeOptOut' => $integration->getCodeOptOut()];
                    $recommended = \true;
                }
            }
            $template->consumerData['isRecommended'] = $recommended;
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
}
