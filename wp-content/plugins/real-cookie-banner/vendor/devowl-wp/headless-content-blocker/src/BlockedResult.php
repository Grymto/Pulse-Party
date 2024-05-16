<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker;

/**
 * A blocked result defines a "blocked" content while processing the content blocker itself.
 * @internal
 */
class BlockedResult
{
    private $blocked = [];
    private $blockedExpressions = [];
    private $tag;
    private $attributes;
    private $markup;
    private $data = [];
    /**
     * C'tor.
     *
     * @param string $tag
     * @param array $attributes
     * @param string $markup
     * @codeCoverageIgnore
     */
    public function __construct($tag, $attributes, $markup)
    {
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->markup = Markup::persist($markup);
    }
    /**
     * Disable blocking.
     */
    public function disableBlocking()
    {
        $this->setBlocked([]);
        $this->setBlockedExpressions([]);
    }
    /**
     * Mark the content to be blocked.
     *
     * @param AbstractBlockable[] $blocked Can also be `[]` to deactivate blocking
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
    }
    /**
     * Add blocked description (to allow multiple blockables cover this blocked content).
     *
     * @param AbstractBlockable $blocked
     */
    public function addBlocked($blocked)
    {
        $this->blocked[] = $blocked;
    }
    /**
     * Mark the content to be blocked through given expressions.
     *
     * @param string[] $expressions Can also be `[]`
     */
    public function setBlockedExpressions($expressions)
    {
        $this->blockedExpressions = $expressions;
    }
    /**
     * Mark the content to be blocked through a given expression.
     *
     * @param string $expression
     */
    public function addBlockedExpression($expression)
    {
        $this->blockedExpressions[] = $expression;
        $this->blockedExpressions = \array_unique($this->blockedExpressions);
    }
    /**
     * Allows to set additional data for this blocked result.
     *
     * @param string $key
     * @param mixed $data
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;
    }
    /**
     * Get additional blocking data.
     *
     * @param string $key
     */
    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }
    /**
     * Should this content be blocked?
     */
    public function isBlocked()
    {
        return \count($this->blocked) > 0;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBlocked()
    {
        return $this->blocked;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getFirstBlocked()
    {
        return \count($this->blocked) > 0 ? $this->blocked[0] : null;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBlockedExpressions()
    {
        return $this->blockedExpressions;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTag()
    {
        return $this->tag;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMarkup()
    {
        return $this->markup;
    }
}
