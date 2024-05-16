<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Converts e.g. `onload` attributes to `consent-original-src` attributes when item got blocked.
 * @internal
 */
class AdditionalAttributesBlocker extends AbstractPlugin
{
    private $tags = [];
    private $attributes = [];
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if (\in_array($match->getTag(), $this->tags, \true)) {
            foreach ($this->attributes as $attribute) {
                $attributeValue = $match->getAttribute($attribute);
                if ($attributeValue !== null) {
                    $match->setAttribute(AttributesHelper::transformAttribute($attribute), $attributeValue);
                    $match->setAttribute($attribute, null);
                }
            }
        }
    }
    /**
     * Set the tag attribute map for this blocker.
     *
     * @param string[] $tags
     * @param string[] $attributes
     */
    public function setTagAttributeMap($tags, $attributes)
    {
        $this->tags = $tags;
        $this->attributes = $attributes;
    }
    /**
     * Append defaults as plugin.
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     */
    public static function defaults($headlessContentBlocker)
    {
        /**
         * Plugin.
         *
         * @var AdditionalAttributesBlocker
         */
        $additionalAttributesBlocker = $headlessContentBlocker->addPlugin(AdditionalAttributesBlocker::class);
        $additionalAttributesBlocker->setTagAttributeMap([
            'iframe',
            'embed',
            'frame',
            /**
             * Imagine this (idk who is doing this, but it exists in the wild):
             *
             * ```html
             * <link rel="preload" href="https://fonts.googleapis.com/css?family=Barlow:600|Bellefair:400|Barlow:400&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
             * ```
             */
            'link',
        ], ['onload']);
    }
}
