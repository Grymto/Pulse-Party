<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
use Exception;
/**
 * This plugin registers the selector syntax `transformAttribute()`.
 *
 * ```
 * div[class="my-class":transformAttribute(name=data-id,target=my-data-id)]
 * ```
 *
 * Parameters:
 *
 * - `[name]` (string): Name of the attribute which should be modified (default is the current attribute)
 * - `[regexpMatch]` (string): Match the attribute value by regular expression
 * - `[regexpReplace]` (string): When given, use this new value (use e.g. $1 to match the first regexp group of regexpMatch). When no regexpMatch is given, it defaults to the whole value
 * - `[target]` (string): Put the value to this new attribute (does not work in conjunction with `rename`)
 * - `[rename="new-attribute-name"]` (string): Rename attribute instead of adding a new one
 * ```
 * @internal
 */
class TransformAttribute extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('transformAttribute', [$this, 'fn']);
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
        $name = $fn->getArgument('name', $fn->getAttributeName());
        $rename = $fn->getArgument('rename');
        $target = $fn->getArgument('target');
        $regexpMatch = $fn->getArgument('regexpMatch', '/(.*)/');
        $regexpReplace = $fn->getArgument('regexpReplace');
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match, $name, $rename, $target, $regexpMatch, $regexpReplace) {
            if ($result->isBlocked()) {
                $attributeValue = $match->getAttribute($name);
                if ($attributeValue) {
                    $newValue = $attributeValue;
                    if (\is_string($newValue) && !empty($regexpReplace)) {
                        try {
                            $newValue = \preg_replace($regexpMatch, $regexpReplace, $newValue);
                        } catch (Exception $e) {
                            // Silence is golden.
                        }
                        if (!\is_string($newValue)) {
                            $newValue = $attributeValue;
                        }
                    }
                    if (!empty($rename)) {
                        $match->setAttribute($rename, $newValue);
                        $match->setAttribute($name, null);
                    } elseif (!empty($target)) {
                        $match->setAttribute($target, $newValue);
                    }
                }
            }
        });
        return \true;
    }
}
