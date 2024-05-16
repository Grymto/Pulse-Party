<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractConsent;
/**
 * A service.
 * @internal
 */
class Service
{
    const LEGAL_BASIS_CONSENT = 'consent';
    const LEGAL_BASIS_LEGITIMATE_INTEREST = 'legitimate-interest';
    const LEGAL_BASIS_LEGAL_REQUIREMENT = 'legal-requirement';
    const LEGAL_BASIS = [self::LEGAL_BASIS_CONSENT, self::LEGAL_BASIS_LEGAL_REQUIREMENT, self::LEGAL_BASIS_LEGITIMATE_INTEREST];
    const SPECIAL_TREATMENT_PROVIDER_IS_SELF_CERTIFIED_TRANS_ATLANTIC_DATA_PRIVACY_FRAMEWORK = 'provider-is-self-certified-trans-atlantic-data-privacy-framework';
    const SPECIAL_TREATMENT_STANDARD_CONTRACTUAL_CLAUSES = 'standard-contractual-clauses';
    /**
     * The ID of the service when it got created in a stafeul way.
     *
     * @var int
     */
    private $id = 0;
    /**
     * Service name.
     *
     * @var string
     */
    private $name = '';
    /**
     * Service purpose.
     *
     * @var string
     */
    private $purpose = '';
    /**
     * Provider contact.
     *
     * @var ProviderContact
     */
    private $providerContact;
    /**
     * Is the current website the provider of the service?
     *
     * @var boolean
     */
    private $isProviderCurrentWebsite = \false;
    /**
     * Provider.
     *
     * @var string
     */
    private $provider = '';
    /**
     * Unique name.
     *
     * @var string
     */
    private $uniqueName = '';
    /**
     * Is the service embedding only external resources and no cookies?
     *
     * @var boolean
     */
    private $isEmbeddingOnlyExternalResources = \false;
    /**
     * The legal basis of the service.
     *
     * @var string
     */
    private $legalBasis = self::LEGAL_BASIS_CONSENT;
    /**
     * Iso 3166-1 alpha 2 countries in which the service is processing data.
     *
     * @var string[]
     */
    private $dataProcessingInCountries = [];
    /**
     * Are there special treatments when processing data in unsafe countries?
     *
     * @var string[]
     */
    private $dataProcessingInCountriesSpecialTreatments = [];
    /**
     * The technical definitions for the cookies used by the service.
     *
     * @var TechnicalDefinitions[]
     */
    private $technicalDefinitions = [];
    /**
     * List of code dynamics applied to the embed scripts.
     *
     * @var array
     */
    private $codeDynamics = [];
    /**
     * Provider privacy policy URL.
     *
     * @var string
     */
    private $providerPrivacyPolicyUrl = '';
    /**
     * Provider legal notice URL.
     *
     * @var string
     */
    private $providerLegalNoticeUrl = '';
    /**
     * If tag manager handling is active the opt-in event name.
     *
     * @var string
     */
    private $tagManagerOptInEventName = '';
    /**
     * If tag manager handling is active the opt-out event name.
     *
     * @var string
     */
    private $tagManagerOptOutEventName = '';
    /**
     * If Google Consent Mode is active the required consent types.
     *
     * @var string[]
     */
    private $googleConsentModeConsentTypes = [];
    /**
     * The HTML code on opt-in.
     *
     * @var string
     */
    private $codeOptIn = '';
    /**
     * Execute the code on opt-in only when no tag manager consent is given.
     *
     * @var boolean
     */
    private $executeCodeOptInWhenNoTagManagerConsentIsGiven = \false;
    /**
     * The HTML code on opt-out.
     *
     * @var string
     */
    private $codeOptOut;
    /**
     * Execute the code on opt-out only when no tag manager consent is given.
     *
     * @var boolean
     */
    private $executeCodeOptOutWhenNoTagManagerConsentIsGiven = \false;
    /**
     * Delete the technical definitions after opt-out.
     *
     * @var boolean
     */
    private $deleteTechnicalDefinitionsAfterOptOut = \false;
    /**
     * The HTML code on page load.
     *
     * @var string
     */
    private $codeOnPageLoad = '';
    /**
     * The used preset ID from the service cloud.
     *
     * @var string
     */
    private $presetId = '';
    /**
     * Takes a HTML string and apply code dynamics to it.
     *
     * @param string $html
     */
    public function applyDynamicsToHtml($html)
    {
        return \preg_replace_callback('/{{([A-Za-z0-9_]+)}}/m', function ($m) {
            return $this->getCodeDynamics()[$m[1]] ?? $m[0];
        }, $html);
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPurpose()
    {
        return $this->purpose;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getProviderContact()
    {
        return $this->providerContact;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isProviderCurrentWebsite()
    {
        return $this->isProviderCurrentWebsite;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getProvider()
    {
        return $this->provider;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isEmbeddingOnlyExternalResources()
    {
        return $this->isEmbeddingOnlyExternalResources;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLegalBasis()
    {
        return $this->legalBasis;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDataProcessingInCountries()
    {
        return $this->dataProcessingInCountries;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDataProcessingInCountriesSpecialTreatments()
    {
        return $this->dataProcessingInCountriesSpecialTreatments;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTechnicalDefinitions()
    {
        return $this->technicalDefinitions;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCodeDynamics()
    {
        return $this->codeDynamics;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getProviderPrivacyPolicyUrl()
    {
        return $this->providerPrivacyPolicyUrl;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getProviderLegalNoticeUrl()
    {
        return $this->providerLegalNoticeUrl;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTagManagerOptInEventName()
    {
        return $this->tagManagerOptInEventName;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTagManagerOptOutEventName()
    {
        return $this->tagManagerOptOutEventName;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getGoogleConsentModeConsentTypes()
    {
        return $this->googleConsentModeConsentTypes;
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
    public function getExecuteCodeOptInWhenNoTagManagerConsentIsGiven()
    {
        return $this->executeCodeOptInWhenNoTagManagerConsentIsGiven;
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
    public function getExecuteCodeOptOutWhenNoTagManagerConsentIsGiven()
    {
        return $this->executeCodeOptOutWhenNoTagManagerConsentIsGiven;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDeleteTechnicalDefinitionsAfterOptOut()
    {
        return $this->deleteTechnicalDefinitionsAfterOptOut;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCodeOnPageLoad()
    {
        return $this->codeOnPageLoad;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPresetId()
    {
        return $this->presetId;
    }
    /**
     * Setter.
     *
     * @param int $id
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Setter.
     *
     * @param string $name
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * Setter.
     *
     * @param string $purpose
     * @codeCoverageIgnore
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }
    /**
     * Setter.
     *
     * @param ProviderContact $providerContact
     * @codeCoverageIgnore
     */
    public function setProviderContact($providerContact)
    {
        $this->providerContact = $providerContact;
    }
    /**
     * Setter.
     *
     * @param boolean $isProviderCurrentWebsite
     * @codeCoverageIgnore
     */
    public function setIsProviderCurrentWebsite($isProviderCurrentWebsite)
    {
        $this->isProviderCurrentWebsite = $isProviderCurrentWebsite;
    }
    /**
     * Setter.
     *
     * @param string $provider
     * @codeCoverageIgnore
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }
    /**
     * Setter.
     *
     * @param string $uniqueName
     * @codeCoverageIgnore
     */
    public function setUniqueName($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }
    /**
     * Setter.
     *
     * @param boolean $isEmbeddingOnlyExternalResources
     * @codeCoverageIgnore
     */
    public function setIsEmbeddingOnlyExternalResources($isEmbeddingOnlyExternalResources)
    {
        $this->isEmbeddingOnlyExternalResources = $isEmbeddingOnlyExternalResources;
    }
    /**
     * Setter.
     *
     * @param string $legalBasis
     * @codeCoverageIgnore
     */
    public function setLegalBasis($legalBasis)
    {
        $this->legalBasis = $legalBasis;
    }
    /**
     * Setter.
     *
     * @param string[] $dataProcessingInCountries
     * @codeCoverageIgnore
     */
    public function setDataProcessingInCountries($dataProcessingInCountries)
    {
        $this->dataProcessingInCountries = $dataProcessingInCountries;
    }
    /**
     * Setter.
     *
     * @param string[] $dataProcessingInCountriesSpecialTreatments
     * @codeCoverageIgnore
     */
    public function setDataProcessingInCountriesSpecialTreatments($dataProcessingInCountriesSpecialTreatments)
    {
        $this->dataProcessingInCountriesSpecialTreatments = $dataProcessingInCountriesSpecialTreatments;
    }
    /**
     * Setter.
     *
     * @param TechnicalDefinitions[] $technicalDefinitions
     * @codeCoverageIgnore
     */
    public function setTechnicalDefinitions($technicalDefinitions)
    {
        $this->technicalDefinitions = $technicalDefinitions;
    }
    /**
     * Setter.
     *
     * @param array $codeDynamics
     * @codeCoverageIgnore
     */
    public function setCodeDynamics($codeDynamics)
    {
        $this->codeDynamics = $codeDynamics;
    }
    /**
     * Setter.
     *
     * @param string $providerPrivacyPolicyUrl
     * @codeCoverageIgnore
     */
    public function setProviderPrivacyPolicyUrl($providerPrivacyPolicyUrl)
    {
        $this->providerPrivacyPolicyUrl = $providerPrivacyPolicyUrl;
    }
    /**
     * Setter.
     *
     * @param string $providerLegalNoticeUrl
     * @codeCoverageIgnore
     */
    public function setProviderLegalNoticeUrl($providerLegalNoticeUrl)
    {
        $this->providerLegalNoticeUrl = $providerLegalNoticeUrl;
    }
    /**
     * Setter.
     *
     * @param string $tagManagerOptInEventName
     * @codeCoverageIgnore
     */
    public function setTagManagerOptInEventName($tagManagerOptInEventName)
    {
        $this->tagManagerOptInEventName = $tagManagerOptInEventName;
    }
    /**
     * Setter.
     *
     * @param string $tagManagerOptOutEventName
     * @codeCoverageIgnore
     */
    public function setTagManagerOptOutEventName($tagManagerOptOutEventName)
    {
        $this->tagManagerOptOutEventName = $tagManagerOptOutEventName;
    }
    /**
     * Setter.
     *
     * @param string[] $googleConsentModeConsentTypes
     * @codeCoverageIgnore
     */
    public function setGoogleConsentModeConsentTypes($googleConsentModeConsentTypes)
    {
        $this->googleConsentModeConsentTypes = $googleConsentModeConsentTypes;
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
     * @param boolean $executeCodeOptInWhenNoTagManagerConsentIsGiven
     * @codeCoverageIgnore
     */
    public function setExecuteCodeOptInWhenNoTagManagerConsentIsGiven($executeCodeOptInWhenNoTagManagerConsentIsGiven)
    {
        $this->executeCodeOptInWhenNoTagManagerConsentIsGiven = $executeCodeOptInWhenNoTagManagerConsentIsGiven;
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
     * Setter.
     *
     * @param boolean $executeCodeOptOutWhenNoTagManagerConsentIsGiven
     * @codeCoverageIgnore
     */
    public function setExecuteCodeOptOutWhenNoTagManagerConsentIsGiven($executeCodeOptOutWhenNoTagManagerConsentIsGiven)
    {
        $this->executeCodeOptOutWhenNoTagManagerConsentIsGiven = $executeCodeOptOutWhenNoTagManagerConsentIsGiven;
    }
    /**
     * Setter.
     *
     * @param boolean $deleteTechnicalDefinitionsAfterOptOut
     * @codeCoverageIgnore
     */
    public function setDeleteTechnicalDefinitionsAfterOptOut($deleteTechnicalDefinitionsAfterOptOut)
    {
        $this->deleteTechnicalDefinitionsAfterOptOut = $deleteTechnicalDefinitionsAfterOptOut;
    }
    /**
     * Setter.
     *
     * @param string $codeOnPageLoad
     * @codeCoverageIgnore
     */
    public function setCodeOnPageLoad($codeOnPageLoad)
    {
        $this->codeOnPageLoad = $codeOnPageLoad;
    }
    /**
     * Setter.
     *
     * @param string $presetId
     * @codeCoverageIgnore
     */
    public function setPresetId($presetId)
    {
        $this->presetId = $presetId;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['id' => $this->id, 'name' => $this->name, 'purpose' => $this->purpose, 'providerContact' => $this->providerContact->toJson(), 'isProviderCurrentWebsite' => $this->isProviderCurrentWebsite, 'provider' => $this->provider, 'uniqueName' => $this->uniqueName, 'isEmbeddingOnlyExternalResources' => $this->isEmbeddingOnlyExternalResources, 'legalBasis' => $this->legalBasis, 'dataProcessingInCountries' => $this->dataProcessingInCountries, 'dataProcessingInCountriesSpecialTreatments' => $this->dataProcessingInCountriesSpecialTreatments, 'technicalDefinitions' => \array_map(function ($technicalDefinition) {
            return $technicalDefinition->toJson();
        }, $this->technicalDefinitions), 'codeDynamics' => $this->codeDynamics, 'providerPrivacyPolicyUrl' => $this->providerPrivacyPolicyUrl, 'providerLegalNoticeUrl' => $this->providerLegalNoticeUrl, 'tagManagerOptInEventName' => $this->tagManagerOptInEventName, 'tagManagerOptOutEventName' => $this->tagManagerOptOutEventName, 'googleConsentModeConsentTypes' => $this->googleConsentModeConsentTypes, 'codeOptIn' => $this->codeOptIn, 'executeCodeOptInWhenNoTagManagerConsentIsGiven' => $this->executeCodeOptInWhenNoTagManagerConsentIsGiven, 'codeOptOut' => $this->codeOptOut, 'executeCodeOptOutWhenNoTagManagerConsentIsGiven' => $this->executeCodeOptOutWhenNoTagManagerConsentIsGiven, 'deleteTechnicalDefinitionsAfterOptOut' => $this->deleteTechnicalDefinitionsAfterOptOut, 'codeOnPageLoad' => $this->codeOnPageLoad, 'presetId' => $this->presetId];
    }
    /**
     * Generate a `Service` object from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromJson($data)
    {
        $instance = new self();
        $instance->setId($data['id'] ?? 0);
        $instance->setName($data['name'] ?? '');
        $instance->setPurpose($data['purpose'] ?? '');
        $instance->setProviderContact(ProviderContact::fromJson($data['providerContact'] ?? []));
        $instance->setIsProviderCurrentWebsite($data['isProviderCurrentWebsite'] ?? \false);
        $instance->setProvider($data['provider'] ?? '');
        $instance->setUniqueName($data['uniqueName'] ?? '');
        $instance->setIsEmbeddingOnlyExternalResources($data['isEmbeddingOnlyExternalResources'] ?? \false);
        $instance->setLegalBasis($data['legalBasis'] ?? self::LEGAL_BASIS_CONSENT);
        $instance->setDataProcessingInCountries($data['dataProcessingInCountries'] ?? []);
        $instance->setDataProcessingInCountriesSpecialTreatments($data['dataProcessingInCountriesSpecialTreatments'] ?? []);
        $instance->setTechnicalDefinitions(\array_map(function ($data) {
            return TechnicalDefinitions::fromJson($data);
        }, $data['technicalDefinitions'] ?? []));
        $instance->setCodeDynamics($data['codeDynamics'] ?? []);
        $instance->setProviderPrivacyPolicyUrl($data['providerPrivacyPolicyUrl'] ?? '');
        $instance->setProviderLegalNoticeUrl($data['providerLegalNoticeUrl'] ?? '');
        $instance->setTagManagerOptInEventName($data['tagManagerOptInEventName'] ?? '');
        $instance->setTagManagerOptOutEventName($data['tagManagerOptOutEventName'] ?? '');
        $instance->setGoogleConsentModeConsentTypes($data['googleConsentModeConsentTypes'] ?? []);
        $instance->setCodeOptIn($data['codeOptIn'] ?? '');
        $instance->setExecuteCodeOptInWhenNoTagManagerConsentIsGiven($data['executeCodeOptInWhenNoTagManagerConsentIsGiven'] ?? \false);
        $instance->setCodeOptOut($data['codeOptOut'] ?? '');
        $instance->setExecuteCodeOptOutWhenNoTagManagerConsentIsGiven($data['executeCodeOptOutWhenNoTagManagerConsentIsGiven'] ?? \false);
        $instance->setDeleteTechnicalDefinitionsAfterOptOut($data['deleteTechnicalDefinitionsAfterOptOut'] ?? \false);
        $instance->setCodeOnPageLoad($data['codeOnPageLoad'] ?? '');
        $instance->setPresetId($data['presetId'] ?? '');
        return $instance;
    }
    /**
     * Calculate unsafe countries from a given array of countries.
     *
     * See also `frontend-packages/react-cookie-banner/src/components/common/groups/cookiePropertyList.tsx` the
     * `calculateUnsafeCountries` method.
     *
     * @param string[] $countries
     * @param string[] $specialTreatments
     * @return string[]
     */
    public static function calculateUnsafeCountries($countries, $specialTreatments = [])
    {
        if (\in_array(self::SPECIAL_TREATMENT_STANDARD_CONTRACTUAL_CLAUSES, $specialTreatments, \true)) {
            return [];
        }
        $safeCountries = [];
        foreach (AbstractConsent::PREDEFINED_DATA_PROCESSING_IN_SAFE_COUNTRIES_LISTS as $listCountries) {
            $safeCountries = \array_merge($safeCountries, $listCountries);
        }
        $unsafeCountries = [];
        // Check if one service country is not safe
        foreach ($countries as $country) {
            if (!\in_array($country, $safeCountries, \true) || $country === 'US' && !\in_array(self::SPECIAL_TREATMENT_PROVIDER_IS_SELF_CERTIFIED_TRANS_ATLANTIC_DATA_PRIVACY_FRAMEWORK, $specialTreatments, \true)) {
                $unsafeCountries[] = $country;
            }
        }
        return $unsafeCountries;
    }
}
