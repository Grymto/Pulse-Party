<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
use WP_Error;
/**
 * Execute a server job.
 * @internal
 */
class Executor
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
     * Run multiple server jobs by ID.
     *
     * @param int $maxExecutionTimeMs
     * @param int[] $ids
     * @param boolean $omitClientData See `Query::read`
     */
    public function run($ids, $maxExecutionTimeMs, $omitClientData = \false)
    {
        $jobs = $this->core->getQuery()->read(['ids' => $ids]);
        $done = [];
        $failed = [];
        $pauseToAvoidRecurringException = \false;
        $start = \microtime(\true);
        for ($i = 0; $i < \count($jobs); $i++) {
            $job = $jobs[$i];
            if (!$this->isValidJob($job)) {
                // It's a client job, skip it here!
                continue;
            }
            if (\is_wp_error($job->exception) && $job->runs > $job->retries) {
                // An exception occurred for this job, let's stop here and we need
                // to manually reset the exception to be rerun.
                $result = $job->exception;
            } else {
                $result = $job->execute();
            }
            if ($result === \false) {
                $result = new WP_Error('real_queue_execute', \__('Process failed due to an unexpected error.', REAL_QUEUE_TD), ['status' => 500]);
            }
            if (\is_wp_error($result)) {
                $job->updateException($result);
                $failed[] = $job;
                break;
            } else {
                $job->updateException(null);
                if ($job->isDone()) {
                    $done[] = $job;
                } else {
                    // The job is not completely done (process), let's do it again
                    --$i;
                }
            }
            if ($job->hasUpdatedJobsToAvoidRecurringException()) {
                $pauseToAvoidRecurringException = \true;
                break;
            }
            if ((\microtime(\true) - $start) * 1000 > $maxExecutionTimeMs) {
                break;
            }
        }
        if ($omitClientData) {
            foreach ($done as $j) {
                $j->omitClientData();
            }
            foreach ($failed as $j) {
                $j->omitClientData();
            }
        }
        return ['done' => $done, 'failed' => $failed, 'remaining' => $this->core->getQuery()->readRemaining(), 'pauseToAvoidRecurringException' => $pauseToAvoidRecurringException];
    }
    /**
     * Build error messages based on the "remaining" status.
     *
     * @param array $remaining Result of `Query::readRemaining`
     */
    public function buildErrorMessages($remaining)
    {
        $errors = ['list' => []];
        foreach ($remaining as $type => $remainArr) {
            if ($remainArr['remaining'] === 0 && $remainArr['failure'] > 0) {
                // Get a list of failed jobs
                $failedJobs = $this->core->getQuery()->read(['limit' => 5, 'type' => 'failure', 'jobType' => $type]);
                /**
                 * Generate a human-readable description for a failed job type.
                 *
                 * @param {string} $description
                 * @param {string} $type
                 * @param {int[]} $remaining
                 * @param {Job[]} $failedJobs A list of maximum 5 items of failed [Jobs](../php/classes/DevOwl-RealQueue-queue-Job.html)
                 * @return {string}
                 * @hook DevOwl/RealQueue/Error/Description
                 */
                $description = \apply_filters('DevOwl/RealQueue/Error/Description', \sprintf(
                    // translators:
                    \__('%1$d / %2$d (thereof %3$d remaining) tasks could not be executed properly.', REAL_QUEUE_TD),
                    $remainArr['failure'],
                    $remainArr['total'],
                    $remainArr['paused']
                ), $type, $remainArr, $failedJobs);
                $errors['list'][$type] = ['type' => $type, 'label' => Job::label($type), 'actions' => Job::actions($type), 'description' => $description, 'failedJobs' => $failedJobs];
            }
        }
        $errors['hash'] = \md5(\json_encode($errors['list']));
        $errors['list'] = (object) $errors['list'];
        return $errors;
    }
    /**
     * Check if a given job is valid for execution.
     *
     * @param Job $job
     */
    protected function isValidJob($job)
    {
        return $job->worker === Job::WORKER_SERVER;
    }
}
