<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\rest;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Create queue REST service.
 * @internal
 */
class Queue
{
    use UtilsProvider;
    const MAX_BATCH_CLIENT_SIZE = 25;
    const MAX_EXECUTION_SECONDS_PER_REQUEST = 5;
    const LOCK_UNTIL_SECONDS = self::MAX_EXECUTION_SECONDS_PER_REQUEST * self::MAX_BATCH_CLIENT_SIZE;
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
     * Register endpoints.
     */
    public function rest_api_init()
    {
        $namespace = UtilsService::getNamespace($this);
        \register_rest_route($namespace, '/job/(?P<id>[0-9]+)', ['methods' => 'GET', 'callback' => [$this, 'routeJobGet'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['lock' => ['type' => 'boolean']]]);
        \register_rest_route($namespace, '/job/(?P<id>[0-9]+)', ['methods' => 'POST', 'callback' => [$this, 'routeJobExecute'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['try' => ['type' => 'string', 'sanitize_callback' => function ($value) {
            return empty($value) ? [] : \array_map('intval', \explode(',', $value));
        }]]]);
        \register_rest_route($namespace, '/job/(?P<id>[0-9]+)/result', ['methods' => 'POST', 'callback' => [$this, 'routeJobResult'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['process' => ['type' => 'number', 'required' => \true, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }], 'errorCode' => ['type' => 'string'], 'errorMessage' => ['type' => 'string'], 'errorData' => ['type' => 'string']]]);
        \register_rest_route($namespace, '/jobs', ['methods' => 'GET', 'callback' => [$this, 'routeJobsGet'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['after' => ['type' => 'number'], 'ids' => ['type' => 'string', 'sanitize_callback' => function ($value) {
            return empty($value) ? [] : \array_map('intval', \explode(',', $value));
        }]]]);
        \register_rest_route($namespace, '/jobs', ['methods' => 'DELETE', 'callback' => [$this, 'routeJobsDelete'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['type' => ['type' => 'string']]]);
        \register_rest_route($namespace, '/jobs/retry', ['methods' => 'POST', 'callback' => [$this, 'routeJobsRetry'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['type' => ['type' => 'string']]]);
        \register_rest_route($namespace, '/jobs/skip', ['methods' => 'POST', 'callback' => [$this, 'routeJobsSkip'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['type' => ['type' => 'string']]]);
        \register_rest_route($namespace, '/status', ['methods' => 'GET', 'callback' => [$this, 'routeStatus'], 'permission_callback' => [$this->core, 'currentUserAllowedToQuery'], 'args' => ['additionalData' => ['type' => 'string']]]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-queue/v1/job/:job Get a job by ID
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} lock Set to `true` or `false` so other tabs could not pick this job
     * @apiName GetJob
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobGet($request)
    {
        $id = $request->get_param('id');
        $lock = $request->get_param('lock');
        $result = $this->core->getQuery()->fetchById($id);
        if ($result === null) {
            return new WP_Error('rest_not_found', null, ['status' => 404]);
        }
        if ($lock !== null) {
            $result->updatedLocked($lock);
        }
        return new WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-queue/v1/job/:job Execute this job (only server-worker jobs are executed!)
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [tryIds] Comma separated list of additional job ids which could be tried to be executed, too
     * @apiName ExecuteJob
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobExecute($request)
    {
        $jobId = $request->get_param('id');
        $tryIds = $request->get_param('try');
        \array_unshift($tryIds, $jobId);
        $run = $this->core->getExecutor()->run($tryIds, self::MAX_EXECUTION_SECONDS_PER_REQUEST * 1000, \true);
        return new WP_REST_Response($run);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {post} /real-queue/v1/job/result/:job Save the result to a given job
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {number} process
     * @apiParam {string} [errorCode]
     * @apiParam {string} [errorMessage]
     * @apiParam {string} [errorData] JSON string (WP 5.0 does not yet support JSON schema as parameters)
     * @apiName ResultJob
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobResult($request)
    {
        $id = $request->get_param('id');
        $process = $request->get_param('process');
        $errorCode = $request->get_param('errorCode');
        $errorMessage = $request->get_param('errorMessage');
        $errorData = $request->get_param('errorData');
        $result = $this->core->getQuery()->fetchById($id);
        if ($result === null) {
            return new WP_Error('rest_not_found', null, ['status' => 404]);
        }
        $result->passthruClientResult($process, $errorCode === null ? null : new WP_Error($errorCode, $errorMessage, $errorData !== null ? \json_decode($errorData, ARRAY_A) : null));
        return new WP_REST_Response(['job' => $result, 'pauseToAvoidRecurringException' => $result->hasUpdatedJobsToAvoidRecurringException()]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-queue/v1/jobs Get pending jobs (with a limit so you need to re-request this route!)
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {number} after Read only jobs after this job ID
     * @apiParam {string} ids Read only this Job IDs (comma-separated string)
     * @apiName GetJobs
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobsGet($request)
    {
        $after = $request->get_param('after');
        $ids = $request->get_param('ids');
        $query = $this->core->getQuery();
        $data = $query->read(['omitClientData' => \true, 'lockUntil' => self::LOCK_UNTIL_SECONDS, 'ids' => $ids, 'after' => $after]);
        return new WP_REST_Response(['jobs' => $data, 'remaining' => $query->readRemaining()]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-queue/v1/jobs Delete jobs
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [type] Delete all jobs by type
     * @apiName DeleteJobs
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobsDelete($request)
    {
        $type = $request->get_param('type');
        $deleted = \false;
        $persist = $this->core->getPersist();
        if (!empty($type)) {
            $deleted = $persist->deleteByType($type);
        }
        return new WP_REST_Response(['deleted' => $deleted]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-queue/v1/jobs/retry Retry jobs
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [type] Retry all jobs by type
     * @apiName RetryJobs
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobsRetry($request)
    {
        $type = $request->get_param('type');
        $updated = \false;
        $persist = $this->core->getPersist();
        if (!empty($type)) {
            $updated = $persist->retryByType($type);
        }
        return new WP_REST_Response(['updated' => $updated]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {delete} /real-queue/v1/jobs/skip Skip failed jobs
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [type] Skip all failed jobs by type
     * @apiName RetryJobs
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeJobsSkip($request)
    {
        $type = $request->get_param('type');
        $updated = \false;
        $persist = $this->core->getPersist();
        if (!empty($type)) {
            $updated = $persist->skipByType($type);
        }
        return new WP_REST_Response(['updated' => $updated]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-queue/v1/status Get status of our complete queue
     * @apiHeader {string} X-WP-Nonce
     * @apiName Status
     * @apiPermission edit_posts
     * @apiGroup Queue
     * @apiVersion 1.0.0
     */
    public function routeStatus($request)
    {
        return new WP_REST_Response($this->buildStatus($request->get_params()));
    }
    /**
     * Create the response array for the `/status` route.
     *
     * @param array $params
     */
    public function buildStatus($params)
    {
        $query = $this->core->getQuery();
        $executor = $this->core->getExecutor();
        $additionalData = $params['additionalData'] ?? null;
        $remaining = $query->readRemaining();
        return ['currentJobs' => $query->readCurrentJobs(\true), 'errors' => $executor->buildErrorMessages($remaining), 'remaining' => $remaining, 'additionalData' => $additionalData ? $this->fetchAdditionalData(\explode(',', $additionalData)) : (object) []];
    }
    /**
     * Extend the REST API response with requested additional data.
     *
     * @param string[] $additionalData
     */
    protected function fetchAdditionalData($additionalData)
    {
        $result = [];
        foreach ($additionalData as $additionalDataName) {
            /**
             * Allows to expand the REST API response for fetching the queue status with additional data.
             *
             * @param {mixed} $data
             * @return {mixed}
             * @hook DevOwl/RealQueue/Rest/Status/AdditionalData/$additionalDataName
             */
            $additionalDataResult = \apply_filters('DevOwl/RealQueue/Rest/Status/AdditionalData/' . $additionalDataName, null);
            if ($additionalDataResult !== null) {
                $result[$additionalDataName] = $additionalDataResult;
            }
        }
        return (object) $result;
    }
}
