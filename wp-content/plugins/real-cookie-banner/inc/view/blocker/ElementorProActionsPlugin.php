<?php

namespace DevOwl\RealCookieBanner\view\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils as FastHtmlTagUtils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\SelectorSyntaxMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview\ImagePreview;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Block `#elementor-action' links containing base64-encoded data.
 *
 * @see https://app.clickup.com/t/3204cj6
 * @internal
 */
class ElementorProActionsPlugin extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $cb = $this->getHeadlessContentBlocker();
        $cb->addSelectorSyntaxMap([
            // [Plugin Comp] Elementor Pro actions
            'a[href^="#elementor-action"]',
        ]);
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
        if ($matcher instanceof SelectorSyntaxMatcher) {
            /**
             * Var.
             *
             * @var SelectorSyntaxMatch
             */
            $match = $match;
            if ($match->getFinder()->getExpression() === 'a[href^="#elementor-action"]') {
                \parse_str(\urldecode($match->getLink()), $value);
                if (isset($value['settings'])) {
                    $value = Utils::isJson(\base64_decode($value['settings']));
                    if ($value !== \false && isset($value['url'])) {
                        $matcher->iterateBlockablesInString($result, $value['url']);
                        if ($result->isBlocked()) {
                            $match->setAttribute(Constants::HTML_ATTRIBUTE_VISUAL_PARENT, '.elementor-widget-container');
                            $match->setAttribute(ImagePreview::HTML_ATTRIBUTE_TO_FETCH_URL_FROM, $value['url']);
                            $match->setAttribute(Constants::HTML_ATTRIBUTE_CLICK_DISPATCH_RESIZE, '1000');
                        }
                    }
                }
            }
        }
        return $result;
    }
}
