<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Provide an object for a given match with match-callbacks which can be used within plugins.
 * This is similar to callback of `HeadlessContentBlocker`, but individual to a match.
 *
 * This class does not contain yet all plugin APIs as from `AbstractPlugin`, but will be
 * implemented as needed.
 * @internal
 */
class MatchPluginCallbacks
{
    private $match;
    private $keepAlwaysAttributes = [];
    private $blockedMatchCallbacks = [];
    /**
     * C'tor.
     *
     * @param AbstractMatch $match
     * @param AbstractMatcher $matcher
     */
    public function __construct($match)
    {
        $this->match = $match;
    }
    /**
     * In some cases we need to keep the attributes as original instead of prefix it with `consent-original-`.
     * Keep in mind, that no external data should be loaded if the attribute is set!
     *
     * @param string[] $attributes
     */
    public function addKeepAlwaysAttributes($attributes)
    {
        $this->keepAlwaysAttributes = \array_merge($this->keepAlwaysAttributes, $attributes);
    }
    /**
     * Add a callable after a blocked match got found so you can alter the match again. Parameters:
     * `BlockedResult $result, MatchPluginCallbacks $matchPluginCallbacks, AbstractMatcher $matcher`.
     *
     * @param callable $callback
     */
    public function addBlockedMatchCallback($callback)
    {
        $this->blockedMatchCallbacks[] = $callback;
    }
    /**
     * Run registered blocked-match callbacks.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     */
    public function runBlockedMatchCallback($result, $matcher)
    {
        foreach ($this->blockedMatchCallbacks as $callback) {
            $callback($result, $this, $matcher);
        }
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getKeepAlwaysAttributes()
    {
        return $this->keepAlwaysAttributes;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMatch()
    {
        return $this->match;
    }
    /**
     * We are using `AbstractMatch#data` to retrieve and set the plugin callbacks instance, lazily.
     *
     * @param AbstractMatch $match
     * @return MatchPluginCallbacks
     */
    public static function getFromMatch($match)
    {
        $current = $match->getData(self::class);
        if ($current === null) {
            $current = new MatchPluginCallbacks($match);
            $match->setData(self::class, $current);
        }
        return $current;
    }
}
