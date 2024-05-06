<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wordpress.org/plugins/pofily-woo-product-filters/
 * @since      1.0.0
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 * @author     Villatheme <support@villatheme.com>
 */
class VIWCPF_Woo_Product_Filters_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pofily-woo-product-filters',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);


		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			include_once VIWCPF_FREE_DIR_PATH . 'includes/support.php';
		}

		$environment = new \VillaTheme_Require_Environment( [
				'plugin_name'     => 'Pofily - WooCommerce Product Filters',
				'php_version'     => '7.0',
				'wp_version'      => '5.0',
				'wc_version'      => '7.0',
				'require_plugins' => [
					[
						'slug' => 'woocommerce',
						'name' => 'WooCommerce',
					],
				]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'    => 'https://wordpress.org/support/plugin/pofily-woo-product-filters/',
					'docs'       => 'https://docs.villatheme.com/?item=pofily',
					'review'     => 'https://wordpress.org/plugins/pofily-woo-product-filters/#reviews',
					'pro_url'    => 'https://1.envato.market/kj9ZJn',
					'css'        => VIWCPF_FREE_CSS,
					'image'      => '',
					'slug'       => 'pofily-woo-product-filters',
					'menu_slug'  => 'viwcpf-woocommerce-product-filters',
					'version'    => VIWCPF_FREE_VERSION,
					'survey_url' => 'https://script.google.com/macros/s/AKfycbxYuj8NVZ5KyD5_eYEBWEaYVyxlTce4xv7cpXWPT0ZDqk0m6l8zoFLiFa49VuyHX83m2Q/exec'
				)
			);
		}

	}


}
