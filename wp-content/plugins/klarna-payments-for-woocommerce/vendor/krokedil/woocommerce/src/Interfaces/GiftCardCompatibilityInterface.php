<?php
namespace Krokedil\WooCommerce\Interfaces;

use Krokedil\WooCommerce\OrderLineData;

/**
 * Interface GiftCardCompatibilityInterface
 */
interface GiftCardCompatibilityInterface {
	/**
	 * Get the giftcards applied to an order.
	 *
	 * @param \WC_Order $order The WooCommerce order.
	 *
	 * @return OrderLineData[]
	 */
	public function get_order_giftcards( $order );

	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards();
}
