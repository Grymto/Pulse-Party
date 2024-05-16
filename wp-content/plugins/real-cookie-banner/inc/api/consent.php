<?php

// namespace DevOwl\RealCookieBanner\Vendor; // excluded from scope due to API exposing

use DevOwl\RealCookieBanner\MyConsent;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
if (!\function_exists('wp_rcb_consent_given')) {
    /**
     * Check if a given technical information (e.g. HTTP Cookie, LocalStorage, ...) has a consent:
     *
     * - When a technical information exists in defined cookies, the Promise is only resolved after given consent
     * - When no technical information exists, the Promise is immediate resolved
     *
     * ```php
     * $consent = function_exists('wp_rcb_consent_given') ? wp_rcb_consent_given("http", "_twitter_sess", ".twitter.com") : true;
     * ```
     *
     * You can also check for consent by cookie ID (ID in `wp_posts`, post id):
     *
     * ```php
     * $consent = function_exists('wp_rcb_consent_given') ? wp_rcb_consent_given(15) : true;
     * ```
     *
     * **Attention:** Do not use this function if you can get the conditional consent into your frontend
     * coding and use instead the `window.consentApi`!
     *
     * @param string|int $typeOrId
     * @param string $name
     * @param string $host
     * @since 2.11.1
     * @internal
     */
    function wp_rcb_consent_given($typeOrId, $name = null, $host = null)
    {
        return MyConsent::getInstance()->getCurrentUser()->hasConsent($typeOrId, $name, $host);
    }
}
