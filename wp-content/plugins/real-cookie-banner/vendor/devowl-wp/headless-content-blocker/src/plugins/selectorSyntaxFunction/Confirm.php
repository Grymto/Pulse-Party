<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `confirm()`.
 *
 * ```
 * a[data-href*="youtube.com"][class*="my-lightbox":confirm()]
 * ```
 *
 * When called for an attribute it will mark the individual node in `confirm` mode (similar to `window.confirm()`). That means,
 * instead of rendering a visual content blocker for the blocked node, the blocked node is still rendered as-is but the
 * `click` event gets caught. Afterward, a confirm dialog with a visual content blocker (text-mode) will be shown.
 *
 * Attention: You need to enable the visual content blocker in your blocker settings!
 * @internal
 */
class Confirm extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('confirm', [$this, 'fn']);
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
                $match->setAttribute(Constants::HTML_ATTRIBUTE_DELEGATE_CLICK, \json_encode(['selector' => 'self']));
                $match->setAttribute(Constants::HTML_ATTRIBUTE_CONFIRM, \true);
            }
        });
        return \true;
    }
}
