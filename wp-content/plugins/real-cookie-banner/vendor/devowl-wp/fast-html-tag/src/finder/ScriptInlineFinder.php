<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\ScriptInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Find inline scripts.
 * @internal
 */
class ScriptInlineFinder extends AbstractRegexFinder
{
    /**
     * Inline scripts are completely different than usual URL scripts. We need to get
     * all available inline scripts, scrape their content and check if it needs to blocked.
     *
     * **Attention**: This also captures usual `script` tags, so you have to check this manually
     * via PHP if the `src` tag is given!
     *
     * Available matches:
     *      $match[0] => Full string
     *      $match[1] => Attributes string after `<script`
     *      $match[2] => Full inline script
     *      $match[3] => When not empty, it is a commented-out script (false-positive) which needs to be skipped
     *
     * @see https://regex101.com/r/7lYPHA/4
     */
    const SCRIPT_INLINE_REGEXP = '/<script([^>]*)>([^<]*(?:<(?!\\/script(?:\\s*--)?>)[^<]*)*)<\\/script(\\s*--)?>/smix';
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
        return self::SCRIPT_INLINE_REGEXP;
    }
    /**
     * See `AbstractRegexFinder`.
     *
     * @param array $m
     */
    public function createMatch($m)
    {
        if (!empty($m[3])) {
            return \false;
        }
        list($attributes, $script) = self::prepareMatch($m);
        // Ignore scripts with `src` attribute as they are not treated as inline scripts
        if (self::isNotAnInlineScript($attributes)) {
            return \false;
        }
        return new ScriptInlineMatch($this, $m[0], $attributes, $script);
    }
    /**
     * Checks if the passed attributes of a found `<script` tag is not an inline script.
     *
     * @param array $attributes
     */
    public static function isNotAnInlineScript($attributes)
    {
        return isset($attributes['src']) && !empty($attributes['src']);
    }
    /**
     * Prepare the result match of a `createRegexp` regexp.
     *
     * @param array $m
     */
    public static function prepareMatch($m)
    {
        $attributes = Utils::parseHtmlAttributes($m[1]);
        $script = $m[2];
        return [$attributes, $script];
    }
}
