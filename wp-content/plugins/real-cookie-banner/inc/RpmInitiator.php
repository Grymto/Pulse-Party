<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\settings\Multisite;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\view\customize\banner\FooterDesign;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealProductManagerWpClient\AbstractInitiator;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealProductManagerWpClient\license\TelemetryData;
use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Initiate real-product-manager-wp-client functionality.
 * @internal
 */
class RpmInitiator extends AbstractInitiator
{
    use UtilsProvider;
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginBase()
    {
        return $this;
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getProductAndVariant()
    {
        return [1, $this->isPro() ? 1 : 2];
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginAssets()
    {
        return $this->getCore()->getAssets();
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPrivacyPolicy()
    {
        return 'https://devowl.io/privacy-policy';
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function isExternalUpdateEnabled()
    {
        return $this->isPro();
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function isAdminNoticeLicenseVisible()
    {
        return \DevOwl\RealCookieBanner\Core::getInstance()->getConfigPage()->isVisible();
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function isLocalAnnouncementVisible()
    {
        return $this->isAdminNoticeLicenseVisible();
    }
    /**
     * Allows you to build telemetry data.
     *
     * @param TelemetryData $telemetry
     */
    public function buildTelemetryData($telemetry)
    {
        $generalSettings = General::getInstance();
        $consentSettings = Consent::getInstance();
        $countryBypassSettings = CountryBypass::getInstance();
        $tcfSettings = TCF::getInstance();
        $multisiteSettings = Multisite::getInstance();
        $stats = \DevOwl\RealCookieBanner\Stats::getInstance();
        $revision = Revision::getInstance();
        $revisionCurrent = $revision->getCurrent();
        $revisionIndependent = $revision->getRevision()->createIndependent();
        $decision = $revisionIndependent['revision']['banner']['customizeValuesBanner']['decision'];
        $telemetry->add('rcb_serviceGroup_count', \count($revisionCurrent['revision']['groups']))->add('rcb_service_count', $revisionCurrent['all_cookie_count'])->add('rcb_contentBlocker_count', $revisionCurrent['all_blocker_count'])->add('rcb_services', \DevOwl\RealCookieBanner\Utils::array_flatten(\array_map(function ($group) {
            $items = [];
            foreach ($group['items'] as $item) {
                $newItem = ['name' => $item['name']];
                if (!empty($item['presetId'])) {
                    $newItem['identifier'] = $item['presetId'];
                }
                $items[] = $newItem;
            }
            return $items;
        }, $revisionCurrent['revision']['groups'])))->add('rcb_contentBlockers', \array_map(function ($blocker) {
            $item = ['name' => $blocker['name']];
            if (!empty($blocker['presetId'])) {
                $item['identifier'] = $blocker['presetId'];
            }
            return $item;
        }, $revisionIndependent['revision']['blocker']))->add('rcb_scanner_externalUrls', \array_values(\array_map(function ($item) {
            return ['host' => $item['host'], 'tags' => $item['tags']];
        }, \DevOwl\RealCookieBanner\Core::getInstance()->getScanner()->getQuery()->getScannedExternalUrls())));
        // Stats
        $telemetry->add('rcb_consent_count', \DevOwl\RealCookieBanner\UserConsent::getInstance()->byCriteria([], \DevOwl\RealCookieBanner\UserConsent::BY_CRITERIA_RESULT_TYPE_COUNT));
        if ($this->isPro()) {
            $statsTimeFrom = \gmdate('Y-m-d', \strtotime('-30 days'));
            $statsTimeTo = \gmdate('Y-m-d');
            $clickedButton = $stats->fetchButtonsClickedStats($statsTimeFrom, $statsTimeTo);
            $customBypass = $stats->fetchCustomBypassStats($statsTimeFrom, $statsTimeTo);
            $clickedButtonResult = [];
            $customBypassResult = [];
            foreach ($clickedButton as $key => $value) {
                $clickedButtonResult[] = ['type' => $key, 'label' => $value[0], 'count' => $value[1]];
            }
            foreach ($customBypass as $key => $value) {
                $customBypassResult[] = ['type' => $key, 'count' => $value];
            }
            $telemetry->add('rcb_statistic_consentByClickedButton', $clickedButtonResult);
            $telemetry->add('rcb_statistic_consentByBypass', $customBypassResult);
            // Pro Settings
            $telemetry->add('rcb_settings_dataProcessingInUnsafeCountries', $consentSettings->isDataProcessingInUnsafeCountries());
        }
        $telemetry->add('rcb_customizer_consentOptions_acceptAll', $decision['acceptAll'])->add('rcb_customizer_consentOptions_acceptEssentials', $decision['acceptEssentials'])->add('rcb_customizer_consentOptions_acceptIndividual', $decision['acceptIndividual'])->add('rcb_settings_setCookiesViaManager', $generalSettings->getSetCookiesViaManager())->add('rcb_settings_acceptAllForBots', $consentSettings->isAcceptAllForBots())->add('rcb_settings_respectDoNotTrack', $consentSettings->isRespectDoNotTrack())->add('rcb_settings_ageNotice', $consentSettings->isAgeNoticeEnabled())->add('rcb_settings_listServicesNotice', $consentSettings->isListServicesNoticeEnabled())->add('rcb_settings_countryBypass', $countryBypassSettings->isActive())->add('rcb_settings_tcf', $tcfSettings->isActive())->add('rcb_settings_consentForwarding', $multisiteSettings->isConsentForwarding())->add('rcb_wpPlugins_active', $telemetry->getActivePlugins())->add('rcb_wpThemes_active', $telemetry->getActiveTheme());
    }
    // Documented in AbstractInitiator
    public function formAdditionalCheckboxes()
    {
        if ($this->isPro() || \boolval(\get_option(FooterDesign::SETTING_POWERED_BY_LINK))) {
            return [];
        }
        return [['id' => 'powered-by-link', 'text' => \__('I allow to place a link in the cookie banner, which informs about the use of this plugin.', RCB_TD), 'stateFn' => function ($state) {
            if ($state) {
                \update_option(FooterDesign::SETTING_POWERED_BY_LINK, \true);
            }
        }]];
    }
}
