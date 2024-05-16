<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent;

/**
 * A transaction simply describes a new consent.
 * @internal
 */
class Transaction
{
    /**
     * A set of accepted cookie groups + cookies or a predefined set like `all` or `essentials`.
     *
     * @var array|string
     */
    public $decision;
    /**
     * The IP address of the website visitor.
     *
     * @var string
     */
    public $ipAddress;
    /**
     * The user agent of the website visitor.
     *
     * @var string
     */
    public $userAgent;
    /**
     * Mark as DNT.
     *
     * @var boolean
     */
    public $markAsDoNotTrack = \false;
    /**
     * The clicked button in the cookie banner.
     *
     * @var string
     */
    public $buttonClicked;
    /**
     * The viewport width.
     *
     * @var int
     */
    public $viewPortWidth = 0;
    /**
     * The viewport height.
     *
     * @var int
     */
    public $viewPortHeight = 0;
    /**
     * Referer.
     *
     * @var string
     */
    public $referer;
    /**
     * If the consent came from a content blocker, the ID of the content blocker.
     *
     * @var int
     */
    public $blocker = 0;
    /**
     * Can be the ID of the blocker thumbnail itself, or in format of `{embedId}-{fileMd5}`.
     *
     * @var int|string
     */
    public $blockerThumbnail;
    /**
     * The reference to the consent ID of the source website (only for forwarded consents).
     *
     * @var int
     */
    public $forwarded = 0;
    /**
     * The UUID reference of the source website.
     *
     * @var string
     */
    public $forwardedUuid;
    /**
     * Determine if forwarded consent came through a content blocker.
     *
     * @var boolean
     */
    public $forwardedBlocker = \false;
    /**
     * TCF string.
     *
     * @var string
     */
    public $tcfString;
    /**
     * Google Consent Mode consent types.
     *
     * @var string[]
     */
    public $gcmConsent;
    /**
     * Allows to set a custom bypass which causes the banner to be hidden (e.g. Geolocation)
     *
     * @var string
     */
    public $customBypass;
    /**
     * The ISO string of `new Date().toISOString()` on client side which reflects the time of consent given (not persist time).
     *
     * @var string
     */
    public $createdClientTime;
    /**
     * Recorder JSON string for Replays.
     *
     * @var string
     */
    public $recorderJsonString;
    /**
     * Can be `initial` (the cookie banner pops up for first time with first and second layer or content blocker) or `change` (Change privacy settings). `null` indicates a UI was never visible.
     *
     * @var string
     */
    public $uiView;
    /**
     * The country of the website visitor. This is automatically calculated when you pass in an
     * IP address and you have enabled Geo Restriction (Country Bypass).
     *
     * @var string
     */
    public $userCountry;
    /**
     * When `false`, the newly added transaction will not return any `SetCookie` instances. Use this, if you just want to save / persist
     * a consent to the database but should not be updated on client side (e.g. delayed consents when server was not reachable).
     */
    public $setCookies = \true;
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
