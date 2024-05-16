<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `delegateClick()`.
 *
 * ```
 * a[data-href="youtube.com":delegateClick(selector=.overlay&hide=true)]
 * ```
 *
 * Parameters:
 *
 * - `[selector="self"]` (string): Can be “self” or a valid CSS selector which will be executed on the unblocked element and used for the click delegation
 * - `[hide=false]` (boolean): If true, the clicked element will be hidden
 *
 * Real Cookie Banner already tries to automatically forward the click to the right place for unblocked elements.
 * However, this is technically not always possible, which is why there is the function with `delegateClick()`
 * to click a specific selector after unblocking a content.
 * @internal
 */
class DelegateClick extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('delegateClick', [$this, 'fn']);
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
        $selector = $fn->getArgument('selector', 'self');
        $hide = $fn->getArgument('hide', \false);
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match, $selector, $hide) {
            if ($result->isBlocked()) {
                $match->setAttribute(Constants::HTML_ATTRIBUTE_DELEGATE_CLICK, \json_encode(['selector' => $selector, 'hide' => $hide]));
            }
        });
        return \true;
    }
}
