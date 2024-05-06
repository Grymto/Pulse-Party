<?php
/**
 * Plugin Name: Pofily - WooCommerce Product Filters
 * Plugin URI: https://villatheme.com/extensions/pofily-woocommerce-product-filters/
 * Description: Pofily - WooCommerce Product Filters is an expert at filtering products. With just a few clicks
 * of the filter, customers can quickly link to desired products.
 * Version: 1.1.1
 * Author: VillaTheme
 * Author URI: https://villatheme.com/
 * Text Domain: pofily-woo-product-filters
 * Domain Path: /languages
 * Copyright 2021-2024 VillaTheme.com. All rights reserved.
 * Tested up to: 6.5.2
 * WC requires at least: 7.0.0
 * WC tested up to: 8.7.0
 * Requires PHP: 7.0
 * Requires Plugins: woocommerce
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Currently plugin version.
 */
define( 'VIWCPF_FREE_VERSION', '1.1.1' );
define( 'VIWCPF_FREE_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIWCPF_FREE_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'VIWCPF_FREE_ADMIN_IMG_URL', VIWCPF_FREE_DIR_URL . 'admin/images/' );
define( 'VIWCPF_FREE_CSS', VIWCPF_FREE_DIR_URL . 'assets/css/' );
define( 'VIWCPF_FREE_BASE_NAME', plugin_basename( __FILE__ ) );
if ( is_plugin_active( 'pofily-woocommerce-product-filters/pofily-woocommerce-product-filters.php' ) ) {
	return;
}
require_once VIWCPF_FREE_DIR_PATH . 'includes/support.php';


//compatible with 'High-Performance order storage (COT)'
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-product-filters-activator.php
 */
function viwcpf_activate_woo_product_filters() {

	require_once VIWCPF_FREE_DIR_PATH . 'includes/class-woo-product-filters-activator.php';
	VIWCPF_Woo_Product_Filters_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-product-filters-deactivator.php
 */
function viwcpf_deactivate_woo_product_filters() {
	require_once VIWCPF_FREE_DIR_PATH . 'includes/class-woo-product-filters-deactivator.php';
	VIWCPF_Woo_Product_Filters_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'viwcpf_activate_woo_product_filters' );
register_deactivation_hook( __FILE__, 'viwcpf_deactivate_woo_product_filters' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */


require VIWCPF_FREE_DIR_PATH . 'includes/class-woo-product-filters.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function viwcpf_run_woo_product_filters() {

	$plugin = new VIWCPF_Woo_Product_Filters();
	$plugin->run();


}

viwcpf_run_woo_product_filters();
