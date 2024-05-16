<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Find HTML tags by tag and attribute name.
 * @internal
 */
class TagAttributeFinder extends AbstractRegexFinder
{
    private $regexpTags;
    private $regexpAttributes;
    private $regexp;
    /**
     * C'tor.
     *
     * @param string[] $tags
     * @param string[] $attributes If you rely on the regular expression match of the link attribute it is highly recommend
     *                             to pass only one attribute and create multiple instances of `TagAttributeFinder`.
     * @codeCoverageIgnore
     */
    public function __construct($tags, $attributes)
    {
        $this->regexpTags = $tags;
        $this->regexpAttributes = $attributes;
        $this->regexp = self::createRegexp($tags, $attributes);
    }
    // See `AbstractRegexFinder`.
    public function getRegularExpression()
    {
        return $this->regexp;
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
        if ($linkAttribute === null) {
            return \false;
        }
        if ($this->isLinkEscaped($link) && Utils::isJson($link) === \false) {
            return \false;
        }
        return new TagAttributeMatch($this, $m[0], $tag, $attributes, $linkAttribute);
    }
    /**
     * Find the `linkAttribute` and `link` for a given set of attributes returned by `prepareMatch`.
     *
     * @param array $attributes
     */
    public function getRegexpAttributesInMatch($attributes)
    {
        // Find the link attribute
        $linkAttribute = null;
        $link = null;
        foreach ($this->getRegexpAttributes() as $attribute) {
            if (isset($attributes[$attribute])) {
                $linkAttribute = $attribute;
                $link = $attributes[$attribute];
            }
        }
        return [$linkAttribute, $link];
    }
    /**
     * Do not modify escaped data as they appear mostly in JSON CDATA - we
     * do not modify behavior of other plugins and themes ;-)
     *
     * @param string $link
     */
    protected function isLinkEscaped($link)
    {
        return \strpos($link, '\\') !== \false || empty(\trim($link));
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRegexpTags()
    {
        return $this->regexpTags;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRegexpAttributes()
    {
        return $this->regexpAttributes;
    }
    /**
     * Prepare the result match of a `createRegexp` regexp and return the used HTML tag and attributes.
     *
     * @param array $m
     */
    public static function extractAttributesFromMatch($m)
    {
        $tag = $m[2];
        $attributesString = \preg_split(\sprintf('/%s\\s/', $tag), $m[0], 2)[1];
        $attributesString = \preg_replace('/[\\/]?>$/', '', $attributesString);
        $attributes = Utils::parseHtmlAttributes($attributesString);
        return [$tag, $attributes];
    }
    /**
     * Prepare the result match of a `createRegexp` regexp.
     *
     * @param array $m
     * @deprecated Use `extractAttributesFromMatch` instead!
     */
    public static function prepareMatch($m)
    {
        // Prepare data
        $beforeLinkAttribute = $m[1];
        $tag = $m[2];
        $linkAttribute = $m[3];
        $quoteChar = $m[4] ?? '';
        $link = \html_entity_decode($m[5] ?? '');
        $afterLink = ($m[6] ?? '') . ($m[7] ?? '');
        $isLinkBooleanAttribute = empty($link) && empty($quoteChar);
        // Fix matches without quotes
        if ($isLinkBooleanAttribute) {
            $afterLink = $linkAttribute . (empty($m[7]) ? '=' : '=' . Utils::PARSE_HTML_ATTRIBUTES_CONSIDER_ATTRIBUTE_AS_BOOLEAN_VALUE_TRUE) . $afterLink;
        }
        // Prepare all attributes as array (unfortunately not available from regexp due to back-reference usage...)
        $beforeLinkAttribute = \preg_split('/\\s/', $beforeLinkAttribute . ' ', 2);
        $withoutClosingTagChars = \rtrim($afterLink, '/>');
        $afterLink = \substr($afterLink, (\strlen($afterLink) - \strlen($withoutClosingTagChars)) * -1);
        $attributes = Utils::parseHtmlAttributes($beforeLinkAttribute[1] . ' ' . \ltrim($withoutClosingTagChars, '"\''));
        $beforeLinkAttribute = $beforeLinkAttribute[0];
        if ($isLinkBooleanAttribute && isset($attributes[$linkAttribute])) {
            $link = $attributes[$linkAttribute];
        }
        // Append our original link attribute to the attributes
        $attributes[$linkAttribute] = $link;
        return [$beforeLinkAttribute, $tag, $linkAttribute, $link, $afterLink, $attributes];
    }
    /**
     * Create regular expression to catch multiple tags and attributes.
     *
     * Available matches:
     *      $match[0] => Full string
     *      $match[1] => All content before the link attribute
     *      $match[2] => Used tag
     *      $match[3] => Used link attribute
     *      $match[4] => Used quote for link attribute
     *      $match[5] => Link
     *      $match[6] => All content after the link when it is an attribute with value (e.g. `data-src=""`)
     *      $match[7] => All content after the link when it is an attribute without value (e.g. `data-src another-attribute=""`)
     *
     * @param string[] $searchTags
     * @param string[] $searchAttributes
     * @see https://regex101.com/r/SPbQzu/13
     */
    public static function createRegexp($searchTags, $searchAttributes)
    {
        return \sprintf('/(<(%s)(?:\\s[^>]*?[\\s"\']|\\s))(?<=[\\s"\'])(?:(%s)(?!\\s*?=\\s*?[\\\\]?(?:&quot|&#039)\\b))(?:\\s*?=\\s*?([\\"\']??)([^\\4]*?)(\\4[^>]*?>)|((?=\\s+?|>)[^>]*?>))/si', \count($searchTags) > 0 ? \join('|', $searchTags) : '[A-Za-z_-]+', \join('|', $searchAttributes));
    }
}
