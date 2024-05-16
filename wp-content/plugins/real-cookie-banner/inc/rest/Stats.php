<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Stats as RealCookieBannerStats;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\ViewStats;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Stats API
 * @internal
 */
class Stats
{
    use UtilsProvider;
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
        \register_rest_route($namespace, '/stats/(?P<slug>[a-zA-Z0-9_-]+)', ['methods' => 'GET', 'callback' => [$this, 'routeGetStats'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['from' => ['type' => 'string', 'required' => \true, 'validate_callback' => function ($param) {
            return \strtotime($param) > 0;
        }], 'to' => ['type' => 'string', 'required' => \true, 'validate_callback' => function ($param) {
            return \strtotime($param) > 0;
        }], 'context' => ['type' => 'string', 'default' => '']]]);
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
     * @api {get} /real-cookie-banner/v1/stats/:type Get the given stats by type
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string='main','buttonsClicked','dnt'} type
     * @apiParam {string} from From date in format YYYY-MM-DD
     * @apiParam {string} to From date in format YYYY-MM-DD
     * @apiName Get
     * @apiGroup Stats
     * @apiVersion 1.0.0
     * @apiPermission manage_options, PRO
     */
    public function routeGetStats($request)
    {
        $slug = $request->get_param('slug');
        $from = \gmdate('Y-m-d', \strtotime($request->get_param('from')));
        $to = \gmdate('Y-m-d', \strtotime($request->get_param('to')));
        $context = $request->get_param('context');
        $stats = RealCookieBannerStats::getInstance();
        $result = new WP_Error('rcb_stats_not_found');
        switch ($slug) {
            case 'main':
                $result = $stats->fetchMainStats($from, $to, $context);
                // Toggle checklist
                if (\count($result) > 0) {
                    Checklist::getInstance()->toggle(ViewStats::IDENTIFIER, \true);
                }
                break;
            case 'buttonsClicked':
                $result = $stats->fetchButtonsClickedStats($from, $to, $context);
                break;
            case 'customBypass':
                $result = $stats->fetchCustomBypassStats($from, $to, $context);
                break;
            default:
                break;
        }
        if (\is_wp_error($result)) {
            return $result;
        }
        return new WP_REST_Response($result);
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Stats();
    }
}
