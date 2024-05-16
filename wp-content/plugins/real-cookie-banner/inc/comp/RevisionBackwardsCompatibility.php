<?php

namespace DevOwl\RealCookieBanner\comp;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractConsent;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\customize\banner\Decision;
use DevOwl\RealCookieBanner\view\customize\banner\Mobile;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Apply backwards compatible variables to an already saved revision. This can be useful e.g. new texts were added
 * to the revision which previously were read from `wp_localize_script`.
 * @internal
 */
class RevisionBackwardsCompatibility
{
    private $revision;
    private $independent;
    /**
     * C'tor.
     *
     * @param array $revision
     * @param boolean $independent
     * @codeCoverageIgnore
     */
    public function __construct($revision, $independent)
    {
        $this->revision = $revision;
        $this->independent = $independent;
    }
    /**
     * Do all the backwards-compatibility calculations.
     */
    public function apply()
    {
        $this->migration_ev2070();
        $this->migration_nz2k7f();
        $this->migration_20chd53();
        $this->migration_2d8dedh_2unhn5x();
        $this->migration_2wpbbhr_863h7nj72();
        $this->migration_2d8dedh();
        $this->migration_861m47jgm();
        $this->migration_863h7nj72();
        $this->migration_cawgkp();
        $this->migration_866awy2fr();
        $this->migration_apv5uu();
        $this->migration_8693n1cc5();
        $this->migration_86940n0a0();
        $this->migration_1za40xb();
        $this->migration_1xpcvre();
        return $this->revision;
    }
    /**
     * Since 1.10: Moved the texts to the customizer, but keep for backwards-compatibility.
     *
     * @see https://app.clickup.com/t/ev2070
     */
    protected function migration_ev2070()
    {
        if ($this->independent && !isset($this->revision['banner']['customizeValuesBanner']['texts']['blockerHeadline'])) {
            $this->revision['banner']['customizeValuesBanner']['texts'] = \array_merge($this->revision['banner']['customizeValuesBanner']['texts'], Core::getInstance()->getCompLanguage()->translateArray(['blockerHeadline' => \_x('{{name}} blocked due to privacy settings', 'legal-text', RCB_TD), 'blockerLinkShowMissing' => \_x('Show all services you still need to agree to', 'legal-text', RCB_TD), 'blockerLoadButton' => \_x('Accept required services and load content', 'legal-text', RCB_TD), 'blockerAcceptInfo' => \_x('Loading the blocked content will adjust your privacy settings. Content from this service will not be blocked in the future. You have the right to revoke or change your decision at any time.', 'legal-text', RCB_TD)], [], null, ['legal-text']));
        }
    }
    /**
     * Disable mobile experience for older revisions.
     *
     * @see https://app.clickup.com/t/nz2k7f
     */
    protected function migration_nz2k7f()
    {
        if ($this->independent && !isset($this->revision['banner']['customizeValuesBanner']['mobile'])) {
            $this->revision['banner']['customizeValuesBanner']['mobile'] = ['enabled' => \false, 'maxHeight' => Mobile::DEFAULT_MAX_HEIGHT, 'hideHeader' => Mobile::DEFAULT_HIDE_HEADER, 'alignment' => Mobile::DEFAULT_ALIGNMENT, 'scalePercent' => Mobile::DEFAULT_SCALE_PERCENT, 'scalePercentVertical' => Mobile::DEFAULT_SCALE_PERCENT_VERTICAL];
        }
    }
    /**
     * Enable bullet groups + default button order for older revision.
     *
     * @see https://app.clickup.com/t/20chd53
     */
    protected function migration_20chd53()
    {
        if ($this->independent) {
            if (!isset($this->revision['banner']['customizeValuesBanner']['decision']['showGroups'])) {
                $this->revision['banner']['customizeValuesBanner']['decision']['showGroups'] = \true;
            }
            if (!isset($this->revision['banner']['customizeValuesBanner']['decision']['buttonOrder'])) {
                $this->revision['banner']['customizeValuesBanner']['decision']['buttonOrder'] = Decision::DEFAULT_BUTTON_ORDER;
            }
        }
    }
    /**
     * Modify already given consents and adjust the metadata field names for "List of consents".
     *
     * @see https://app.clickup.com/t/2d8dedh
     * @see https://app.clickup.com/t/2unhn5x
     */
    protected function migration_2d8dedh_2unhn5x()
    {
        if (!$this->independent && isset($this->revision['groups'])) {
            $renameCookieFields = [
                'providerPrivacyPolicy' => 'providerPrivacyPolicyUrl',
                'codeOptOutDelete' => 'deleteTechnicalDefinitionsAfterOptOut',
                'noTechnicalDefinitions' => 'isEmbeddingOnlyExternalResources',
                'consentForwardingUniqueName' => 'uniqueName',
                'thisIsGoogleTagManager' => \false,
                // remove field
                'thisIsMatomoTagManager' => \false,
            ];
            $setCookiesViaManager = $this->revision['options']['SETTING_SET_COOKIES_VIA_MANAGER'] ?? 'none';
            if ($setCookiesViaManager === 'googleTagManager') {
                $renameCookieFields['googleTagManagerInEventName'] = 'tagManagerOptInEventName';
                $renameCookieFields['googleTagManagerOutEventName'] = 'tagManagerOptOutEventName';
                $renameCookieFields['codeOptInNoGoogleTagManager'] = 'executeCodeOptInWhenNoTagManagerConsentIsGiven';
                $renameCookieFields['codeOptOutNoGoogleTagManager'] = 'executeCodeOptOutWhenNoTagManagerConsentIsGiven';
                $renameCookieFields['matomoTagManagerInEventName'] = \false;
                $renameCookieFields['matomoTagManagerOutEventName'] = \false;
                $renameCookieFields['codeOptInNoMatomoTagManager'] = \false;
                $renameCookieFields['codeOptOutNoMatomoTagManager'] = \false;
            } else {
                $renameCookieFields['matomoTagManagerInEventName'] = 'tagManagerOptInEventName';
                $renameCookieFields['matomoTagManagerOutEventName'] = 'tagManagerOptOutEventName';
                $renameCookieFields['codeOptInNoMatomoTagManager'] = 'executeCodeOptInWhenNoTagManagerConsentIsGiven';
                $renameCookieFields['codeOptOutNoMatomoTagManager'] = 'executeCodeOptOutWhenNoTagManagerConsentIsGiven';
                $renameCookieFields['googleTagManagerInEventName'] = \false;
                $renameCookieFields['googleTagManagerOutEventName'] = \false;
                $renameCookieFields['codeOptInNoGoogleTagManager'] = \false;
                $renameCookieFields['codeOptOutNoGoogleTagManager'] = \false;
            }
            foreach ($this->revision['groups'] as &$group) {
                if (isset($group['items'])) {
                    foreach ($group['items'] as &$cookie) {
                        foreach ($renameCookieFields as $renameCookieField => $newFieldName) {
                            if (isset($cookie[$renameCookieField])) {
                                if ($newFieldName !== \false) {
                                    $cookie[$newFieldName] = $cookie[$renameCookieField];
                                }
                                unset($cookie[$renameCookieField]);
                            }
                        }
                        // Special cases
                        if (isset($cookie['technicalDefinitions']) && \is_array($cookie['technicalDefinitions'])) {
                            $cookie['technicalDefinitions'] = \json_decode(\str_replace('"sessionDuration"', '"isSessionDuration"', \json_encode($cookie['technicalDefinitions'])), ARRAY_A);
                        }
                    }
                }
            }
        }
    }
    /**
     * Modify already given consents and adjust the metadata field for legal notice URL and provider contact
     * information for "List of consents".
     *
     * @see https://app.clickup.com/t/2wpbbhr
     * @see https://app.clickup.com/t/863h7nj72
     */
    protected function migration_2wpbbhr_863h7nj72()
    {
        if (!$this->independent && isset($this->revision['groups'])) {
            $makeEmpty = [Cookie::META_NAME_PROVIDER_LEGAL_NOTICE_URL];
            foreach ($this->revision['groups'] as &$group) {
                if (isset($group['items'])) {
                    foreach ($group['items'] as &$cookie) {
                        foreach ($makeEmpty as $me) {
                            if (!isset($cookie[$me])) {
                                $cookie[$me] = '';
                            }
                        }
                        if (!isset($cookie['providerContact'])) {
                            $cookie['providerContact'] = ['phone' => '', 'email' => '', 'link' => ''];
                        }
                    }
                }
            }
        }
    }
    /**
     * Modify already given consents and adjust the metadata field names for "List of consents".
     *
     * @see https://app.clickup.com/t/2d8dedh
     */
    protected function migration_2d8dedh()
    {
        $renameBlockerFields = ['visual' => 'isVisual', 'visualDarkMode' => 'isVisualDarkMode', 'forceHidden' => 'shouldForceToShowVisual', 'hosts' => 'rules', 'cookies' => 'services'];
        $useBlockers = [];
        if ($this->independent && isset($this->revision['blocker'])) {
            $useBlockers =& $this->revision['blocker'];
        } elseif (!$this->independent && isset($this->revision['nonVisualBlocker'])) {
            $useBlockers =& $this->revision['nonVisualBlocker'];
        }
        foreach ($useBlockers as &$blocker) {
            foreach ($renameBlockerFields as $renameBlockerField => $newFieldName) {
                if (isset($blocker[$renameBlockerField])) {
                    if ($newFieldName !== \false) {
                        $blocker[$newFieldName] = $blocker[$renameBlockerField];
                    }
                    unset($blocker[$renameBlockerField]);
                }
            }
            // Special cases
            if (isset($blocker['criteria']) && $blocker['criteria'] === 'cookies') {
                $blocker['criteria'] = 'services';
            }
        }
    }
    /**
     * Modify already given consents and adjust the "data processing in unsafe countries" field names for "List of consents".
     *
     * @see https://app.clickup.com/t/861m47jgm
     */
    protected function migration_861m47jgm()
    {
        if (!$this->independent && isset($this->revision['options']['SETTING_ACCEPT_ALL_FOR_BOTS'])) {
            if (!isset($this->revision['options']['SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES'])) {
                $this->revision['options']['SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES'] = \false;
                $this->revision['options']['SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES_SAFE_COUNTRIES'] = '';
            }
            foreach ($this->revision['groups'] as &$group) {
                foreach ($group['items'] as &$item) {
                    $item['dataProcessingInCountries'] = $item['dataProcessingInCountries'] ?? [];
                    $item['dataProcessingInCountriesSpecialTreatments'] = $item['dataProcessingInCountriesSpecialTreatments'] ?? [];
                }
            }
            if (isset($this->revision['tcf'])) {
                foreach ($this->revision['tcf']['vendorConfigurations'] as &$vendorConfiguration) {
                    $vendorConfiguration['dataProcessingInCountries'] = $vendorConfiguration['dataProcessingInCountries'] ?? [];
                    $vendorConfiguration['dataProcessingInCountriesSpecialTreatments'] = $vendorConfiguration['dataProcessingInCountriesSpecialTreatments'] ?? [];
                }
            }
        }
    }
    /**
     * Set the default legal basis for older consent records.
     *
     * @see https://app.clickup.com/t/863h7nj72
     */
    protected function migration_863h7nj72()
    {
        if (!$this->independent && !isset($this->revision['options']['SETTING_TERRITORIAL_LEGAL_BASIS'])) {
            $this->revision['options']['SETTING_TERRITORIAL_LEGAL_BASIS'] = 'gdpr-eprivacy';
        }
    }
    /**
     * With version 3.12.0 we have introduced banner / footer links in Cookies > Settings > General.
     * Instead of using the IDs (imprint, privacy policy) from the customizer, we now manage the links
     * via a Custom Post Type. For backwards-compatibility, we migrate the links accordingly.
     *
     * @see https://app.clickup.com/t/cawgkp
     */
    protected function migration_cawgkp()
    {
        // Convert old `websiteOperator.contactForm` (page ID) to `contactFormUrl`
        $homeUrl = \trailingslashit(\home_url());
        if (!$this->independent && isset($this->revision['websiteOperator']) && isset($this->revision['websiteOperator']['contactForm'])) {
            $contactFormId = $this->revision['websiteOperator']['contactForm'];
            $this->revision['websiteOperator']['contactFormUrl'] = $contactFormId > 0 ? \add_query_arg('page_id', $contactFormId, $homeUrl) : '';
            unset($this->revision['websiteOperator']['contactForm']);
        }
        // Convert old settings to new `links`
        if ($this->independent && !isset($this->revision['links'])) {
            $customizeLegal = $this->revision['banner']['customizeValuesBanner']['legal'] ?? [];
            $linkTarget = $this->revision['banner']['customizeValuesBanner']['footerDesign']['linkTarget'] ?? [];
            $pageIdToPermalink = $this->revision['pageIdToPermalink'] ?? [];
            $links = [];
            foreach (['privacyPolicy', 'imprint'] as $pageType) {
                $pageId = $customizeLegal[$pageType] ?? 0;
                $isExternalUrl = $customizeLegal[$pageType . 'IsExternalUrl'] ?? \false;
                $externalUrl = $customizeLegal[$pageType . 'ExternalUrl'] ?? '';
                $hideCookieBanner = $customizeLegal[$pageType . 'Hide'] ?? \true;
                $url = '';
                if ($isExternalUrl) {
                    $url = $externalUrl;
                } elseif ($pageId > 0) {
                    $url = $pageIdToPermalink[$pageId] ?? \add_query_arg('page_id', $pageId, $homeUrl);
                }
                $link = ['label' => $customizeLegal[$pageType . 'Label'] ?? '', 'pageType' => $pageType === 'imprint' ? 'legalNotice' : $pageType, 'url' => $url, 'hideCookieBanner' => $hideCookieBanner, 'isTargetBlank' => $linkTarget === '_blank'];
                if (!empty($link['url']) && !empty($link['label'])) {
                    $links[] = $link;
                }
            }
            $this->revision['links'] = $links;
        }
    }
    /**
     * Modify already given consents and adjust the age limit for the age notice for "List of consents".
     *
     * @see https://app.clickup.com/t/866awy2fr
     */
    protected function migration_866awy2fr()
    {
        if ($this->independent && !isset($this->revision['ageNoticeAgeLimit'])) {
            $this->revision['ageNoticeAgeLimit'] = 16;
        }
    }
    /**
     * With version 4.4.0 we have introduced the Google Consent Mode. As it needs an accordion in the first
     * view of the cookie banner, the "TCF Stacks" got renamed to "Accordion" and moved to the free version.
     *
     * @see https://app.clickup.com/t/apv5uu
     */
    protected function migration_apv5uu()
    {
        if ($this->independent && isset($this->revision['banner']['customizeValuesBanner']['bodyDesign'])) {
            $bodyDesign =& $this->revision['banner']['customizeValuesBanner']['bodyDesign'];
            foreach ($bodyDesign as $key => $value) {
                if (Utils::startsWith($key, 'tcfStacks')) {
                    $newKey = \str_replace('tcfStacks', 'accordion', $key);
                    $bodyDesign[$newKey] = $value;
                    unset($bodyDesign[$key]);
                }
            }
        }
    }
    /**
     * With the introduction of `@devowl-wp/cookie-consent-management` we aim modularity also for the consent management
     * on the PHP side in an isomorphic way. For this, we also try to iteratively move the revision management to that package.
     *
     * @see https://app.clickup.com/t/8693n1cc5?comment=90120023423338
     */
    public function migration_8693n1cc5()
    {
        if (isset($this->revision['options'])) {
            $options = $this->revision['options'];
            $operatorCountry = $options['SETTING_OPERATOR_COUNTRY'] ?? '';
            foreach ($options as $key => $val) {
                $newKey = $key;
                switch ($key) {
                    case 'SETTING_BANNER_ACTIVE':
                        $newKey = 'isBannerActive';
                        break;
                    case 'SETTING_BLOCKER_ACTIVE':
                        $newKey = 'isBlockerActive';
                        break;
                    case 'SETTING_HIDE_PAGE_IDS':
                        $newKey = 'hidePageIds';
                        $val = \array_map('intval', \array_filter(\explode(',', $val)));
                        break;
                    case 'SETTING_RESPECT_DO_NOT_TRACK':
                        $newKey = 'isRespectDoNotTrack';
                        break;
                    case 'SETTING_SAVE_IP':
                        $newKey = 'isSaveIp';
                        break;
                    case 'SETTING_CONSENT_DURATION':
                        $newKey = 'consentDuration';
                        break;
                    case 'SETTING_OPERATOR_COUNTRY':
                        $newKey = 'operatorCountry';
                        break;
                    case 'SETTING_OPERATOR_CONTACT_ADDRESS':
                        $newKey = 'operatorContactAddress';
                        break;
                    case 'SETTING_OPERATOR_CONTACT_PHONE':
                        $newKey = 'operatorContactPhone';
                        break;
                    case 'SETTING_OPERATOR_CONTACT_EMAIL':
                        $newKey = 'operatorContactEmail';
                        break;
                    case 'SETTING_OPERATOR_CONTACT_FORM_ID':
                        $newKey = 'operatorContactFormId';
                        break;
                    case 'SETTING_TERRITORIAL_LEGAL_BASIS':
                        $newKey = 'territorialLegalBasis';
                        $val = \explode(',', $val);
                        break;
                    case 'SETTING_SET_COOKIES_VIA_MANAGER':
                        $newKey = 'setCookiesViaManager';
                        break;
                    case 'SETTING_ACCEPT_ALL_FOR_BOTS':
                        $newKey = 'isAcceptAllForBots';
                        break;
                    case 'SETTING_COOKIE_DURATION':
                        $newKey = 'cookieDuration';
                        break;
                    case 'SETTING_COOKIE_VERSION':
                        $newKey = 'cookieVersion';
                        break;
                    // @deprecated
                    case 'SETTING_EPRIVACY_USA':
                        $newKey = 'isEPrivacyUSA';
                        break;
                    case 'SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES':
                        $newKey = 'isDataProcessingInUnsafeCountries';
                        break;
                    case 'SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES_SAFE_COUNTRIES':
                        $newKey = 'dataProcessingInUnsafeCountriesSafeCountries';
                        $val = \explode(',', $val);
                        break;
                    case 'SETTING_AGE_NOTICE':
                        $newKey = 'isAgeNotice';
                        break;
                    case 'SETTING_AGE_NOTICE_AGE_LIMIT':
                        $newKey = 'ageNoticeAgeLimit';
                        if ($val === 'INHERIT') {
                            $val = AbstractConsent::AGE_NOTICE_COUNTRY_AGE_MAP[$operatorCountry] ?? AbstractConsent::AGE_NOTICE_COUNTRY_AGE_MAP['GDPR'];
                        }
                        break;
                    case 'SETTING_LIST_SERVICES_NOTICE':
                        $newKey = 'isListServicesNotice';
                        break;
                    case 'SETTING_CONSENT_FORWARDING':
                        $newKey = 'isConsentForwarding';
                        break;
                    case 'SETTING_FORWARD_TO':
                        $newKey = 'forwardTo';
                        $val = \array_filter(\explode('|', $val));
                        break;
                    case 'SETTING_CROSS_DOMAINS':
                        $newKey = 'crossDomains';
                        $val = \array_filter(\explode("\n", $val));
                        break;
                    case 'SETTING_TCF':
                        $newKey = 'isTcf';
                        break;
                    // @deprecated
                    case 'SETTING_TCF_PUBLISHER_CC':
                    case 'SETTING_TCF_SCOPE_OF_CONSENT':
                        $newKey = null;
                        $val = null;
                        break;
                    case 'SETTING_COUNTRY_BYPASS_ACTIVE':
                        $newKey = 'isCountryBypass';
                        break;
                    case 'SETTING_COUNTRY_BYPASS_COUNTRIES':
                        $newKey = 'countryBypassCountries';
                        $val = \explode(',', $val);
                        break;
                    case 'SETTING_COUNTRY_BYPASS_TYPE':
                        $newKey = 'countryBypassType';
                        break;
                    case 'SETTING_GCM_ENABLED':
                        $newKey = 'isGcm';
                        break;
                    case 'SETTING_GCM_ADDITIONAL_URL_PARAMETERS':
                        $newKey = 'isGcmCollectAdditionalDataViaUrlParameters';
                        break;
                    case 'SETTING_GCM_REDACT_DATA_WITHOUT_CONSENT':
                        $newKey = 'isGcmRedactAdsDataWithoutConsent';
                        break;
                    case 'SETTING_GCM_LIST_PURPOSES':
                        $newKey = 'isGcmListPurposes';
                        break;
                    default:
                        break;
                }
                if ($newKey !== $key) {
                    if ($val !== null) {
                        $this->revision['options'][$newKey] = $val;
                    }
                    unset($this->revision['options'][$key]);
                }
            }
        }
        // We do no longer save the country-bypass database download as this could lead to too many revisions in the database for every day
        if (isset($this->revision['countryBypassDbDownload'])) {
            unset($this->revision['countryBypassDbDownload']);
        }
        // We do no longer save the age limit as own key, it is now directly exposed through `options`
        if (isset($this->revision['ageNoticeAgeLimit'])) {
            unset($this->revision['ageNoticeAgeLimit']);
        }
        // This got renamed
        if (isset($this->revision['tcfMeta'])) {
            $this->revision['tcfMetadata'] = $this->revision['tcfMeta'];
            unset($this->revision['tcfMeta']);
        }
        // Lazy loading for the revision
        if (!isset($this->revision['lazyLoadedDataForSecondView'])) {
            $frontend = Core::getInstance()->getCookieConsentManagement()->getFrontend();
            $lazyLoadedData = $frontend->prepareLazyData($this->revision);
            $this->revision['lazyLoadedDataForSecondView'] = $lazyLoadedData;
        }
    }
    /**
     * Disable maximum height customize setting for the cookie banner.
     *
     * @see https://app.clickup.com/t/86940n0a0
     */
    public function migration_86940n0a0()
    {
        if ($this->independent && !isset($this->revision['banner']['customizeValuesBanner']['layout']['maxHeightEnabled'])) {
            $this->revision['banner']['customizeValuesBanner']['layout']['maxHeightEnabled'] = \false;
            $this->revision['banner']['customizeValuesBanner']['layout']['maxHeight'] = 700;
        }
    }
    /**
     * Disable sticky legal links for older revisions.
     *
     * @see https://app.clickup.com/t/1za40xb
     */
    protected function migration_1za40xb()
    {
        if ($this->independent && !isset($this->revision['banner']['customizeValuesBanner']['sticky'])) {
            $this->revision['banner']['customizeValuesBanner']['sticky'] = ['enabled' => \false];
        }
    }
    /**
     * Introducing a default value for `Handling of failed consent documentation`.
     *
     * @see https://app.clickup.com/t/1xpcvre
     */
    public function migration_1xpcvre()
    {
        if ($this->independent && !isset($this->revision['options']['failedConsentDocumentationHandling'])) {
            $this->revision['options']['failedConsentDocumentationHandling'] = AbstractConsent::FAILED_CONSENT_DOCUMENTATION_HANDLING_ESSENTIALS_ONLY;
        }
    }
}
