<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf;

/**
 * A TCF vendor configuration.
 * @internal
 */
class VendorConfiguration
{
    /**
     * The ID of the content blocker when it got created in a stafeul way.
     *
     * @var int
     */
    private $id = 0;
    /**
     * The vendor ID from the official GVL vendor list.
     *
     * @var int
     */
    private $vendorId = 0;
    /**
     * Restrictive purposes settings.
     *
     * @var array
     */
    private $restrictivePurposes = [];
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
     * The vendor object from the GVL.
     *
     * @var array
     */
    private $vendor;
    /**
     * An array of used declarations (purposes, special purposes, ...) so the best suitable stacks
     * can be calculated from that result.
     *
     * @var array
     */
    private $usedDeclarations = [StackCalculator::DECLARATION_TYPE_PURPOSES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_PURPOSES => [], StackCalculator::DECLARATION_TYPE_FEATURES => [], StackCalculator::DECLARATION_TYPE_SPECIAL_FEATURES => [], StackCalculator::DECLARATION_TYPE_DATA_CATEGORIES => []];
    /**
     * "Correct" the restrictive purposes (e.g. `global` scope does not allow configurations) of a
     * TCF vendor configuration. It fills `$used` with used declarations.
     *
     * @param string $scope Can be `global` or `service-specific`
     */
    public function applyRestrictivePurposes($scope)
    {
        $vendor =& $this->vendor;
        $restrictivePurposes =& $this->restrictivePurposes;
        // "Correct" the restrictive purposes (e.g. `global` scope does not allow configurations)
        if ($scope === 'global') {
            $restrictivePurposes = ['normal' => (object) [], 'special' => (object) []];
        } else {
            $allPurposes = \array_merge($vendor['purposes'] ?? [], $vendor['legIntPurposes'] ?? []);
            $vendor['purposesAfterRestriction'] = $allPurposes;
            $vendor['specialPurposesAfterRestriction'] = $vendor['specialPurposes'];
            foreach ($restrictivePurposes as $type => &$configs) {
                foreach ($configs as $id => &$config) {
                    if (empty($config)) {
                        continue;
                    }
                    // Purposes existence
                    if ($type === 'normal' && !\in_array($id, $allPurposes, \true)) {
                        unset($configs[$id]);
                        continue;
                    }
                    // Special purposes existence (currently never possible!)
                    if ($type === 'special' && !\in_array($id, $vendor['specialPurposes'], \true)) {
                        unset($configs[$id]);
                        continue;
                    }
                    // Legitimate interest
                    if ($type === 'normal') {
                        $isFlexible = \in_array($id, $vendor['flexiblePurposes'], \true);
                        $isLegInt = \in_array($id, $vendor['legIntPurposes'], \true);
                        $expectedLegInt = $isLegInt ? 'yes' : 'no';
                        if (!$isFlexible || $config['legInt'] === $expectedLegInt) {
                            unset($config['legInt']);
                        }
                    }
                    if ($config['enabled'] === \false) {
                        $deleteFrom = $type === 'normal' ? 'purposesAfterRestriction' : 'specialPurposesAfterRestriction';
                        \array_splice($vendor[$deleteFrom], \array_search($id, $vendor[$deleteFrom], \true), 1);
                    }
                }
            }
        }
        // Make sure both arrays are objects to avoid `[]` typings
        $restrictivePurposes['normal'] = (object) ($restrictivePurposes['normal'] ?? []);
        $restrictivePurposes['special'] = (object) ($restrictivePurposes['special'] ?? []);
        // At the moment, special purposes can not be restricted
        unset($restrictivePurposes['special']);
        // Catch up all used (special) purposes and (special) features so we can calculate stacks
        foreach (StackCalculator::DECLARATION_TYPES as $declaration) {
            $this->usedDeclarations[$declaration] = \array_unique($vendor[$declaration . 'AfterRestriction'] ?? $vendor[$declaration] ?? []);
        }
        unset($vendor['purposesAfterRestriction']);
        unset($vendor['specialPurposesAfterRestriction']);
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
    public function getVendorId()
    {
        return $this->vendorId;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRestrictivePurposes()
    {
        return $this->restrictivePurposes;
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
    public function getVendor()
    {
        return $this->vendor;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getUsedDeclarations()
    {
        return $this->usedDeclarations;
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
     * @param int $vendorId
     * @codeCoverageIgnore
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
    }
    /**
     * Setter.
     *
     * @param RestrictivePurposes $restrictivePurposes
     * @codeCoverageIgnore
     */
    public function setRestrictivePurposes($restrictivePurposes)
    {
        $this->restrictivePurposes = $restrictivePurposes;
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
     * @param array $dataProcessingInCountriesSpecialTreatments
     * @codeCoverageIgnore
     */
    public function setDataProcessingInCountriesSpecialTreatments($dataProcessingInCountriesSpecialTreatments)
    {
        $this->dataProcessingInCountriesSpecialTreatments = $dataProcessingInCountriesSpecialTreatments;
    }
    /**
     * Setter.
     *
     * @param array $vendor
     * @codeCoverageIgnore
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['id' => $this->id, 'vendorId' => $this->vendorId, 'restrictivePurposes' => $this->restrictivePurposes, 'dataProcessingInCountries' => $this->dataProcessingInCountries, 'dataProcessingInCountriesSpecialTreatments' => $this->dataProcessingInCountriesSpecialTreatments];
    }
    /**
     * Generate a `VendorConfiguration` object from an array.
     *
     * @param array $data
     * @param array $vendor The used vendor object for this vendor configuration
     * @return self
     */
    public static function fromJson($data, $vendor)
    {
        $instance = new self();
        $instance->setId($data['id'] ?? 0);
        $instance->setVendorId($data['vendorId'] ?? 0);
        $instance->setRestrictivePurposes($data['restrictivePurposes'] ?? []);
        $instance->setDataProcessingInCountries($data['dataProcessingInCountries'] ?? []);
        $instance->setDataProcessingInCountriesSpecialTreatments($data['dataProcessingInCountriesSpecialTreatments'] ?? []);
        $instance->setVendor($vendor);
        return $instance;
    }
}
