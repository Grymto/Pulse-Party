<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\TagAttributeFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\StyleInlineAttributeMatch;
/**
 * Find HTML tags with a potential `style` attribute.
 * @internal
 */
class StyleInlineAttributeFinder extends TagAttributeFinder
{
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        parent::__construct([], ['style']);
    }
    /**
     * See `AbstractRegexFinder`.
     *
     * @param array $m
     */
    public function createMatch($m)
    {
        list($tag, $attributes) = self::extractAttributesFromMatch($m);
        list($linkAttribute, $link) = $this->getRegexpAttributesInMatch($attributes);
        if ($link === null) {
            return \false;
        }
        return new StyleInlineAttributeMatch($this, $m[0], $tag, $attributes, $linkAttribute);
    }
}
