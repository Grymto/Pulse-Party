<?php
namespace Krokedil\KlarnaOnsiteMessaging;

use Krokedil\KlarnaOnsiteMessaging\Pages\Product;
use Krokedil\KlarnaOnsiteMessaging\Pages\Cart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KOSM_VERSION', '1.0.1' );

/**
 * The orchestrator class.
 */
class KlarnaOnsiteMessaging {
	/**
	 * The internal settings state.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Display placement on product page.
	 *
	 * @var Product
	 */
	private $product;

	/**
	 * Display placement on cart page.
	 *
	 * @var Cart
	 */
	private $cart;

	/**
	 * Display placement with shortcode.
	 *
	 * @var Shortcode
	 */
	private $shortcode;


	/**
	 * Class constructor.
	 *
	 * @param array $settings Any existing KOSM settings.
	 */
	public function __construct( $settings ) {
		$this->settings  = new Settings( $settings );
		$this->product   = new Product( $this->settings );
		$this->cart      = new Cart( $this->settings );
		$this->shortcode = new Shortcode();

		add_action( 'widgets_init', array( $this, 'init_widget' ) );

		if ( class_exists( 'WooCommerce' ) ) {
			// Lower hook priority to ensure the dequeue of the KOSM plugin scripts happens AFTER they have been enqueued.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
			add_filter( 'script_loader_tag', array( $this, 'add_data_attributes' ), 10, 2 );
		}

		add_action( 'admin_notices', array( $this, 'kosm_installed_admin_notice' ) );

		// Unhook the KOSM plugin's action hooks.
		if ( class_exists( 'Klarna_OnSite_Messaging_For_WooCommerce' ) ) {
			$hooks    = wc_get_var( $GLOBALS['wp_filter']['wp_head'] );
			$priority = 10;
			foreach ( $hooks->callbacks[ $priority ] as $callback ) {
				$function = $callback['function'];
				if ( is_array( $function ) ) {
					$class  = reset( $function );
					$method = end( $function );
					if ( is_object( $class ) && strpos( get_class( $class ), 'Klarna_OnSite_Messaging' ) !== false ) {
						remove_action( 'wp_head', array( $class, $method ), $priority );
					}
				}
			}
		}
	}

	/**
	 * Register the widget.
	 *
	 * @return void
	 */
	public function init_widget() {
		register_widget( new Widget() );
	}

	/**
	 * Check if the Klarna On-Site Messaging plugin is active, and notify the admin about the new changes.
	 *
	 * @return void
	 */
	public function kosm_installed_admin_notice() {
		$plugin = 'klarna-onsite-messaging-for-woocommerce/klarna-onsite-messaging-for-woocommerce.php';
		if ( is_plugin_active( $plugin ) ) {
			$message = __( 'The "Klarna On-Site Messaging for WooCommerce" plugin is now integrated into Klarna Payments. Please disable the plugin.', 'klarna-onsite-messaging-for-woocommerce' );
			printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );

		}
	}

	/**
	 * Add data- attributes to <script> tag.
	 *
	 * @param string $tag The <script> tag for the enqueued script.
	 * @param string $handle The script’s registered handle.
	 * @return string
	 */
	public function add_data_attributes( $tag, $handle ) {
		if ( 'klarna_onsite_messaging_sdk' !== $handle ) {
			return $tag;
		}

		$environment    = 'yes' === $this->settings->get( 'onsite_messaging_test_mode' ) ? 'playground' : 'production';
		$data_client_id = apply_filters( 'kosm_data_client_id', $this->settings->get( 'data_client_id' ) );
		$tag            = str_replace( ' src', ' async src', $tag );
		$tag            = str_replace( '></script>', " data-environment={$environment} data-client-id='{$data_client_id}'></script>", $tag );

		return $tag;
	}

	/**
	 * Enqueue KOSM and library scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		global $post;

		$has_shortcode = ( ! empty( $post ) && has_shortcode( $post->post_content, 'onsite_messaging' ) );
		if ( ! ( $has_shortcode || is_product() || is_cart() ) ) {
			return;
		}

		$region        = 'eu-library';
		$base_location = wc_get_base_location();
		if ( is_array( $base_location ) && isset( $base_location['country'] ) ) {
			if ( in_array( $base_location['country'], array( 'US', 'CA' ) ) ) {
				$region = 'na-library';
			} elseif ( in_array( $base_location['country'], array( 'AU', 'NZ' ) ) ) {
				$region = 'oc-library';
			}
		}
		$region = apply_filters( 'kosm_region_library', $region );

		if ( ! empty( $this->settings->get( 'data_client_id' ) ) ) {
			// phpcs:ignore -- The version is managed by Klarna.
			wp_register_script( 'klarna_onsite_messaging_sdk', 'https://js.klarna.com/web-sdk/v1/klarna.js', array(), false );
		}

		// Deregister the script that is registered by the KOSM plugin.
		wp_deregister_script( 'klarna_onsite_messaging' );
		wp_deregister_script( 'klarna-onsite-messaging' );
		wp_deregister_script( 'onsite_messaging_script' );

		$script_path = plugin_dir_url( __FILE__ ) . 'assets/js/klarna-onsite-messaging.js';
		wp_register_script( 'klarna_onsite_messaging', $script_path, array( 'jquery', 'klarna_onsite_messaging_sdk' ), KOSM_VERSION, true );

		$localize = array(
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'get_cart_total_url' => \WC_AJAX::get_endpoint( 'kosm_get_cart_total' ),
		);

		if ( isset( $_GET['osmDebug'] ) ) {
			$localize['debug_info'] = array(
				'product'       => is_product(),
				'cart'          => is_cart(),
				'shortcode'     => $has_shortcode,
				'data_client'   => ! ( empty( $this->settings->get( 'data_client_id' ) ) ),
				'locale'        => Utility::get_locale_from_currency(),
				'currency'      => get_woocommerce_currency(),
				'library'       => ( wp_scripts() )->registered['klarna_onsite_messaging_sdk']->src ?? $region,
				'base_location' => $base_location['country'],
			);

			$product = Utility::get_product();
			if ( ! empty( $product ) ) {
				$type                                   = $product->get_type();
				$localize['debug_info']['product_type'] = $type;
				if ( method_exists( $product, 'get_available_variations' ) ) {
					foreach ( $product->get_available_variations() as $variation ) {
						$attribute                                   = wc_get_var( $variation['attributes'] );
						$localize['debug_info']['default_variation'] = reset( $attribute );
						break;
					}
				}
			}
		}

		wp_localize_script(
			'klarna_onsite_messaging',
			'klarna_onsite_messaging_params',
			$localize
		);

		wp_enqueue_script( 'klarna_onsite_messaging' );
	}

	/**
	 * Get the settings object.
	 *
	 * @return Settings
	 */
	public function settings() {
		return $this->settings;
	}

	/**
	 * Get the product object.
	 *
	 * @return Product
	 */
	public function product() {
		return $this->product;
	}

	/**
	 * Get the cart object.
	 *
	 * @return Cart
	 */
	public function cart() {
		return $this->cart;
	}

	/**
	 * Get the shortcode object.
	 *
	 * @return Shortcode
	 */
	public function shortcode() {
		return $this->shortcode;
	}
}
