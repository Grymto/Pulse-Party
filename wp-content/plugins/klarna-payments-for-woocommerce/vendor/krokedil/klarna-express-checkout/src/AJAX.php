<?php
namespace Krokedil\KlarnaExpressCheckout;

defined( 'ABSPATH' ) || exit;

/**
 * AJAX class for Klarna Express Checkout
 *
 * @package Krokedil\KlarnaExpressCheckout
 */
class AJAX {
	/**
	 * The callable to get the payload for the Klarna Express Checkout.
	 *
	 * @var callable
	 */
	public $get_payload;

	/**
	 * The callback for the finalization of the Klarna Express Checkout.
	 *
	 * @var callable
	 */
	public $finalize_callback;

	/**
	 * The client token parser.
	 *
	 * @var ClientTokenParser
	 */
	private $client_token_parser;

	/**
	 * AJAX constructor.
	 *
	 * @param ClientTokenParser $client_token_parser The client token parser.
	 */
	public function __construct( $client_token_parser ) {
		$this->client_token_parser = $client_token_parser;
		$this->add_ajax_events();
	}

	/**
	 * Setup hooks for the AJAX events.
	 *
	 * @return void
	 */
	public function add_ajax_events() {
		$ajax_events = array(
			'kec_get_payload',
			'kec_set_cart',
			'kec_auth_callback',
			'kec_finalize_callback',
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wc_ajax_' . $ajax_event, array( $this, $ajax_event ) );
		}
	}

	/**
	 * Set the callable to get the payload for the Klarna Express Checkout.
	 *
	 * @param callable $get_payload_method The callable.
	 */
	public function set_get_payload( $get_payload_method ) {
		$this->get_payload = $get_payload_method;
	}

	/**
	 * Set the callback for the finalization of the Klarna Express Checkout.
	 *
	 * @param callable $finalize_callback The callable.
	 */
	public function set_finalize_callback( $finalize_callback ) {
		$this->finalize_callback = $finalize_callback;
	}

