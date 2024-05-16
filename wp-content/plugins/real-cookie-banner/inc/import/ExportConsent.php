<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\UserConsent;
use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle export consent data.
 * @internal
 */
class ExportConsent
{
    use UtilsProvider;
    const EXPORT_CONSENT_OUTPUT_FILENAME = 'export-consents.csv';
    const CSV_DELIMITER = "\t";
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Export consents by given criteria.
     *
     * @param string $uuid
     * @param string $from
     * @param string $to
     */
    public function downloadCsv($uuid, $from, $to)
    {
        global $wpdb;
        $userConsent = UserConsent::getInstance();
        $count = $userConsent->getCount();
        // @codeCoverageIgnoreStart
        if (!\defined('PHPUNIT_FILE')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        // @codeCoverageIgnoreEnd
        // Pick up by UUID
        if (!empty($uuid)) {
            $sql = $userConsent->byCriteria(['revisionJson' => \true, 'uuid' => $uuid, 'perPage' => $count], UserConsent::BY_CRITERIA_RESULT_TYPE_SQL_QUERY);
        } elseif (isset($from, $to)) {
            // Pick up by date range
            $sql = $userConsent->byCriteria(['revisionJson' => \true, 'from' => $from . ' 00:00:00', 'to' => $to . ' 23:59:59', 'perPage' => $count], UserConsent::BY_CRITERIA_RESULT_TYPE_SQL_QUERY);
        } else {
            return new WP_Error('rest_rcb_data', \__('Please provide a UUID or a date range.', RCB_TD));
        }
        // We need to fetch directly via `mysql(i)_` functions so we can use `mysql(i)_fetch_array` in a while-loop
        $dbh = $wpdb->dbh;
        $functionPrefix = $wpdb->use_mysqli ? 'mysqli' : 'mysql';
        $result = \call_user_func($functionPrefix . '_query', $wpdb->dbh, $sql) or \wp_die(\call_user_func($functionPrefix . '_error', $dbh));
        $count = \call_user_func($functionPrefix . '_num_rows', $result);
        if ($count === 0) {
            // As this is a download, we can use `wp_die` to show an error message
            \wp_die(\__('No consents found. Please navigate back and check your input.', RCB_TD));
        }
        // Output headers
        \header('Content-Disposition: attachment; filename=' . self::EXPORT_CONSENT_OUTPUT_FILENAME);
        \header('Content-Type: text/csv; charset=utf8');
        // open the "output" stream, see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        // phpcs:disable WordPress.PHP.TypeCasts.BinaryFound
        $f = \fopen('php://output', 'w');
        \fwrite($f, "﻿");
        // write BOM
        // phpcs:enable WordPress.PHP.TypeCasts.BinaryFound
        // Output rows
        $i = 0;
        while ($line = \call_user_func($functionPrefix . '_fetch_array', $result, 1)) {
            // Create headers
            if ($i === 0) {
                \fputcsv($f, \array_keys($line), self::CSV_DELIMITER);
            }
            $lines = [(object) \array_map([$this, 'convertToUTF8Charset'], $line)];
            $userConsent->castReadRows($lines, \false);
            \fputcsv($f, (array) $lines[0], self::CSV_DELIMITER);
            ++$i;
        }
        // Close (wp_die could not be used here because it generates unwanted output)
        $wpdb->close();
        die;
    }
    /**
     * Convert string to UTF8 charset.
     *
     * @param string $string
     * @see https://stackoverflow.com/questions/12488954/php-fputcsv-encoding
     */
    public function convertToUTF8Charset($string)
    {
        if (\function_exists('mb_detect_encoding') && $string !== null) {
            $charset = \mb_detect_encoding($string);
            return \mb_convert_encoding($string, 'UTF-8', $charset);
        }
        return $string;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\import\ExportConsent();
    }
}
