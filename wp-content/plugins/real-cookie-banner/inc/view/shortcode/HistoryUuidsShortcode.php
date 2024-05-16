<?php

namespace DevOwl\RealCookieBanner\view\shortcode;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Shortcode to print the consent UUIDs of the current user.
 * @internal
 */
class HistoryUuidsShortcode
{
    use UtilsProvider;
    const TAG = 'rcb-consent-history-uuids';
    /**
     * Real Cookie Banner v2 had only one UUID in their consent. It does not change any behavior,
     * but the name changed accordingly.
     *
     * @deprecated Remove in v4
     */
    const DEPRECATED_TAG = 'rcb-consent-print-uuid';
    /**
     * Render shortcode HTML.
     *
     * @param mixed $atts
     * @return string
     */
    public static function render($atts)
    {
        $atts = \shortcode_atts(['fallback' => ''], $atts, self::TAG);
        return \sprintf('<span class="%s" data-fallback="%s"></span>', self::TAG, \esc_attr($atts['fallback']));
    }
}
