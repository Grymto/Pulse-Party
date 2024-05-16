<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\ScriptInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\RerunOnMatchException;
/**
 * Match by `TagAttributeFinder`.
 * @internal
 */
class TagAttributeMatcher extends AbstractMatcher
{
    const FIX_SCRIPT_INLINE_DATA_URL_TRANSFORMATION_ATTRIBUTE = 'consent-fix-script-inline-data-url-transformation-attribute';
    /**
     * See `AbstractMatcher`.
     *
     * @param TagAttributeMatch $match
     */
    public function match($match)
    {
        $result = $this->createResult($match);
        if (!$result->isBlocked()) {
            return $result;
        }
        $linkAttribute = $match->getLinkAttribute();
        $link = $match->getLink();
        $this->applyCommonAttributes($result, $match, $linkAttribute, $link);
        return $result;
    }
    /**
     * See `AbstractMatcher`.
     *
     * @param TagAttributeMatch $match
     * @throws RerunOnMatchException When a inline script got converted to a data URL
     */
    public function createResult($match)
    {
        $result = $this->createPlainResultFromMatch($match);
        $linkAttribute = $match->getLinkAttribute();
        $isDataUrlScript = $match->getTag() === 'script' && $match->isAttributeDataUrl($linkAttribute) !== \false;
        // Fix inline scripts which hold the inline script in `src` attribute with base64 encoded content
        if ($isDataUrlScript) {
            // Already processed?
            if ($match->hasInvisibleAttribute(self::FIX_SCRIPT_INLINE_DATA_URL_TRANSFORMATION_ATTRIBUTE)) {
                return $result;
            }
            $match->setInvisibleAttribute(self::FIX_SCRIPT_INLINE_DATA_URL_TRANSFORMATION_ATTRIBUTE, \true);
            // Interesting, why not to append `</script>`: https://stackoverflow.com/a/28719226/5506547
            $match->setAfterTag($match->getLink() . $match->getAfterTag());
            $match->setAttribute($linkAttribute, null);
            $mimeType = $match->isAttributeDataUrl($linkAttribute);
            throw new RerunOnMatchException($match, function ($match, $matcher) use($linkAttribute, $mimeType) {
                if ($match instanceof ScriptInlineMatch) {
                    if ($match->hasAttribute(Constants::HTML_ATTRIBUTE_INLINE)) {
                        // Got blocked, so use original `src` with transformed attribute
                        $match->setAttribute(AttributesHelper::transformAttribute($linkAttribute), $match->getAttribute(Constants::HTML_ATTRIBUTE_INLINE), $mimeType);
                        $match->setAttribute(Constants::HTML_ATTRIBUTE_INLINE, null);
                    } else {
                        // Did not get blocked, so use original `src`
                        $match->setAttribute($linkAttribute, $match->getScript(), $mimeType);
                    }
                    $match->setScript('');
                    throw new RerunOnMatchException($match);
                }
            });
        }
        $this->iterateBlockablesInString(
            $result,
            $match->getLink(),
            // Consider `script[src]` data URL as inline script
            $isDataUrlScript,
            $isDataUrlScript
        );
        $this->probablyDisableDueToSkipped($result, $match);
        return $this->applyCheckResultHooks($result, $match);
    }
}
