<?php

namespace DevOwl\RealCookieBanner\overrides\interfce;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
interface IOverrideStats
{
    /**
     * Get the main stats (e. g. use in bar chart).
     *
     * @param string $from
     * @param string $to
     * @param string $context
     * @return array|WP_Error
     */
    public function fetchMainStats($from, $to, $context);
    /**
     * Get the "Buttons clicked" stats (e. g. use in pie chart).
     *
     * @param string $from
     * @param string $to
     * @param string $context
     * @return array|WP_Error
     */
    public function fetchButtonsClickedStats($from, $to, $context);
    /**
     * Get the "Do not track" stats (e. g. use in pie chart) and other custom bypass.
     * The result `none` is similar to "banner got shown to user".
     *
     * @param string $from
     * @param string $to
     * @param string $context
     * @return array|WP_Error
     */
    public function fetchCustomBypassStats($from, $to, $context);
}
