<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * There are plugins like TranslatePress, which does use a completely different way of
 * implementing multilingual content to WordPress sites.
 * @internal
 */
abstract class AbstractOutputBufferPlugin extends AbstractLanguagePlugin
{
    /**
     * Wrap the complete string within a HTML tag so our plugin can
     * extract it correctly and handles it as HTML.
     */
    const HTML_TAG_KEEP = 'keep-me';
    /**
     * Our plugin does in general support JSON, but it slows down the site extremely,
     * let's do this hacky with a single HTML string...
     */
    const HTML_TAG_IGNORE = '<ignore-me-completely %s></ignore-me-completely>';
    // Documented in AbstractLanguagePlugin
    public function disableCopyAndSync($sync)
    {
        // Silence is golden.
    }
    // Documented in AbstractLanguagePlugin
    public function getOriginalPostId($id, $post_type)
    {
        return $id;
    }
    // Documented in AbstractLanguagePlugin
    public function getOriginalTermId($id, $taxonomy)
    {
        return $id;
    }
    // Documented in AbstractLanguagePlugin
    public function getCurrentPostId($id, $post_type, $locale = null)
    {
        return $id;
    }
    // Documented in AbstractLanguagePlugin
    public function getCurrentTermId($id, $taxonomy, $locale = null)
    {
        return $id;
    }
    // Documented in AbstractLanguagePlugin
    public function getPostTranslationIds($id, $post_type)
    {
        return [];
    }
    // Documented in AbstractLanguagePlugin
    public function getTaxonomyTranslationIds($id, $taxonomy)
    {
        return [];
    }
    /**
     * Wrap a complete array to valid HTML format so output buffer plugins can translate
     * the HTML instead of JSON. This can be useful if the plugin does not support it
     * well enough or JSON walker slows down the page extremely.
     *
     * @param string[] $content
     */
    protected function wrapArrayToHtml($content)
    {
        // Make associative array so we can remap the key value pairs
        $processStrings = [];
        foreach ($content as $i => $string) {
            $processStrings[$i] = \sprintf('<%1$s>%2$s</%1$s>', self::HTML_TAG_KEEP, $string);
        }
        $joinDelimiter = \sprintf(self::HTML_TAG_IGNORE, $this->getSkipHTMLForTag());
        return \join($joinDelimiter, $processStrings);
    }
    /**
     * Reverse `wrapArrayToHtml` functionality.
     *
     * @param string $html
     * @param callable $strip
     */
    protected function wrapHtmlToArray($html, $strip = null)
    {
        $joinDelimiter = \sprintf('<ignore-me-completely %s></ignore-me-completely>', $this->getSkipHTMLForTag());
        $result = \explode($joinDelimiter, $html);
        // Remove `keep-me` HTML tag to get real results
        foreach ($result as &$value) {
            $value = \substr(\substr($value, \strlen(self::HTML_TAG_KEEP) + 2), 0, (\strlen(self::HTML_TAG_KEEP) + 3) * -1);
            if (\is_callable($strip)) {
                $value = \call_user_func($strip, $value);
            }
        }
        return $result;
    }
    /**
     * Remap the result to the referenced value `$content` for `translateString` method.
     *
     * @param string[] $content
     * @param string[] $result
     * @param string $locale
     * @param string[] $context
     */
    protected function remapResultToReference(&$content, $result, $locale, $context = null)
    {
        foreach ($content as $i => &$untranslated) {
            $previousContent = $untranslated;
            $translation = $result[$i] ?? null;
            // A translation can not be empty, restore
            if (empty($translation)) {
                $translation = $previousContent;
            }
            // Unchanged, let's check our internal `.mo` file
            if ($previousContent === $translation) {
                list(, $translation) = $this->translateStringFromMo($translation, $locale, $context);
            }
            $untranslated = $translation;
        }
    }
}
