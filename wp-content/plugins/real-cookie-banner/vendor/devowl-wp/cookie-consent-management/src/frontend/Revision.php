<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\CookieConsentManagement;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\Blocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractConsent;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf\StackCalculator;
/**
 * A revision is a document of all settings at the time of consent.
 *
 * *What does "independent" in context to "Revision" mean?* There are two types of revisions, the independent
 * one holds all settings and data which would not lead to a new required consent on the frontend for the cookie
 * banner. On the other side, when a non-independent setting and data changes, this needs a new consent from
 * the website visitor on the frontend.
 * @internal
 */
class Revision
{
    const TYPE_INDEPENDENT = 'independent';
    const TYPE_REQUIRE_NEW_CONSENT = 'dependent';
    const TYPE_ALL = 'all';
    /**
     * See `CookieConsentManagement`.
     *
     * @var CookieConsentManagement
     */
    private $cookieConsentManagement;
    /**
     * See `AbstractRevisionPersistance`.
     *
     * @var AbstractRevisionPersistance
     */
    private $persistence;
    /**
     * C'tor.
     *
     * @param CookieConsentManagement $cookieConsentManagement
     * @param AbstractRevisionPersistance $persistence
     */
    public function __construct($cookieConsentManagement, $persistence)
    {
        $this->cookieConsentManagement = $cookieConsentManagement;
        $this->persistence = $persistence;
        $persistence->setRevision($this);
    }
    /**
     * Create a MD5 hash from all available options and settings, save also as "current revision" if necessery.
     * If the hash differs, a new consent is needed!
     *
     * @param boolean|string $persist Persist the revision in database, pass `force` to force-write into the database
     * @param boolean $forceNewConsent
     * @return array 'revision' and 'hash'
     */
    public function create($persist = \false, $forceNewConsent = \true)
    {
        $settings = $this->getCookieConsentManagement()->getSettings();
        // Automatically update to the latest cookie version
        if ($persist && $forceNewConsent) {
            $this->getCookieConsentManagement()->getSettings()->getConsent()->setCookieVersion(AbstractConsent::DEFAULT_COOKIE_VERSION);
        }
        // Create hashable revision
        $revision = \array_merge(['options' => $this->optionsToJson(self::TYPE_REQUIRE_NEW_CONSENT), 'groups' => $this->serviceGroupsToJson(), 'websiteOperator' => $this->websiteOperatorToJson(), 'nonVisualBlocker' => $this->nonVisualBlockersToJson()], $this->getPersistence()->getContextVariablesExplicit());
        $tcf = $settings->getTcf();
        if ($tcf->isActive()) {
            $revision['tcf'] = $this->tcfToJson();
        }
        $frontend = $this->getCookieConsentManagement()->getFrontend();
        $lazyLoadedData = $frontend->prepareLazyData($revision);
        $revision['lazyLoadedDataForSecondView'] = $lazyLoadedData;
        $revision = $this->getPersistence()->alterRevision($revision);
        $json_revision = \json_encode($revision);
        $hash = \md5($json_revision);
        $result = ['revision' => $revision, 'hash' => $hash];
        if ($persist && ($this->getPersistence()->getCurrentHash() !== $hash || $persist === 'force')) {
            $this->getPersistence()->persist($result, $forceNewConsent);
        }
        return $result;
    }
    /**
     * Create a MD5 hash from all available options and settings which do not require a new consent (independent).
     *
     * @param boolean $persist Persist the revision in database
     * @return array 'revision' and 'hash'
     */
    public function createIndependent($persist = \false)
    {
        $settings = $this->getCookieConsentManagement()->getSettings();
        // Create hashable revision
        $revision = ['options' => $this->optionsToJson(self::TYPE_INDEPENDENT), 'blocker' => $this->blockersToJson(), 'links' => $this->bannerLinkToJson(), 'languageSwitcher' => \array_map(function ($ls) {
            $result = $ls->toJson();
            // Remove the `url` as this leads to a lot of revisions (per sub page)
            unset($result['url']);
            return $result;
        }, $settings->getGeneral()->getLanguages())];
        $consentForwardingExternalHosts = $settings->getMultisite()->getExternalHosts();
        if (!empty($consentForwardingExternalHosts)) {
            $revision['consentForwardingExternalHosts'] = $consentForwardingExternalHosts;
        }
        $tcf = $settings->getTcf();
        if ($tcf->isActive()) {
            $revision['tcfMetadata'] = $this->tcfMetadataToJson();
        }
        $revision = $this->getPersistence()->alterRevisionIndependent($revision);
        $json_revision = \json_encode($revision);
        $hash = \md5($json_revision);
        $result = ['revision' => $revision, 'hash' => $hash];
        if ($persist) {
            $this->getPersistence()->persistIndependent($result, $hash);
        }
        return $result;
    }
    /**
     * Get settings as array object so it can be used within e.g. JSON or serialize to the frontend.
     *
     * @param string $type See `TYPE_` constants
     */
    public function optionsToJson($type = self::TYPE_ALL)
    {
        $result = [];
        $settings = $this->getCookieConsentManagement()->getSettings();
        $general = $settings->getGeneral();
        $consent = $settings->getConsent();
        $multisite = $settings->getMultisite();
        $tcf = $settings->getTcf();
        $countryBypass = $settings->getCountryBypass();
        $gcm = $settings->getGoogleConsentMode();
        if ($type === self::TYPE_ALL || $type === self::TYPE_INDEPENDENT) {
            $result['isBannerActive'] = $general->isBannerActive();
            $result['isBlockerActive'] = $general->isBlockerActive();
            $result['hidePageIds'] = $general->getAdditionalPageHideIds();
            $result['isRespectDoNotTrack'] = $consent->isRespectDoNotTrack();
            $result['failedConsentDocumentationHandling'] = $consent->getFailedConsentDocumentationHandling();
            $result['isSaveIp'] = $consent->isSaveIpEnabled();
            $result['consentDuration'] = $consent->getConsentDuration();
        }
        if ($type === self::TYPE_ALL || $type === self::TYPE_REQUIRE_NEW_CONSENT) {
            $result['operatorContactAddress'] = $general->getOperatorContactAddress();
            $result['operatorCountry'] = $general->getOperatorCountry();
            $result['operatorContactPhone'] = $general->getOperatorContactPhone();
            $result['operatorContactEmail'] = $general->getOperatorContactEmail();
            $result['operatorContactFormId'] = $general->getOperatorContactFormId();
            $result['territorialLegalBasis'] = $general->getTerritorialLegalBasis();
            $result['setCookiesViaManager'] = $general->getSetCookiesViaManager();
            $result['isAcceptAllForBots'] = $consent->isAcceptAllForBots();
            $result['cookieDuration'] = $consent->getCookieDuration();
            $result['cookieVersion'] = $consent->getCookieVersion();
            $result['isDataProcessingInUnsafeCountries'] = $consent->isDataProcessingInUnsafeCountries();
            $result['dataProcessingInUnsafeCountriesSafeCountries'] = $consent->getDataProcessingInUnsafeCountriesSafeCountries();
            $result['isAgeNotice'] = $consent->isAgeNoticeEnabled();
            $result['ageNoticeAgeLimit'] = $consent->getAgeNoticeAgeLimit();
            $result['isListServicesNotice'] = $consent->isListServicesNoticeEnabled();
            $result['isConsentForwarding'] = $multisite->isConsentForwarding();
            $result['forwardTo'] = $multisite->getForwardTo();
            $result['crossDomains'] = $multisite->getCrossDomains();
            $result['isTcf'] = $tcf->isActive();
            // $result['tcfPublisherCc'] = // deprecated, operator country is used instead
            // $result['tcfScopeOfConsent'] = // no longer in use
            $result['isCountryBypass'] = $countryBypass->isActive();
            $result['countryBypassCountries'] = $countryBypass->getCountries();
            $result['countryBypassType'] = $countryBypass->getType();
            $result['isGcm'] = $gcm->isEnabled();
            $result['isGcmCollectAdditionalDataViaUrlParameters'] = $gcm->isCollectAdditionalDataViaUrlParameters();
            $result['isGcmRedactAdsDataWithoutConsent'] = $gcm->isRedactAdsDataWithoutConsent();
            $result['isGcmListPurposes'] = $gcm->isListPurposes();
        }
        // Not needed in frontend / revision
        // $countryBypass->getNextUpdateTime();
        // $gcm->isShowRecommandationsWithoutConsent()
        return $result;
    }
    /**
     * TCF data (vendors, declarations, stacks, ...) to array.
     */
    public function tcfToJson()
    {
        $tcf = $this->getCookieConsentManagement()->getSettings()->getTcf();
        if (!$tcf->isActive()) {
            return \false;
        }
        $gvl = $tcf->getGvl();
        $vendorConfigurations = $tcf->getVendorConfigurations();
        $output = ['vendors' => [], 'stacks' => [], StackCalculator::DECLARATION_TYPE_PURPOSES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_PURPOSES => [], StackCalculator::DECLARATION_TYPE_FEATURES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_FEATURES => [], StackCalculator::DECLARATION_TYPE_DATA_CATEGORIES => []];
        $usedDeclarations = [StackCalculator::DECLARATION_TYPE_PURPOSES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_PURPOSES => [], StackCalculator::DECLARATION_TYPE_FEATURES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_FEATURES => [], StackCalculator::DECLARATION_TYPE_DATA_CATEGORIES => []];
        foreach ($vendorConfigurations as $vendorConfiguration) {
            $vendorId = $vendorConfiguration->getVendorId();
            $output['vendorConfigurations'][$vendorId] = $vendorConfiguration->toJson();
            $output['vendors'][$vendorId] = $vendorConfiguration->getVendor();
            foreach ($usedDeclarations as $declaration => $used) {
                $usedDeclarations[$declaration] = \array_values(\array_unique(\array_merge($vendorConfiguration->getUsedDeclarations()[$declaration], $used)));
            }
        }
        // Map declaration types to objects and mark unused
        $output = \array_merge($output, $gvl->allDeclarations(['onlyReturnDeclarations' => \true]));
        foreach (StackCalculator::DECLARATION_TYPES as $declaration) {
            foreach ($output[$declaration] as $id => $declarationObject) {
                if (isset($usedDeclarations[$declaration]) && !\in_array($id, $usedDeclarations[$declaration], \true)) {
                    $output['unused'][$declaration][] = $id;
                }
            }
        }
        foreach (StackCalculator::DECLARATION_TYPES as $declaration) {
            $output['unused'][$declaration] = $output['unused'][$declaration] ?? [];
        }
        // Calculate stacks on used
        if (\count($usedDeclarations) > 0) {
            $output['stacks'] = (new StackCalculator($gvl->stacks()['stacks'], $usedDeclarations))->calculateBestSuitableStacks();
        }
        // Cast the output values to objects as they can be empty
        foreach (\array_keys($output) as $key) {
            $output[$key] = (object) $output[$key];
        }
        return $output;
    }
    /**
     * TCF metadata to array.
     */
    public function tcfMetadataToJson()
    {
        $tcf = $this->getCookieConsentManagement()->getSettings()->getTcf();
        if (!$tcf->isActive()) {
            return \false;
        }
        $gvl = $tcf->getGvl();
        list($gvlSpecificationVersion, $tcfPolicyVersion, $vendorListVersion) = $gvl->getLatestVersions();
        return ['publisherCc' => $this->getCookieConsentManagement()->getSettings()->getGeneral()->getOperatorCountry(), 'gvlSpecificationVersion' => $gvlSpecificationVersion, 'tcfPolicyVersion' => $tcfPolicyVersion, 'vendorListVersion' => $vendorListVersion, 'scope' => $tcf->getScopeOfConsent(), 'language' => $gvl->getCurrentLanguage()];
    }
    /**
     * Operator as own object as it is needed in this format for `@devowl-wp/react-cookie-banner`.
     */
    public function websiteOperatorToJson()
    {
        $general = $this->getCookieConsentManagement()->getSettings()->getGeneral();
        return ['address' => $general->getOperatorContactAddress(), 'country' => $general->getOperatorCountry(), 'contactEmail' => $general->getOperatorContactEmail(), 'contactPhone' => $general->getOperatorContactPhone(), 'contactFormUrl' => $general->getOperatorContactFormUrl()];
    }
    /**
     * All service groups as JSON representation.
     *
     * @return array[]
     */
    public function serviceGroupsToJson()
    {
        $result = [];
        foreach ($this->getCookieConsentManagement()->getSettings()->getGeneral()->getServiceGroups() as $group) {
            $result[] = $group->toJson();
        }
        return $result;
    }
    /**
     * All content blockers as JSON representation.
     *
     * @return array[]
     */
    public function blockersToJson()
    {
        $result = [];
        foreach ($this->getCookieConsentManagement()->getSettings()->getGeneral()->getBlocker() as $blocker) {
            $result[] = $blocker->toJson();
        }
        return $result;
    }
    /**
     * All non-visual content blockers as they should require a new consent.
     *
     * @return array[]
     */
    public function nonVisualBlockersToJson()
    {
        $result = [];
        foreach ($this->getCookieConsentManagement()->getSettings()->getGeneral()->getBlocker() as $blocker) {
            if ($blocker->isVisual()) {
                continue;
            }
            $criteria = $blocker->getCriteria();
            $nonVisualRow = ['id' => $blocker->getId(), 'rules' => $blocker->getRules()];
            if ($criteria !== Blocker::DEFAULT_CRITERIA) {
                $nonVisualRow['criteria'] = $criteria;
            }
            switch ($criteria) {
                case Blocker::CRITERIA_SERVICES:
                    $nonVisualRow['services'] = $blocker->getServices();
                    break;
                case Blocker::CRITERIA_TCF_VENDORS:
                    $nonVisualRow['tcfVendors'] = $blocker->getTcfVendors();
                    $nonVisualRow['tcfPurposes'] = $blocker->getTcfPurposes();
                    break;
                default:
                    break;
            }
            $result[] = $nonVisualRow;
        }
        return $result;
    }
    /**
     * All banner links as JSON representation.
     *
     * @return array[]
     */
    public function bannerLinkToJson()
    {
        $result = [];
        foreach ($this->getCookieConsentManagement()->getSettings()->getGeneral()->getBannerLinks() as $link) {
            $result[] = $link->toJson();
        }
        return $result;
    }
    /**
     * Get the current active revision. If there is not current revision (only after activating the plugin itself),
     * the current revision hash gets calculated from current settings.
     *
     * @return string
     */
    public function getEnsuredCurrentHash()
    {
        $hash = $this->getPersistence()->getCurrentHash();
        if (empty($hash)) {
            $hash = $this->create(\true)['hash'];
        }
        return $hash;
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
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPersistence()
    {
        return $this->persistence;
    }
}