	/**
	 * Get the payload for the Klarna Express Checkout.
	 *
	 * @return void
	 * @throws \Exception If the payload could not be retrieved.
	 */
	public function kec_get_payload() {
		// Verify nonce.
		check_ajax_referer( 'kec_get_payload', 'nonce' );

		try {
			$payload = call_user_func( $this->get_payload );

			if ( ! is_array( $payload ) ) {
				throw new \Exception( 'Could not get a Payload for the cart' );
			}

			wp_send_json_success( $payload );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Set the cart in WooCommerce to the product KEC was initiated from.
	 *
	 * @return void
	 * @codeCoverageIgnore - Cant test due to filter_input_array
	 */
	public function kec_set_cart() {
		// Verify nonce.
		check_ajax_referer( 'kec_set_cart', 'nonce' );

		$posted_data = filter_input_array( INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// Get the posted result.
		$product_id   = $posted_data['product_id'] ?? '';
		$variation_id = $posted_data['variation_id'] ?? null;

		if ( empty( $product_id ) ) {
			wp_send_json_error( 'No product ID was posted' );
		}

		// Clear the cart.
		WC()->cart->empty_cart();

		// Add the product to the cart.
		$result = WC()->cart->add_to_cart( $product_id, 1, $variation_id );

		if ( ! $result ) {
			wp_send_json_error( 'Could not add the product to the cart' );
		}

		wp_send_json_success();
	}

	/**
	 * Handle the auth callback.
	 *
	 * @return void
	 * @codeCoverageIgnore - Cant test due to filter_input_array
	 */
	public function kec_auth_callback() {
		// Verify nonce.
		check_ajax_referer( 'kec_auth_callback', 'nonce' );

		$posted_data = filter_input_array( INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// Get the posted result.
		$result = $posted_data['result'] ?? array();

		if ( empty( $result ) ) {
			wp_send_json_error( 'No result was posted' );
		}

		// Get the approved status, client token, and collected shipping address from the result.
		$approved       = $result['approved'] ?? false;
		$client_token   = $result['client_token'] ?? '';
		$klarna_address = $result['collected_shipping_address'] ?? array();

		// Decode the token and ensure it is valid.
		try {
			$token = $this->client_token_parser->parse( $client_token );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

		if ( ! $approved ) {
			wp_send_json_error( 'The payment was not approved by Klarna' );
		}

		$klarna_address = $result['collected_shipping_address'];

		$this->set_customer_address( $klarna_address );
		Session::set_client_token( $client_token );
		Session::set_klarna_address( $klarna_address );

		do_action( 'kec_auth_callback_processed', $result );

		// Send a success response with a redirect URL to the checkout.
		wp_send_json_success( wc_get_checkout_url() );
	}

	/**
	 * Handle the finalize callback.
	 *
	 * @return void
	 * @throws \Exception If the order could not be finalized.
	 * @codeCoverageIgnore - Cant test due to filter_input_array
	 */
	public function kec_finalize_callback() {
		// Verify nonce.
		check_ajax_referer( 'kec_finalize_callback', 'nonce' );

		$posted_data = filter_input_array( INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// Get the posted result.
		$result    = $posted_data['result'] ?? array();
		$order_id  = $posted_data['order_id'] ?? '';
		$order_key = $posted_data['order_key'] ?? '';

		if ( empty( $result ) ) {
			wp_send_json_error( 'No result was posted' );
		}

		// Get the approved status and auth token.
		$approved   = $result['approved'] ?? false;
		$auth_token = $result['authorization_token'] ?? '';

		if ( ! $approved ) {
			wp_send_json_error( 'The payment was not approved by Klarna' );
		}

		try {
			if ( ! is_callable( $this->finalize_callback ) ) {
				throw new \Exception( 'Could not finalize the order' );
			}

			$callback_response = call_user_func( $this->finalize_callback, $auth_token, $order_id, $order_key );

			if ( ! is_array( $callback_response ) ) {
				throw new \Exception( 'Could not finalize the order' );
			}

			wp_send_json_success( $callback_response );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Set the customer address.
	 *
	 * @param array $klarna_address The Klarna address.
	 * @codeCoverageIgnore - Only runs external code.
	 */
	private function set_customer_address( $klarna_address ) {
		// Set the billing and shipping address to the current customer for the checkout.
		WC()->customer->set_billing_address( $klarna_address['street_address'] ?? '' );
		WC()->customer->set_billing_address_2( $klarna_address['street_address2'] ?? '' );
		WC()->customer->set_billing_city( $klarna_address['city'] ?? '' );
		WC()->customer->set_billing_postcode( $klarna_address['postal_code'] ?? '' );
		WC()->customer->set_billing_country( $klarna_address['country'] ?? '' );
		WC()->customer->set_billing_first_name( $klarna_address['given_name'] ?? '' );
		WC()->customer->set_billing_last_name( $klarna_address['family_name'] ?? '' );
		WC()->customer->set_billing_company( $klarna_address['organization_name'] ?? '' );
		WC()->customer->set_billing_email( $klarna_address['email'] ?? '' );
		WC()->customer->set_billing_phone( $klarna_address['phone'] ?? '' );

		WC()->customer->set_shipping_address( $klarna_address['street_address'] ?? '' );
		WC()->customer->set_shipping_address_2( $klarna_address['street_address2'] ?? '' );
		WC()->customer->set_shipping_city( $klarna_address['city'] ?? '' );
		WC()->customer->set_shipping_postcode( $klarna_address['postal_code'] ?? '' );
		WC()->customer->set_shipping_country( $klarna_address['country'] ?? '' );
		WC()->customer->set_shipping_first_name( $klarna_address['given_name'] ?? '' );
		WC()->customer->set_shipping_last_name( $klarna_address['family_name'] ?? '' );
		WC()->customer->set_shipping_company( $klarna_address['organization_name'] ?? '' );
		WC()->customer->set_shipping_phone( $klarna_address['phone'] ?? '' );
	}
}
