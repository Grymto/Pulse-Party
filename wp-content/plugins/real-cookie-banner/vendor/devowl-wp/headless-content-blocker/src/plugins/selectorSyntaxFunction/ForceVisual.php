<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `forceVisual()`.
 *
 * ```
 * input[class*="my-hidden-input-field"][type="hidden":forceVisual()]
 * ```
 *
 * When called for an attribute it will mark the individual node to force a visual content blocker. This is
 * the same setting as we know from the "Force visual content blocker for hidden elements" for a whole content blocker.
 * This can be useful for hidden elements like `input[type="hidden"]` which do usually not get a visual content blocker.
 * @internal
 */
class ForceVisual extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('forceVisual', [$this, 'fn']);
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
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match) {
            if ($result->isBlocked()) {
                $match->setAttribute(Constants::HTML_ATTRIBUTE_VISUAL_FORCE, \true);
            }
        });
        return \true;
    }
}
