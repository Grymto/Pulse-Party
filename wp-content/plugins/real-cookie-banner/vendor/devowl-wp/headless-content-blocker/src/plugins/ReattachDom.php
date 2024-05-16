<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Allows to reattach a specific DOM node. That means, DOM node gets removed and added again.
 * This could be useful if e.g. a script is `MutationObserver` and is only listening to `addedNodes`.
 * @internal
 */
class ReattachDom extends AbstractPlugin
{
    private $matcher = [];
    /**
     * Init.
     */
    public function init()
    {
        // Predefined matchers
        // @see https://github.com/verlok/vanilla-lazyload
        // @see https://wordpress.org/plugins/rocket-lazy-load/
        $this->addMatcher(function ($match) {
            /**
             * Match.
             *
             * @var AbstractMatch
             */
            $match = $match;
            return $match->getTag() === 'iframe' && $match->hasAttribute(AttributesHelper::transformAttribute('data-lazy-src'));
        });
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if ($result->isBlocked()) {
            foreach ($this->matcher as $fn) {
                if ($fn($match, $matcher)) {
                    $match->setAttribute(Constants::HTML_ATTRIBUTE_REATTACH_DOM, '1');
                    break;
                }
            }
        }
    }
    /**
     * Add a custom matcher functionality which returns `boolean` for a given `$match`.
     *
     * @param callback $fn
     */
    public function addMatcher($fn)
    {
        $this->matcher[] = $fn;
    }
}
