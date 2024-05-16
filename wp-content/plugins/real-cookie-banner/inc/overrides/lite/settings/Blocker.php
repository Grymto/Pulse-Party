<?php

namespace DevOwl\RealCookieBanner\lite\settings;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
trait Blocker
{
    // Documented in IOverrideBlocker
    public function overrideGetOrderedCastMeta($post, &$meta)
    {
        // Silence is golden.
    }
}
