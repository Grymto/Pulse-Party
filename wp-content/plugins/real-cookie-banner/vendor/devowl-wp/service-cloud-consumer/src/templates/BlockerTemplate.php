<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates;

/**
 * Blocker template.
 * @internal
 */
class BlockerTemplate extends AbstractTemplate
{
    /**
     * Connected services as identifier.
     *
     * @var string[]
     */
    public $serviceTemplateIdentifiers = [];
    /**
     * Connected TCF vendor IDs.
     *
     * @var int[]
     */
    public $tcfVendorIds = [];
    /**
     * Rule groups definitions.
     *
     * See `api-packages/api-real-cookie-banner/src/entity/template/content-blocker/content-blocker.ts`.
     *
     * @var array
     */
    public $ruleGroups = [];
    /**
     * Rule definitions.
     *
     * See `api-packages/api-real-cookie-banner/src/entity/template/content-blocker/content-blocker.ts`.
     *
     * @var array
     */
    public $rules = [];
    /**
     * Rule notice.
     *
     * @var string
     */
    public $ruleNotice;
    /**
     * Is visual?
     *
     * @var boolean
     */
    public $isVisual;
    /**
     * Should the visual be rendered in dark mode?
     *
     * @var boolean
     */
    public $isVisualDarkMode;
    /**
     * Blurry the visual content blocker background image.
     *
     * @var number
     */
    public $visualBlur;
    /**
     * Should the visual be forced to render also for e.g. script tags?
     *
     * @var boolean
     */
    public $shouldForceToShowVisual;
    /**
     * Additional description for the content blocker.
     *
     * @var string
     */
    public $description;
    /**
     * Visual type (default, wrapped, hero).
     *
     * See `api-packages/api-real-cookie-banner/src/entity/template/content-blocker/content-blocker.ts`.
     *
     * @var string
     */
    public $visualType;
    /**
     * Visual content type (audio-player, video-player, ...).
     *
     * See `api-packages/api-real-cookie-banner/src/entity/template/content-blocker/content-blocker.ts`.
     *
     * @var string
     */
    public $visualContentType;
    /**
     * Visual hero button text.
     *
     * @var string
     */
    public $visualHeroButtonText;
    /**
     * Override all properties from an array.
     *
     * @param array $arr
     */
    public function fromArray($arr)
    {
        parent::fromArray($arr);
        if (\is_array($arr)) {
            $this->serviceTemplateIdentifiers = \is_array($arr['serviceTemplateIdentifiers'] ?? null) ? $arr['serviceTemplateIdentifiers'] : [];
            $this->tcfVendorIds = \is_array($arr['tcfVendorIds'] ?? null) ? $arr['tcfVendorIds'] : [];
            $this->ruleGroups = \is_array($arr['ruleGroups'] ?? null) ? $arr['ruleGroups'] : [];
            $this->rules = \is_array($arr['rules'] ?? null) ? $arr['rules'] : [];
            $this->ruleNotice = \is_string($arr['ruleNotice'] ?? null) ? $arr['ruleNotice'] : '';
            $this->isVisual = \boolval($arr['isVisual'] ?? null);
            $this->isVisualDarkMode = \boolval($arr['isVisualDarkMode'] ?? null);
            $this->visualBlur = \is_numeric($arr['visualBlur'] ?? null) ? \intval($arr['visualBlur']) : 0;
            $this->shouldForceToShowVisual = \boolval($arr['shouldForceToShowVisual'] ?? null);
            $this->description = \is_string($arr['description'] ?? null) ? $arr['description'] : '';
            $this->visualType = \is_string($arr['visualType'] ?? null) ? $arr['visualType'] : 'default';
            $this->visualContentType = \is_string($arr['visualContentType'] ?? null) ? $arr['visualContentType'] : '';
            $this->visualHeroButtonText = \is_string($arr['visualHeroButtonText'] ?? null) ? $arr['visualHeroButtonText'] : '';
        }
    }
}
