<?php

namespace DevOwl\RealCookieBanner\view\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\Autoplay;
use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Enhanced `Autoplay` plugin to enable plugin and theme compatibilities.
 * @internal
 */
class PluginAutoplay extends Autoplay
{
    use UtilsProvider;
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
            // [Theme Comp] Themify
            if ($tag === 'div' && $linkAttribute === 'data-url' && $match->hasAttribute('class') && \strpos($match->getAttribute('class'), 'tb_') !== \false) {
                $originalClickAttributeName = 'data-auto';
                $clickAttribute = AttributesHelper::transformAttribute($originalClickAttributeName, \true);
                $match->setAttribute($clickAttribute, '1');
            }
        }
    }
}
