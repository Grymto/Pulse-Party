<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize;

use WP_Customize_Manager;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Utility functionality for customize controls and output.
 * @internal
 */
class Utils
{
    /**
     * Calculate rgba() from hex and alpha.
     *
     * @param string $hex
     * @param int $alpha
     */
    public static function calculateOverlay($hex, $alpha)
    {
        $rgb = self::hexToRgb($hex);
        return \sprintf('rgba(%d, %d, %d, %s)', $rgb['r'], $rgb['g'], $rgb['b'], \number_format($alpha / 100, 3, '.', ''));
    }
    /**
     * Calculate RGB from hex string.
     *
     * @param string $hex
     */
    public static function hexToRgb($hex)
    {
        if (\preg_match('/^#?([a-f\\d]{2})([a-f\\d]{2})([a-f\\d]{2})$/i', $hex, $matches, \PREG_OFFSET_CAPTURE, 0) === 1) {
            return ['r' => \intval($matches[1][0], 16), 'g' => \intval($matches[2][0], 16), 'b' => \intval($matches[3][0], 16)];
        }
        return null;
    }
    /**
     * Get the value of a given setting ID. If it is currently in "save" mode, it returns the
     * new value it tries to update.
     *
     * @param string $id
     * @param AbstractCustomizePanel $panel
     */
    public static function getValue($id, $panel)
    {
        global $wp_customize;
        /**
         * Customize manager.
         *
         * @var WP_Customize_Manager
         */
        $manager = $wp_customize;
        return $manager->post_value($manager->get_setting($id), $panel->getSetting($id));
    }
}
