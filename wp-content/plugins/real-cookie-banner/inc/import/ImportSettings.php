<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\settings\GoogleConsentMode;
use DevOwl\RealCookieBanner\settings\Multisite;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\Utils;
use ReflectionClass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Trait to handle the importer for settings in the `Import` class.
 * @internal
 */
trait ImportSettings
{
    /**
     * Import settings from JSON.
     *
     * @param array $settings
     */
    protected function doImportSettings($settings)
    {
        $availableOptions = $this->optionsMap(\true);
        $availableOptionKeys = \array_keys($availableOptions);
        foreach ($settings as $key => $value) {
            if (\in_array($key, $availableOptionKeys, \true) && \is_scalar($value)) {
                $optionName = $availableOptions[$key];
                // Skip already persistent options with the same value (no strict comparision)
                // phpcs:disable Universal.Operators.StrictComparisons.LooseEqual
                if (\get_option($optionName) == $value) {
                    continue;
                }
                // phpcs:enable Universal.Operators.StrictComparisons.LooseEqual
                // Check for special cases and abort it
                if (!$this->handleSepcialSetting($optionName, $value, $key)) {
                    continue;
                }
                // Handle update
                if (!\update_option($optionName, $value)) {
                    $this->addMessageUpdateOptionFailure($optionName);
                }
            } else {
                $this->addMessageOptionOutdated($key);
            }
        }
    }
    /**
     * Read all available options.
     *
     * @param boolean $asOptionName If true, the returned map contains the option name instead of value
     */
    public function optionsMap($asOptionName = \false)
    {
        $clazzes = [General::class, Consent::class, Multisite::class, TCF::class, CountryBypass::class, GoogleConsentMode::class];
        $options = [];
        $excludeOptions = [TCF::SETTING_TCF_FIRST_ACCEPTED_TIME, TCF::SETTING_TCF_ACCEPTED_TIME, TCF::SETTING_TCF_GVL_DOWNLOAD_TIME, CountryBypass::SETTING_COUNTRY_BYPASS_DB_DOWNLOAD_TIME, GoogleConsentMode::SETTING_GCM_SHOW_RECOMMONDATIONS_WITHOUT_CONSENT];
        foreach ($clazzes as $clazzName) {
            $clazz = new ReflectionClass($clazzName);
            $constants = $clazz->getConstants();
            foreach ($constants as $key => $value) {
                if (Utils::startsWith($key, 'SETTING_') && !\in_array($value, $excludeOptions, \true)) {
                    if (!$asOptionName) {
                        $value = \get_option($value);
                    }
                    $options[$key] = $value;
                }
            }
        }
        return $options;
    }
    /**
     * Handle special cases for settings.
     *
     * @param string $optionName
     * @param mixed $value
     * @param string $key
     */
    protected function handleSepcialSetting($optionName, $value, $key)
    {
        $onlyPro = \false;
        switch ($optionName) {
            case General::SETTING_OPERATOR_CONTACT_FORM_ID:
            case General::SETTING_HIDE_PAGE_IDS:
                if ($value > 0 || !empty($value)) {
                    $label = \__('Hide on additional pages', RCB_TD);
                    switch ($optionName) {
                        case General::SETTING_OPERATOR_CONTACT_FORM_ID:
                            $label = \__('Contact form', RCB_TD);
                            break;
                        default:
                            break;
                    }
                    $this->addMessageOptionRelatesPageId($label, $key);
                    break;
                }
                return \true;
            case General::SETTING_SET_COOKIES_VIA_MANAGER:
                if (!$this->isPro() && $value !== 'none') {
                    $onlyPro = \true;
                    break;
                }
                return \true;
            case GoogleConsentMode::SETTING_GCM_ENABLED:
            case GoogleConsentMode::SETTING_GCM_SHOW_RECOMMONDATIONS_WITHOUT_CONSENT:
            case GoogleConsentMode::SETTING_GCM_ADDITIONAL_URL_PARAMETERS:
            case GoogleConsentMode::SETTING_GCM_REDACT_DATA_WITHOUT_CONSENT:
            case GoogleConsentMode::SETTING_GCM_LIST_PURPOSES:
            case Multisite::SETTING_CONSENT_FORWARDING:
                if (!$this->isPro() && $value === \true) {
                    $onlyPro = \true;
                    break;
                }
                return \true;
            case Multisite::SETTING_FORWARD_TO:
            case Multisite::SETTING_CROSS_DOMAINS:
                if (!empty($value)) {
                    if ($this->isPro()) {
                        $this->addMessageOptionMultisite($optionName === Multisite::SETTING_FORWARD_TO ? \__('Forward to', RCB_TD) : \__('External \'Forward To\' endpoints', RCB_TD), $key);
                    } else {
                        $onlyPro = \true;
                    }
                    break;
                }
                return \true;
            case TCF::SETTING_TCF:
                if ($value === \true) {
                    $this->addMessage(\sprintf(
                        // translators:
                        \__('Setting/Option <code>%1$s</code> (%2$s) cannot be imported because it needs explicit opt-in. Skipped.', RCB_TD),
                        $key,
                        \__('enabling TCF-compatibility', RCB_TD)
                    ), 'warning', 'settings', ['settingsTab' => 'tcf']);
                    break;
                }
                return \true;
            case CountryBypass::SETTING_COUNTRY_BYPASS_ACTIVE:
                if ($value === \true) {
                    $this->addMessage(\sprintf(
                        // translators:
                        \__('Setting/Option <code>%1$s</code> (%2$s) cannot be imported because it needs explicit opt-in. Skipped.', RCB_TD),
                        $key,
                        \__('enabling geo-restriction', RCB_TD)
                    ), 'warning', 'settings', ['settingsTab' => 'country-bypass']);
                    break;
                }
                return \true;
            default:
                return \true;
        }
        $this->probablyAddMessageSettingOnlyPro($onlyPro, $key);
        return \false;
    }
}
