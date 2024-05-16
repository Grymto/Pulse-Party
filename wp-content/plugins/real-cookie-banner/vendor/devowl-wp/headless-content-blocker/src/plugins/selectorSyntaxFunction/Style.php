<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `style()`.
 *
 * ```
 * div[class="my-div":style(this=display:block&rule[]=>.my-selector{display:table;width:100%;})]
 * ```
 *
 * Parameters:
 *
 * - `[this]` (string): CSS rules applied to the blocked element
 * - `[applyUnblocked=false]` (boolean): If true, the styles will still be applied after unblocking the element
 * - `[rules]` (string[]): An array of CSS selectors and rules relative to the blocked element
 *
 * The above example will result in the following CSS which modifies the style of the blocked element:
 *
 * ```
 * <style>
 *   .my-div{display:block;}
 *   .my-div>.selector{display:table;width:100%;}
 * </style>
 * ```
 * @internal
 */
class Style extends AbstractPlugin
{
    private $counter = 0;
    const INVISIBLE_ATTRIBUTE_ALREADY_SET = 'selector-syntax-function-style';
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('style', [$this, 'fn']);
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
        $selectors = $fn->getArguments();
        $applyUnblocked = $fn->getArgument('applyUnblocked');
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match, $selectors, $applyUnblocked) {
            if ($result->isBlocked() && !$match->hasInvisibleAttribute(self::INVISIBLE_ATTRIBUTE_ALREADY_SET)) {
                $selectorId = \sprintf('consent-style-%d', ++$this->counter);
                $selector = \sprintf('%s%s[%s]', $match->getTag(), $applyUnblocked === 'true' ? '' : \sprintf('[%s]', Constants::HTML_ATTRIBUTE_COOKIE_IDS), $selectorId);
                $match->setAttribute($selectorId, \true);
                $styles = [];
                foreach ($selectors as $argName => $value) {
                    if ($argName === 'this') {
                        $styles[] = \sprintf('%s{%s}', $selector, $value);
                    } elseif ($argName === 'rule') {
                        $value = \is_array($value) ? $value : [$value];
                        foreach ($value as $ruleValue) {
                            $strpos = \strpos($ruleValue, '{');
                            if ($strpos !== \false) {
                                $styles[] = \sprintf('%s %s%s', $selector, \substr($ruleValue, 0, $strpos), \substr($ruleValue, $strpos));
                            }
                        }
                    }
                }
                $styles = \sprintf('<style>%s</style>', \join('', $styles));
                $beforeTag = $match->getBeforeTag();
                if (\strpos($beforeTag, $styles) === \false) {
                    $match->setBeforeTag($beforeTag . $styles);
                    $match->setInvisibleAttribute(self::INVISIBLE_ATTRIBUTE_ALREADY_SET, \true);
                }
            }
        });
        return \true;
    }
}
