<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with YITH Gift Cards.
 */
class YITHGiftCards extends AbstractGiftCardCompatibility {
	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		// If no gift cards are applied to the cart, return an empty array.
		if ( ! isset( WC()->cart->applied_gift_cards ) ) {
			return $coupons;
		}

		foreach ( WC()->cart->applied_gift_cards as $code ) {
			$amount = ( isset( WC()->cart->applied_gift_cards_amounts[ $code ] ) ?
				WC()->cart->applied_gift_cards_amounts[ $code ] : 0 ) * -1;

			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}

	/**
	 * Get the giftcards applied to an order.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineData[]
	 */
	public function get_order_giftcards( $order ) {
		$coupons   = array();
		$yith_meta = $order->get_meta( '_ywgc_applied_gift_cards', true );

		// If no YITH meta data can be found, return an empty array.
		if ( empty( $yith_meta ) ) {
			return $coupons;
		}

		foreach ( $yith_meta as $code => $amount ) {
			$amount    = $amount * -1;
			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}
}
