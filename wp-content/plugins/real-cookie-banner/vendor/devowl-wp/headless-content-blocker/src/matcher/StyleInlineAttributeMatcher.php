<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\StyleInlineAttributeMatch;
/**
 * Match by `StyleInlineAttributeFinder`.
 * @internal
 */
class StyleInlineAttributeMatcher extends AbstractMatcher
{
    /**
     * See `AbstractMatcher`.
     *
     * @param StyleInlineAttributeMatch $match
     */
    public function match($match)
    {
        $result = $this->createResult($match);
        if (!$result->isBlocked()) {
            return $result;
        }
        $cb = $this->getHeadlessContentBlocker();
        $cssBlocker = new CssBlocker(\false, $match, $this);
        list($documentWithoutBlockedElements, , $blockedUrls) = $cssBlocker->parse();
        $cb->runInlineStyleModifyDocumentsCallback($documentWithoutBlockedElements, null, $this, $match);
        $match->setAttribute('style', StyleInlineAttributeMatch::unwrapStyle($documentWithoutBlockedElements->render()));
        $this->applyCommonAttributes($result, $match);
        $result->setData('blockedUrls', $blockedUrls);
        return $result;
    }
    /**
     * See `AbstractMatcher`.
     *
     * @param StyleInlineAttributeMatch $match
     */
    public function createResult($match)
    {
        $result = $this->createPlainResultFromMatch($match);
        $this->iterateBlockablesInString($result, $match->getLink());
        $this->probablyDisableDueToSkipped($result, $match);
        return $this->applyCheckResultHooks($result, $match);
    }
}
