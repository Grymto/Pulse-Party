<?php

namespace DevOwl\RealCookieBanner\lite;

use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait Stats
{
    // Documented in IOverrideStats
    public function fetchMainStats($from, $to, $context)
    {
        return new WP_Error('rest_rcb_lite', \__('You are not allowed to do this in the free version of Real Cookie Banner. Please upgrade to Real Cookie Banner PRO!', RCB_TD));
    }
    // Documented in IOverrideStats
    public function fetchButtonsClickedStats($from, $to, $context)
    {
        return new WP_Error('rest_rcb_lite', \__('You are not allowed to do this in the free version of Real Cookie Banner. Please upgrade to Real Cookie Banner PRO!', RCB_TD));
    }
    // Documented in IOverrideStats
    public function fetchCustomBypassStats($from, $to, $context)
    {
        return new WP_Error('rest_rcb_lite', \__('You are not allowed to do this in the free version of Real Cookie Banner. Please upgrade to Real Cookie Banner PRO!', RCB_TD));
    }
}
