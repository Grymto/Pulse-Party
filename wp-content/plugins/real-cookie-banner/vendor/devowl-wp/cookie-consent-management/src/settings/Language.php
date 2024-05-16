<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

/**
 * A language definition for which the consent management is available.
 * @internal
 */
class Language
{
    /**
     * Name.
     *
     * @var string
     */
    private $name;
    /**
     * Is this the current language?
     *
     * @var boolean
     */
    private $current;
    /**
     * URL to the flag.
     *
     * @var string
     */
    private $flag;
    /**
     * Translation URL to the current opened website URL.
     *
     * @var string
     */
    private $url;
    /**
     * The locale (currently, there is no ISO standard necessary).
     *
     * @var string
     */
    private $locale;
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
    public function isCurrent()
    {
        return $this->current;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getFlag()
    {
        return $this->flag;
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
    public function getLocale()
    {
        return $this->locale;
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
     * @param boolean $current
     * @codeCoverageIgnore
     */
    public function setIsCurrent($current)
    {
        $this->current = $current;
    }
    /**
     * Setter.
     *
     * @param string $flag
     * @codeCoverageIgnore
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
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
     * @param string $locale
     * @codeCoverageIgnore
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['name' => $this->name, 'current' => $this->current, 'flag' => $this->flag, 'url' => $this->url, 'locale' => $this->locale];
    }
    /**
     * Generate a `Language` object from an array.
     *
     * @param array $data
     * @return self
     */
    public static function fromJson($data)
    {
        $instance = new self();
        $instance->setName($data['name']);
        $instance->setIsCurrent($data['current']);
        $instance->setFlag($data['flag'] ?? '');
        $instance->setUrl($data['url']);
        $instance->setLocale($data['locale']);
        return $instance;
    }
}
