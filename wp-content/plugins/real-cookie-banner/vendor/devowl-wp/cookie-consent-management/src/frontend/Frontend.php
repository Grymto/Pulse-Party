<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\CookieConsentManagement;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractCountryBypass;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\Iso3166OneAlpha2;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\ManagerMiddleware;
/**
 * Functions for the frontend (e.g. generating "Code on page load" HTML output).
 * @internal
 */
class Frontend
{
    const COOKIE_NAME_USER_PREFIX = 'real_cookie_banner';
    const COOKIE_NAME_SUFFIX_GCM = '-gcm';
    const COOKIE_NAME_SUFFIX_TCF = '-tcf';
    /**
     * See `CookieConsentManagement`.
     *
     * @var CookieConsentManagement
     */
    private $cookieConsentManagement;
    /**
     * C'tor.
     *
     * @param CookieConsentManagement $cookieConsentManagement
     */
    public function __construct($cookieConsentManagement)
    {
        $this->cookieConsentManagement = $cookieConsentManagement;
    }
    /**
     * Generate an array which can be used for the frontend to show the cookie banner / content
     * blocker to the website visitor.
     *
     * Behind the scenes, it reuses `Revision` where possible.
     */
    public function toJson()
    {
        $management = $this->getCookieConsentManagement();
        $settings = $management->getSettings();
        $revision = $management->getRevision();
        $general = $settings->getGeneral();
        $consent = $settings->getConsent();
        $googleConsentMode = $settings->getGoogleConsentMode();
        $tcf = $settings->getTcf();
        $multisite = $settings->getMultisite();
        $output = [
            // Also part in revision
            'groups' => $revision->serviceGroupsToJson(),
            'links' => $revision->bannerLinkToJson(),
            'websiteOperator' => $revision->websiteOperatorToJson(),
            'blocker' => $revision->blockersToJson(),
            'languageSwitcher' => \array_map(function ($language) {
                return $language->toJson();
            }, $general->getLanguages()),
            // Misc
            'decisionCookieName' => $this->getCookieName(),
            'revisionHash' => $revision->getEnsuredCurrentHash(),
            // Options
            'territorialLegalBasis' => $general->getTerritorialLegalBasis(),
            'setCookiesViaManager' => $general->getSetCookiesViaManager(),
            'isRespectDoNotTrack' => $consent->isRespectDoNotTrack(),
            'failedConsentDocumentationHandling' => $consent->getFailedConsentDocumentationHandling(),
            'isAcceptAllForBots' => $consent->isAcceptAllForBots(),
            'isDataProcessingInUnsafeCountries' => $consent->isDataProcessingInUnsafeCountries(),
            'dataProcessingInUnsafeCountriesSafeCountries' => $consent->getDataProcessingInUnsafeCountriesSafeCountries(),
            'isAgeNotice' => $consent->isAgeNoticeEnabled(),
            'ageNoticeAgeLimit' => $consent->getAgeNoticeAgeLimit(),
            'isListServicesNotice' => $consent->isListServicesNoticeEnabled(),
            'isTcf' => $tcf->isActive(),
            'isGcm' => $googleConsentMode->isEnabled(),
            'isGcmListPurposes' => $googleConsentMode->isListPurposes(),
        ];
        if ($tcf->isActive()) {
            $output['tcf'] = $revision->tcfToJson();
            $output['tcfMetadata'] = $revision->tcfMetadataToJson();
        }
        $consentForwardingExternalHosts = $multisite->getExternalHosts();
        if (!empty($consentForwardingExternalHosts)) {
            $output['consentForwardingExternalHosts'] = $consentForwardingExternalHosts;
        }
        return $output;
    }
    /**
     * The `toJson` method prepares the data for the complete data of the frontend. Use this function
     * to outsource lazy-loadable data for the second view in your cookie banner.
     *
     * @param array $output
     * @param boolean $appendStateToOutput If `true`, a `hasLazyData` property will be added to the passe `$output` object
     */
    public function prepareLazyData(&$output, $appendStateToOutput = \false)
    {
        $lazyLoaded = [];
        // Remove `additionalInformation`, `urls` and `deviceStorageDisclosure` from the GVL
        if (isset($output['tcf']) && !empty($output['tcf'])) {
            $lazyLoaded['tcf'] = ['vendors' => []];
            foreach ($output['tcf']['vendors'] as $vendorId => &$row) {
                foreach ([
                    // This keys are part of the main GVL model
                    'urls',
                    'deviceStorageDisclosureUrl',
                    // This keys are not part of the main GVL model, but inserted into the object by Real Cookie Banner backend
                    'additionalInformation',
                    'deviceStorageDisclosure',
                ] as $key) {
                    if (isset($row[$key])) {
                        $lazyLoaded['tcf']['vendors'][$vendorId][$key] = $row[$key];
                        unset($row[$key]);
                    }
                }
            }
        }
        if ($appendStateToOutput) {
            $output['hasLazyData'] = !empty($lazyLoaded);
        }
        return (object) $lazyLoaded;
    }
    /**
     * Generate the "Code on page load" for all our configured services.
     *
     * @param callable $outputModifier Allows to modify the HTML output of a single service by a function
     * @return string[]
     */
    public function generateCodeOnPageLoad($outputModifier = null)
    {
        $groups = $this->getCookieConsentManagement()->getSettings()->getGeneral()->getServiceGroups();
        $output = [];
        $uniqueNames = [];
        foreach ($groups as $group) {
            foreach ($group->getItems() as $service) {
                $html = $service->getCodeOnPageLoad();
                if (!empty($html)) {
                    $html = $service->applyDynamicsToHtml($html);
                    $output[] .= \is_callable($outputModifier) ? $outputModifier($html, $service) : $html;
                }
                $uniqueName = $service->getUniqueName();
                if (!empty($uniqueName) && $uniqueName !== ManagerMiddleware::IDENTIFIER_GOOGLE_TAG_MANAGER) {
                    $uniqueNames[] = $uniqueName;
                }
            }
        }
        $gcmOutput = $this->generateGoogleConsentModeCodeOnPageLoad($uniqueNames);
        if (!empty($gcmOutput)) {
            $output[] = \is_callable($outputModifier) ? $outputModifier($gcmOutput, null) : $gcmOutput;
        }
        return $output;
    }
    /**
     * Generate the code on page load for Google Consent Mode.
     *
     * @param string[] $uniqueNames Additional unique names which should be sent to Google beside e.g. `ad_storage`
     */
    protected function generateGoogleConsentModeCodeOnPageLoad($uniqueNames = [])
    {
        $settings = $this->getCookieConsentManagement()->getSettings();
        $setCookiesViaManager = $settings->getGeneral()->getSetCookiesViaManager();
        $countryBypass = $settings->getCountryBypass();
        $gcm = $settings->getGoogleConsentMode();
        $output = '';
        if ($gcm->isEnabled()) {
            $denied = 'denied';
            $granted = 'granted';
            $consentTypes = \array_merge(['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage', 'functionality_storage', 'personalization_storage', 'security_storage'], $setCookiesViaManager === ManagerMiddleware::SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER_WITH_GCM ? $uniqueNames : []);
            $defaults = \array_fill_keys($consentTypes, $denied);
            // Implicit consent for users from third countries which automatically accept all cookies
            $regionGtag = '';
            if ($countryBypass->isActive() && $countryBypass->getType() === AbstractCountryBypass::TYPE_ALL) {
                $regionGtag = \sprintf("\ngtag('consent', 'default', %s );", \json_encode(\array_merge(\array_fill_keys($consentTypes, $granted), ['wait_for_update' => 1000, 'region' => \array_values(
                    // TODO: extract from external package
                    \array_diff(\array_keys(Iso3166OneAlpha2::getCodes()), $countryBypass->getCountries())
                )])));
            }
            $output = \sprintf("<script>window.gtag && (()=>{gtag('set', 'url_passthrough', %s);\ngtag('set', 'ads_data_redaction', %s);%s\ngtag('consent', 'default', %s);})()</script>", $gcm->isCollectAdditionalDataViaUrlParameters() ? 'true' : 'false', $gcm->isRedactAdsDataWithoutConsent() ? 'true' : 'false', $regionGtag, \json_encode(\array_merge($defaults, ['wait_for_update' => 1000])));
        }
        return $output;
    }
    /**
     * Get the cookie name for the consent decision.
     *
     * @param string $suffix See also constants starting with `COOKIE_NAME_SUFFIX_`
     */
    public function getCookieName($suffix = '')
    {
        $revision = $this->getCookieConsentManagement()->getRevision()->getPersistence();
        $implicitString = $revision->getContextVariablesString(\true);
        $contextString = $revision->getContextVariablesString();
        return self::COOKIE_NAME_USER_PREFIX . (empty($implicitString) ? '' : '-' . $implicitString) . (empty($contextString) ? '' : '-' . $contextString) . $suffix;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCookieConsentManagement()
    {
        return $this->cookieConsentManagement;
    }
}
