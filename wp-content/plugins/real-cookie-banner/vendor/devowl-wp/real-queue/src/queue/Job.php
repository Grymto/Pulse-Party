<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
use ErrorException;
use Exception;
use WP_Error;
/**
 * A job model.
 * @internal
 */
class Job
{
    use UtilsProvider;
    const WORKER_SERVER = 'server';
    const WORKER_CLIENT = 'client';
    /**
     * If x jobs of the same type fails, pause all other jobs to avoid unnecessary executions.
     * A UI dialog needs to be shown to the user so the user can decide to repeat or delete jobs.
     */
    const RECURRING_EXCEPTION_ITEMS = 4;
    const RECURRING_EXCEPTION_CODE = 'queue_paused_previous_failed';
    /**
     * ID.
     *
     * @var int
     */
    public $id;
    /**
     * The type describes the unique type of this job. Something like `rpm-move-file`
     * (with a prefix of your plugin).
     *
     * @var string
     */
    public $type;
    /**
     * Where should this job be executed? Default is `server`. If you set this to `client`
     * you need to manually resolve this job as done in your frontend coding (e.g. extra request).
     *
     * **Client-Worker**:
     *
     * - You need to register your callable via JavaScript function: `exposeCallable()`
     * - You need to manually expose a REST API call so you can update the job with `passthruClientResult()`
     * - You can run into multiple executions for client-tasks, expect you do an explicit `locked` check before starting the expensive task
     *   - You can also avoid this, e.g. when you send the results of the client-worked task to the PHP client -> check
     *     if the job got already processed by another tab.
     *
     * Please use `WORKER_` constants.
     *
     * @var string
     */
    public $worker = self::WORKER_SERVER;
    /**
     * Do not use this manually! See `Persist::startGroup`.
     *
     * @var string
     */
    public $group_uuid;
    /**
     * The jobs' position within this group.
     *
     * @var int
     */
    public $group_position;
    /**
     * Due to the fact we have a garbage collection of jobs in our database table, but we want to keep
     * track of the total items within this group (e.g. Website scanner = Total sites to scan).
     *
     * @var int
     */
    public $group_total;
    /**
     * Describe the process for this job. So you could iteratively work at this job within different executions.
     *
     * @var int
     */
    public $process = 0;
    /**
     * The total process needed of `process` to consider this job as `done`.
     *
     * @var int
     */
    public $process_total = 1;
    /**
     * The duration took to complete this task.
     *
     * @var int
     */
    public $duration_ms;
    /**
     * Created time as ISO string.
     *
     * @var string
     */
    public $created;
    /**
     * A `json_encode`able object which gets persisted for this job which you can use
     * in your executor.
     *
     * @var mixed
     */
    public $data;
    /**
     * Shows, how often this job has been run already. This is needed for `retries`. You do not have to fill this!
     *
     * @var int
     */
    public $runs = 0;
    /**
     * How often should the job be retried when failure before stopping the complete queue
     * and show an error message?
     *
     * @var int
     */
    public $retries = 0;
    /**
     * How long should be waited until the next job can be passed to the executor?
     *
     * @var int
     */
    public $delay_ms = 1000;
    /**
     * Timestamp at which this job can be picked again. This is not yet implemented but
     * this mechanism should be used for the `client` worker.
     *
     * @var int
     */
    public $lock_until;
    /**
     * Is this job currently processing?
     *
     * @var boolean
     */
    public $locked;
    /**
     * The callable for this job.
     *
     * **Attention**: If you are using a client-worker, you need to register a custom event, see
     * also `CLIENT_JOB_EVENT_PREFIX` for more information!
     *
     * Please note the following things:
     *
     * - This field is only needed for `server` worker
     * - You are not allowed to use object instances; only static methods or functions!
     * - The callable gets one parameter: `Job $job`
     * - You need to `$job->updateProcess()` to mark the job as in progress or done!
     * - Your callable can throw an Exception or return a `WP_Error` instance
     * - Your callable should be save to be also executed by users with minimal capabilities
     *
     * @var callable
     */
    public $callable;
    /**
     * Exception caused by this job. This automatically pauses the complete queue.
     *
     * @var null|WP_Error
     */
    public $exception;
    private $core;
    /**
     * Indicates, when run this job a recurring got detected and jobs got paused automatically.
     *
     * @var boolean
     */
    private $updatedJobsToAvoidRecurringException = \false;
    /**
     * C'tor.
     *
     * @param Core $core
     */
    public function __construct($core)
    {
        $this->core = $core;
    }
    /**
     * Check if the job is already done?
     */
    public function isDone()
    {
        return $this->process >= $this->process_total;
    }
    /**
     * Check if this job is the last in this group?
     */
    public function isLastInGroup()
    {
        return $this->group_uuid === null || $this->group_total === $this->group_position;
    }
    /**
     * Save the state of the client worker result.
     *
     * @param int|true $process
     * @param WP_Error|null $error
     */
    public function passthruClientResult($process, $error)
    {
        $previousProcess = $this->process;
        $this->updateProcess($process);
        $this->updateRuns($previousProcess, \is_wp_error($error) ? $this->retries + 1 : null);
        // retries are run client-side
        $this->updateException($error);
        $this->updatedLocked($this->process < $this->process_total);
    }
    /**
     * Update `process` and mark the job as in progress or done.
     *
     * @param int|true $process
     */
    public function updateProcess($process)
    {
        global $wpdb;
        $this->process = $process === \true ? $this->process_total : $process;
        $wpdb->update($this->getTableName(), ['process' => $this->process], ['id' => $this->id], '%d', '%d');
    }
    /**
     * Update `runs` so `retries` works as expected.
     *
     * @param int $previousProcess
     * @param int $force
     */
    protected function updateRuns($previousProcess, $force = null)
    {
        global $wpdb;
        if ($this->process === $previousProcess || $this->process === $this->process_total || $force !== null) {
            if ($force === null) {
                $this->runs += 1;
            } else {
                $this->runs = $force;
            }
            $wpdb->update($this->getTableName(), ['runs' => $this->runs], ['id' => $this->id], '%d', '%d');
        }
    }
    /**
     * Update `locked` attribute so jobs are not executed twice.
     *
     * @param boolean $locked
     */
    public function updatedLocked($locked)
    {
        global $wpdb;
        $this->locked = $locked;
        $wpdb->update($this->getTableName(), ['locked' => $locked ? 1 : 0], ['id' => $this->id], '%d', '%d');
    }
    /**
     * Update `exception` and mark the job as failed. This causes the complete queue to be paused.
     *
     * @param WP_Error|null $error
     */
    public function updateException($error)
    {
        global $wpdb;
        if ($error === $this->exception) {
            // Avoid `null` to result in a query
            return;
        }
        $this->exception = $error;
        $wpdb->update($this->getTableName(), ['exception' => \is_wp_error($error) ? \json_encode(['code' => $error->get_error_code(), 'message' => $error->get_error_message(), 'data' => $error->get_error_data()]) : null], ['id' => $this->id], '%s', '%d');
        $this->avoidRecurringException();
    }
    /**
     * Execute this job on our server.
     *
     * @return void|WP_Error
     */
    public function execute()
    {
        if (!\is_callable($this->callable)) {
            return new WP_Error('real_queue_job_execute_not_callable', \__('The passed callable persisted to the job cannot be called (`is_callable`)', REAL_QUEUE_TD));
        }
        // Check if already done
        if ($this->isDone()) {
            return \true;
        }
        try {
            /**
             * Allows to skip an job in the queue. Simply add_action and throw an exception to skip it.
             *
             * @param {Job} $job See [Job](../php/classes/DevOwl-RealQueue-queue-Job.html) class
             * @throws Exception
             * @hook DevOwl/RealQueue/Queue/Skip
             */
            \do_action('DevOwl/RealQueue/Queue/Skip', $this);
        } catch (Exception $e) {
            return \true;
        }
        $start = \microtime(\true);
        // Do not update run if the process got incremented (at least, something got done?)
        $previousProcess = $this->process;
        // Already running?
        if ($this->locked) {
            return new WP_Error('real_queue_job_locked', \__('This job is already running in another thread on your server.', REAL_QUEUE_TD));
        }
        $this->updatedLocked(\true);
        // Process with given error handler
        try {
            \set_error_handler([self::class, 'set_error_handler']);
            $result = \call_user_func_array($this->callable, [$this]);
            \restore_error_handler();
            if (\is_wp_error($result)) {
                return $result;
            }
        } catch (Exception $e) {
            \restore_error_handler();
            return new WP_Error('real_queue_job_execute_exception', \__('Unexpected exception.', REAL_QUEUE_TD), ['exception' => ['id' => $this->id, 'code' => $e->getCode(), 'message' => $e->getMessage(), 'stack' => $e->getTraceAsString(), 'at' => \time()]]);
        } finally {
            $this->updatedLocked(\false);
            $this->updateRuns($previousProcess);
        }
        $this->cumulateDuration($start);
    }
    /**
     * Update `duration`.
     *
     * @param int|float $start `microtime(true)` before you do something heavy
     */
    protected function cumulateDuration($start)
    {
        global $wpdb;
        // Get the duration in milliseconds and cumulate it to the current duration
        $this->duration_ms += \ceil((\microtime(\true) - $start) * 1000);
        $wpdb->update($this->getTableName(), ['duration_ms' => $this->duration_ms], ['id' => $this->id], '%d', '%d');
    }
    /**
     * When a job fails, check if jobs from the same type failed a few times before, too.
     * If yes, update `runs` and set an exception that signals a paused queue.
     */
    protected function avoidRecurringException()
    {
        global $wpdb;
        // Short circuit: If this job is not done completely, the queue will try this again
        if ($this->runs <= $this->retries || !\is_wp_error($this->exception)) {
            return;
        }
        $failed = $this->core->getQuery()->read(['limit' => self::RECURRING_EXCEPTION_ITEMS, 'type' => 'failure', 'jobType' => $this->type]);
        if (\count($failed) === self::RECURRING_EXCEPTION_ITEMS) {
            $table_name = $this->getTableName();
            $exception = \json_encode(['code' => self::RECURRING_EXCEPTION_CODE, 'message' => null, 'data' => ['origin' => $this->id]]);
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query($wpdb->prepare("UPDATE {$table_name} SET exception = %s, runs = retries + 1 WHERE type = %s AND exception IS NULL AND runs = 0", $exception, $this->type));
            // phpcs:enable WordPress.DB.PreparedSQL
            $this->updatedJobsToAvoidRecurringException = \true;
        }
    }
    /**
     * Omit client data e.g. `data` of server-worker and `callable`.
     */
    public function omitClientData()
    {
        if ($this->worker === Job::WORKER_SERVER) {
            $this->data = (object) [];
            $this->callable = null;
        }
    }
    /**
     * Getter.
     */
    public function hasUpdatedJobsToAvoidRecurringException()
    {
        return $this->updatedJobsToAvoidRecurringException;
    }
    /**
     * Set error handler.
     *
     * @param string $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param string $errcontext
     * @see https://stackoverflow.com/a/1241751/5506547
     * @throws ErrorException
     */
    public static function set_error_handler($errno, $errstr, $errfile, $errline, $errcontext = [])
    {
        // error was suppressed with the @-operator
        if (0 === \error_reporting()) {
            return \false;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    /**
     * See filter `DevOwl/RealQueue/Job/Label`.
     *
     * @param string $type
     */
    public static function label($type)
    {
        /**
         * Generate a human-readable label for the job type.
         *
         * @param {string} $label
         * @param {string} $type
         * @return {string}
         * @hook DevOwl/RealQueue/Job/Label
         */
        return \apply_filters('DevOwl/RealQueue/Job/Label', $type, $type);
    }
    /**
     * See filter `DevOwl/RealQueue/Job/Actions`.
     *
     * @param string $type
     */
    public static function actions($type)
    {
        /**
         * Generate a set of links with custom actions (e.g. shown in the error modal dialog).
         *
         * An action has the following properties: `url, linkText, [target="_blank"]`.
         *
         * @param {array[]} $actions
         * @param {string} $type
         * @return {string}
         * @hook DevOwl/RealQueue/Job/Actions
         */
        return \apply_filters('DevOwl/RealQueue/Job/Actions', [], $type);
    }
}
