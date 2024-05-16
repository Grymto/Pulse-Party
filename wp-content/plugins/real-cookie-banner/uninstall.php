<?php
/**
 * This file is automatically requested when the uninstalls the plugin.
 *
 * @see https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 */

use DevOwl\RealCookieBanner\Activator;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit(); // Remove if you want to do some tests locally!
}

/**
 * Uninstall our plugin! For the first, it is completely OK to just remove filesystem
 * files. Database and options will be added soon...!
 */
function rcb_uninstall() {
    // require_once '../../../wp-load.php'; // Just for testing purposes, remove me!

    if (!defined('RCB_FILE')) {
        // ported from index.php
        define('RCB_FILE', __DIR__ . '/index.php');
        define('RCB_PATH', dirname(RCB_FILE));
        define('RCB_ROOT_SLUG', 'devowl-wp');
        define('RCB_SLUG', basename(RCB_PATH));
        define('RCB_INC', RCB_PATH . '/inc/');
        define('RCB_MIN_PHP', '7.4.0');
        define('RCB_MIN_WP', '5.8.0');
        define('RCB_NS', 'DevOwl\\RealCookieBanner');
        define('RCB_DB_PREFIX', 'rcb');
        define('RCB_OPT_PREFIX', 'rcb');
        define('RCB_SLUG_CAMELCASE', lcfirst(str_replace('-', '', ucwords(RCB_SLUG, '-'))));
    }

    // Require autoloader so we can use functions and classes
    require_once RCB_INC . 'base/others/start.php';

    Activator::uninstall();
}

rcb_uninstall();
