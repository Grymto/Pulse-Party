<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
/**
 * Block `srcset` attribute for media tags and create the correct visual parent for media tags.
 *
 * @see https://www.w3schools.com/tags/tag_img.asp
 * @see https://www.w3schools.com/tags/tag_source.asp
 * @internal
 */
class Image extends AbstractPlugin
{
    const HTML_TAG_SOURCE = 'source';
    const HTML_TAG_IMG = 'img';
    const HTML_ATTRIBUTE_SRCSET = 'srcset';
    const HTML_ATTRIBUTE_SRC = 'src';
    // Documented in AbstractPlugin
    public function init()
    {
        $cb = $this->getHeadlessContentBlocker();
        // A `<video` and `<audio` tag uses a set of `<source` tags with `src` attribute.
        $cb->addTagAttributeMap([self::HTML_TAG_SOURCE], [self::HTML_ATTRIBUTE_SRC], 'imageSourceSrc');
        // A `<picture` tag uses a set of `<source` tags with `srcset` attribute.
        $cb->addTagAttributeMap([self::HTML_TAG_SOURCE], [self::HTML_ATTRIBUTE_SRCSET], 'imageSourceSrcSet');
    }
    /**
     * Create an additional `use-parent` attribute to the DOM element so the content blocker get's created
     * for the main media tag like `<video`.
     *
     * @param boolean|string|number $visualParent
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     * @return boolean|string|number
     */
    public function visualParent($visualParent, $matcher, $match)
    {
        if ($match->getTag() === self::HTML_TAG_SOURCE) {
            return \true;
        }
        return $visualParent;
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if ($result->isBlocked() && $matcher instanceof TagAttributeMatcher && $match->getTag() === self::HTML_TAG_IMG && $match->hasAttribute(self::HTML_ATTRIBUTE_SRCSET)) {
            $newSrcAttribute = AttributesHelper::transformAttribute(self::HTML_ATTRIBUTE_SRCSET);
            $match->setAttribute($newSrcAttribute, $match->getAttribute(self::HTML_ATTRIBUTE_SRCSET));
            $match->setAttribute(self::HTML_ATTRIBUTE_SRCSET, null);
        }
        return $result;
    }
}
