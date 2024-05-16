<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Core as UtilsCore;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Core.
 * @internal
 */
trait Core
{
    /**
     * This method should be called in your 'init' action through your core.
     */
    public function overrideInitCustomize()
    {
        /**
         * Core.
         *
         * @var UtilsCore
         */
        $core = $this;
        \add_action('customize_controls_print_scripts', [$core->getAssets(), 'customize_controls_print_scripts']);
    }
}
