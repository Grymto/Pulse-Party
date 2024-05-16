<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\migration\AbstractDashboardTileMigration;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\ConfigPage;
use DevOwl\RealCookieBanner\view\navmenu\NavMenuLinks;
use DevOwl\RealCookieBanner\view\Notices;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Settings_Controller;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Create an config REST API, extending from the official `wp/v2/settings` route to also provide a route
 * for all Real Cookie Banner specific settings.
 * @internal
 */
class Config extends WP_REST_Settings_Controller
{
    use UtilsProvider;
    /**
     * Register endpoints.
     */
    public function rest_api_init()
    {
        $namespace = Service::getNamespace($this);
        $this->namespace = $namespace;
        $this->register_routes();
        \register_rest_route($namespace, '/checklist', ['methods' => 'GET', 'callback' => [$this, 'routeGetChecklist'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/checklist/(?P<id>[a-zA-Z0-9_-]+)', ['methods' => 'PUT', 'callback' => [$this, 'routePutChecklist'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['state' => ['type' => 'boolean', 'required' => \true]]]);
        \register_rest_route($namespace, '/revision/second-view', ['methods' => 'GET', 'callback' => [$this, 'routeGetRevisionSecondView'], 'permission_callback' => '__return_true']);
        \register_rest_route($namespace, '/revision/current', ['methods' => 'GET', 'callback' => [$this, 'routeGetRevisionCurrent'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/revision/(?P<hash>[a-zA-Z0-9_-]{32})', ['methods' => 'GET', 'callback' => [$this, 'routeGetRevisionByHash'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['backwardsCompatibility' => ['type' => 'boolean', 'default' => \true]]]);
        \register_rest_route($namespace, '/revision/independent/(?P<hash>[a-zA-Z0-9_-]{32})', ['methods' => 'GET', 'callback' => [$this, 'routeGetRevisionIndependentByHash'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['backwardsCompatibility' => ['type' => 'boolean', 'default' => \true]]]);
        \register_rest_route($namespace, '/revision/current', ['methods' => 'PUT', 'callback' => [$this, 'routePutRevisionCurrent'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['needs_retrigger' => ['type' => 'boolean', 'default' => \true]]]);
        \register_rest_route($namespace, '/cookie-groups/order', ['methods' => 'PUT', 'callback' => [$this, 'routeCookieGroupsOrder'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/cookies/order', ['methods' => 'PUT', 'callback' => [$this, 'routeCookiesOrder'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/cookies/unassigned', ['methods' => 'GET', 'callback' => [$this, 'routeCookiesUnassigned'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/country-bypass/database', ['methods' => 'PUT', 'callback' => [$this, 'routeCountryBypassDownload'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/migration/(?P<migrationId>[a-zA-Z0-9_-]+)/(?P<actionId>[a-zA-Z0-9_-]+)', ['methods' => 'POST', 'callback' => [$this, 'routeMigrationPost'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/migration/(?P<migrationId>[a-zA-Z0-9_-]+)', ['methods' => 'DELETE', 'callback' => [$this, 'routeMigrationDelete'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/nav-menu/add-links', ['methods' => 'POST', 'callback' => [$this, 'routeNavMenuAddLinksPost'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['id' => ['type' => 'string', 'required' => \true]]]);
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
     * @api {get} /real-cookie-banner/v1/checklist Get all checklist items with their state
     * @apiHeader {string} X-WP-Nonce
     * @apiName ChecklistGet
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeGetChecklist()
    {
        return new WP_REST_Response(\array_merge(Checklist::getInstance()->result(), ['overdue' => Checklist::getInstance()->isOverdue(ConfigPage::CHECKLIST_OVERDUE)]));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {put} /real-cookie-banner/v1/checklist/:id Mark a checklist item as checked / unchecked
     * @apiHeader {string} X-WP-Nonce
     * @apiHeader {boolean} state
     * @apiName ChecklistPut
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routePutChecklist($request)
    {
        Checklist::getInstance()->toggle($request->get_param('id'), $request->get_param('state'));
        return new WP_REST_Response(\array_merge(Checklist::getInstance()->result(), ['overdue' => Checklist::getInstance()->isOverdue(ConfigPage::CHECKLIST_OVERDUE)]));
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/revision/second-view Get the current lazy loadable data for the second view
     * @apiName RevisionSecondView
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeGetRevisionSecondView()
    {
        $frontend = Core::getInstance()->getCookieConsentManagement()->getFrontend();
        $output = $frontend->toJson();
        return new WP_REST_Response($frontend->prepareLazyData($output));
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/revision/current Get the current revision hash
     * @apiHeader {string} X-WP-Nonce
     * @apiName RevisionCurrent
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeGetRevisionCurrent()
    {
        $current = Revision::getInstance()->getCurrent();
        return new WP_REST_Response(\array_merge(['needs_retrigger' => Revision::getInstance()->needsRetrigger($current)], $current));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @since 1.3.0
     * @api {get} /real-cookie-banner/v1/revision/:hash Get the revision by hash
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} [backwardsCompatibility=true]
     * @apiName RevisionByHash
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeGetRevisionByHash($request)
    {
        $result = Revision::getInstance()->getByHash($request->get_param('hash'), \false, $request->get_param('backwardsCompatibility'));
        return $result === null ? new WP_Error('rest_not_found', null, ['status' => 404]) : new WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @since 1.3.0
     * @api {get} /real-cookie-banner/v1/revision/independent/:hash Get the independent revision by hash
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} [backwardsCompatibility=true]
     * @apiName RevisionIndependentByHash
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeGetRevisionIndependentByHash($request)
    {
        $result = Revision::getInstance()->getByHash($request->get_param('hash'), \true, $request->get_param('backwardsCompatibility'));
        return $result === null ? new WP_Error('rest_not_found', null, ['status' => 404]) : new WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {put} /real-cookie-banner/v1/revision/current Update current revision hash from the latest settings
     * @apiHeader {string} X-WP-Nonce
     * @apiName RevisionCurrentPut
     * @apiParam {boolean} [needs_retrigger=true] If you do not want to collect new consents for the current revision, pass `false`
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routePutRevisionCurrent($request)
    {
        $revision = Revision::getInstance();
        $needsRetrigger = $request->get_param('needs_retrigger');
        if (!$needsRetrigger) {
            Core::getInstance()->getNotices()->getStates()->set(Notices::NOTICE_REVISON_REQUEST_NEW_CONSENT_PREFIX . $revision->getContextVariablesString(), $revision->getCurrent()['calculated']);
        }
        $current = $revision->getCurrent($needsRetrigger);
        return new WP_REST_Response(\array_merge(['needs_retrigger' => $revision->needsRetrigger($current)], $current));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {put} /real-cookie-banner/v1/cookie-groups/order Order the cookie groups
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {number[]} ids
     * @apiName CookieGroupsOrder
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeCookieGroupsOrder($request)
    {
        // Get ids array
        $ids = $request->get_param('ids');
        if (!isset($ids) || !\is_array($ids) || empty($ids)) {
            return new WP_Error('rest_rcb_wrong_ids');
        }
        $ids = \array_map('absint', $ids);
        // Persist
        foreach ($ids as $index => $id) {
            \update_term_meta($id, 'order', $index);
        }
        return new WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {put} /real-cookie-banner/v1/cookies/order Order the cookies
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {number[]} ids
     * @apiName CookiesOrder
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeCookiesOrder($request)
    {
        // Get ids array
        $ids = $request->get_param('ids');
        if (!isset($ids) || !\is_array($ids) || empty($ids)) {
            return new WP_Error('rest_rcb_wrong_ids');
        }
        $ids = \array_map('absint', $ids);
        // Persist
        foreach ($ids as $index => $id) {
            \wp_update_post(['ID' => $id, 'menu_order' => $index]);
        }
        return new WP_REST_Response(\true);
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/cookies/unassigned Get unassigned services
     * @apiHeader {string} X-WP-Nonce
     * @apiName CookiesUnassigned
     * @apiGroup Config
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeCookiesUnassigned()
    {
        $posts = Cookie::getInstance()->getUnassignedCookies();
        $result = [];
        foreach ($posts as $post) {
            $result[] = ['id' => $post->ID, 'title' => $post->post_title];
        }
        return new WP_REST_Response($result);
    }
    /**
     * See API docs.
     *
     * @api {put} /real-cookie-banner/v1/country-bypass/database Download the Country Bypass IP database
     * @apiHeader {string} X-WP-Nonce
     * @apiName CountryBypassDatabaseDownload
     * @apiGroup Config
     * @apiPermission manage_options, PRO
     * @apiVersion 1.0.0
     */
    public function routeCountryBypassDownload()
    {
        $result = CountryBypass::getInstance()->updateDatabase();
        return \is_wp_error($result) ? $result : new WP_REST_Response(['dbDownloadTime' => \mysql2date('c', CountryBypass::getInstance()->getDatabaseDownloadTime(), \false)]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {post} /real-cookie-banner/v1/migration/:migration/:action Apply a registered migration action registered via `AbstractDashboardTileMigration::addAction`
     * @apiParam {string} migration
     * @apiParam {string} action
     * @apiHeader {string} X-WP-Nonce
     * @apiName DoMigration
     * @apiGroup Config
     * @apiPermission manage_options, PRO
     * @apiVersion 1.0.0
     */
    public function routeMigrationPost($request)
    {
        $result = AbstractDashboardTileMigration::doAction($request->get_param('migrationId'), $request->get_param('actionId'));
        if (\is_wp_error($result)) {
            return $result;
        }
        if ($result['success'] !== \true) {
            return new WP_Error('rcb_migration_failed', null, ['result' => $result]);
        }
        return $result;
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {delete} /real-cookie-banner/v1/migration/:migration/ Dismiss a migration by migration ID
     * @apiParam {string} migration
     * @apiHeader {string} X-WP-Nonce
     * @apiName DismissMigration
     * @apiGroup Config
     * @apiPermission manage_options, PRO
     * @apiVersion 1.0.0
     */
    public function routeMigrationDelete($request)
    {
        AbstractDashboardTileMigration::doDismiss($request->get_param('migrationId'));
        return new WP_REST_Response(['success' => \true]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {post} /real-cookie-banner/v1//nav-menu/add-links Add
     * @apiParam {string} id The ID for this navigation
     * @apiHeader {string} X-WP-Nonce
     * @apiName NavMenuAddLinks
     * @apiGroup Config
     * @apiPermission manage_options, PRO
     * @apiVersion 1.0.0
     */
    public function routeNavMenuAddLinksPost($request)
    {
        $result = NavMenuLinks::instance()->addLinksToMenu($request->get_param('id'));
        if (\is_wp_error($result)) {
            return $result;
        }
        return new WP_REST_Response(['success' => $result]);
    }
    /**
     * Retrieves all of the registered options for the Settings API, specific to Real Cookie Banner.
     *
     * @return array Array of registered options.
     */
    public function get_registered_options()
    {
        $array = parent::get_registered_options();
        foreach ($array as $key => $value) {
            if (!Utils::startsWith($key, RCB_OPT_PREFIX)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
    /**
     * See `WP_REST_Settings_Controller`.
     *
     * @param WP_REST_Request $request Full details about the request.
     */
    public function get_item_permissions_check($request)
    {
        return \current_user_can(Core::MANAGE_MIN_CAPABILITY);
    }
    /**
     * Check if settings got updated and `do_action`.
     *
     * @param WP_HTTP_Response $response
     * @param WP_REST_Server $server
     * @param WP_REST_Request $request
     */
    public function rest_post_dispatch($response, $server, $request)
    {
        if ($request->get_route() === \sprintf('/%s/%s', $this->namespace, $this->rest_base) && $request->get_method() === 'PATCH' && isset($response->data) && \is_array($response->data)) {
            // Check if any RCB specific option was set
            foreach (\array_keys($response->data) as $key) {
                if (\strpos($key, RCB_OPT_PREFIX, 0) === 0) {
                    /**
                     * Settings got updated in the "Settings" tab.
                     *
                     * @hook RCB/Settings/Updated
                     * @param {WP_HTTP_Response} $response
                     * @param {WP_REST_Request} $request
                     * @return {WP_HTTP_Response}
                     */
                    $response = \apply_filters('RCB/Settings/Updated', $response, $request);
                    break;
                }
            }
        }
        return $response;
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Config();
    }
}
