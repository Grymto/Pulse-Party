<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\SelectorSyntaxMatcher;
/**
 * This plugin registers the selector syntax `matchesUrl()`.
 *
 * ```
 * div[data-href:matchesUrl()]
 * ```
 *
 * Imagine you have created a Content Blocker with the following rules:
 *
 * ```
 * *youtube.com*
 * *youtu.be*
 * div[data-href*="youtube.com"]
 * div[data-href*="youtu.be"]
 * ```
 *
 * Instead of writing two rules for the `div` we can solve this with `div[data-href:matchesUrl()]`.
 * It automatically takes all non-selector-syntax rules of the Content Blocker and matches with data-href:
 *
 * ```
 * *youtube.com*
 * *youtu.be*
 * div[data-href:matchesUrl()]
 * ```
 * @internal
 */
class MatchesUrl extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('matchesUrl', [$this, 'fn']);
    }
    /**
     * Function implementation.
     *
     * @param SelectorSyntaxAttributeFunction $fn
     * @param SelectorSyntaxMatch $match
     * @param mixed $value
     */
    public function fn($fn, $match, $value)
    {
        $matcher = $this->getHeadlessContentBlocker()->getFinderToMatcher()[$fn->getAttribute()->getFinder()] ?? null;
        if ($matcher !== null && $matcher instanceof SelectorSyntaxMatcher) {
            $blockedResult = new BlockedResult('', [], '');
            $matcher->iterateBlockablesInString($blockedResult, $value, \false, \false, null, [$matcher->getBlockable()]);
            return $blockedResult->isBlocked();
        }
        // @codeCoverageIgnoreStart
        return \true;
        // @codeCoverageIgnoreEnd
    }
}
