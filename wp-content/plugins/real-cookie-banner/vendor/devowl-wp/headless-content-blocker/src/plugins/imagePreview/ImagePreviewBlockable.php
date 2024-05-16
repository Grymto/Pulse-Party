<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
/**
 * Provide an interface and mechanism to determine if a blockable should load a thumbnail via `ImagePreview`.
 * @internal
 */
interface ImagePreviewBlockable
{
    /**
     * Determine if a thumbnail / image preview should be downloaded for this match.
     *
     * @param string $url
     * @param BlockedResult $result
     * @param AbstractMatch $match
     * @return boolean
     */
    public function downloadImagePreviewFor($url, $result, $match);
}
