<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `keepAttributes()`.
 *
 * ```
 * a[class*="my-lightbox":keepAttributes(value=class)]
 * ```
 *
 * Parameters:
 *
 * - `value` (string): A comma-separated list of attributes which should not be converted to `consent-original-...`
 * @internal
 */
class KeepAttributes extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('keepAttributes', [$this, 'fn']);
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
        $attributes = $fn->getArgument('value');
        if (!empty($attributes)) {
            MatchPluginCallbacks::getFromMatch($match)->addKeepAlwaysAttributes(\explode(',', $attributes));
        }
        return \true;
    }
}
