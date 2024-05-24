<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with WooCommerce Gift Cards.
 *
 * @suppress PHP0417
 * @suppress PHP0413
 */
class WCGiftCards extends AbstractGiftCardCompatibility {
	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		foreach ( WC_GC()->cart->get_applied_gift_cards()['giftcards'] as $wc_giftcard ) {
			$code   = $wc_giftcard['giftcard']->get_data()['code'];
			$amount = $wc_giftcard['amount'] * -1;

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
		$coupons = array();

		/**
		 * Loop through the gift cards and add them to the array.
		 *
		 * @var \WC_GC_Order_Item_Gift_Card $wc_giftcard
		 */
		foreach ( $order->get_items( 'gift_card' ) as $wc_giftcard ) {
			$amount    = $wc_giftcard->get_amount() * -1;
			$code      = $wc_giftcard->get_code();
			$coupons[] = $this->create_gift_card( "$this->name $code", $this->sku, $this->type, $amount );
		}

		return $coupons;
	}
}
