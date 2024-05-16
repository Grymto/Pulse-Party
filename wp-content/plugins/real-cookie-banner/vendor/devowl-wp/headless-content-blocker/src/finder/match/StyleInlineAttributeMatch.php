<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
/**
 * Match for `StyleInlineAttributeFinder`.
 * @internal
 */
class StyleInlineAttributeMatch extends TagAttributeMatch
{
    /**
     * Get the style attribute.
     *
     * @param boolean $wrap If `true`, the string gets wrapped into a valid style sheet rule. You can `self::unwrap` it.
     */
    public function getStyle($wrap = \false)
    {
        return $wrap ? \sprintf('#a{%s}', $this->getLink()) : $this->getLink();
    }
    /**
     * Unwrap the style from `getStyle(true)` to the original style.
     *
     * @param string $style
     */
    public static function unwrapStyle($style)
    {
        return \trim(\trim(\explode('{', $style, 2)[1] ?? ''), '}');
    }
}
