<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Allows to block custom elements.
 *
 * @see https://stackoverflow.com/a/22545622/5506547
 * @see https://html.spec.whatwg.org/multipage/custom-elements.html#custom-elements-customized-builtin-example)
 * @internal
 */
class CustomElementBlocker extends AbstractPlugin
{
    const HTML_TAG_EXCLUDE = ['font-face'];
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        $tag = $match->getTag();
        if ($result->isBlocked() && !\in_array($tag, self::HTML_TAG_EXCLUDE, \true) && \strpos($tag, '-') !== \false) {
            $match->setTag(\sprintf('%s-%s', 'consent', $match->getTag()));
        }
    }
}
