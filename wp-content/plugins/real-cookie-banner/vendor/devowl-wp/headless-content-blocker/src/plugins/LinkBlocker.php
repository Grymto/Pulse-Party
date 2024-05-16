<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
/**
 * Block `href` attribute for special links. Usually, the blocker does not block
 * links cause they do not load external sources. But there are some special cases, e.g.
 * lightbox plugins which need a content blocker for a link.
 *
 * @see https://www.w3schools.com/tags/tag_a.asp
 * @internal
 */
class LinkBlocker extends AbstractPlugin
{
    const REPLACE_TAGS = ['a'];
    const REPLACE_ATTRIBUTES = ['href'];
    private $blockIfClass = [];
    // Documented in AbstractPlugin
    public function init()
    {
        $cb = $this->getHeadlessContentBlocker();
        $cb->addTagAttributeMap(self::REPLACE_TAGS, self::REPLACE_ATTRIBUTES, 'linkBlocker');
    }
    /**
     * Only block links with this class.
     *
     * @param string[] $classNames
     */
    public function addBlockIfClass($classNames)
    {
        $this->blockIfClass = \array_merge_recursive($this->blockIfClass, $classNames);
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        if ($result->isBlocked() && $matcher instanceof TagAttributeMatcher && \in_array($match->getTag(), self::REPLACE_TAGS, \true)) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            if (\in_array($match->getLinkAttribute(), self::REPLACE_ATTRIBUTES, \true)) {
                $classes = \explode(' ', \strtolower($match->getAttribute('class', '')));
                $blockOnlyIfClass = $this->blockIfClass;
                foreach ($classes as $class) {
                    if (\in_array($class, $blockOnlyIfClass, \true)) {
                        return $result;
                    }
                }
                $result->disableBlocking();
            }
        }
        return $result;
    }
}
