<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
/**
 * Persist new elements to the queue.
 * @internal
 */
class Persist
{
    use UtilsProvider;
    const TABLE_NAME = 'p_queue';
    private $core;
    /**
     * Persistable jobs.
     *
     * @var Job[]
     */
    private $jobs = [];
    private $group_uuid;
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
     * Start a group for the next added items. This can help you to keep track
     * of "bundled" jobs. Please consider to `stopGroup`, too!
     *
     * @return string The used UUID for this group, you do not have to manually set the `group_id` in your upcoming models!
     */
    public function startGroup()
    {
        return $this->group_uuid = \wp_generate_uuid4();
    }
    /**
     * Stop the group.
     */
    public function stopGroup()
    {
        $this->group_uuid = null;
    }
    /**
     * Start the persist mechanism.
     */
    public function startTransaction()
    {
        $this->jobs = [];
        $this->stopGroup();
    }
    /**
     * Add a new job. You can pass multiple jobs, but do not forget to `commit`
     * your jobs so they get persisted to the database!
     *
     * @param Job $job
     */
    public function addJob($job)
    {
        $job->group_uuid = $this->group_uuid;
        /**
         * Allows you to modify the job before added to the queuing. If you return `null` the job will be ignored completely.
         *
         * You can find the `Job` instance definition {@link ../php/classes/DevOwl.RealQueue.queue.Job.html|here}.
         *
         * @param {Job|null} $job See [Job](../php/classes/DevOwl-RealQueue-queue-Job.html) class
         * @return {Job}
         * @hook DevOwl/RealQueue/Job/Add
         */
        $job = \apply_filters('DevOwl/RealQueue/Job/Add', $job);
        if ($job !== null) {
            $this->jobs[] = $job;
        }
    }
    /**
     * Write the jobs to the database.
     */
    public function commit()
    {
        global $wpdb;
        if (\count($this->jobs) === 0) {
            // Clear transaction
            $this->startTransaction();
            return;
        }
        $this->fillGroupTotal();
        $rows = [];
        foreach ($this->jobs as $entry) {
            // Generate `VALUES` SQL
            // phpcs:disable WordPress.DB.PreparedSQL
            $rows[] = \str_ireplace("'NULL'", 'NULL', $wpdb->prepare('%s, %s, %s, %d, %d, %d, %d, %d, %s, %s, %d, %d, %s', $entry->type, $entry->worker, $entry->group_uuid ?? 'NULL', $entry->group_position ?? 'NULL', $entry->group_total ?? 'NULL', $entry->process, $entry->process_total, $entry->duration_ms, \current_time('mysql'), isset($entry->data) ? \json_encode($entry->data) : 'NULL', $entry->retries, $entry->delay_ms, \json_encode($entry->callable)));
            // phpcs:enable WordPress.DB.PreparedSQL
        }
        // Chunk to boost performance
        $chunks = \array_chunk($rows, 50);
        $table_name = $this->core->getTableName();
        foreach ($chunks as $sqlInsert) {
            $sql = "INSERT INTO {$table_name}\n            (`type`, `worker`, `group_uuid`, `group_position`, `group_total`, `process`, `process_total`, `duration_ms`, `created`, `data`, `retries`, `delay_ms`, `callable`)\n            VALUES (" . \implode('),(', $sqlInsert) . ')';
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query($sql);
            // phpcs:enable WordPress.DB.PreparedSQL
        }
        // Clear transaction
        $this->startTransaction();
        return \count($rows);
    }
    /**
     * Clear the queue / job database table. Cause we do not want to get a messy database.
     * If you want to keep statistics, do this in your job callback and persist data to
     * your own database table.
     */
    public function clearJobTable()
    {
        global $wpdb;
        $table_name = $this->core->getTableName();
        // phpcs:disable WordPress.DB.PreparedSQL
        $deleteGroups = $wpdb->get_col("SELECT group_uuid FROM (SELECT * FROM {$table_name}) rq2 GROUP BY group_uuid HAVING SUM(process) >= SUM(process_total)");
        // phpcs:enable WordPress.DB.PreparedSQL
        if (\count($deleteGroups) === 0) {
            $deleteGroups[] = '';
            // Always delete empty group ids
        }
        $deleteGroups = \array_map(function ($uuid) use($wpdb) {
            return $wpdb->prepare('%s', $uuid);
        }, $deleteGroups);
        $sqlIn = \join(',', $deleteGroups);
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query("DELETE rq FROM {$table_name} rq\nWHERE (rq.group_uuid IS NULL AND rq.process >= rq.process_total)\nOR (rq.group_uuid IS NOT NULL AND rq.group_uuid IN ({$sqlIn}))");
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Delete jobs by type. This is similar to "Cancel".
     *
     * @param string $type
     */
    public function deleteByType($type)
    {
        global $wpdb;
        return $wpdb->delete($this->core->getTableName(), ['type' => $type]);
    }
    /**
     * Retry jobs by type.
     *
     * @param string $type
     */
    public function retryByType($type)
    {
        global $wpdb;
        $table_name = $this->core->getTableName();
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query($wpdb->prepare("UPDATE {$table_name}\n                    SET locked = 0, lock_until = NULL, exception = NULL, runs = 0\n                    WHERE type = %s AND exception IS NOT NULL", $type));
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Skip jobs by type.
     *
     * @param string $type
     */
    public function skipByType($type)
    {
        global $wpdb;
        $table_name = $this->core->getTableName();
        $escLikeExcludeRecurringPaused = '%"' . $wpdb->esc_like(Job::RECURRING_EXCEPTION_CODE) . '"%';
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query($wpdb->prepare("UPDATE {$table_name}\n                    SET exception = NULL, runs = 1, process = process_total\n                    WHERE type = %s AND exception IS NOT NULL AND exception NOT LIKE %s", $type, $escLikeExcludeRecurringPaused));
        // Reactivate paused jobs
        $wpdb->query($wpdb->prepare("UPDATE {$table_name}\n                    SET exception = NULL, runs = 0, process = 0\n                    WHERE type = %s AND exception LIKE %s", $type, $escLikeExcludeRecurringPaused));
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Fill `group_total`. Why is this not calculated in our database? Our database is self-cleaning because all
     * done jobs are automatically erased after some time (e.g. each 100 records).
     */
    protected function fillGroupTotal()
    {
        $groupCountCache = [];
        foreach ($this->jobs as $job) {
            if (!empty($job->group_uuid)) {
                if (!isset($groupCountCache[$job->group_uuid])) {
                    $groupCountCache[$job->group_uuid] = 1;
                } else {
                    ++$groupCountCache[$job->group_uuid];
                }
                $job->group_position = $groupCountCache[$job->group_uuid];
            }
        }
        foreach ($this->jobs as $job) {
            if (!empty($job->group_uuid)) {
                $job->group_total = $groupCountCache[$job->group_uuid];
            }
        }
    }
}
