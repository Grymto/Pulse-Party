<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\import\Export;
use DevOwl\RealCookieBanner\import\ExportConsent;
use DevOwl\RealCookieBanner\import\Import as RealCookieBannerImport;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Import / Export API
 * @internal
 */
class Import
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
        \register_rest_route($namespace, '/import', ['methods' => 'POST', 'callback' => [$this, 'routeImport'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['json' => ['type' => 'string', 'required' => \true, 'validate_callback' => function ($param) {
            return \json_decode($param) !== null;
        }], 'cookieGroup' => ['type' => 'number', 'required' => \true], 'cookieStatus' => ['type' => 'enum', 'enum' => RealCookieBannerImport::IMPORT_POST_STATI, 'required' => \true], 'cookieSkipExisting' => ['type' => 'boolean', 'default' => \true], 'blockerStatus' => ['type' => 'enum', 'enum' => RealCookieBannerImport::IMPORT_POST_STATI, 'required' => \true], 'blockerSkipExisting' => ['type' => 'boolean', 'default' => \true], 'tcfVendorConfigurationStatus' => ['type' => 'enum', 'enum' => RealCookieBannerImport::IMPORT_POST_STATI, 'required' => \true]]]);
        \register_rest_route($namespace, '/export', ['methods' => 'GET', 'callback' => [$this, 'routeExport'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['download' => ['type' => 'boolean'], 'settings' => ['type' => 'boolean', 'default' => \true], 'cookieGroups' => ['type' => 'boolean', 'default' => \true], 'cookies' => ['type' => 'boolean', 'default' => \true], 'blocker' => ['type' => 'boolean', 'default' => \true], 'tcfVendorConfigurations' => ['type' => 'boolean', 'default' => \false], 'customizeBanner' => ['type' => 'boolean', 'default' => \true]]]);
        \register_rest_route($namespace, '/export/consents', ['methods' => 'GET', 'callback' => [$this, 'routeExportConsents'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['uuid' => ['type' => 'string'], 'from' => ['type' => 'string', 'validate_callback' => function ($param) {
            return \strtotime($param) > 0 || empty($param);
        }], 'to' => ['type' => 'string', 'validate_callback' => function ($param) {
            return \strtotime($param) > 0 || empty($param);
        }]]]);
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
     * @api {post} /real-cookie-banner/v1/import Import settings, cookies, groups, ...
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} json
     * @apiParam {boolean} cookieGroup
     * @apiParam {string='keep','publish','private','draft'} cookieStatus
     * @apiParam {string='keep','publish','private','draft'} blockerStatus
     * @apiParam {string='keep','publish','private','draft'} tcfVendorConfigurationStatus
     * @apiParam {boolean} [cookieSkipExisting=true]
     * @apiParam {boolean} [blockerSkipExisting=true]
     * @apiName Import
     * @apiGroup Import
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeImport($request)
    {
        // Prepare importer
        $result = RealCookieBannerImport::instance(\json_decode($request->get_param('json'), ARRAY_A));
        $result->setCookieGroup($request->get_param('cookieGroup'));
        $result->setCookieStatus($request->get_param('cookieStatus'));
        $result->setCookieSkipExisting($request->get_param('cookieSkipExisting'));
        $result->setBlockerStatus($request->get_param('blockerStatus'));
        $result->setBlockerSkipExisting($request->get_param('blockerSkipExisting'));
        $result->setTcfVendorConfigurationStatus($request->get_param('tcfVendorConfigurationStatus'));
        $result->import();
        return new WP_REST_Response(['messages' => $result->getMessages()]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/export Export as JSON (for consent export see ExportConsent API!)
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} [download=false]
     * @apiParam {boolean} [settings=true]
     * @apiParam {boolean} [cookieGroups=true]
     * @apiParam {boolean} [cookies=true]
     * @apiParam {boolean} [blocker=true]
     * @apiParam {boolean} [tcfVendorConfigurations=true] Since 2.0.0
     * @apiParam {boolean} [customizeBanner=true]
     * @apiName Export
     * @apiGroup Import
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeExport($request)
    {
        global $wpdb;
        $result = Export::instance();
        if ($request->get_param('settings')) {
            $result->appendSettings();
            $result->appendBannerLinks();
        }
        if ($request->get_param('cookieGroups')) {
            $result->appendCookieGroups();
        }
        if ($request->get_param('cookies')) {
            $result->appendCookies();
        }
        if ($request->get_param('blocker')) {
            $result->appendBlocker();
        }
        if ($request->get_param('tcfVendorConfigurations')) {
            $result->appendTcfVendorConfigurations();
        }
        if ($request->get_param('customizeBanner')) {
            $result->appendCustomizeBanner();
        }
        $jsonResult = $result->finish();
        // Download instead of output
        if ($request->get_param('download')) {
            $json = \json_encode($jsonResult);
            \header('Content-Disposition: attachment; filename="rcb-export-' . \md5($json) . '.json";');
            echo $json;
            // Close (wp_die could not be used here because it generates unwanted output)
            $wpdb->close();
            die;
        }
        return new WP_REST_Response($jsonResult);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/export/consents Export consents as CSV
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [uuid]
     * @apiParam {string} [from]
     * @apiParam {string} [to]
     * @apiName ExportConsents
     * @apiGroup Import
     * @apiVersion 1.0.0
     * @apiPermission manage_options
     */
    public function routeExportConsents($request)
    {
        $uuid = $request->get_param('uuid');
        $from = $request->get_param('from');
        $to = $request->get_param('to');
        ExportConsent::instance()->downloadCsv($uuid, $from, $to);
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Import();
    }
}
