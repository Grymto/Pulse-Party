<?php

namespace DevOwl\RealCookieBanner\lite\view\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Implementation for `ImagePreviewBlockable`.
 * @internal
 */
trait ImagePreviewBlockableTrait
{
    /**
     * See `ImagePreviewBlockable`.
     *
     * @param string $url
     * @param BlockedResult $result
     * @param AbstractMatch $match
     * @return boolean
     */
    public function downloadImagePreviewFor($url, $result, $match)
    {
        return \false;
    }
}
