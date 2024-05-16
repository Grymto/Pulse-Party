<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Utils;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Clear server-side cookies like `http` cookies.
 * @internal
 */
class Clear
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var Clear
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Clear cookies by cookie ids.
     *
     * @param int[] $cookies
     */
    public function byCookies($cookies)
    {
        $result = [];
        foreach (CookieGroup::getInstance()->getOrdered() as $group) {
            foreach (Cookie::getInstance()->getOrdered($group->term_id) as $cookie) {
                foreach ($cookies as $cookieId) {
                    if ($cookie->ID === $cookieId) {
                        foreach ($cookie->metas[Cookie::META_NAME_TECHNICAL_DEFINITIONS] as $def) {
                            $result[$cookieId] = $this->byTechnicalDefinition($def);
                        }
                    }
                }
            }
        }
        /**
         * Opt-out to a specific cookie. You can use this action to server-side clear a specific
         * cookie if it is not possible due to possible RCB limitations.
         *
         * @hook RCB/OptOut/ByCookies
         * @param {int[]} $ids Opt out by cookie id (post type)
         */
        \do_action('RCB/OptOut/ByCookies', $cookies);
        return $result;
    }
    /**
     * Clear a cookie by technical definition.
     *
     * @param array $technicalDefinition
     */
    public function byTechnicalDefinition($technicalDefinition)
    {
        $result = [];
        $type = $technicalDefinition['type'];
        $name = $technicalDefinition['name'];
        $host = $technicalDefinition['host'];
        switch ($type) {
            case 'http':
                $result['http'] = $this->byHttpCookieName($name, $host);
                break;
            default:
                break;
        }
        /**
         * Opt-out to a specific technical definition (a cookie has more technical definitions).
         * You can use this action to server-side clear a specific cookie if it is not possible
         * due to possible RCB limitations.
         *
         * @hook RCB/OptOut/ByTechnicalDefinition
         * @param {array} $technicalDefinition
         */
        \do_action('RCB/OptOut/ByTechnicalDefinition', $technicalDefinition);
        return $result;
    }
    /**
     * Unset a cookie from the same host by name.
     *
     * @param string $name
     * @param string $host
     * @see https://stackoverflow.com/a/19972329/5506547
     */
    public function byHttpCookieName($name, $host)
    {
        $result = [];
        $pattern = Utils::createRegexpPatternFromWildcardName($name);
        // Find by regexp
        foreach (\array_keys($_COOKIE) as $key) {
            \preg_match_all($pattern, $key, $matches, \PREG_SET_ORDER, 0);
            if (!empty($matches)) {
                unset($_COOKIE[$key]);
                // unset for this page request
                $result[$key] = \setcookie($key, '', -1, '/', $host);
                // only 1st party is supported here
            }
        }
        /**
         * Opt-out to a specific HTTP cookie. You can use this action to server-side clear
         * a specific cookie if it is not possible due to possible RCB limitations.
         *
         * @hook RCB/OptOut/ByHttpCookie
         * @param {string} $name
         * @param {string} $host
         */
        \do_action('RCB/OptOut/ByHttpCookie', $name, $host);
        return $result;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\Clear() : self::$me;
    }
}
