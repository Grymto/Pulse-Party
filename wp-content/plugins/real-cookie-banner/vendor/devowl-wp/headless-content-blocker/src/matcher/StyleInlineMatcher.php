<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\StyleInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
/**
 * Block inline `<style>`'s. This is a special use case and we need to go one step further:
 * The complete inline style is parsed to an abstract tree (AST) and all rules with an
 * URL are blocked individually.
 * @internal
 */
class StyleInlineMatcher extends AbstractMatcher
{
    /**
     * See `AbstractMatcher`.
     *
     * @param StyleInlineMatch $match
     */
    public function match($match)
    {
        $result = $this->createResult($match);
        if (!$result->isBlocked()) {
            return $result;
        }
        $cb = $this->getHeadlessContentBlocker();
        $extract = $cb->runInlineStyleShouldBeExtractedByCallback(\true, $this, $match);
        $cssBlocker = new CssBlocker($extract, $match, $this);
        list($document, $extractedDocument, $blockedUrls) = $cssBlocker->parse();
        $cb->runInlineStyleModifyDocumentsCallback($document, $extractedDocument, $this, $match);
        if ($extractedDocument !== null) {
            $blockedStyle = $extractedDocument->render();
            // Return original document as we did not found any values that we needed to block
            if (!AttributesHelper::hasCssDocumentConsentRules($blockedStyle)) {
                $result->disableBlocking();
                return $result;
            }
            $match->setStyle($document->render());
            $match->setAfterTag(\sprintf('<%1$s %2$s></%1$s>', 'script', Utils::htmlAttributes(\array_merge([Constants::HTML_ATTRIBUTE_INLINE_STYLE => $blockedStyle, Constants::HTML_ATTRIBUTE_TYPE_NAME => Constants::HTML_ATTRIBUTE_TYPE_VALUE], $match->getAttributes()))));
            $match->setAttributes([]);
        } else {
            $match->setAttribute(Constants::HTML_ATTRIBUTE_INLINE_STYLE, $document->render());
            $match->setAttribute(Constants::HTML_ATTRIBUTE_TYPE_NAME, Constants::HTML_ATTRIBUTE_TYPE_VALUE);
            $match->setTag('script');
            $match->setStyle('');
        }
        $result->setData('blockedUrls', $blockedUrls);
        return $result;
    }
    /**
     * See `AbstractMatcher`.
     *
     * @param StyleInlineMatch $match
     */
    public function createResult($match)
    {
        $result = $this->createPlainResultFromMatch($match);
        if ($match->isCSS()) {
            $this->iterateBlockablesInString($result, $match->getStyle(), \true, \true);
        }
        $this->probablyDisableDueToSkipped($result, $match);
        return $this->applyCheckResultHooks($result, $match);
    }
}
