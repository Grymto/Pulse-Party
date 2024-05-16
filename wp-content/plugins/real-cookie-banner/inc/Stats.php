<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\Stats as LiteStats;
use DevOwl\RealCookieBanner\overrides\interfce\IOverrideStats;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Consent stats handling. Only write operations are implemented in both free and PRO version to
 * allow "unlocking" after upgrading to PRO.
 * @internal
 */
class Stats implements IOverrideStats
{
    use UtilsProvider;
    use LiteStats;
    /**
     * TODO: rename to something like `stats_terms`.
     */
    const TABLE_NAME_TERMS = 'stats';
    const TABLE_NAME_BUTTONS_CLICKED = 'stats_buttons_clicked';
    const TABLE_NAME_CUSTOM_BYPASS = 'stats_custom_bypass';
    const STATS_ACCEPTED_TYPE_ALL = 2;
    const STATS_ACCEPTED_TYPE_PARTIAL = 1;
    const STATS_ACCEPTED_TYPE_NONE = 0;
    const STATS_ACCEPTED_ALL_TYPES = [self::STATS_ACCEPTED_TYPE_ALL, self::STATS_ACCEPTED_TYPE_NONE, self::STATS_ACCEPTED_TYPE_PARTIAL];
    /**
     * Singleton instance.
     *
     * @var Stats
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
     * Persist a new consent to a given clicked button to the stats database table.
     *
     * @param string $contextString
     * @param string $buttonClicked
     */
    public function persistButtonClicked($contextString, $buttonClicked)
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME_BUTTONS_CLICKED);
        $dayIso = \mysql2date('Y-m-d', \current_time('mysql'));
        $wpdb->query(
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->prepare("INSERT INTO {$table_name}\n                    (`day`, `context`, `button_clicked`, `count`)\n                    VALUES\n                    (%s, %s, %s, 1)\n                    ON DUPLICATE KEY UPDATE `count` = `count` + 1", $dayIso, $contextString, $buttonClicked)
        );
    }
    /**
     * Persist a new consent to a given clicked button to the stats database table.
     *
     * @param string $contextString
     * @param string $customBypass
     */
    public function persistCustomBypass($contextString, $customBypass)
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME_CUSTOM_BYPASS);
        $dayIso = \mysql2date('Y-m-d', \current_time('mysql'));
        $wpdb->query(
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->prepare("INSERT INTO {$table_name}\n                    (`day`, `context`, `custom_bypass`, `count`)\n                    VALUES\n                    (%s, %s, %s, 1)\n                    ON DUPLICATE KEY UPDATE `count` = `count` + 1", $dayIso, $contextString, $customBypass === null ? 'none' : $customBypass)
        );
    }
    /**
     * Persist a new consent to a given group to the stats database table.
     *
     * @param string $contextString
     * @param array $consent
     * @param array $previousConsent Do not count recurring users in stats
     * @param string $previousCreated ISO string of previous consent
     */
    public function persistTerm($contextString, $consent, $previousConsent, $previousCreated)
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME_TERMS);
        $newConsentAcceptTypes = $this->calculateGroupAcceptTypes($consent);
        $dayIso = \mysql2date('Y-m-d', \current_time('mysql'));
        foreach ($newConsentAcceptTypes as $term_id => $accepted) {
            $term_name = \get_term($term_id)->name;
            $wpdb->query(
                // phpcs:disable WordPress.DB.PreparedSQL
                $wpdb->prepare("INSERT INTO {$table_name}\n                        (`day`, `context`, `term_name`, `term_id`, `accepted`, `count`)\n                        VALUES\n                        (%s, %s, %s, %d, %d, 1)\n                        ON DUPLICATE KEY UPDATE `count` = `count` + 1", $dayIso, $contextString, $term_name, $term_id, $accepted)
            );
            // Also persist other accept-types so the stats are intact
            // This is needed because the defined service groups are defined by the user, not hardcoded within our app
            foreach (\array_diff(self::STATS_ACCEPTED_ALL_TYPES, [$accepted]) as $missingAccepted) {
                $wpdb->query(
                    // phpcs:disable WordPress.DB.PreparedSQL
                    $wpdb->prepare("INSERT IGNORE INTO {$table_name}\n                            (`day`, `context`, `term_name`, `term_id`, `accepted`, `count`)\n                            VALUES\n                            (%s, %s, %s, %d, %d, 0)", $dayIso, $contextString, $term_name, $term_id, $missingAccepted)
                );
            }
        }
        // Subtract recurring users
        if (\is_array($previousConsent) && !empty($previousConsent) && $previousCreated) {
            $previousConsentAcceptTypes = $this->calculateGroupAcceptTypes($previousConsent);
            // Simply reduce by one each group / accept type because it still get's added above
            // With this method, it is ensured that only differences are subtracted
            foreach ($previousConsentAcceptTypes as $term_id => $accepted) {
                $wpdb->query(
                    // phpcs:disable WordPress.DB.PreparedSQL
                    $wpdb->prepare("UPDATE {$table_name}\n                            SET `count` = `count` - 1\n                            WHERE `count` > 0\n                            AND context = %s\n                            AND day = %s\n                            AND term_id = %d\n                            AND accepted = %d", $contextString, \mysql2date('Y-m-d', $previousCreated), $term_id, $accepted)
                );
            }
        }
    }
    /**
     * Calculate the accept types for a given consent. It returns an array of keyed term id
     * with accept type.
     *
     * @param array $consent
     */
    private function calculateGroupAcceptTypes($consent)
    {
        $result = [];
        $groups = CookieGroup::getInstance()->getOrdered();
        foreach ($groups as $term) {
            $term_id = $term->term_id;
            $term_id_string = \strval($term->term_id);
            if (!isset($consent[$term_id_string]) || \count($consent[$term_id_string]) === 0) {
                $accepted = self::STATS_ACCEPTED_TYPE_NONE;
            } else {
                $allCount = \count(Cookie::getInstance()->getOrdered($term_id));
                $accepted = $allCount === \count($consent[$term_id_string]) ? self::STATS_ACCEPTED_TYPE_ALL : self::STATS_ACCEPTED_TYPE_PARTIAL;
            }
            $result[$term_id] = $accepted;
        }
        return $result;
    }
    /**
     * Fires after a group got changed, let's update the plain name in database
     * for future display when the group got deleted.
     *
     * @param int $term_id
     */
    public function edited_group($term_id)
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME_TERMS);
        $term_name = \get_term($term_id)->name;
        $wpdb->query(
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->prepare("UPDATE {$table_name} SET term_name = %s WHERE term_id = %d", $term_name, $term_id)
        );
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\Stats() : self::$me;
    }
}
