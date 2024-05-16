<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\templates\BannerTemplates;
use DevOwl\RealCookieBanner\templates\CloudDataSource;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Create REST API for all available service / blocker templates.
 * @internal
 */
class Templates
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
        \register_rest_route($namespace, '/presets/banner', ['methods' => 'GET', 'callback' => [$this, 'routeBannerTemplates'], 'permission_callback' => [$this, 'permission_callback_customize']]);
        \register_rest_route($namespace, '/templates/(?P<type>blocker|services)', ['methods' => 'GET', 'callback' => [$this, 'routeTemplatesGet'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['storage' => ['type' => 'enum', 'enum' => ['use', 'invalidate', 'redownload'], 'default' => 'use']]]);
        \register_rest_route($namespace, '/templates/(?P<type>blocker|services)/(?P<identifier>[a-zA-Z0-9_-]+)/', ['methods' => 'GET', 'callback' => [$this, 'routeTemplatesGetSingle'], 'permission_callback' => [$this, 'permission_callback']]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback()
    {
        return \current_user_can(Core::MANAGE_MIN_CAPABILITY);
    }
    /**
     * Check if user is allowed to call this service requests with `customize` cap.
     */
    public function permission_callback_customize()
    {
        return \current_user_can(BannerCustomize::NEEDED_CAPABILITY);
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/presets/banner Get all available customize banner templates
     * @apiHeader {string} X-WP-Nonce
     * @apiName Banner
     * @apiGroup Templates
     * @apiPermission customize
     * @apiVersion 1.0.0
     */
    public function routeBannerTemplates()
    {
        $templates = new BannerTemplates();
        return new WP_REST_Response(['items' => $templates->get(), 'defaults' => $templates->defaults(), 'constants' => $templates->constants()]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/templates/:type Get all available templates by type (can be blocker or services)
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [storage=use] Can be `'use' | 'invalidate' | 'redownload'`
     * @apiName Templates
     * @apiGroup Templates
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeTemplatesGet($request)
    {
        $type = $request->get_param('type');
        $storage = $request->get_param('storage');
        if ($storage === 'redownload') {
            TemplateConsumers::getInstance()->currentForceRedownload();
        }
        $consumer = $type === 'blocker' ? TemplateConsumers::getCurrentBlockerConsumer() : TemplateConsumers::getCurrentServiceConsumer();
        $items = AbstractTemplate::toArrays($consumer->retrieve($storage === 'invalidate'));
        $retryIn = $consumer->getVariableResolver()->resolveDefault(CloudDataSource::VARIABLE_NAME_FAILED_DOWNLOAD_RETRY_IN);
        if ($storage === 'redownload' && $retryIn !== null && $retryIn > 0) {
            if ($retryIn < 60) {
                // The UI should retry again in x seconds
                \header('Retry-After: ' . $retryIn);
                return new WP_Error('rcb_template_cache_pending', 'We requested the service cloud to download templates and allowed async cache calculation, which is still pending.');
            } else {
                // Throw error that the service cloud is not reachable
                return new WP_Error('rcb_template_service_cloud_not_reachable', \__('The Service Cloud is currently not reachable, we will try to download the templates again in a few minutes automatically...', RCB_TD));
            }
        }
        return new WP_REST_Response(['items' => $items]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     * @api {get} /real-cookie-banner/v1/templates/:type/:identifier Get template by identifier and apply use-middlewares
     * @apiHeader {string} X-WP-Nonce
     * @apiName UseTemplate
     * @apiGroup Templates
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeTemplatesGetSingle($request)
    {
        $type = $request->get_param('type');
        $identifier = $request->get_param('identifier');
        $consumer = $type === 'blocker' ? TemplateConsumers::getCurrentBlockerConsumer() : TemplateConsumers::getCurrentServiceConsumer();
        $template = $consumer->retrieveBy('identifier', $identifier);
        if (\count($template) === 0) {
            return new WP_Error('rest_not_found', '', ['status' => 404]);
        }
        return new WP_REST_Response(AbstractTemplate::toArray($template[0]->use()));
    }
    /**
     * Modify the response of the cookie / blocker Custom Post Type.
     *
     * @param WP_REST_Response $response The response object.
     * @param WP_Post $post Post object.
     */
    public function rest_prepare_templates($response, $post)
    {
        $template = \false;
        $templateIdentifier = \get_post_meta($post->ID, Blocker::META_NAME_PRESET_ID, \true);
        if (!empty($templateIdentifier)) {
            /**
             * Cloud consumer.
             *
             * @var ServiceCloudConsumer
             */
            $templates = null;
            switch ($response->data['type']) {
                case Cookie::CPT_NAME:
                    $templates = TemplateConsumers::getCurrentServiceConsumer();
                    break;
                case Blocker::CPT_NAME:
                    $templates = TemplateConsumers::getCurrentBlockerConsumer();
                    break;
                default:
                    return $response;
            }
            $template = $templates->retrieveBy('identifier', $templateIdentifier);
            $template = $template[0] ?? \false;
            if ($template !== \false) {
                $template = AbstractTemplate::toArray($template);
            }
        }
        $response->data['usedTemplate'] = $template;
        return $response;
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Templates();
    }
}
