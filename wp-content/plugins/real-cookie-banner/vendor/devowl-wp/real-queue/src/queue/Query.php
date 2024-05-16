<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\rest\Queue;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
use WP_Error;
/**
 * Query jobs and transform them to a proper `Job` instance.
 * @internal
 */
class Query
{
    use UtilsProvider;
    private $core;
    /**
     * C'tor.
     *
     * @param Core $core
     * @codeCoverageIgnore
     */
    public function __construct($core)
    {
        $this->core = $core;
    }
    /**
     * Read jobs and cast them to proper `Job` instance.
     *
     * Arguments:
     * - `[limit]`
     * - `[type=all|pending|failure]`
     * - `[jobType]`
     * - `[dataContains]` Allows you in a very basic way to check if a job exists by `data LIKE '%YOUR_STRING%'`
     * - `[ids]` An array of Job ids which should be read
     * - `[omitClientData=false]` If `true`, `data` will be omitted for `server` workers
     * - `[after]` Read all job ids after that one
     * - `[lockUntil=0]` Add this seconds to UNIX-timestamp and mark the read jobs as locked_until
     *
     * @param array $args
     * @return Job[]
     */
    public function read($args = [])
    {
        global $wpdb;
        $this->core->getPersist()->clearJobTable();
        $args = \wp_parse_args($args, ['limit' => Queue::MAX_BATCH_CLIENT_SIZE, 'type' => 'pending', 'jobType' => null, 'dataContains' => '', 'ids' => null, 'omitClientData' => \false, 'after' => null, 'lockUntil' => 0]);
        $table_name = $this->getTableName();
        $type = $args['type'];
        $jobType = $args['jobType'];
        $dataContains = $args['dataContains'];
        $ids = $args['ids'];
        $after = $args['after'];
        $lockUntil = $args['lockUntil'];
        $limit = $args['limit'];
        $where = '1=1';
        if ($ids !== null) {
            if (\count($ids) > 0) {
                $where .= \sprintf(' AND id IN (%s)', \join(',', \array_map(function ($id) use($wpdb) {
                    return $wpdb->prepare('%d', $id);
                }, $ids)));
                $limit = \PHP_INT_MAX;
            } else {
                $where .= ' AND 1=0';
            }
        } else {
            switch ($type) {
                case 'pending':
                    $where .= ' AND process < process_total AND runs < (retries + 1) AND CURRENT_TIMESTAMP >= lock_until';
                    break;
                case 'failure':
                    $where .= ' AND process < process_total AND runs > retries';
                    break;
                default:
                    break;
            }
            if (!empty($jobType)) {
                $where .= $wpdb->prepare(' AND type = %s', $jobType);
            }
        }
        if ($after > 0) {
            $where .= $wpdb->prepare(' AND id > %d', $after);
        }
        if (!empty($dataContains)) {
            $where .= $wpdb->prepare(' AND data LIKE %s', '%' . $wpdb->esc_like($dataContains) . '%');
        }
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE {$where} LIMIT 0, %d", $limit), ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        $this->castRows($rows);
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row->id;
            if ($args['omitClientData']) {
                $row->omitClientData();
            }
        }
        if ($lockUntil > 0 && \count($ids) > 0) {
            $this->lockUntil($ids, $lockUntil);
        }
        return $rows;
    }
    /**
     * Fetch a single job by ID.
     *
     * @param int $id
     * @return Job|null
     */
    public function fetchById($id)
    {
        global $wpdb;
        $table_name = $this->getTableName();
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $id), ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        $this->castRows($rows);
        return \count($rows) > 0 ? $rows[0] : null;
    }
    /**
     * Lock a set of jobs by a given time of seconds.
     *
     * @param int[] $ids
     * @param int $seconds
     */
    protected function lockUntil($ids, $seconds)
    {
        global $wpdb;
        $table_name = $this->getTableName();
        $sqlIn = \join(',', \array_map('intval', $ids));
        // phpcs:disable WordPress.DB.PreparedSQL
        $sql = $wpdb->prepare("UPDATE {$table_name} SET lock_until = CURRENT_TIMESTAMP + %d WHERE id IN ({$sqlIn})", $seconds);
        $wpdb->query($sql);
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Cast read jobs to `Job` instance.
     *
     * @param array $rows
     */
    protected function castRows(&$rows)
    {
        foreach ($rows as $idx => $row) {
            $job = new Job($this->core);
            $job->id = \intval($row['id']);
            $job->type = $row['type'];
            $job->worker = $row['worker'];
            $job->group_uuid = $row['group_uuid'] ?? null;
            $job->group_position = \is_numeric($row['group_position']) ? \intval($row['group_position']) : null;
            $job->group_total = \is_numeric($row['group_total']) ? \intval($row['group_total']) : null;
            $job->process = \intval($row['process']);
            $job->process_total = \intval($row['process_total']);
            $job->duration_ms = \intval($row['duration_ms']);
            $job->created = \mysql2date('c', $row['created'], \false);
            $job->data = \json_decode($row['data'], ARRAY_A);
            $job->runs = \intval($row['runs']);
            $job->retries = \intval($row['retries']);
            $job->delay_ms = \intval($row['delay_ms']);
            $job->lock_until = \strtotime($row['lock_until']);
            $job->locked = $row['locked'] > 0;
            if (isset($row['callable'])) {
                $job->callable = \json_decode($row['callable'], ARRAY_A);
            }
            if (isset($row['exception'])) {
                $exception = \json_decode($row['exception'], ARRAY_A);
                $job->exception = new WP_Error($exception['code'], $exception['message'], $exception['data']);
            }
            $rows[$idx] = $job;
        }
    }
    /**
     * Read remaining jobs per type. This does not count failed jobs! The result is an associative array:
     *
     * `[type: string]: [remaining: number, total: number, failure: number]`
     */
    public function readRemaining()
    {
        global $wpdb;
        $table_name = $this->getTableName();
        $result = [];
        $escLikeExcludeRecurringPaused = '%"' . $wpdb->esc_like(Job::RECURRING_EXCEPTION_CODE) . '"%';
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results($wpdb->prepare("SELECT\n                    type,\n                    SUM(IF(process < process_total AND runs < (retries + 1), 1, 0)) AS remaining,\n                    COUNT(*) AS total,\n                    SUM(IF(process < process_total AND runs > retries AND exception NOT LIKE %s, 1, 0)) AS failure,\n                    SUM(IF(exception LIKE %s, 1, 0)) AS paused\n                FROM {$table_name}\n                GROUP BY type", $escLikeExcludeRecurringPaused, $escLikeExcludeRecurringPaused));
        // phpcs:enable WordPress.DB.PreparedSQL
        foreach ($rows as $row) {
            $result[$row->type] = ['remaining' => \intval($row->remaining), 'total' => \intval($row->total), 'failure' => \intval($row->failure), 'paused' => \intval($row->paused)];
        }
        return $result;
    }
    /**
     * Read current jobs per type.
     *
     * @param boolean $omitClientData
     */
    public function readCurrentJobs($omitClientData = \false)
    {
        global $wpdb;
        $table_name = $this->getTableName();
        $result = [];
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results("SELECT * FROM {$table_name} WHERE process < process_total AND runs < retries + 1 GROUP BY type", ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        foreach ($rows as $row) {
            $castRows = [$row];
            $this->castRows($castRows);
            $job = $castRows[0];
            if ($omitClientData) {
                $job->omitClientData();
            }
            $result[$row['type']] = $job;
        }
        return $result;
    }
}
