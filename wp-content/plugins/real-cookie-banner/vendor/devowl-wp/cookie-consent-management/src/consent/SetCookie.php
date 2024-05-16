<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent;

/**
 * Every commit to a `Consent` produces a set of cookies which need to be set on the client's website.
 * @internal
 */
class SetCookie
{
    /**
     * Cookie key.
     *
     * @var string
     */
    public $key;
    /**
     * Cookie value.
     *
     * @var string
     */
    public $value;
    /**
     * Cookie expire.
     *
     * @var int
     */
    public $expire;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        // Silence is golden.
    }
}
