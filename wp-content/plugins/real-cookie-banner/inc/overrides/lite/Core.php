<?php

namespace DevOwl\RealCookieBanner\lite;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Freemium\CoreLite;
use DevOwl\RealCookieBanner\lite\rest\Service;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait Core
{
    use CoreLite;
    // Documented in IOverrideCore
    public function overrideConstruct()
    {
        // Silence is golden.
    }
    // Documented in IOverrideCore
    public function overrideRegisterSettings()
    {
        // Silence is golden.
    }
    // Documented in IOverrideCore
    public function overrideRegisterPostTypes()
    {
        // Silence is golden.
    }
    // Documented in IOverrideCore
    public function overrideInit()
    {
        \add_filter('RCB/Revision/Current', [new \DevOwl\RealCookieBanner\lite\FomoCoupon(), 'revisionCurrent']);
    }
}
