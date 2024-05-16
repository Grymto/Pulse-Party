<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent\Transaction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\BannerLink as SettingsBannerLink;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\BannerLink;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\view\Banner;
use DevOwl\RealCookieBanner\view\Blocker;
use DevOwl\RealCookieBanner\view\blocker\Plugin;
use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle consents of "me".
 * @internal
 */
class MyConsent
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var MyConsent
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
     * Persist an user consent to the database.
     *
     * @param Transaction $transaction
     * @param boolean $dummy If `true`, no data will saved in database
     */
    public function persist($transaction, $dummy = \false)
    {
        global $wpdb;
        $table_name_blocker_thumbnails = $this->getTableName(Plugin::TABLE_NAME_BLOCKER_THUMBNAILS);
        $blockerThumbnail = $transaction->blockerThumbnail;
        if (\is_string($blockerThumbnail)) {
            $blockerThumbnailSplit = \explode('-', $blockerThumbnail, 2);
            if (\count($blockerThumbnailSplit) > 1) {
                $blockerThumbnail = $wpdb->get_var(
                    // phpcs:disable WordPress.DB.PreparedSQL
                    $wpdb->prepare("SELECT id FROM {$table_name_blocker_thumbnails} WHERE embed_id = %s AND file_md5 = %s", $blockerThumbnailSplit[0], $blockerThumbnailSplit[1])
                );
                // Blocker thumbnail does not exist - this cannot be the case (expect user deletes database table entries)
                $blockerThumbnail = \is_numeric($blockerThumbnail) ? \intval($blockerThumbnail) : null;
            } else {
                $blockerThumbnail = null;
            }
            $transaction->blockerThumbnail = $blockerThumbnail;
        }
        $consent = $this->getCurrentUser();
        $previousDecision = $consent->getDecision() ?? \false;
        $previousTcfString = $this->isPro() ? $consent->getTcfString() ?? 'NULL' : 'NULL';
        $previousGcmConsent = $this->isPro() ? $consent->getGcmConsent() ?? \false : \false;
        $previousCreated = $consent->getCreated() ?? \false;
        $revision = $consent->getCookieConsentManagement()->getRevision();
        $revisionHash = $revision->create('force', \false)['hash'];
        $revisionIndependentHash = $revision->createIndependent(\true)['hash'];
        $ips = \DevOwl\RealCookieBanner\IpHandler::getInstance()->persistIp();
        $contextString = $revision->getPersistence()->getContextVariablesString();
        $transaction->ipAddress = \DevOwl\RealCookieBanner\Utils::getIpAddress();
        $result = $consent->commit($transaction, function () use($transaction, $ips, $consent, $revisionHash, $revisionIndependentHash, $contextString, $previousDecision, $previousTcfString, $previousGcmConsent, $dummy) {
            if ($dummy) {
                return 1;
            }
            global $wpdb;
            $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
            $wpdb->query(
                // phpcs:disable WordPress.DB.PreparedSQL
                \str_ireplace("'NULL'", 'NULL', $wpdb->prepare("INSERT IGNORE INTO {$table_name}\n                            (plugin_version, design_version,\n                            ipv4, ipv6, ipv4_hash, ipv6_hash,\n                            uuid, revision, revision_independent,\n                            previous_decision, decision, decision_hash,\n                            blocker, blocker_thumbnail,\n                            dnt, custom_bypass,\n                            button_clicked, context, viewport_width, viewport_height,\n                            referer, pure_referer,\n                            url_imprint, url_privacy_policy,\n                            forwarded, forwarded_blocker,\n                            user_country,\n                            previous_tcf_string, tcf_string,\n                            previous_gcm_consent, gcm_consent,\n                            recorder, ui_view, created, created_client_time)\n                            VALUES\n                            (%s, %d,\n                            %d, %s, %s, %s,\n                            %s, %s, %s,\n                            %s, %s, %s,\n                            %s, %s,\n                            %d, %s,\n                            %s, %s, %d, %d,\n                            %s, %s,\n                            %s, %s,\n                            %s, %s,\n                            %s,\n                            %s, %s,\n                            %s, %s,\n                            %s, %s, %s, %s)", RCB_VERSION, Banner::DESIGN_VERSION, $ips['ipv4'] === null ? 'NULL' : $ips['ipv4'], $ips['ipv6'] === null ? 'NULL' : $ips['ipv6'], $ips['ipv4_hash'] === null ? 'NULL' : $ips['ipv4_hash'], $ips['ipv6_hash'] === null ? 'NULL' : $ips['ipv6_hash'], $consent->getUuid(), $revisionHash, $revisionIndependentHash, \json_encode($previousDecision === \false ? [] : $previousDecision), \json_encode($consent->getDecision()), \md5(\json_encode($consent->getDecision())), $transaction->blocker > 0 ? $transaction->blocker : 'NULL', $transaction->blockerThumbnail > 0 ? $transaction->blockerThumbnail : 'NULL', $transaction->markAsDoNotTrack, $transaction->customBypass === null ? 'NULL' : $transaction->customBypass, $transaction->buttonClicked, $contextString, $transaction->viewPortWidth, $transaction->viewPortHeight, $transaction->referer, \DevOwl\RealCookieBanner\Utils::removeNonPermalinkQueryFromUrl($transaction->referer), BannerLink::getInstance()->getLegalLink(SettingsBannerLink::PAGE_TYPE_LEGAL_NOTICE, 'url'), BannerLink::getInstance()->getLegalLink(SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY, 'url'), $transaction->forwarded > 0 ? $transaction->forwarded : 'NULL', $transaction->forwardedBlocker, $transaction->userCountry ?? 'NULL', $previousTcfString, $consent->getTcfString() ?? 'NULL', $previousGcmConsent === \false ? 'NULL' : \json_encode($previousGcmConsent), $consent->getGcmConsent() === null ? 'NULL' : \json_encode($consent->getGcmConsent()), $transaction->recorderJsonString ?? 'NULL', $transaction->uiView === null || !\in_array($transaction->uiView, ['initial', 'change'], \true) ? 'NULL' : $transaction->uiView, \mysql2date('c', \current_time('mysql'), \false), \is_string($transaction->createdClientTime) ? \mysql2date('c', $transaction->createdClientTime, \false) : 'NULL'))
            );
            return $wpdb->insert_id;
        });
        if ($result === \false) {
            return new WP_Error('rcb_consent_commit_failed');
        }
        // Set cookies on browser
        foreach ($result['setCookie'] as $i => $setCookie) {
            $setCookieResult = \DevOwl\RealCookieBanner\Utils::setCookie($setCookie->key, $setCookie->value, $setCookie->expire, \constant('COOKIEPATH'), \constant('COOKIE_DOMAIN'), \is_ssl(), \false, 'None');
            if (!$dummy && $setCookieResult && $i === 0) {
                /**
                 * Real Cookie Banner saved the cookie which holds information about the user with
                 * UUID, revision and consent choices.
                 *
                 * @hook RCB/Consent/SetCookie
                 * @param {string} $cookieName
                 * @param {string} $cookieValue
                 * @param {boolean} $result Got the cookie successfully created?
                 * @param {boolean} $revoke `true` if the cookie should be deleted
                 * @param {string|null} $uuid
                 * @param {string[]} $uuids Since v3 multiple consent UUIDs are saved to the database
                 * @param {array}
                 * @since 2.0.0
                 * @deprecated This will removed in a future release!
                 */
                \do_action('RCB/Consent/SetCookie', $setCookie->key, $setCookie->value, \true, \false, $consent->getUuid(), \array_merge([$consent->getUuid()], $consent->getPreviousUuids() ?? []));
            }
        }
        // Persist stats (only when not forwarded)
        if (!$dummy && $transaction->forwarded === 0) {
            $stats = \DevOwl\RealCookieBanner\Stats::getInstance();
            $stats->persistTerm($contextString, $transaction->decision, $previousDecision, $previousCreated);
            $stats->persistButtonClicked($contextString, $transaction->buttonClicked);
            if ($transaction->buttonClicked !== Blocker::BUTTON_CLICKED_IDENTIFIER) {
                $stats->persistCustomBypass(
                    $contextString,
                    // Save DNT also as custom_bypass
                    $transaction->customBypass === null ? $transaction->markAsDoNotTrack ? 'dnt' : null : $transaction->customBypass
                );
            }
        }
        // Backwards-compatibility for RCB/Consent/Created filter
        $filterResult = ['uuid' => $consent->getUuid(), 'previous_uuids' => $consent->getPreviousUuids(), 'created' => \is_numeric($consent->getCreated()) ? \mysql2date('c', \gmdate('Y-m-d H:i:s', \intval($consent->getCreated())), \false) : null, 'cookie_revision' => $revisionHash, 'decision_in_cookie' => $consent->getDecision(), 'updated' => \true, 'consent_id' => $result['response']['consentId']];
        if (!$dummy) {
            \DevOwl\RealCookieBanner\UserConsent::getInstance()->scheduleDeletionOfConsents();
            /**
             * An user has given a new consent.
             *
             * @hook RCB/Consent/Created
             * @param {array} $result
             * @param {array} $args Passed arguments to `MyConsent::persist` as map (since 2.0.0)
             * @param {Transaction} $transaction The full transaction representing the new consent (since 4.4.2), use this instead of `$args`
             */
            \do_action('RCB/Consent/Created', $filterResult, [$transaction->decision, $transaction->markAsDoNotTrack, $transaction->buttonClicked, $transaction->viewPortWidth, $transaction->viewPortHeight, $transaction->referer, $transaction->blocker, $transaction->blockerThumbnail, $transaction->forwarded, $transaction->forwardedUuid, $transaction->forwardedBlocker, $transaction->tcfString, $transaction->gcmConsent, $transaction->customBypass, $transaction->recorderJsonString, $transaction->uiView], $transaction);
        }
        return $result['response'];
    }
    /**
     * Get's the current user from the cookie.
     */
    public function getCurrentUser()
    {
        $consent = \DevOwl\RealCookieBanner\Core::getInstance()->getCookieConsentManagement()->startConsent();
        $consent->setCurrentCookies($_COOKIE);
        return $consent;
    }
    /**
     * Get the history of the current user.
     */
    public function getCurrentHistory()
    {
        $user = $this->getCurrentUser();
        $result = [];
        if (!empty($user->getUuid())) {
            $rows = \DevOwl\RealCookieBanner\UserConsent::getInstance()->byCriteria(['revisionJson' => \true, 'context' => Revision::getInstance()->getContextVariablesString(), 'perPage' => 100, 'uuids' => \array_merge([$user->getUuid()], $user->getPreviousUuids())]);
            foreach ($rows as $row) {
                $jsonRevision = $row->revision;
                $jsonRevisionIndependent = $row->revision_independent;
                $obj = ['id' => $row->id, 'uuid' => $row->uuid, 'isDoNotTrack' => $row->dnt, 'isUnblock' => $row->blocker > 0, 'isForwarded' => $row->forwarded > 0, 'created' => $row->created, 'context' => [
                    'groups' => $jsonRevision['groups'],
                    'consent' => $row->decision,
                    'gcmConsent' => $row->gcm_consent,
                    // TCF compatibility
                    'tcf' => isset($jsonRevision['tcf']) ? [
                        'tcf' => $jsonRevision['tcf'],
                        // Keep `tcfMeta` for backwards-compatibility
                        'tcfMetadata' => $jsonRevisionIndependent['tcfMetadata'] ?? $jsonRevisionIndependent['tcfMeta'],
                        'tcfString' => $row->tcf_string,
                    ] : null,
                ]];
                $lazyLoaded = \DevOwl\RealCookieBanner\Core::getInstance()->getCookieConsentManagement()->getFrontend()->prepareLazyData($obj['context']['tcf']);
                $obj['context']['lazyLoadedDataForSecondView'] = $lazyLoaded;
                $result[] = $obj;
            }
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
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\MyConsent() : self::$me;
    }
}
