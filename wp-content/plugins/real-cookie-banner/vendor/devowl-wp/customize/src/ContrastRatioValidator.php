<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize;

use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Validate contrast ratio. To validate at "Publish" time add it as `setting.validate_callback` to
 * your customize setting.
 * @internal
 */
class ContrastRatioValidator
{
    private $message;
    private $minimum;
    private $setting1;
    private $setting2;
    /**
     * See `AbstractCustomizePanel`.
     *
     * @var AbstractCustomizePanel
     */
    private $panel;
    private $modifier;
    private $currentlyDisabled = \false;
    /**
     * C'tor.
     *
     * @param string $message Localized message thrown to the customizer (two `%s` (ratio, minimum) are given)
     * @param int $minimum
     * @param string $setting1 The setting ID to check against
     * @param string $setting2 The setting ID to check against
     * @param AbstractCustomizePanel $panel
     * @param callable $modifier Allows you to modify the value for the settings at runtime
     * @codeCoverageIgnore
     */
    public function __construct($message, $minimum, $setting1, $setting2, $panel, $modifier = null)
    {
        $this->message = $message;
        $this->minimum = $minimum;
        $this->setting1 = $setting1;
        $this->setting2 = $setting2;
        $this->panel = $panel;
        $this->modifier = $modifier;
    }
    /**
     * Validate minimum contrast ratio.
     *
     * @param WP_Error $validity
     * @return mixed
     */
    public function validate_callback($validity)
    {
        // Avoid circular issues with `post_value`
        if ($this->currentlyDisabled || !empty($_POST['customize_changeset_autosave'])) {
            return $validity;
        }
        // Collect both values
        $this->currentlyDisabled = \true;
        $against1 = Utils::getValue($this->setting1, $this->panel);
        $against2 = Utils::getValue($this->setting2, $this->panel);
        if (\is_callable($this->modifier)) {
            $modifier = $this->modifier;
            $modifier = $modifier($against1, $against2, $this->panel);
            $against1 = $modifier[0];
            $against2 = $modifier[1];
        }
        $this->currentlyDisabled = \false;
        // Calculate ratio
        $ratio = self::getLuminosityRatio($against1, $against2);
        if ($ratio < $this->minimum) {
            $validity->add('contrast_ratio_minimum', \sprintf($this->message, \number_format_i18n($ratio, 3), $this->minimum), ['color1' => $against1, 'color2' => $against2]);
        }
        return $validity;
    }
    /**
     * Calculate luminosity of a given `#RRGGBB` string.
     *
     * Also ported to `utils/getLuminosity.tsx`.
     *
     * @param string $color
     * @see http://www.w3.org/TR/WCAG20/#relativeluminancedef
     */
    public static function getLuminosity($color)
    {
        $color = \substr($color, 1);
        $r = \hexdec(\substr($color, 0, 2)) / 255;
        $g = \hexdec(\substr($color, 2, 2)) / 255;
        $b = \hexdec(\substr($color, 4, 2)) / 255;
        $r = $r <= 0.03928 ? $r / 12.92 : \pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : \pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : \pow(($b + 0.055) / 1.055, 2.4);
        return 0.2126 * $r + 0.7151999999999999 * $g + 0.0722 * $b;
    }
    /**
     * Calculate contrast ratio of two given `#RRGGBB` strings.
     *
     * Also ported to `utils/getLuminosityRatio.tsx`.
     *
     * @param string $color1
     * @param string $color2
     * @see http://www.w3.org/TR/WCAG20/#contrast-ratiodef
     */
    public static function getLuminosityRatio($color1, $color2)
    {
        $l1 = self::getLuminosity($color1);
        $l2 = self::getLuminosity($color2);
        return $l1 > $l2 ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
    }
}
