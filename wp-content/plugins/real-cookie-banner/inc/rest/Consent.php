<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\consent\Transaction;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Clear;
use DevOwl\RealCookieBanner\IpHandler;
use DevOwl\RealCookieBanner\MyConsent;
use DevOwl\RealCookieBanner\UserConsent;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Consent API
 * @internal
 */
class Consent
{
    use UtilsProvider;
    const CONSENT_ALL_ITEMS_PER_PAGE = 10;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Register endpoints.
     */
    public function rest_api_init()
    {
        $namespace = Service::getNamespace($this);
        \register_rest_route($namespace, '/consent/all', ['methods' => 'GET', 'callback' => [$this, 'routeGetAll'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['offset' => ['type' => 'number', 'required' => \true], 'per_page' => ['type' => 'number'], 'uuid' => ['type' => 'string'], 'ip' => ['type' => 'string'], 'referer' => ['type' => 'string'], 'from' => ['type' => 'string', 'validate_callback' => function ($param) {
            return empty($param) ? \true : \strtotime($param) > 0;
        }], 'to' => ['type' => 'string', 'validate_callback' => function ($param) {
            return empty($param) ? \true : \strtotime($param) > 0;
        }], 'context' => ['type' => 'string', 'default' => '']]]);
        \register_rest_route($namespace, '/consent/referer', ['methods' => 'GET', 'callback' => [$this, 'routeGetAllReferer'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/consent/all', ['methods' => 'DELETE', 'callback' => [$this, 'routeDeleteAll'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/consent/clear', ['methods' => 'DELETE', 'callback' => [$this, 'routeDeleteClear'], 'permission_callback' => '__return_true', 'args' => ['cookies' => ['type' => 'string', 'required' => \true]]]);
        \register_rest_route($namespace, '/consent', ['methods' => 'GET', 'callback' => [$this, 'routeGet'], 'permission_callback' => '__return_true']);
        \register_rest_route($namespace, '/consent/dynamic-predecision', ['methods' => 'POST', 'callback' => [$this, 'routePostDynamicPredecision'], 'args' => ['viewPortWidth' => ['type' => 'number', 'default' => 0], 'viewPortHeight' => ['type' => 'number', 'default' => 0]], 'permission_callback' => '__return_true']);
        \register_rest_route($namespace, '/consent', ['methods' => 'POST', 'callback' => [$this, 'routePost'], 'permission_callback' => '__return_true', 'args' => [
            'dummy' => ['type' => 'boolean', 'default' => \false],
            'markAsDoNotTrack' => ['type' => 'boolean', 'default' => \false],
            // Also ported to wp-api/consent.post.tsx
            'buttonClicked' => ['type' => 'string', 'enum' => UserConsent::CLICKABLE_BUTTONS, 'required' => \true],
            // Content Blocker ID
            'blocker' => ['type' => 'number', 'default' => 0],
            'blockerThumbnail' => ['type' => 'string'],
            'viewPortWidth' => ['type' => 'number', 'default' => 0],
            'viewPortHeight' => ['type' => 'number', 'default' => 0],
            // TCF compatibility: encoded TCF string
            'tcfString' => ['type' => 'string'],
            'recorderJsonString' => ['type' => 'string'],
            'uiView' => ['type' => 'string'],
            'createdClientTime' => ['type' => 'string'],
            'setCookies' => ['type' => 'boolean', 'default' => \true],
        ]]);
        \register_rest_route($namespace, '/consent/(?P<id>[0-9]+)', ['methods' => 'DELETE', 'callback' => [$this, 'routeDelete'], 'permission_callback' => [$this, 'permission_callback']]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback()
    {
        return \current_user_can(Core::MANAGE_MIN_CAPABILITY);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/consent/all Get all consent entries
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {number} offset
     * @apiParam {number} [per_page=10]
     * @apiParam {string} [uuid]
     * @apiParam {string} [ip]
     * @apiParam {string} [referer]
     * @apiParam {string} [from] From date in format YYYY-MM-DD
     * @apiParam {string} [to] From date in format YYYY-MM-DD
     * @apiParam {string} [context]
     * @apiName GetAll
     * @apiGroup Consent
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeGetAll($request)
    {
        // Validate parameters
        $limitOffset = $request->get_param('offset');
        $limitOffset = $limitOffset >= 0 ? $limitOffset : 0;
        $perPage = $request->get_param('per_page');
        $perPage = isset($perPage) && $perPage >= 1 && $perPage <= 100 ? \intval($perPage) : self::CONSENT_ALL_ITEMS_PER_PAGE;
        $uuid = $request->get_param('uuid');
        $ip = $request->get_param('ip');
        $referer = $request->get_param('referer');
        $from = $request->get_param('from');
        $to = $request->get_param('to');
        $context = $request->get_param('context');
        $args = ['offset' => $limitOffset, 'perPage' => $perPage, 'uuid' => $uuid, 'ip' => $ip, 'pure_referer' => $referer, 'context' => $context];
        if (!empty($from)) {
            $args['from'] = $from . ' 00:00:00';
        }
        if (!empty($from)) {
            $args['to'] = $to . ' 23:59:59';
        }
        $userConsent = UserConsent::getInstance();
        $items = $userConsent->byCriteria($args);
        if (\is_wp_error($items)) {
            return $items;
        }
        $count = $userConsent->byCriteria($args, UserConsent::BY_CRITERIA_RESULT_TYPE_COUNT);
        return new WP_REST_Response(\array_merge(
            ['count' => $count, 'items' => $items],
            // Due to performance reasons, fill this only when requesting the first page
            $limitOffset > 0 ? [] : ['truncatedIpsCount' => $userConsent->getTruncatedIpsCount()]
        ));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/consent/referer Get all referer across all consents
     * @apiHeader {string} X-WP-Nonce
     * @apiName GetAllReferer
     * @apiGroup Consent
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeGetAllReferer($request)
    {
        return new WP_REST_Response(['items' => UserConsent::getInstance()->getReferer()]);
    }
    /**
     * See API docs.
     *
     * @api {delete} /real-cookie-banner/v1/consent/all Delete all consent entries (including revisions)
     * @apiHeader {string} X-WP-Nonce
     * @apiName DeleteAll
     * @apiGroup Consent
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeDeleteAll()
    {
        return new WP_REST_Response(UserConsent::getInstance()->purge());
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {delete} /real-cookie-banner/v1/consent/clear Delete server-side cookies like `http` by cookie ids
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} cookies A comma separated list of cookie ids which should be opt-out
     * @apiName DeleteClear
     * @apiGroup Consent
     * @apiVersion 1.0.0
     */
    public function routeDeleteClear($request)
    {
        $cookies = \array_map('intval', \explode(',', $request->get_param('cookies')));
        return new WP_REST_Response(Clear::getInstance()->byCookies($cookies));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/consent Get the last 100 consent entries for the current user
     * @apiHeader {string} X-WP-Nonce
     * @apiName Get
     * @apiGroup Consent
     * @apiVersion 1.0.0
     */
    public function routeGet($request)
    {
        return new WP_REST_Response(MyConsent::getInstance()->getCurrentHistory());
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {post} /real-cookie-banner/v1/consent/dynamic-predecision Calculate a dynamic predecision for the current page request
     * @apiParam {number} [viewPortWidth=0]
     * @apiParam {number} [viewPortHeight=0]
     * @apiName CalculateDynamicPredecision
     * @apiGroup Consent
     * @apiVersion 1.0.0
     */
    public function routePostDynamicPredecision($request)
    {
        if (IpHandler::getInstance()->isFlooding()) {
            return new WP_Error('rest_rcb_forbidden');
        }
        $start = \microtime(\true);
        /**
         * Before the banner gets shown to the user, this WP REST API request is called. Please do only
         * add a filter to this hook if your option is active (e.g. Country Bypass only when active), this avoids
         * the request when no dynamic predecision can be made.
         *
         * The result must be one of this: `('all'|'dnt'|'consent'|'nothing')`.
         *
         * @hook RCB/Consent/DynamicPreDecision
         * @param {boolean|string} $result
         * @param {WP_REST_Request} $request
         * @return {boolean|string}
         * @since 2.0.0
         */
        $predecision = \apply_filters('RCB/Consent/DynamicPreDecision', \false, $request);
        return new WP_REST_Response(['predecision' => $predecision, 'calculationTimeSeconds' => \microtime(\true) - $start]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {post} /real-cookie-banner/v1/consent Create or update an existing consent
     * @apiParam {array} decision
     * @apiParam {string} buttonClicked
     * @apiParam {boolean} [dummy]
     * @apiParam {boolean} [markAsDoNotTrack]
     * @apiParam {number} [viewPortWidth=0]
     * @apiParam {number} [viewPortHeight=0]
     * @apiParam {number} [blocker=0]
     * @apiParam {string} [blockerThumbnail]
     * @apiParam {string} [tcfString]
     * @apiParam {array} [gcmConsent]
     * @apiParam {string} [recorderJsonString]
     * @apiParam {string} [uiView]
     * @apiParam {string} [createdClientTime]
     * @apiParam {string} [setCookies=true]
     * @apiName Create
     * @apiGroup Consent
     * @apiVersion 1.0.0
     */
    public function routePost($request)
    {
        $dummy = $request->get_param('dummy');
        $markAsDoNotTrack = $request->get_param('markAsDoNotTrack');
        $buttonClicked = $request->get_param('buttonClicked');
        $viewPortWidth = $request->get_param('viewPortWidth');
        $viewPortHeight = $request->get_param('viewPortHeight');
        $tcfString = $request->get_param('tcfString');
        $recorderJsonString = $request->get_param('recorderJsonString');
        $uiView = $request->get_param('uiView');
        $blocker = $request->get_param('blocker');
        $blockerThumbnail = $request->get_param('blockerThumbnail');
        $createdClientTime = $request->get_param('createdClientTime');
        $setCookies = $request->get_param('setCookies');
        $referer = \wp_get_raw_referer();
        if (IpHandler::getInstance()->isFlooding()) {
            return new WP_Error('rest_rcb_forbidden');
        }
        $transaction = new Transaction();
        $transaction->decision = $request->get_param('decision');
        $transaction->markAsDoNotTrack = $markAsDoNotTrack;
        $transaction->buttonClicked = $buttonClicked;
        $transaction->viewPortWidth = $viewPortWidth;
        $transaction->viewPortHeight = $viewPortHeight;
        $transaction->referer = $referer;
        $transaction->blocker = $blocker;
        $transaction->blockerThumbnail = $blockerThumbnail;
        $transaction->tcfString = $tcfString;
        $transaction->gcmConsent = $request->get_param('gcmConsent');
        $transaction->recorderJsonString = $recorderJsonString;
        $transaction->uiView = $uiView;
        $transaction->setCookies = $setCookies;
        if ($createdClientTime !== null && \strtotime($createdClientTime) > 0) {
            $transaction->createdClientTime = $createdClientTime;
        }
        $persist = MyConsent::getInstance()->persist($transaction, $dummy);
        if (\is_wp_error($persist)) {
            return $persist;
        }
        return new WP_REST_Response($persist);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {delete} /real-cookie-banner/v1/consent/:id Create or update an existing consent
     * @apiName Delete
     * @apiGroup Consent
     * @apiVersion 1.0.0
     */
    public function routeDelete($request)
    {
        global $wpdb;
        $deleted = $wpdb->delete($this->getTableName(UserConsent::TABLE_NAME), ['id' => $request->get_param('id')], ['id' => '%d']);
        return $deleted > 0 ? new WP_REST_Response(null, 204) : new WP_Error('rest_rcb_consent_delete_failed', 'No consent with this ID found.');
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Consent();
    }
}
