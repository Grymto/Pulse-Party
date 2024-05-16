<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\StyleInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\StyleInlineAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Markup;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\ScriptInlineMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\SelectorSyntaxMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\StyleInlineAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\StyleInlineMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\ScriptInlineExtractExternalUrl;
/**
 * Provide a scanner for our content blocker. That means, you can pass `ScannableBlockable` instances
 * to your `HeadlessContentBlocker` instance and you can continually fetch the scanned blockables from this plugin instance.
 * @internal
 */
class BlockableScanner extends AbstractPlugin
{
    const IGNORE_LINK_REL = ['profile', 'author', 'shortlink', 'canonical'];
    const BLOCKED_RESULT_DATA_KEY_IGNORE_IN_SCANNER = 'Plugin.BlockableScanner.ignore-in-scanner';
    /**
     * Scan results.
     *
     * @var ScanEntry[]
     */
    private $results = [];
    private $active = \true;
    /**
     * A list of excluded hosts.
     *
     * @param string[]
     */
    private $excludeHosts = [];
    /**
     * See `AbstractPlugin`. Needed to obtain external URLs.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function notBlockedMatch($result, $matcher, $match)
    {
        $this->blockedMatch($result, $matcher, $match);
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if (!$this->active || $result->getData(self::BLOCKED_RESULT_DATA_KEY_IGNORE_IN_SCANNER)) {
            return;
        }
        if ($matcher instanceof TagAttributeMatcher) {
            /**
             * Var.
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            $this->processBlockedByTagAttributeMatch($result, $match->getLinkAttribute(), $match->getLink());
        } elseif ($matcher instanceof ScriptInlineMatcher) {
            $this->processBlockedByScriptInlineMatch($result);
        } elseif ($matcher instanceof StyleInlineMatcher || $matcher instanceof StyleInlineAttributeMatcher) {
            /**
             * Var.
             *
             * @var StyleInlineMatch|StyleInlineAttributeMatch
             */
            $match = $match;
            $blockedUrls = $result->getData('blockedUrls') ?? [];
            foreach ($blockedUrls as $blockedUrl) {
                $this->probablyMemorizeIsBlocked($result, $blockedUrl, $match->getTag(), null);
            }
        } elseif ($matcher instanceof SelectorSyntaxMatcher) {
            $this->processBlockedBySelectorSyntax($result, $match);
        }
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function beforeMatch($matcher, $match)
    {
        $this->getHeadlessContentBlocker()->setAllowMultipleBlockerResults(\true);
    }
    /**
     * Memorize when a content got blocked through a non-created template.
     *
     * @param BlockedResult $isBlocked
     * @param string $linkAttribute
     * @param string $link
     */
    protected function processBlockedByTagAttributeMatch($isBlocked, $linkAttribute, $link)
    {
        // Check for some edge cases we want to exclude
        $attributes = $isBlocked->getAttributes();
        $tag = $isBlocked->getTag();
        $markup = $isBlocked->getMarkup();
        if ($tag === 'link' && isset($attributes['rel']) && \in_array($attributes['rel'], self::IGNORE_LINK_REL, \true)) {
            return $isBlocked;
        }
        if (!$this->probablyMemorizeIsBlocked($isBlocked, $link, $tag, $linkAttribute) && !$this->isStyleAttributeFalsePositive($linkAttribute, $link)) {
            $this->probablyMemorizeExternalUrl($isBlocked, $link, $tag, $linkAttribute, $markup);
        }
        return $isBlocked;
    }
    /**
     * Memorize when an inline script got blocked through a non-created template.
     *
     * @param BlockedResult $isBlocked
     */
    protected function processBlockedByScriptInlineMatch($isBlocked)
    {
        // Check if the `ScriptInlineExtractExternalUrl` plugin were able to extract an URL
        $scriptInlineExtractExternalUrl = $isBlocked->getData(ScriptInlineExtractExternalUrl::BLOCKED_RESULT_DATA_KEY);
        $added = $this->probablyMemorizeIsBlocked($isBlocked, $scriptInlineExtractExternalUrl, 'script', null);
        if (!empty($scriptInlineExtractExternalUrl) && !$added && !$isBlocked->isBlocked()) {
            $this->probablyMemorizeExternalUrl($isBlocked, $scriptInlineExtractExternalUrl, 'script', null, $isBlocked->getMarkup());
        }
        return $isBlocked;
    }
    /**
     * Memorize when a custom element by CSS Selector got blocked through a non-created template.
     *
     * @param BlockedResult $isBlocked
     * @param SelectorSyntaxMatch $m
     */
    protected function processBlockedBySelectorSyntax($isBlocked, $m)
    {
        $possibleUrl = $m->getLink();
        $this->probablyMemorizeIsBlocked($isBlocked, $this->isNotAnExcludedUrl($possibleUrl) ? $possibleUrl : null, $m->getTag(), $m->getLinkAttribute());
        return $isBlocked;
    }
    /**
     * Probably memorize an external URL when it got not blocked through template nor created content blocker.
     *
     * @param BlockedResult $isBlocked
     * @param string $url
     * @param string $tag
     * @param string $attribute
     * @param Markup|null $markup
     */
    protected function probablyMemorizeExternalUrl($isBlocked, $url, $tag, $attribute, $markup)
    {
        if (!$isBlocked->isBlocked() && !\in_array($tag, ['a'], \true) && $this->isNotAnExcludedUrl($url)) {
            $this->results[] = $entry = new ScanEntry();
            $entry->blocked_url = \strpos($url, '//') === 0 ? 'https:' . $url : $url;
            $entry->tag = $tag;
            $entry->attribute = $attribute;
            $entry->markup = $markup;
        }
    }
    /**
     * Probably memorize a blocked content.
     *
     * @param BlockedResult $isBlocked
     * @param string|null $url
     * @param string $tag
     * @param string $attribute
     */
    protected function probablyMemorizeIsBlocked($isBlocked, $url, $tag, $attribute)
    {
        $found = 0;
        // At least one non-existing created template was used to block this content
        foreach ($isBlocked->getBlocked() as $blockable) {
            if ($blockable instanceof ScannableBlockable) {
                $this->results[] = $entry = new ScanEntry();
                $entry->blockable = $blockable;
                $entry->template = $blockable->getIdentifier();
                $entry->blocked_url = $url !== null && \strpos($url, '//') === 0 ? 'https:' . $url : $url;
                $entry->tag = $tag;
                $entry->attribute = $attribute;
                $entry->expressions = $isBlocked->getBlockedExpressions();
                // Inline scripts, styles and `SelectorSyntaxBlocker` support also the markup
                $entry->markup = $isBlocked->getMarkup();
                ++$found;
            }
        }
        return $found > 0;
    }
    /**
     * Give any URL and the host is completely excluded from the scanner results. In most
     * cases, you need to exclude your own host!
     *
     * @param string $url
     */
    public function excludeHostByUrl($url)
    {
        $currentHost = \parse_url($url, \PHP_URL_HOST);
        $this->excludeHosts[] = $currentHost;
        $this->excludeHosts[] = \sprintf('www.%s', $currentHost);
        $this->excludeHosts[] = \preg_replace('/^www\\./', '', $currentHost);
    }
    /**
     * See `FalsePositivesProcessor`.
     */
    public function filterFalsePositives()
    {
        $falsePositivesProcess = new FalsePositivesProcessor($this, $this->results);
        $this->results = $falsePositivesProcess->process();
    }
    /**
     * Check if a given URL is not excluded from our hosts.
     *
     * @param string $url
     */
    public function isNotAnExcludedUrl($url)
    {
        return \is_array($parseUrl = \parse_url($url)) && isset($parseUrl['host']) && \filter_var(\sprintf('http://%s', $parseUrl['host']), \FILTER_VALIDATE_URL) && !\in_array($parseUrl['host'], $this->excludeHosts, \true);
        // Exclude some hosts (e.g. ourself);
    }
    /**
     * Example: `<div style="opacity:0"`. Here, the `opacity:0` could be considered as external URL.
     *
     * @param string $linkAttribute
     * @param string $link
     */
    public function isStyleAttributeFalsePositive($linkAttribute, $link)
    {
        return $linkAttribute === 'style' && \preg_match('/^\\s*[A-Za-z-_]+:\\s*\\d+\\s*[;]*\\s*$/m', $link);
    }
    /**
     * Reset the scanner and return the found results.
     */
    public function flushResults()
    {
        $result = $this->results;
        $this->results = [];
        return $result;
    }
    /**
     * Setter.
     *
     * @param boolean $active
     * @codeCoverageIgnore
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
