<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\Consent;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle IPs hashed correctly depending on settings.
 * @internal
 */
class IpHandler
{
    use UtilsProvider;
    const FLOODING_SECONDS = 60;
    const FLOODING_MAX_ENTRIES_IN_DEFINED_SECONDS = 255;
    /**
     * Singleton instance.
     *
     * @var IpHandler
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
     * Checks the current IP if it is already persisted, it avoids to
     * flood the API all x seconds.
     *
     * @return boolean Returns true if you are allowed to create a new consent
     */
    public function isFlooding()
    {
        /**
         * IP flooding is implemented to forbid a new user consent by max requests to the API.
         * Here you can modify the flooding seconds.
         *
         * @hook RCB/IpHandler/FloodingSeconds
         * @param {int} $seconds
         * @returns {int}
         */
        $floodingSeconds = \intval(\apply_filters('RCB/IpHandler/FloodingSeconds', self::FLOODING_SECONDS));
        /**
         * IP flooding is implemented to forbid a new user consent by max requests to the API.
         * Here you can modify the maximum allowed entries within the given flooding seconds.
         *
         * @hook RCB/IpHandler/FloodingMaxEntries
         * @param {int} $max
         * @returns {int}
         */
        $floodingMaxEntries = \intval(\apply_filters('RCB/IpHandler/FloodingMaxEntries', self::FLOODING_MAX_ENTRIES_IN_DEFINED_SECONDS));
        $result = \DevOwl\RealCookieBanner\UserConsent::getInstance()->byCriteria(['ip' => \DevOwl\RealCookieBanner\Utils::getIpAddress(), 'exactIp' => \false, 'sinceSeconds' => $floodingSeconds], \DevOwl\RealCookieBanner\UserConsent::BY_CRITERIA_RESULT_TYPE_COUNT);
        $isFlooding = \intval($result) > $floodingMaxEntries;
        /**
         * IP flooding is implemented to forbid a new user consent by max requests to the API.
         *
         * @hook RCB/IpHandler/Flooding
         * @param {boolean} isFlooding
         * @returns {boolean}
         */
        return \boolval(\apply_filters('RCB/IpHandler/Flooding', $isFlooding));
    }
    /**
     * Get persistable IP for database. It also respects IP settings.
     * If the IP is not stored, we need to truncate the last IP block so it
     * is annonymous.
     *
     * @param string $ip Default current IP
     * @param boolean $saveIp Determines if the return result should contain persitable `ipv4` and `ipv6` values, defaults to Consent > "Save IP" option
     * @see https://www.onlinemarketingrecht.de/2017/09/ip-adressen-anonymisieren-wann-wie-und-warum/
     */
    public function persistIp($ip = null, $saveIp = null)
    {
        $saveIp = $saveIp === null ? Consent::getInstance()->isSaveIpEnabled() : $saveIp;
        $ip = $ip === null ? \DevOwl\RealCookieBanner\Utils::getIpAddress() : $ip;
        $ipVersion = \strpos($ip, ':') === \false ? 4 : 6;
        $ipDel = $ipVersion === 6 ? ':' : '.';
        // Truncate plain IP for annonymous hashing
        $truncateIp = \explode($ipDel, $ip);
        \array_pop($truncateIp);
        $truncateIp[] = 0;
        $truncateIp = \join($ipDel, $truncateIp);
        // Always use the truncated IP as hash
        $hashIp = \DevOwl\RealCookieBanner\Utils::hash($truncateIp);
        $result = ['ipv4' => null, 'ipv6' => null, 'ipv4_hash' => null, 'ipv6_hash' => null];
        if ($ipVersion === 4) {
            if ($saveIp) {
                $result['ipv4'] = \ip2long($ip);
            }
            $result['ipv4_hash'] = $hashIp;
        } else {
            if ($saveIp) {
                $result['ipv6'] = \inet_pton($ip);
            }
            $result['ipv6_hash'] = $hashIp;
        }
        return $result;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\IpHandler() : self::$me;
    }
}
