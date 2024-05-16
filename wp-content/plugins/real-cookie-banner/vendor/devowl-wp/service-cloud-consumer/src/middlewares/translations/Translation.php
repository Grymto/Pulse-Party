<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\translations;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Represent a Translation entry for `PersistTranslationsMiddleware`.
 * @internal
 */
class Translation
{
    private $englishValue;
    private $translatedValue;
    /**
     * C'tor.
     *
     * @param string $englishValue
     * @param string $translatedValue
     */
    public function __construct($englishValue, $translatedValue)
    {
        $this->englishValue = $englishValue;
        $this->translatedValue = $translatedValue;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getEnglishValue()
    {
        return $this->englishValue;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTranslatedValue()
    {
        return $this->translatedValue;
    }
}
