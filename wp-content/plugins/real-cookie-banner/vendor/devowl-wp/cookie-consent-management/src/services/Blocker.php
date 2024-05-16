<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services;

/**
 * A content blocker definition for a service.
 * @internal
 */
class Blocker
{
    const DEFAULT_CRITERIA = self::CRITERIA_SERVICES;
    const CRITERIA_SERVICES = 'services';
    const CRITERIA_TCF_VENDORS = 'tcfVendors';
    const CRITERIAS = [self::CRITERIA_SERVICES, self::CRITERIA_TCF_VENDORS];
    const DEFAULT_VISUAL_TYPE = self::VISUAL_TYPE_DEFAULT;
    const VISUAL_TYPE_DEFAULT = 'default';
    const VISUAL_TYPE_WRAPPED = 'wrapped';
    const VISUAL_TYPE_HERO = 'hero';
    const VISUAL_TYPES = [self::VISUAL_TYPE_DEFAULT, self::VISUAL_TYPE_WRAPPED, self::VISUAL_TYPE_HERO];
    const DEFAULT_VISUAL_CONTENT_TYPE = self::VISUAL_CONTENT_TYPE_GENERIC;
    const VISUAL_CONTENT_TYPE_MAP = 'map';
    const VISUAL_CONTENT_TYPE_VIDEO_PLAYER = 'video-player';
    const VISUAL_CONTENT_TYPE_AUDIO_PLAYER = 'audio-player';
    const VISUAL_CONTENT_TYPE_FEED_TEXT = 'feed-text';
    const VISUAL_CONTENT_TYPE_FEED_VIDEO = 'feed-video';
    const VISUAL_CONTENT_TYPE_GENERIC = 'generic';
    const VISUAL_CONTENT_TYPES = [self::VISUAL_CONTENT_TYPE_MAP, self::VISUAL_CONTENT_TYPE_VIDEO_PLAYER, self::VISUAL_CONTENT_TYPE_AUDIO_PLAYER, self::VISUAL_CONTENT_TYPE_FEED_TEXT, self::VISUAL_CONTENT_TYPE_FEED_VIDEO, self::VISUAL_CONTENT_TYPE_GENERIC];
    /**
     * The ID of the content blocker when it got created in a stafeul way.
     *
     * @var int
     */
    private $id = 0;
    /**
     * The name.
     *
     * @var string
     */
    private $name = '';
    /**
     * The description.
     *
     * @var string
     */
    private $description = '';
    /**
     * A list of rules.
     *
     * @var string[]
     */
    private $rules = [];
    /**
     * The criterium which resolves the content blocker (e.g. `services` or `tcfVendors`).
     *
     * @var string
     */
    private $criteria = self::DEFAULT_CRITERIA;
    /**
     * If `$criteria` is `tcfVendors`, this is the list of needed vendor IDs.
     *
     * @var int[]
     */
    private $tcfVendors = [];
    /**
     * If `$criteria` is `tcfVendors`, this is the list of needed purposes.
     *
     * @var int[]
     */
    private $tcfPurposes = [];
    /**
     * If `$criteria` is `services`, this is the list of needed `Services::$id`'s.
     *
     * @var int[]
     */
    private $services = [];
    /**
     * Is the content blocker visual?
     *
     * @var boolean
     */
    private $isVisual = \false;
    /**
     * Can be `default`, `wrapped` or `hero`.
     *
     * @var string
     */
    private $visualType = self::DEFAULT_VISUAL_TYPE;
    /**
     * The conntected visual media ID when the visual type needs a background image.
     *
     * @var string|int
     */
    private $visualMediaThumbnail = 0;
    /**
     * The visual content type (e.g. `map` or `video-player`).
     *
     * @var string
     */
    private $visualContentType = self::DEFAULT_VISUAL_CONTENT_TYPE;
    /**
     * Apply dark mode to the visual content blocker.
     *
     * @var boolean
     */
    private $isVisualDarkMode = \false;
    /**
     * Blur the background image by percentage (e.g. `80`).
     *
     * @var int
     */
    private $visualBlur = 0;
    /**
     * Should a background image automatically be downloaded from the blocked URL?
     *
     * @var boolean
     */
    private $visualDownloadThumbnail = \false;
    /**
     * The button text for the `hero` content blocker.
     *
     * @var string
     */
    private $visualHeroButtonText = '';
    /**
     * Force to show the visual independent of some rules which could potentially disable the visual rendering.
     * For example, a visual content blocker is never rendered for a `<script` tag.
     *
     * @var boolean
     */
    private $shouldForceToShowVisual = \false;
    /**
     * The used preset ID from the service cloud.
     *
     * @var string
     */
    private $presetId = '';
    /**
     * See `VisualThumbnail`.
     *
     * @var VisualThumbnail
     */
    private $visualThumbnail;
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
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRules()
    {
        return $this->rules;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCriteria()
    {
        return $this->criteria;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTcfVendors()
    {
        return $this->tcfVendors;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTcfPurposes()
    {
        return $this->tcfPurposes;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getServices()
    {
        return $this->services;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isVisual()
    {
        return $this->isVisual;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualType()
    {
        return $this->visualType;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualMediaThumbnail()
    {
        return $this->visualMediaThumbnail;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualContentType()
    {
        return $this->visualContentType;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isVisualDarkMode()
    {
        return $this->isVisualDarkMode;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualBlur()
    {
        return $this->visualBlur;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualDownloadThumbnail()
    {
        return $this->visualDownloadThumbnail;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualHeroButtonText()
    {
        return $this->visualHeroButtonText;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getShouldForceToShowVisual()
    {
        return $this->shouldForceToShowVisual;
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
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVisualThumbnail()
    {
        return $this->visualThumbnail;
    }
    /**
     * Setter.
     *
     * @param int $id
     * @codecoverageignore
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Setter.
     *
     * @param string $name
     * @codecoverageignore
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * Setter.
     *
     * @param string $description
     * @codecoverageignore
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * Setter.
     *
     * @param string[] $rules
     * @codecoverageignore
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }
    /**
     * Setter.
     *
     * @param string $criteria
     * @codecoverageignore
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }
    /**
     * Setter.
     *
     * @param int[] $tcfVendors
     * @codecoverageignore
     */
    public function setTcfVendors($tcfVendors)
    {
        $this->tcfVendors = $tcfVendors;
    }
    /**
     * Setter.
     *
     * @param int[] $tcfPurposes
     * @codecoverageignore
     */
    public function setTcfPurposes($tcfPurposes)
    {
        $this->tcfPurposes = $tcfPurposes;
    }
    /**
     * Setter.
     *
     * @param int[] $services
     * @codecoverageignore
     */
    public function setServices($services)
    {
        $this->services = $services;
    }
    /**
     * Setter.
     *
     * @param boolean $isVisual
     * @codecoverageignore
     */
    public function setIsVisual($isVisual)
    {
        $this->isVisual = $isVisual;
    }
    /**
     * Setter.
     *
     * @param string $visualType
     * @codecoverageignore
     */
    public function setVisualType($visualType)
    {
        $this->visualType = $visualType;
    }
    /**
     * Setter.
     *
     * @param string|int $visualMediaThumbnail
     * @codecoverageignore
     */
    public function setVisualMediaThumbnail($visualMediaThumbnail)
    {
        $this->visualMediaThumbnail = $visualMediaThumbnail;
    }
    /**
     * Setter.
     *
     * @param string $visualContentType
     * @codecoverageignore
     */
    public function setVisualContentType($visualContentType)
    {
        $this->visualContentType = $visualContentType;
    }
    /**
     * Setter.
     *
     * @param boolean $isVisualDarkMode
     * @codecoverageignore
     */
    public function setIsVisualDarkMode($isVisualDarkMode)
    {
        $this->isVisualDarkMode = $isVisualDarkMode;
    }
    /**
     * Setter.
     *
     * @param int $visualBlur
     * @codecoverageignore
     */
    public function setVisualBlur($visualBlur)
    {
        $this->visualBlur = $visualBlur;
    }
    /**
     * Setter.
     *
     * @param boolean $visualDownloadThumbnail
     * @codecoverageignore
     */
    public function setVisualDownloadThumbnail($visualDownloadThumbnail)
    {
        $this->visualDownloadThumbnail = $visualDownloadThumbnail;
    }
    /**
     * Setter.
     *
     * @param string $visualHeroButtonText
     * @codecoverageignore
     */
    public function setVisualHeroButtonText($visualHeroButtonText)
    {
        $this->visualHeroButtonText = $visualHeroButtonText;
    }
    /**
     * Setter.
     *
     * @param boolean $shouldForceToShowVisual
     * @codecoverageignore
     */
    public function setShouldForceToShowVisual($shouldForceToShowVisual)
    {
        $this->shouldForceToShowVisual = $shouldForceToShowVisual;
    }
    /**
     * Setter.
     *
     * @param string $presetId
     * @codecoverageignore
     */
    public function setPresetId($presetId)
    {
        $this->presetId = $presetId;
    }
    /**
     * Setter.
     *
     * @param VisualThumbnail $visualThumbnail
     * @codecoverageignore
     */
    public function setVisualThumbnail($visualThumbnail)
    {
        $this->visualThumbnail = $visualThumbnail;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'rules' => $this->rules, 'criteria' => $this->criteria, 'tcfVendors' => $this->tcfVendors, 'tcfPurposes' => $this->tcfPurposes, 'services' => $this->services, 'isVisual' => $this->isVisual, 'visualType' => $this->visualType, 'visualMediaThumbnail' => $this->visualMediaThumbnail, 'visualContentType' => $this->visualContentType, 'isVisualDarkMode' => $this->isVisualDarkMode, 'visualBlur' => $this->visualBlur, 'visualDownloadThumbnail' => $this->visualDownloadThumbnail, 'visualHeroButtonText' => $this->visualHeroButtonText, 'shouldForceToShowVisual' => $this->shouldForceToShowVisual, 'presetId' => $this->presetId, 'visualThumbnail' => $this->visualThumbnail ? $this->visualThumbnail->toJson() : null];
    }
    /**
     * Generate a `Blocker` object from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromJson($data)
    {
        $instance = new self();
        $instance->setId($data['id'] ?? 0);
        $instance->setName($data['name'] ?? '');
        $instance->setDescription($data['description'] ?? '');
        $instance->setRules($data['rules'] ?? []);
        $instance->setCriteria($data['criteria'] ?? self::DEFAULT_CRITERIA);
        $instance->setTcfVendors($data['tcfVendors'] ?? []);
        $instance->setTcfPurposes($data['tcfPurposes'] ?? []);
        $instance->setServices($data['services'] ?? []);
        $instance->setIsVisual($data['isVisual'] ?? \false);
        $instance->setVisualType($data['visualType'] ?? self::DEFAULT_VISUAL_TYPE);
        $instance->setVisualMediaThumbnail($data['visualMediaThumbnail'] ?? 0);
        $instance->setVisualContentType($data['visualContentType'] ?? self::DEFAULT_VISUAL_CONTENT_TYPE);
        $instance->setIsVisualDarkMode($data['isVisualDarkMode'] ?? \false);
        $instance->setVisualBlur($data['visualBlur'] ?? 0);
        $instance->setVisualDownloadThumbnail($data['visualDownloadThumbnail'] ?? \false);
        $instance->setVisualHeroButtonText($data['visualHeroButtonText'] ?? '');
        $instance->setShouldForceToShowVisual($data['shouldForceToShowVisual'] ?? \false);
        $instance->setPresetId($data['presetId'] ?? '');
        $instance->setVisualThumbnail(isset($data['visualThumbnail']) ? VisualThumbnail::fromJson($data['visualThumbnail']) : null);
        return $instance;
    }
}
