<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services;

/**
 * A visual thumbnail for a hero content blocker.
 * @internal
 */
class VisualThumbnail
{
    /**
     * The URL to the thumbnail.
     *
     * @var string
     */
    private $url = '';
    /**
     * The width of the thumbnail
     *
     * @var int
     */
    private $width = 0;
    /**
     * The height of the thumbnail
     *
     * @var int
     */
    private $height = 0;
    /**
     * Can be `overlay` and `title`.
     *
     * @var string[]
     */
    private $hide = [];
    /**
     * Can be `center` or `top`.
     *
     * @var string
     */
    private $titleType = 'center';
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
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getHide()
    {
        return $this->hide;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTitleType()
    {
        return $this->titleType;
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
     * @param int $width
     * @codeCoverageIgnore
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
    /**
     * Setter.
     *
     * @param int $height
     * @codeCoverageIgnore
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
    /**
     * Setter.
     *
     * @param string[] $hide
     * @codeCoverageIgnore
     */
    public function setHide($hide)
    {
        $this->hide = $hide;
    }
    /**
     * Setter.
     *
     * @param string $titleType
     * @codeCoverageIgnore
     */
    public function setTitleType($titleType)
    {
        $this->titleType = $titleType;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['url' => $this->url, 'width' => $this->width, 'height' => $this->height, 'hide' => $this->hide, 'titleType' => $this->titleType];
    }
    /**
     * Generate a `VisualThumbnail` object from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromJson($data)
    {
        $instance = new self();
        $instance->setUrl($data['url'] ?? '');
        $instance->setWidth($data['width'] ?? 0);
        $instance->setHeight($data['height'] ?? 0);
        $instance->setHide($data['hide'] ?? []);
        $instance->setTitleType($data['titleType'] ?? 'center');
        return $instance;
    }
}
