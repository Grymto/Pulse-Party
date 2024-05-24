<?php
namespace Krokedil\KlarnaExpressCheckout;

defined( 'ABSPATH' ) || exit;

/**
 * Assets class for Klarna Express Checkout
 *
 * @package Krokedil\KlarnaExpressCheckout
 */
class Assets {
	/**
	 * The path to the assets directory.
	 *
	 * @var string
	 */
	private $assets_path;

	/**
	 * Settings class instance for the package.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * The Locale to use for the KEC integration.
	 *
	 * @var string|bool
	 */
	private $locale;

	/**
	 * Assets constructor.
	 *
	 * @param Settings    $settings The credentials secret from Klarna for KEC.
	 * @param string|bool $locale   The locale to use for the KEC integration. Defaults to using the browser locale. Optional.
	 */
	public function __construct( $settings, $locale = false ) {
		$this->assets_path = plugin_dir_url( __FILE__ ) . '../assets/';
		$this->settings    = $settings;
		$this->locale      = $locale;

		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 15 );
	}

	/**
	 * Register scripts.
	 */
	public function register_assets() {
		// Register the style for the cart page.
		wp_register_style( 'kec-cart', $this->assets_path . 'css/kec-cart.css', array(), KlarnaExpressCheckout::VERSION );

		// Register the Klarna Payments library script.
		wp_register_script( 'klarnapayments', 'https://x.klarnacdn.net/kp/lib/v1/api.js', array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

		wp_register_script( 'kec-cart', $this->assets_path . 'js/kec-cart.js', array(), KlarnaExpressCheckout::VERSION, true );
		wp_register_script( 'kec-checkout', $this->assets_path . 'js/kec-checkout.js', array(), KlarnaExpressCheckout::VERSION, true );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue_assets() {
		// If KEC is not enabled, return.
		if ( ! $this->settings->is_enabled() ) {
			return;
		}

		if ( is_cart() || is_product() ) {
			$this->enqueue_cart_assets();
		}

		if ( is_checkout() ) {
			$this->enqueue_checkout_assets();
		}
	}

	/**
	 * Enqueue cart scripts.
	 */
	private function enqueue_cart_assets() {
		$is_product_page = is_product();
		$product         = $is_product_page ? wc_get_product( get_the_ID() ) : null;

		$params = array(
			'ajax'            => array(
				'get_payload'   => array(
					'url'    => \WC_AJAX::get_endpoint( 'kec_get_payload' ),
					'nonce'  => wp_create_nonce( 'kec_get_payload' ),
					'method' => 'POST',
				),
				'auth_callback' => array(
					'url'    => \WC_AJAX::get_endpoint( 'kec_auth_callback' ),
					'nonce'  => wp_create_nonce( 'kec_auth_callback' ),
					'method' => 'POST',
				),
				'set_cart'      => array(
					'url'    => \WC_AJAX::get_endpoint( 'kec_set_cart' ),
					'nonce'  => wp_create_nonce( 'kec_set_cart' ),
					'method' => 'POST',
				),
			),
			'is_product_page' => $is_product_page,
			'product'         => $is_product_page ? array(
				'id'   => $product->get_id(),
				'type' => $product->get_type(),
			) : null,
			'client_id'       => $this->settings->get_credentials_secret(),
			'theme'           => $this->settings->get_theme(),
			'shape'           => $this->settings->get_shape(),
			'locale'          => $this->locale,
		);

		wp_localize_script( 'kec-cart', 'kec_cart_params', $params );

		// Enqueue the style for the cart page.
		wp_enqueue_style( 'kec-cart' );

		// Load the Klarna Payments library script before our script.
		wp_enqueue_script( 'klarnapayments' );
		wp_enqueue_script( 'kec-cart' );
	}

	/**
	 * Enqueue checkout scripts.
	 */
	private function enqueue_checkout_assets() {
		$client_token = Session::get_client_token();

		if ( empty( $client_token ) ) {
			return;
		}

		$params = array(
			'ajax'         => array(
				'get_payload'       => array(
					'url'    => \WC_AJAX::get_endpoint( 'kec_get_payload' ),
					'nonce'  => wp_create_nonce( 'kec_get_payload' ),
					'method' => 'POST',
				),
				'checkout'          => array(
					'url'    => \WC_AJAX::get_endpoint( 'checkout' ),
					'method' => 'POST',
				),
				'finalize_callback' => array(
					'url'    => \WC_AJAX::get_endpoint( 'kec_finalize_callback' ),
					'nonce'  => wp_create_nonce( 'kec_finalize_callback' ),
					'method' => 'POST',
				),
			),
			'client_token' => $client_token,
		);

		wp_localize_script( 'kec-checkout', 'kec_checkout_params', $params );

		// Load the Klarna Payments library script before our script.
		wp_enqueue_script( 'klarnapayments' );
		wp_enqueue_script( 'kec-checkout' );
	}
}
