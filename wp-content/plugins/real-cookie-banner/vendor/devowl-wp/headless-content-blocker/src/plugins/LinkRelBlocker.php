<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\BlockableScanner;
/**
 * Block `<link`'s with `preconnect`, `dns-prefetch` and `preload`. It means, it removes
 * the node completely from the HTML document if possible.
 * @internal
 */
class LinkRelBlocker extends AbstractPlugin
{
    const REL = [
        'preconnect',
        'dns-prefetch',
        'preload',
        /**
         * Example: Mastdon verify process.
         *
         * @see https://docs.joinmastodon.org/user/profile/
         */
        'me',
        'alternate',
    ];
    /**
     * Do-not-touch `rel`s and never find them in scanner.
     *
     * @var string[]
     */
    private $doNotTouch = [];
    /**
     * Similar to `$doNotTouch` but not customizable.
     */
    const DO_NOT_TOUCH = ['me', 'alternate'];
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        $isLink = $match->getTag() === 'link' && $match->hasAttribute('rel') && \count(\array_intersect(\explode(' ', $match->getAttribute('rel')), self::REL)) > 0;
        // Split a `<link rel="dns-prefetch preconnect"` into multiple nodes and rerun the content blocker
        if ($isLink && \strpos($match->getAttribute('rel'), ' ') !== \false) {
            $result->disableBlocking();
            $result->setData(BlockableScanner::BLOCKED_RESULT_DATA_KEY_IGNORE_IN_SCANNER, \true);
            $beforeTag = $match->getBeforeTag();
            $firstRel = '';
            foreach (\explode(' ', $match->getAttribute('rel')) as $key => $relRow) {
                $match->setAttribute('rel', $relRow);
                if ($key === 0) {
                    $firstRel = $relRow;
                } else {
                    $beforeTag .= $match->render();
                }
                $match->setAttribute('rel', $firstRel);
            }
            $match->setAfterTag($beforeTag);
            return $result;
        }
        // Never touch e.g. `dns-prefetch` as they are GDPR compliant
        if ($isLink && \in_array($match->getAttribute('rel'), \array_merge($this->doNotTouch, self::DO_NOT_TOUCH), \true)) {
            $result->disableBlocking();
            $result->setData(BlockableScanner::BLOCKED_RESULT_DATA_KEY_IGNORE_IN_SCANNER, \true);
            return $result;
        }
        // @codeCoverageIgnoreStart
        if (!$result->isBlocked() && $matcher instanceof TagAttributeMatcher && $isLink) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            $matcher->iterateBlockablesInString($result, $match->getLink(), \false, \false, $this->getHeadlessContentBlocker()->blockablesToHosts());
        }
        // @codeCoverageIgnoreEnd
        // Omit the rendering, if possible
        if ($isLink && $result->isBlocked() && !$match->hasAttribute('onload') && !$match->hasAttribute(AttributesHelper::transformAttribute('onload'))) {
            $match->omit();
        }
        return $result;
    }
    /**
     * Do not touch some `rel`s.
     *
     * @param string[] $doNotTouch
     */
    public function setDoNotTouch($doNotTouch)
    {
        $this->doNotTouch = $doNotTouch;
    }
}
