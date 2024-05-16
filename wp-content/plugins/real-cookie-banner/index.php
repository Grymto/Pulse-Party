<?php
/**
 * Main file for WordPress.
 *
 * @wordpress-plugin
 * Plugin Name:      Real Cookie Banner (Free)
 * Plugin URI:      https://devowl.io/wordpress-real-cookie-banner/
 * Description:     Obtain GDPR (DSGVO) and ePrivacy (EU cookie law) compliant opt-in consent. Find cookies and fill all legal information in your cookie banner. More than just a cookie notice!
 * Author:          devowl.io
 * Author URI:      https://devowl.io
 * Version:                                                                          4.7.8
 * Text Domain:     real-cookie-banner
 * Domain Path:     /languages
 */

defined('ABSPATH') or die('No script kiddies please!'); // Avoid direct file request

/**
 * Plugin constants. This file is procedural coding style for initialization of
 * the plugin core and definition of plugin configuration.
 */
if (defined('RCB_PATH')) {
    require_once __DIR__ . '/inc/base/others/fallback-already.php';
    return;
}
define('RCB_FILE', __FILE__);
define('RCB_PATH', dirname(RCB_FILE));
define('RCB_ROOT_SLUG', 'devowl-wp');
define('RCB_SLUG', basename(RCB_PATH));
define('RCB_INC', RCB_PATH . '/inc/');
define('RCB_MIN_PHP', '7.4.0'); // Minimum of PHP 5.3 required for autoloading and namespacing
define('RCB_MIN_WP', '5.8.0'); // Minimum of WordPress 5.0 required
define('RCB_NS', 'DevOwl\\RealCookieBanner');
define('RCB_DB_PREFIX', 'rcb'); // The table name prefix wp_{prefix}
define('RCB_OPT_PREFIX', 'rcb'); // The option name prefix in wp_options
define('RCB_SLUG_CAMELCASE', lcfirst(str_replace('-', '', ucwords(RCB_SLUG, '-'))));
//define('RCB_TD', ''); This constant is defined in the core class. Use this constant in all your __() methods
//define('RCB_VERSION', ''); This constant is defined in the core class
//define('RCB_DEBUG', true); This constant should be defined in wp-config.php to enable the Base#debug() method

define('RCB_SLUG_PRO', 'real-cookie-banner-pro');
define('RCB_SLUG_LITE', 'real-cookie-banner');
//  define('RCB_PRO_VERSION', 'https://devowl.io/go/real-cookie-banner?source=rcb-lite'); This constant is defined in the core class

// Check PHP Version and print notice if minimum not reached, otherwise start the plugin core
require_once RCB_INC .
    'base/others/' .
    (version_compare(phpversion(), RCB_MIN_PHP, '>=') ? 'start.php' : 'fallback-php-version.php');
