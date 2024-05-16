<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils as FastHtmlTagUtils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Utils;
/**
 * Convert links to autoplay links for known video and sound platforms.
 * @internal
 */
class Autoplay extends AbstractPlugin
{
    const HTML_TAG_IFRAME = 'iframe';
    const HTML_TAG_EMBED = 'embed';
    const HTML_TAG_DIV = 'div';
    const HTML_ATTRIBUTE_SRC = 'src';
    /**
     * Some plugins are using `data-src` (e.g. Thrive Architect) to provide the iframe to the frontend.
     */
    const HTML_ATTRIBUTE_ALTERNATIVE_SRC = 'data-src';
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            $tag = $match->getTag();
            $linkAttribute = $match->getLinkAttribute();
            $transformedAttributeValue = AttributesHelper::transformAttribute($linkAttribute);
            $link = $match->getAttribute($transformedAttributeValue, $match->getLink(), \true);
            if (\in_array($tag, [self::HTML_TAG_IFRAME, self::HTML_TAG_EMBED], \true) && \in_array($linkAttribute, [self::HTML_ATTRIBUTE_SRC, self::HTML_ATTRIBUTE_ALTERNATIVE_SRC], \true)) {
                $autoplayUrl = $this->transformUrl($link);
                // Add the attribute
                if ($autoplayUrl !== null) {
                    $clickAttribute = AttributesHelper::transformAttribute($linkAttribute, \true);
                    $match->setAttribute($clickAttribute, $autoplayUrl);
                }
            }
        }
    }
    /**
     * Transform a given YouTube / Vimeo URL to autoplay URL.
     *
     * @param string $link
     */
    protected function transformUrl($link)
    {
        $autoplayUrl = null;
        $host = \parse_url($link, \PHP_URL_HOST);
        if (Utils::endsWith($host, 'youtube.com') || Utils::endsWith($host, 'dailymotion.com') || Utils::endsWith($host, 'loom.com') || Utils::endsWith($host, 'wistia.net')) {
            $autoplayUrl = FastHtmlTagUtils::addParametersToUrl($link, ['autoplay' => '1']);
        }
        // Vimeo (https://vimeo.zendesk.com/hc/en-us/articles/115004485728-Autoplaying-and-looping-embedded-videos)
        if (Utils::endsWith($host, 'vimeo.com')) {
            $autoplayUrl = FastHtmlTagUtils::addParametersToUrl($link, ['autoplay' => '1', 'loop' => '1']);
        }
        // no `esc_url` needed cause this is done by `DevOwl\FastHtmlTag\Utils#htmlAttributes()`
        return $autoplayUrl;
    }
}
