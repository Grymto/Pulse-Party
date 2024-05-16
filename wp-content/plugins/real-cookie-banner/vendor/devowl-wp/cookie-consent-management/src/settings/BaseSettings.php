<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

/**
 * Helper methods for each settings category.
 * @internal
 */
class BaseSettings
{
    /**
     * See Settings
     *
     * @var Settings
     */
    private $settings;
    /**
     * Setter.
     *
     * @param Settings $settings
     * @codeCoverageIgnore
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
