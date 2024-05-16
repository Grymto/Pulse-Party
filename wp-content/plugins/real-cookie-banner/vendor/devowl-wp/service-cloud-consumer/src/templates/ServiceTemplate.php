<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates;

use stdClass;
/**
 * Service template.
 * @internal
 */
class ServiceTemplate extends AbstractTemplate
{
    /**
     * If yes there will be no external privacy policy, imprint and provider name. It needs to
     * be filled with a middleware or manually.
     *
     * @var boolean
     */
    public $isProviderCurrentWebsite;
    /**
     * Provider name.
     *
     * @var string
     */
    public $provider;
    /**
     * Provider contact (email, phone, link).
     *
     * @var stdClass
     */
    public $providerContact;
    /**
     * Provider notice.
     *
     * @var string
     */
    public $providerNotice;
    /**
     * Provider legal notice URL (e.g. imprint).
     *
     * @var string
     */
    public $providerLegalNoticeUrl;
    /**
     * Provider privacy policy URL.
     *
     * @var string
     */
    public $providerPrivacyPolicyUrl;
    /**
     * Technical definitions.
     *
     * @var array
     */
    public $technicalDefinitions = [];
    /**
     * By default, when a content blocker for a service exists it should be automatically created in UI.
     * Set this to `true` if the checkbox in the UI should not be checked and continue without Content Blocker.
     *
     * @var boolean
     */
    public $shouldUncheckContentBlockerCheckbox;
    /**
     * Set `shouldUncheckContentBlockerCheckbox` with `WhenOneOf` statements.
     *
     * @var string[]
     */
    public $shouldUncheckContentBlockerCheckboxWhenOneOf = [];
    /**
     * Code on opt in.
     *
     * @var string
     */
    public $codeOptIn;
    /**
     * Code on opt in.
     *
     * @var string
     */
    public $codeOptOut;
    /**
     * Code on page load.
     *
     * @var string
     */
    public $codeOnPageLoad;
    /**
     * Tag manager opt-in event name.
     *
     * @var string
     */
    public $tagManagerOptInEventName;
    /**
     * Tag manager opt-out event name.
     *
     * @var string
     */
    public $tagManagerOptOutEventName;
    /**
     * Execute code on opt-in only when consent for tag manager is not given.
     *
     * @var boolean
     */
    public $executeCodeOptInWhenNoTagManagerConsentIsGiven;
    /**
     * Execute code on opt-in only when consent for tag manager is not given.
     *
     * @var boolean
     */
    public $executeCodeOptOutWhenNoTagManagerConsentIsGiven;
    /**
     * If `true` the plugin should try to delete all technical definitions on opt-out.
     *
     * @var boolean
     */
    public $deleteTechnicalDefinitionsAfterOptOut;
    /**
     * Dynamic fields for the code / script fields.
     *
     * @var array
     */
    public $dynamicFields = [];
    /**
     * In which countries does thie service template process data? 2-letter ISO codes.
     *
     * @var string[]
     */
    public $dataProcessingInCountries = [];
    /**
     * When a service processes data to an unsafe country, the user can select from various
     * special treatments to skip the consent via GDPR Article 49.
     *
     * @var string[]
     */
    public $dataProcessingInCountriesSpecialTreatments = [];
    /**
     * Google Consent Mode consent types.
     *
     * @var string[]
     */
    public $googleConsentModeConsentTypes = [];
    /**
     * If `true` the service does not set any cookies but transfers e.g. ip address through a network request.
     *
     * @var boolean
     */
    public $isEmbeddingOnlyExternalResources;
    /**
     * Legal basis.
     *
     * @var string
     */
    public $legalBasis;
    /**
     * Legal basis notice.
     *
     * @var string
     */
    public $legalBasisNotice;
    /**
     * Purpose of the service.
     *
     * @var string
     */
    public $purpose;
    /**
     * Predefined group.
     *
     * @var string
     */
    public $group;
    /**
     * Technical-handling notice.
     *
     * @var string
     */
    public $technicalHandlingNotice;
    /**
     * Create-content-blocker notice.
     *
     * @var string
     */
    public $createContentBlockerNotice;
    /**
     * Group notice.
     *
     * @var string
     */
    public $groupNotice;
    /**
     * Override all properties from an array.
     *
     * @param array $arr
     */
    public function fromArray($arr)
    {
        parent::fromArray($arr);
        if (\is_array($arr)) {
            $this->isProviderCurrentWebsite = \boolval($arr['isProviderCurrentWebsite'] ?? null);
            $this->provider = \is_string($arr['provider'] ?? null) ? $arr['provider'] : '';
            $this->providerContact = \is_array($arr['providerContact'] ?? null) ? $arr['providerContact'] : new stdClass();
            if (\is_array($this->providerContact) && \count($this->providerContact) === 0) {
                $this->providerContact = new stdClass();
            }
            $this->providerNotice = \is_string($arr['providerNotice'] ?? null) ? $arr['providerNotice'] : '';
            $this->providerLegalNoticeUrl = \is_string($arr['providerLegalNoticeUrl'] ?? null) ? $arr['providerLegalNoticeUrl'] : '';
            $this->providerPrivacyPolicyUrl = \is_string($arr['providerPrivacyPolicyUrl'] ?? null) ? $arr['providerPrivacyPolicyUrl'] : '';
            $this->technicalDefinitions = \is_array($arr['technicalDefinitions'] ?? null) ? \array_map(function ($m) {
                // Apply default values (see als `TECHNICAL_DEFINITION_DEFAULTS`)
                $m['duration'] = $m['duration'] ?? 1;
                $m['durationUnit'] = $m['durationUnit'] ?? 'y';
                return $m;
            }, $arr['technicalDefinitions']) : [];
            $this->dynamicFields = \is_array($arr['dynamicFields'] ?? null) ? $arr['dynamicFields'] : [];
            $this->dataProcessingInCountries = \is_array($arr['dataProcessingInCountries'] ?? null) ? $arr['dataProcessingInCountries'] : [];
            $this->dataProcessingInCountriesSpecialTreatments = \is_array($arr['dataProcessingInCountriesSpecialTreatments'] ?? null) ? $arr['dataProcessingInCountriesSpecialTreatments'] : [];
            $this->googleConsentModeConsentTypes = \is_array($arr['googleConsentModeConsentTypes'] ?? null) ? $arr['googleConsentModeConsentTypes'] : [];
            $this->shouldUncheckContentBlockerCheckbox = \boolval($arr['shouldUncheckContentBlockerCheckbox'] ?? null);
            $this->shouldUncheckContentBlockerCheckboxWhenOneOf = \is_array($arr['shouldUncheckContentBlockerCheckboxWhenOneOf'] ?? null) ? $arr['shouldUncheckContentBlockerCheckboxWhenOneOf'] : [];
            $this->codeOptIn = \is_string($arr['codeOptIn'] ?? null) ? $arr['codeOptIn'] : '';
            $this->codeOptOut = \is_string($arr['codeOptOut'] ?? null) ? $arr['codeOptOut'] : '';
            $this->codeOnPageLoad = \is_string($arr['codeOnPageLoad'] ?? null) ? $arr['codeOnPageLoad'] : '';
            $this->tagManagerOptInEventName = \is_string($arr['tagManagerOptInEventName'] ?? null) ? $arr['tagManagerOptInEventName'] : '';
            $this->tagManagerOptOutEventName = \is_string($arr['tagManagerOptOutEventName'] ?? null) ? $arr['tagManagerOptOutEventName'] : '';
            $this->executeCodeOptInWhenNoTagManagerConsentIsGiven = \boolval($arr['shouldUncheckContentBlockerCheckbox'] ?? null);
            $this->executeCodeOptOutWhenNoTagManagerConsentIsGiven = \boolval($arr['shouldUncheckContentBlockerCheckbox'] ?? null);
            $this->deleteTechnicalDefinitionsAfterOptOut = \boolval($arr['deleteTechnicalDefinitionsAfterOptOut'] ?? null);
            $this->isEmbeddingOnlyExternalResources = \boolval($arr['isEmbeddingOnlyExternalResources'] ?? null);
            $this->legalBasis = \is_string($arr['legalBasis'] ?? null) ? $arr['legalBasis'] : '';
            $this->legalBasisNotice = \is_string($arr['legalBasisNotice'] ?? null) ? $arr['legalBasisNotice'] : '';
            $this->purpose = \is_string($arr['purpose'] ?? null) ? $arr['purpose'] : '';
            $this->group = \is_string($arr['group'] ?? null) ? $arr['group'] : '';
            $this->technicalHandlingNotice = \is_string($arr['technicalHandlingNotice'] ?? null) ? $arr['technicalHandlingNotice'] : '';
            $this->createContentBlockerNotice = \is_string($arr['createContentBlockerNotice'] ?? null) ? $arr['createContentBlockerNotice'] : '';
            $this->groupNotice = \is_string($arr['groupNotice'] ?? null) ? $arr['groupNotice'] : '';
        }
    }
}
