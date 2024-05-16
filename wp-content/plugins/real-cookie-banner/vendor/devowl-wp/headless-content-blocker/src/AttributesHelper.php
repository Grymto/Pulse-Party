<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils as FastHtmlTagUtils;
/**
 * Helper functionality for HTML attributes in association with `Constants`.
 * @internal
 */
class AttributesHelper
{
    /**
     * Check if a given set of HTML attributes already contains the "blocked"-attribute
     * so we can skip duplicate blockages.
     *
     * @param string[] $attributes
     */
    public static function isAlreadyBlocked($attributes)
    {
        return isset($attributes[Constants::HTML_ATTRIBUTE_BLOCKER_ID]) || isset($attributes[Constants::HTML_ATTRIBUTE_INLINE_STYLE]);
    }
    /**
     * Transform an attribute to `consent-original-%s_` attribute.
     *
     * @param string $attribute
     * @param boolean $useClickEvent Uses `consent-click-original` instead of `consent-original`
     */
    public static function transformAttribute($attribute, $useClickEvent = \false)
    {
        return \sprintf('%s-%s-%s', $useClickEvent ? Constants::HTML_ATTRIBUTE_CAPTURE_CLICK_PREFIX : Constants::HTML_ATTRIBUTE_CAPTURE_PREFIX, $attribute, Constants::HTML_ATTRIBUTE_CAPTURE_SUFFIX);
    }
    /**
     * Transform an attribute from `consent-original-%s_` to original attribute name.
     *
     * @param string $attribute
     */
    public static function revertTransformAttribute($attribute)
    {
        if (FastHtmlTagUtils::startsWith($attribute, Constants::HTML_ATTRIBUTE_CAPTURE_PREFIX . '-') && FastHtmlTagUtils::endsWith($attribute, '-' . Constants::HTML_ATTRIBUTE_CAPTURE_SUFFIX)) {
            return \substr(\substr($attribute, \strlen(Constants::HTML_ATTRIBUTE_CAPTURE_PREFIX) + 1), 0, \strlen(Constants::HTML_ATTRIBUTE_CAPTURE_SUFFIX) * -1 - 1);
        } else {
            return \false;
        }
    }
    /**
     * Check if given HTML attributes contain a skipper.
     *
     * @param string[] $attributes
     */
    public static function isSkipped($attributes)
    {
        return isset($attributes[Constants::HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER]) && $attributes[Constants::HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER] === Constants::HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER_VALUE;
    }
    /**
     * Transform a set of given HTML tags to be skipped for the complete content blocker.
     *
     * @param string $html
     * @param string $additionalTags
     */
    public static function skipHtmlTagsInContentBlocker($html, $additionalTags = '')
    {
        return \preg_replace(\sprintf('/^(<(%s))/m', \join('|', Constants::HTML_POTENTIAL_SKIP_TAGS)), \sprintf('$1 %s="%s"%s', Constants::HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER, Constants::HTML_ATTRIBUTE_CONSENT_SKIP_BLOCKER_VALUE, empty($additionalTags) ? '' : ' ' . $additionalTags), $html);
    }
    /**
     * Check if a given string has blocked CSS rules.
     *
     * @param string $document
     */
    public static function hasCssDocumentConsentRules($document)
    {
        return \strpos($document, Constants::URL_QUERY_ARG_ORIGINAL_URL_IN_STYLE) !== \false;
    }
}
