<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxFinder;
/**
 * Describe a blockable item by selector syntax and regular expressions (e.g. to be used in `href` and `src`).
 * @internal
 */
abstract class AbstractBlockable
{
    /**
     * See `SelectorSyntaxFinder`.
     *
     * @var SelectorSyntaxFinder[]
     */
    private $selectorSyntaxFinder = [];
    private $regexp = ['wildcard' => [], 'contains' => []];
    private $headlessContentBlocker;
    /**
     * Original rules from string rules.
     *
     * @var string[]
     */
    private $originalExpressions = [];
    /**
     * C'tor.
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     */
    public function __construct($headlessContentBlocker)
    {
        $this->headlessContentBlocker = $headlessContentBlocker;
    }
    /**
     * Generate the custom element blockers and regular expressions and append
     * it to this blockable instance.
     *
     * @param string[] $blockers
     */
    public function appendFromStringArray($blockers)
    {
        if (!\is_array($blockers)) {
            return;
        }
        // Filter out custom element expressions
        foreach ($blockers as $idx => &$line) {
            $line = $this->headlessContentBlocker->runBlockableStringExpressionCallback($line, $this);
            $selectorSyntaxFinder = SelectorSyntaxFinder::fromExpression($line);
            if ($selectorSyntaxFinder !== \false) {
                $selectorSyntaxFinder->setFastHtmlTag($this->headlessContentBlocker);
                unset($blockers[$idx]);
                $this->selectorSyntaxFinder[] = $selectorSyntaxFinder;
                $this->originalExpressions[] = $line;
            }
        }
        foreach ($blockers as $expression) {
            $this->regexp['wildcard'][$expression] = Utils::createRegexpPatternFromWildcardName($expression);
            $this->originalExpressions[] = $expression;
        }
        // Force to wildcard all hosts look like a `contains`
        foreach ($blockers as $host) {
            $this->regexp['contains'][$host] = Utils::createRegexpPatternFromWildcardName('*' . $host . '*');
        }
    }
    /**
     * Find a `SyntaxSelectorFinder` for a given `AbstractMatch`.
     *
     * @param AbstractMatch $match
     */
    public function findSelectorSyntaxFinderForMatch($match)
    {
        foreach ($this->getSelectorSyntaxFinder() as $selectorSyntaxFinder) {
            if ($selectorSyntaxFinder->getTag() === $match->getTag()) {
                if ($selectorSyntaxFinder->matchesAttributes($selectorSyntaxFinder->getAttributes(), $match)) {
                    return $selectorSyntaxFinder;
                }
            }
        }
        return null;
    }
    /**
     * Get the blocker ID. This is added as a custom HTML attribute to the blocked
     * element so your frontend can e.g. add a visual content blocker.
     *
     * @return int|null
     */
    public abstract function getBlockerId();
    /**
     * Get required IDs. This is added as a custom HTML attribute to the blocked
     * element so your frontend can determine which items by ID are needed so the
     * item can be unblocked.
     *
     * @return (int|string)[]
     */
    public abstract function getRequiredIds();
    /**
     * The criteria type. This is added as a custom HTML attribute to the blocked
     * element so your frontend can determine the origin for the `getRequiredIds`.
     * E.g. differ between TCF vendors and another custom criteria.
     *
     * @return string
     */
    public abstract function getCriteria();
    /**
     * Determine if this blockable should be blocked.
     */
    public function hasBlockerId()
    {
        return $this->getBlockerId() !== null;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getSelectorSyntaxFinder()
    {
        return $this->selectorSyntaxFinder;
    }
    /**
     * Getter.
     *
     * @return string[]
     */
    public function getRegularExpressions()
    {
        return $this->regexp['wildcard'] ?? [];
    }
    /**
     * Getter.
     *
     * @return string[]
     */
    public function getContainsRegularExpressions()
    {
        return $this->regexp['contains'] ?? [];
    }
    /**
     * Getter.
     *
     * @return string[]
     */
    public function getOriginalExpressions()
    {
        return $this->originalExpressions;
    }
}
