<?php
namespace Krokedil\KlarnaExpressCheckout;

defined( 'ABSPATH' ) || exit;

/**
 * Session class for Klarna Express Checkout
 *
 * @package Krokedil\KlarnaExpressCheckout
 */
class Session {
	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'woocommerce_checkout_update_order_review', __CLASS__ . '::on_update_order_review' );
		add_action( 'woocommerce_thankyou', __CLASS__ . '::unset_client_token', 1 );
		add_action( 'woocommerce_thankyou', __CLASS__ . '::unset_klarna_address', 1 );
	}

	/**
	 * On update order review.
	 *
	 * @param string $posted_data The updated checkout data.
	 *
	 * @return void
	 */
	public static function on_update_order_review( $posted_data ) {
		// Try to get the client token and klarna address from the session.
		$client_token   = self::get_client_token();
		$klarna_address = self::get_klarna_address();

		// If we don't have a client token or klarna address, return.
		if ( ! $client_token || ! $klarna_address ) {
			return;
		}

		// Parse the posted data from the checkout.
		parse_str( $posted_data, $parsed_data );

		// Only if the payment method is Klarna Payments.
		$payment_method = isset( $parsed_data['payment_method'] ) ? $parsed_data['payment_method'] : '';
		if ( 'klarna_payments' !== $payment_method ) {
			return;
		}

		// Check if ship to different address is checked.
		$ship_to_different_address = isset( $parsed_data['ship_to_different_address'] ) ? $parsed_data['ship_to_different_address'] : false;

		// Verify that the address fields have not changed.
		$address_fields_map = array(
			'given_name'      => 'first_name',
			'family_name'     => 'last_name',
			'email'           => 'email',
			'phone'           => 'phone',
			'street_address'  => 'address_1',
			'street_address2' => 'address_2',
			'postal_code'     => 'postcode',
			'city'            => 'city',
			'region'          => 'state',
			'country'         => 'country',
		);

		foreach ( $address_fields_map as $klarna_field => $wc_field ) {
			if ( ! self::verify_address_field( $wc_field, $klarna_field, $parsed_data, $klarna_address, $ship_to_different_address ) ) {
				// Clear the token and klarna address from the session.
				self::unset_client_token();
				self::unset_klarna_address();

				// Ensure WooCommerce reloads the checkout page.
				WC()->session->set( 'reload_checkout', true );
			}
		}
	}

	/**
	 * Verify that the address fields have not changed.
	 *
	 * @param string $wc_field The WooCommerce field.
	 * @param string $klarna_field The Klarna field.
	 * @param array  $posted_data The posted data.
	 * @param array  $klarna_address The Klarna address.
	 * @param bool   $ship_to_different_address Whether or not the customer has checked the ship to different address checkbox.
	 *
	 * @return bool
	 */
	private static function verify_address_field( $wc_field, $klarna_field, $posted_data, $klarna_address, $ship_to_different_address ) {
		$check_shipping_address = get_option( 'woocommerce_ship_to_destination' ) === 'billing_only' ? false : true;

		$billing_value  = isset( $posted_data[ 'billing_' . $wc_field ] ) ? $posted_data[ 'billing_' . $wc_field ] : '';
		$shipping_value = isset( $posted_data[ 'shipping_' . $wc_field ] ) ? $posted_data[ 'shipping_' . $wc_field ] : '';
		$klarna_value   = isset( $klarna_address[ $klarna_field ] ) ? $klarna_address[ $klarna_field ] : '';

		// Decode any HTML entities in the values.
		$billing_value  = html_entity_decode( $billing_value );
		$shipping_value = html_entity_decode( $shipping_value );
		$klarna_value   = html_entity_decode( $klarna_value );

		if ( $check_shipping_address && $shipping_value !== $klarna_value ) {
			// Ignore the check if the shipping field is email or phone and the value is empty.
			if ( in_array( $wc_field, array( 'email', 'phone' ), true ) && empty( $shipping_value ) ) {
				return true;
			}

			// Ensure we set the value to the customer.
			self::set_field( 'shipping_' . $wc_field, $shipping_value );
			return false;
		}

		// Validate the billing field if the ship to different address checkbox is checked.
		if ( ! $ship_to_different_address && $billing_value !== $klarna_value ) {
			// Ensure we set the value to the customer.
			self::set_field( 'billing_' . $wc_field, $billing_value );
			return false;
		}

		return true;
	}

	/**
	 * Maybe set the field to the WooCommerce checkout when we need to reload the checkout.
	 * This is needed since WooCommerce will not set all fields when triggering the update_order_review event.
	 *
	 * @param string $field The field to set.
	 * @param string $value The value to set.
	 *
	 * @return void
	 */
	private static function set_field( $field, $value ) {
		WC()->customer->set_props(
			array(
				$field => $value,
			)
		);
	}

	/**
	 * Set the client token to the WooCommerce session.
	 *
	 * @param string $token The cart token.
	 *
	 * @return bool
	 */
	public static function set_client_token( $token ) {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		// Set the client token in a session using the cart_hash as a key.
		WC()->session->set( 'kec_client_token', $token );
		return true;
	}

	/**
	 * Set the Klarna address to the WooCommerce sessions.
	 *
	 * @param array $address The Klarna address.
	 *
	 * @return bool
	 */
	public static function set_klarna_address( $address ) {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		// Set the Klarna address in a session using the cart_hash as a key.
		WC()->session->set( 'kec_klarna_address', wp_json_encode( $address ) );

		return true;
	}

	/**
	 * Get the client token from the WooCommerce session.
	 *
	 * @return string|bool
	 */
	public static function get_client_token() {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		// Get the client token from the session using the cart_hash as a key.
		return WC()->session->get( 'kec_client_token', false );
	}

	/**
	 * Get the Klarna address from the WooCommerce session.
	 *
	 * @return array|bool
	 */
	public static function get_klarna_address() {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		// Get the Klarna address from the session using the cart_hash as a key.
		$klarna_address = WC()->session->get( 'kec_klarna_address', false );

		if ( ! $klarna_address ) {
			return false;
		}

		return json_decode( $klarna_address, true );
	}

	/**
	 * Unset the client token from the WooCommerce session.
	 *
	 * @return bool
	 */
	public static function unset_client_token() {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		WC()->session->__unset( 'kec_client_token' );
		return true;
	}

	/**
	 * Unset the Klarna address from the WooCommerce session.
	 *
	 * @return bool
	 */
	public static function unset_klarna_address() {
		// Ensure session is initialized.
		if ( ! WC()->session ) {
			return false;
		}

		WC()->session->__unset( 'kec_klarna_address' );
		return true;
	}
}
