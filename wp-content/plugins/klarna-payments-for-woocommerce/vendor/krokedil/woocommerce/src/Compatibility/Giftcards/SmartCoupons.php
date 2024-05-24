<?php
namespace Krokedil\WooCommerce\Compatibility\Giftcards;

use Krokedil\WooCommerce\Compatibility\Abstracts\AbstractGiftCardCompatibility;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Class to handle compatibility with Smart Coupons.
 */
class SmartCoupons extends AbstractGiftCardCompatibility {
	/**
	 * Initialize the class.
	 *
	 * @param KrokedilWooCommerce $package The main package class.
	 */
	public function __construct( $package ) {
		parent::__construct( $package );

		$this->name = 'Discount';
		$this->type = 'discount';
	}

	/**
	 * Get the giftcards applied to the current cart.
	 *
	 * @return OrderLineData[]
	 */
	public function get_cart_giftcards() {
		$coupons = array();

		foreach ( WC()->cart->get_coupons() as $code => $cart_coupon ) {
			if ( 'smart_coupon' !== $cart_coupon->get_discount_type() && 'store_credit' !== $cart_coupon->get_discount_type() ) {
				continue;
			}

			$apply_before_tax = 'yes' === get_option( 'woocommerce_smart_coupon_apply_before_tax', 'no' );
			if ( wc_tax_enabled() && $apply_before_tax ) {
				// The discount is applied directly to the cart item. Send gift card amount as zero for bookkeeping.
				$amount = 0;
			} else {
				$amount = -1 * ( WC()->cart->get_coupon_discount_amount( $code ) + WC()->cart->get_coupon_discount_tax_amount( $code ) );
			}
			$sku = substr( strval( $code ), 0, 64 );

			$coupons[] = $this->create_gift_card( "$this->name $code", $sku, $this->type, $amount );
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
		 * @var \WC_Order_Item_Coupon $order_coupon
		 */
		foreach ( $order->get_items( 'coupon' ) as $order_coupon ) {
			$discount_type = $order_coupon->meta_exists( 'coupon_data' ) ? $order_coupon->get_meta( 'coupon_data' )['discount_type'] : ( new \WC_Coupon( $order_coupon->get_name() ) )->get_discount_type();

			if ( 'smart_coupon' !== $discount_type && 'store_credit' !== $discount_type ) {
				continue;
			}

			$apply_before_tax = 'yes' === get_option( 'woocommerce_smart_coupon_apply_before_tax', 'no' );
			if ( wc_tax_enabled() && $apply_before_tax ) {
				// The discount is applied directly to the order item. Send gift card amount as zero for bookkeeping.
				$amount = 0;
			} else {
				$amount = -1 * ( $order_coupon->get_discount() + $order_coupon->get_discount_tax() );
			}

			$code = $order_coupon->get_code();
			$sku  = substr( strval( $code ), 0, 64 );

			$coupons[] = $this->create_gift_card( "$this->name $code", $sku, $this->type, $amount );
		}

		return $coupons;
	}
}
