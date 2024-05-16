<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use Exception;
use WPML_Config;
// Simply check for defined constants, we do not need to `die` here
if (\defined('ABSPATH')) {
    Utils::setupConstants();
    Localization::instanceThis()->hooks();
    // Add an universal string for REST API requests to pass the current requested language and correctly switch to it.
    \add_action('init', function () {
        $compLanguage = AbstractLanguagePlugin::determineImplementation();
        \add_filter('DevOwl/Utils/RestQuery', function ($restQuery) use($compLanguage) {
            // Add language to each REST query string (only non-defaults, because WPML automatically redirects for default `lang` parameter)
            if ($compLanguage->isActive()) {
                $currentLanguage = $compLanguage->getCurrentLanguage();
                $restQuery['_dataLocale'] = $currentLanguage;
                // PolyLang compatibility: It expects a `lang` parameter as it gets overriden by PolyLang itself after `rest_api_init`
                if ($compLanguage instanceof PolyLang) {
                    $restQuery['lang'] = $currentLanguage;
                }
            }
            return $restQuery;
        });
        \add_action('rest_api_init', function () use($compLanguage) {
            if (isset($_GET['_dataLocale'])) {
                $compLanguage->switch($_GET['_dataLocale']);
            }
        }, 0);
    });
    /**
     * Automatically clear the `wpml-config.xml` cache.
     *
     * @see https://github.com/wp-premium/woocommerce-multilingual/blob/134e1a789622341e8de7690b955b25ea8d8f7cfc/inc/class-wcml-install.php#L60-L61
     */
    \add_action('DevOwl/Utils/NewVersionInstallation', function () {
        if (\class_exists(WPML_Config::class)) {
            \add_action('init', function () {
                if (!\did_action('DevOwl/Multilingual/WPML_Config_load_config_run')) {
                    try {
                        WPML_Config::load_config_run();
                    } catch (Exception $e) {
                        // Silence is golden.
                    }
                    // Do this only once, action is only used for internal use
                    \do_action('DevOwl/Multilingual/WPML_Config_load_config_run');
                }
            }, 2);
        }
    });
}
