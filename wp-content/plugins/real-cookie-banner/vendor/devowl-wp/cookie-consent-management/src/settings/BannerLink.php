<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

/**
 * Banner links configured in settings.
 * @internal
 */
class BannerLink
{
    const PAGE_TYPE_LEGAL_NOTICE = 'legalNotice';
    const PAGE_TYPE_PRIVACY_POLICY = 'privacyPolicy';
    const PAGE_TYPE_GENERAL_TERMS_AND_CONDITIONS = 'gtc';
    const PAGE_TYPE_TERMS_OF_USE = 'tos';
    const PAGE_TYPE_CANCELLATION_POLICY = 'cancellationPolicy';
    const PAGE_TYPE_COOKIE_POLICY = 'cookiePolicy';
    const PAGE_TYPE_DATA_PROCESSING_AGREEMENT = 'dpa';
    const PAGE_TYPE_DISPUTE_RESOLUTION = 'disputeResolution';
    const PAGE_TYPE_OTHER = 'other';
    /**
     * ID.
     *
     * @var int
     */
    private $id = 0;
    /**
     * Label.
     *
     * @var string
     */
    private $label = '';
    /**
     * Page type.
     *
     * @var string
     */
    private $pageType = self::PAGE_TYPE_OTHER;
    /**
     * URL.
     *
     * @var string
     */
    private $url = '';
    /**
     * Hide the cookie banner on that site?
     *
     * @var boolean
     */
    private $hideCookieBanner = \false;
    /**
     * Open the link in a new window.
     *
     * @var boolean
     */
    private $isTargetBlank = \false;
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
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPageType()
    {
        return $this->pageType;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isHideCookieBanner()
    {
        return $this->hideCookieBanner;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isTargetBlank()
    {
        return $this->isTargetBlank;
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
     * @param string $label
     * @codeCoverageIgnore
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    /**
     * Setter.
     *
     * @param string $pageType
     * @codeCoverageIgnore
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;
    }
    /**
     * Setter.
     *
     * @param string $url
     * @codeCoverageIgnore
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    /**
     * Setter.
     *
     * @param boolean $hideCookieBanner
     * @codeCoverageIgnore
     */
    public function setHideCookieBanner($hideCookieBanner)
    {
        $this->hideCookieBanner = $hideCookieBanner;
    }
    /**
     * Setter.
     *
     * @param boolean $isTargetBlank
     * @codeCoverageIgnore
     */
    public function setIsTargetBlank($isTargetBlank)
    {
        $this->isTargetBlank = $isTargetBlank;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['id' => $this->id, 'label' => $this->label, 'pageType' => $this->pageType, 'url' => $this->url, 'hideCookieBanner' => $this->hideCookieBanner, 'isTargetBlank' => $this->isTargetBlank];
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
        $instance->setLabel($data['label'] ?? '');
        $instance->setPageType($data['pageType'] ?? self::PAGE_TYPE_OTHER);
        $instance->setUrl($data['url'] ?? '');
        $instance->setHideCookieBanner($data['hideCookieBanner'] ?? \false);
        $instance->setIsTargetBlank($data['isTargetBlank'] ?? \false);
        return $instance;
    }
}
