<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/pofily-woo-product-filters/
 * @since      1.0.0
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 * @author     Villatheme <support@villatheme.com>
 */
class VIWCPF_Woo_Product_Filters_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.4", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 4.4 or higher." );
		}


		if ( ! get_option( 'viwcpf_setting_params' ) ) {
			$default_option = new VIWCPF_Woo_Product_Filters_Data();
			$args_option = $default_option->get_default();
			add_option( 'viwcpf_setting_params', $args_option );

		}

	}

	public function after_activated( $plugin ) {}

}
