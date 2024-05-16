<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\StyleInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Find inline styles.
 * @internal
 */
class StyleInlineFinder extends AbstractRegexFinder
{
    /**
     * Inline styles are completely different than usual URL `link`s. We need to get
     * all available inline styles, scrape their content and check if it needs to blocked.
     *
     * Available matches:
     *      $match[0] => Full string
     *      $match[1] => Attributes string after `<style`
     *      $match[2] => Full inline style
     *      $match[3] => Empty or `\` if style is escaped
     *
     * @see https://regex101.com/r/lU6i7F/3
     */
    const STYLE_INLINE_REGEXP = '/<style([^>]*)>([^<]*(?:<(?![\\\\]*\\/style>)[^<]*)*)<([\\\\]*)\\/style>/mixs';
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        // Silence is golden.
    }
    // See `AbstractRegexFinder`.
    public function getRegularExpression()
    {
        return self::STYLE_INLINE_REGEXP;
    }
    /**
     * See `AbstractRegexFinder`.
     *
     * @param array $m
     */
    public function createMatch($m)
    {
        list($attributes, $style) = self::prepareMatch($m);
        // Do not match escaped style
        if (!empty($m[3])) {
            return \false;
        }
        return new StyleInlineMatch($this, $m[0], $attributes, $style);
    }
    /**
     * Prepare the result match of a `createRegexp` regexp.
     *
     * @param array $m
     */
    public static function prepareMatch($m)
    {
        $attributes = Utils::parseHtmlAttributes($m[1]);
        $style = $m[2];
        return [$attributes, $style];
    }
}
