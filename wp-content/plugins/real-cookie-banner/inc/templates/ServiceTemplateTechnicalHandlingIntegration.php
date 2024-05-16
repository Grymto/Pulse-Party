<?php

namespace DevOwl\RealCookieBanner\templates;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * This class is only needed for the `RCB/Templates/TechnicalHandlingIntegration` filter.
 * @internal
 */
class ServiceTemplateTechnicalHandlingIntegration
{
    /**
     * Code opt-in.
     *
     * @var string|null
     */
    private $codeOptIn;
    /**
     * Code opt-out.
     *
     * @var string|null
     */
    private $codeOptOut;
    /**
     * The plugin file which creates an integration for this template.
     *
     * @var string
     */
    private $pluginFile;
    /**
     * Result of `get_plugin_data` for the integrated plugin.
     *
     * @var array
     */
    private $pluginData;
    private $templateIdentifier;
    /**
     * C'tor.
     *
     * @param string $templateIdentifier
     */
    public function __construct($templateIdentifier)
    {
        $this->templateIdentifier = $templateIdentifier;
        $this->codeOptIn = null;
        $this->codeOptOut = null;
    }
    /**
     * Checks, if the current template matches the unique identifier. It returns `true` when you as plugin
     * developer are able to override the opt-in / opt-out scripts, otherwise it returns `false` (e.g. another plugin
     * already created an integration for this template, the unique identifier does not match or the passed
     * `__FILE__` is not valid).
     *
     * @param string $pluginFile The `__FILE__` of your main WordPress plugin file (the absolute path to your `your-plugin/your-plugin.php` file), see also https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
     * @param string $identifier
     */
    public function integrate($pluginFile, $identifier)
    {
        if (empty($this->pluginFile) && $this->templateIdentifier === $identifier) {
            $this->pluginData = \get_plugin_data($pluginFile, \true, \false);
            if (!empty($this->pluginData['Name'])) {
                $this->pluginFile = $pluginFile;
                return \true;
            }
        }
        return \false;
    }
    /**
     * Run the `RCB/Templates/TechnicalHandlingIntegration` filter.
     *
     * @param string $templateIdentifier
     */
    public static function applyFilters($templateIdentifier)
    {
        $integration = new \DevOwl\RealCookieBanner\templates\ServiceTemplateTechnicalHandlingIntegration($templateIdentifier);
        /**
         * This filter is only intended for developers, which addresses the following scenario:
         *
         * - You are working on a plugin, which embeds e.g. Google Analytics.
         * - You now want to build an integration to Real Cookie Banner, your users should be able to get consent for Google Analytics as comfortable as possible.
         * - You as plugin developer need to ensure that Google Analytics is not embedded twice (through your plugin and Real Cookie Banner).
         *
         * **Note**: Google Analytics is only used as an example here to better illustrate the integration. This works with all integrations which need to change the way how Real Cookie Banner inject scripts (Opt-in, opt-out).
         *
         * For this to work technically clean and with a good UX, you need to consider the following things:
         *
         * 1. **Recommend service**: So that your user base doesn't have to figure out on their own which services need to be created, this filter is used to display the service in the Real Cookie Banner service scanner so that the service can be created with just one click.
         * 1. **Double injection**: You need to prevent Real Cookie Banner from "double" embedding Google Analytics, as this is already done by your plugin.
         * 1. **Consent API**: Ensure that no JavaScript code is executed that depends on the Google Analytics API `gtag`, for example. We may need to "wrap" these calls with our Consent API so that it waits for consent. See below "But how can I wait for consent in my script?".
         *
         * ### Filter usage
         *
         * *This section covers the integration for point **Recommend service** and **Double injection**.*
         *
         * In our example, your plugin already embeds Google Analytics. Now to make sure that Real Cookie Banner does not embed this again, we can easily use this filter. The filter also ensures that the template is displayed in the Service Scanner:
         *
         * ```php
         * <?php
         * add_action("RCB/Templates/TechnicalHandlingIntegration", function($integration) {
         *     if (
         *          // google_analytics_embed_active() is a example function which checks if Google Analytics embed is active in your plugin
         *          google_analytics_embed_active() &&
         *          // Replace __FILE__ with your main plugin file (the absolute path to your `your-plugin/your-plugin.php` file)
         *          // See also https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
         *          $integration->integrate(__FILE__, 'google-analytics-analytics-4'))
         *     {
         *          // Disable opt-in and opt-out script
         *          $integration->setCodeOptIn("");
         *          $integration->setCodeOptOut("");
         *     }
         * });
         * ```
         *
         * *Where do I find this `google-analytics-analytics-4` identifier?* This identifier is unique per template and should definitely be used for matching.
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
         * // Replace my_plugin_is_google_analytics_active with your option name
         * add_action('update_option_my_plugin_is_google_analytics_active', function() {
         *   wp_rcb_invalidate_templates_cache();
         * });
         *
         * // Listen also to add_option as it is called for the first time when option is not
         * // available in update_option
         * add_action('add_option_my_plugin_is_google_analytics_active', function() {
         *   wp_rcb_invalidate_templates_cache();
         * });
         * ```
         *
         * ### But how can I wait for consent in my script?
         *
         * *This section covers the integration for point **Consent API**.*
         *
         * Generally, you only need to extend your script for parts that cannot be blocked by our content blocker. In our example of Google Analytics we could potentially have the following cases:
         *
         * - You include the Google Analytics SDK script via a `<script` tag directly in the HTML DOM (statically, not dynamic), for this **no further** action is needed, as this can be blocked via our content blocker.
         * - You include the Google Analytics SDK script via a custom script that uses jQuery, for example, to dynamically place the script in the `<head`. For this, it needs further integration with APIs below.
         * - You are using the external `gtag` API of the Google Analytics SDK
         *      - If this is used within an **isolated** inline script in the HTML DOM, it can be blocked by our content blocker and **no further** action is needed on your part.
         *      - If you use this within a bundled script, our content blocker will not be able to block this, and it needs further integration with APIs below.
         *
         * Don't worry, we also provide corresponding APIs to "wait" for a consent in your JavaScript code and load Google Analytics accordingly afterward:
         *
         * - [`window.consentApi.consent()`](../js/functions/frontend_packages_cookie_consent_web_client.consent.html)
         * - [`window.consentApi.consentSync()`](../js/functions/frontend_packages_cookie_consent_web_client.consentSync.html)
         *
         * Both `consent()` and `consentSync()` allows to wait for a consent for a given service. It allows multiple mechanism to identify the service (e.g.
         * technical definition, HTTP cookie names, ...) but we **highly recommend** to use the unique identifier:
         *
         * ```js
         * (window.consentApi?.consent("google-analytics-analytics-4") || Promise.resolve()).then(() => {
         *     console.log("Consent for Google Analytics given, load them...!");
         * });
         * ```
         *
         * **Attention**: Each template can be only integrated once! That means, if another plugin is already using this filter and the `intergrate` method, you can no longer modify the technical handling.
         *
         * @param {ServiceTemplateTechnicalHandlingIntegration} $integration See [ServiceTemplateTechnicalHandlingIntegration](../php/classes/DevOwl-RealCookieBanner-templates-ServiceTemplateTechnicalHandlingIntegration.html)
         * @hook RCB/Templates/TechnicalHandlingIntegration
         */
        \do_action('RCB/Templates/TechnicalHandlingIntegration', $integration);
        return $integration;
    }
    /**
     * Setter.
     *
     * @param string $codeOptIn
     * @codeCoverageIgnore
     */
    public function setCodeOptIn($codeOptIn)
    {
        $this->codeOptIn = $codeOptIn;
    }
    /**
     * Setter.
     *
     * @param string $codeOptOut
     * @codeCoverageIgnore
     */
    public function setCodeOptOut($codeOptOut)
    {
        $this->codeOptOut = $codeOptOut;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTemplateIdentifier()
    {
        return $this->templateIdentifier;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCodeOptIn()
    {
        return $this->codeOptIn;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCodeOptOut()
    {
        return $this->codeOptOut;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPluginName()
    {
        return $this->pluginData['Name'];
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isIntegrated()
    {
        return !empty($this->pluginFile);
    }
}
