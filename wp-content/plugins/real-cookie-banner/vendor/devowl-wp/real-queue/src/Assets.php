<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\rest\Queue;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Assets as UtilsAssets;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Assets handling.
 * @internal
 */
class Assets
{
    use UtilsProvider;
    use UtilsAssets;
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
     * Localize the plugin with additional options.
     *
     * @param string $context
     * @return array
     */
    public function overrideLocalizeScript($context)
    {
        $query = $this->core->getQuery();
        $remaining = $query->readRemaining();
        return ['localStorageSuffix' => \md5(\site_url() . \get_current_blog_id()), 'remaining' => $remaining, 'errors' => $this->core->getExecutor()->buildErrorMessages($remaining)];
    }
    /**
     * Enqueue scripts and styles depending on the type. This function is called
     * from both admin_enqueue_scripts and wp_enqueue_scripts. You can check the
     * type through the $type parameter. In this function you can include your
     * external libraries from public/lib, too.
     *
     * Note: The scripts are loaded only on backend (`admin_enqueue_scripts`). If your plugin
     * is also loaded on frontend you need to make sure to enqueue via `wp_enqueue_scripts`, too.
     * See also https://app.clickup.com/t/4rknyh for more information about this (commits).
     *
     * @param string $type The type (see Assets constants)
     * @param string $hook_suffix The current admin page
     */
    public function enqueue_scripts_and_styles($type, $hook_suffix = null)
    {
        if (!$this->core->currentUserAllowedToQuery()) {
            return;
        }
        $this->enqueue();
    }
    /**
     * Enqueue scripts and styles for this library.
     *
     * @param UtilsAssets $assets
     */
    public function enqueue($assets = null)
    {
        $assets = $this->core->getPluginCore()->getAssets();
        $expectedHandle = \sprintf('%s-%s', REAL_QUEUE_ROOT_SLUG, REAL_QUEUE_SLUG);
        if (!\wp_script_is($expectedHandle)) {
            $scriptDeps = $assets->enqueueUtils();
            $handle = $assets->enqueueComposerScript(REAL_QUEUE_SLUG, $scriptDeps);
            $assets->enqueueComposerStyle(REAL_QUEUE_SLUG, []);
            \wp_localize_script($handle, REAL_QUEUE_SLUG_CAMELCASE, $this->localizeScript($this));
            /**
             * The queue worker scripts got enqueued to the frontend. If you are using client-worker jobs
             * you can use this hook to enqueue your scripts which listens to the `document`'s `RealQueue/ClientJob/$type` event
             * and executes the job on the client.
             *
             * @hook DevOwl/RealQueue/EnqueueScripts
             * @param {string} $handle The `real-queue` JavaScript handle
             */
            \do_action('DevOwl/RealQueue/EnqueueScripts', $handle);
            return $handle;
        }
        return $expectedHandle;
    }
}
