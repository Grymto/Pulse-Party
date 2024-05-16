<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\rest\Queue;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Executor;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Persist;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Query;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Activator;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Core as UtilsCore;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ServiceNoStore;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Core for real-queue.
 * @internal
 */
class Core
{
    use UtilsProvider;
    private $pluginCore;
    private $assets;
    private $persist;
    private $query;
    private $executor;
    private $restQuery;
    /**
     * `real-queue` allows to define multiple capabilities needed for fetching and working the queue.
     * For this, you can use `Core#addCapability()`.
     */
    private $capabilities = ['edit_posts'];
    /**
     * C'tor.
     *
     * @param UtilsCore $pluginCore
     */
    public function __construct($pluginCore)
    {
        $this->pluginCore = $pluginCore;
        $this->assets = new Assets($this);
        $this->persist = new Persist($this);
        $this->query = new Query($this);
        $this->executor = new Executor($this);
        $this->restQuery = new Queue($this);
        // Enable `no-store` for our relevant WP REST API endpoints
        ServiceNoStore::hook('/' . Service::getNamespace($this));
        //add_action('wp_enqueue_scripts', [$this->getAssets(), 'admin_enqueue_scripts'], 9);
        \add_action('admin_enqueue_scripts', [$this->getAssets(), 'admin_enqueue_scripts'], 9);
        \add_action('rest_api_init', [$this->restQuery, 'rest_api_init']);
    }
    /**
     * Make sure the database tables are created.
     *
     * @param Activator $activator
     */
    public function dbDelta($activator)
    {
        $charset_collate = $activator->getCharsetCollate();
        $table_name = $this->getTableName();
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            type varchar(50) NOT NULL,\n            worker varchar (10) NOT NULL,\n            group_uuid char(36),\n            group_position int(11),\n            group_total int(11),\n            process int(11) NOT NULL,\n            process_total int(11) NOT NULL,\n            duration_ms int(11),\n            data text NOT NULL,\n            runs int NOT NULL DEFAULT 0,\n            retries int NOT NULL,\n            delay_ms int NOT NULL,\n            created datetime NOT NULL,\n            lock_until timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,\n            locked tinyint(1) DEFAULT 0,\n            callable tinytext,\n            exception text,\n            PRIMARY KEY  (id)\n        ) {$charset_collate};";
        \dbDelta($sql);
    }
    /**
     * Check if the current user is allowed to query the queue. This only enables
     * frontend assets and UI.
     */
    public function currentUserAllowedToQuery()
    {
        foreach ($this->capabilities as $cap) {
            if (\current_user_can($cap)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Add a capability which is allowed to query the queue.
     *
     * @param string $cap
     */
    public function addCapability($cap)
    {
        $this->capabilities[] = $cap;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAssets()
    {
        return $this->assets;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPluginCore()
    {
        return $this->pluginCore;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPersist()
    {
        return $this->persist;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getQuery()
    {
        return $this->query;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRestQuery()
    {
        return $this->restQuery;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExecutor()
    {
        return $this->executor;
    }
}
