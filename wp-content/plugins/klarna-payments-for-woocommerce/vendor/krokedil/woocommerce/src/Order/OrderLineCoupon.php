<?php
/**
 * Order line coupon.
 *
 * @package Krokedil/WooCommerce/Classes/Order
 */

namespace Krokedil\WooCommerce\Order;

use Krokedil\WooCommerce\OrderLineData;

defined( 'ABSPATH' ) || exit;

/**
 * Order line coupon class.
 */
class OrderLineCoupon extends OrderLineData {

	/**
	 * WooCommerce order item coupon.
	 *
	 * @var \WC_Order_Item_Coupon
	 */
	public $coupon;

	/**
	 * Coupon amount.
	 *
	 * @var float|int
	 */
	public $discount_amount;

	/**
	 * Coupon amount.
	 *
	 * @var float|int
	 */
	public $discount_tax_amount;

	/**
	 * Sets the data for the order line coupon.
	 *
	 * @param \WC_Order_Item_Coupon $coupon The WooCommerce coupon.
	 *
	 * @return void
	 */
	public function set_coupon_data( $coupon ) {
		$this->coupon = $coupon;

		$this->discount_amount     = $this->format_price( $coupon->get_discount() );
		$this->discount_tax_amount = $this->format_price( $coupon->get_discount_tax() );

		$this->set_name();
		$this->set_sku();
		$this->set_quantity();
		$this->set_unit_price();
		$this->set_subtotal_unit_price();
		$this->set_tax_rate();
		$this->set_total_amount();
		$this->set_subtotal_amount();
		$this->set_total_discount_amount();
		$this->set_total_discount_tax_amount();
		$this->set_total_tax_amount();
		$this->set_subtotal_tax_amount();
		$this->set_type();
		$this->set_product_url();
		$this->set_image_url();
		$this->set_compatibility();
	}

	/**
	 * Set coupon data from Smart coupons.
	 *
	 * @param \WC_Order_Item_Coupon $coupon The WooCommerce coupon.
	 *
	 * @return void
	 */
	public function set_smart_coupon_data( $coupon ) {
		$code              = $coupon->get_code();
		$coupon_amount     = $coupon->get_discount() * -1;
		$coupon_tax_amount = 0;
		$coupon_name       = 'Discount';

		$this->name                = "$coupon_name $code";
		$this->sku                 = substr( strval( $code ), 0, 64 );
		$this->quantity            = 1;
		$this->unit_price          = $this->format_price( $coupon_amount );
		$this->subtotal_unit_price = $this->format_price( $coupon_amount );
		$this->total_amount        = $this->format_price( $coupon_amount );
		$this->total_tax_amount    = $this->format_price( $coupon_tax_amount );
		$this->tax_rate            = 0;
		$this->type                = 'discount';
	}

	/**
	 * Set the data from WC_Gift_Card plugin.
	 *
	 * @param \WC_GC_Order_Item_Gift_Card $wc_gift_card WC Giftcard.
	 *
	 * @return void
	 */
	public function set_wc_gc_data( $wc_gift_card ) {
		$coupon_amount     = $wc_gift_card->get_amount() * -1;
		$code              = $wc_gift_card->get_code();
		$coupon_tax_amount = 0;
		$coupon_name       = 'Gift card';

		$this->name                = "$coupon_name $code";
		$this->sku                 = 'gift_card';
		$this->quantity            = 1;
		$this->unit_price          = $this->format_price( $coupon_amount );
		$this->subtotal_unit_price = $this->format_price( $coupon_amount );
		$this->total_amount        = $this->format_price( $coupon_amount );
		$this->total_tax_amount    = $this->format_price( $coupon_tax_amount );
		$this->tax_rate            = 0;
		$this->type                = 'gift_card';
	}

	/**
	 * Set the data from the YITH WooCommerce Gift Cards plugin.
	 *
	 * @param string           $code YITH Giftcard code.
	 * @param string|int|float $amount YITH Giftcard amount.
	 *
	 * @return void
	 */
	public function set_yith_wc_gc_data( $code, $amount ) {
		$coupon_amount     = $amount * -1;
		$coupon_tax_amount = 0;
		$coupon_name       = 'Gift card';

		$this->name                = "$coupon_name $code";
		$this->sku                 = 'gift_card';
		$this->quantity            = 1;
		$this->unit_price          = $this->format_price( $coupon_amount );
		$this->subtotal_unit_price = $this->format_price( $coupon_amount );
		$this->total_amount        = $this->format_price( $coupon_amount );
		$this->total_tax_amount    = $this->format_price( $coupon_tax_amount );
		$this->tax_rate            = 0;
		$this->type                = 'gift_card';
	}

	/**
	 * Set the data from the PW Giftcard plugin.
	 *
	 * @param string $code PW Giftcard code.
	 * @param string $amount PW Giftcard amount.
	 *
	 * @return void
	 */
	public function set_pw_giftcards_data( $code, $amount ) {
		$coupon_amount     = $amount * -1;
		$coupon_tax_amount = 0;
		$coupon_name       = 'Gift card';

		$this->name                = "$coupon_name $code";
		$this->sku                 = 'gift_card';
		$this->quantity            = 1;
		$this->unit_price          = $this->format_price( $coupon_amount );
		$this->subtotal_unit_price = $this->format_price( $coupon_amount );
		$this->total_amount        = $this->format_price( $coupon_amount );
		$this->total_tax_amount    = $this->format_price( $coupon_tax_amount );
		$this->tax_rate            = 0;
		$this->type                = 'gift_card';
	}

	/**
	 * Function to set product name
	 *
	 * @param string $name Name.
	 *
	 * @return self
	 */
	public function set_name( $name = null ) {
		$name       = $name ?? $this->coupon->get_code();
		$this->name = apply_filters( $this->get_filter_name( 'name' ), $name, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product sku
	 *
	 * @param string $sku SKU.
	 *
	 * @return self
	 */
	public function set_sku( $sku = null ) {
		$sku       = $sku ?? $this->coupon->get_code();
		$this->sku = apply_filters( $this->get_filter_name( 'sku' ), $sku, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product quantity
	 *
	 * @param int $quantity Quantity.
	 *
	 * @return self
	 */
	public function set_quantity( $quantity = null ) {
		$quantity       = $quantity ?? 1;
		$this->quantity = apply_filters( $this->get_filter_name( 'quantity' ), $quantity, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product unit price
	 *
	 * @param float|int $unit_price Unit price.
	 *
	 * @return self
	 */
	public function set_unit_price( $unit_price = null ) {
		$unit_price       = is_numeric( $unit_price ) ? $this->format_price( $unit_price ) : $this->discount_amount;
		$this->unit_price = apply_filters( $this->get_filter_name( 'unit_price' ), $unit_price, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product subtotal unit price
	 *
	 * @param float|int $unit_price Subtotal unit price.
	 *
	 * @return self
	 */
	public function set_subtotal_unit_price( $unit_price = null ) {
		$unit_price                = is_numeric( $unit_price ) ? $this->format_price( $unit_price ) : $this->discount_amount;
		$this->subtotal_unit_price = apply_filters( $this->get_filter_name( 'subtotal_unit_price' ), $unit_price, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product tax rate
	 *
	 * @param float|int $tax_rate Tax rate.
	 *
	 * @return self
	 */
	public function set_tax_rate( $tax_rate = null ) {
		$tax_rate       = $tax_rate ?? 0;
		$this->tax_rate = apply_filters( $this->get_filter_name( 'tax_rate' ), $tax_rate, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product total amount
	 *
	 * @param float|int $total_amount Total amount.
	 *
	 * @return self
	 */
	public function set_total_amount( $total_amount = null ) {
		$total_amount       = is_numeric( $total_amount ) ? $this->format_price( $total_amount ) : $this->discount_amount;
		$this->total_amount = apply_filters( $this->get_filter_name( 'total_amount' ), $total_amount, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product subtotal amount
	 *
	 * @param float|int $subtotal_amount Subtotal amount.
	 *
	 * @return self
	 */
	public function set_subtotal_amount( $subtotal_amount = null ) {
		$subtotal_amount       = is_numeric( $subtotal_amount ) ? $this->format_price( $subtotal_amount ) : $this->discount_amount;
		$this->subtotal_amount = apply_filters( $this->get_filter_name( 'subtotal_amount' ), $subtotal_amount, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product total discount amount
	 *
	 * @param float|int $total_discount_amount Total discount amount.
	 *
	 * @return self
	 */
	public function set_total_discount_amount( $total_discount_amount = null ) {
		$total_discount_amount       = is_numeric( $total_discount_amount ) ? $this->format_price( $total_discount_amount ) : 0;
		$this->total_discount_amount = apply_filters( $this->get_filter_name( 'total_discount_amount' ), $total_discount_amount, $this->coupon );

		return $this;
	}

	/**
	 * Abstract function to set product total discount tax amount
	 *
	 * @param float|int $total_discount_tax_amount Total discount tax amount.
	 *
	 * @return self
	 */
	public function set_total_discount_tax_amount( $total_discount_tax_amount = null ) {
		$total_discount_tax_amount       = is_numeric( $total_discount_tax_amount ) ? $this->format_price( $total_discount_tax_amount ) : 0;
		$this->total_discount_tax_amount = apply_filters( $this->get_filter_name( 'total_discount_tax_amount' ), $total_discount_tax_amount, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product total tax amount
	 *
	 * @param float|int $total_tax_amount Total tax amount.
	 *
	 * @return self
	 */
	public function set_total_tax_amount( $total_tax_amount = null ) {
		$total_tax_amount       = is_numeric( $total_tax_amount ) ? $this->format_price( $total_tax_amount ) : $this->discount_tax_amount;
		$this->total_tax_amount = apply_filters( $this->get_filter_name( 'total_tax_amount' ), $total_tax_amount, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product subtotal tax amount
	 *
	 * @param float|int $subtotal_tax_amount Subtotal tax amount.
	 *
	 * @return self
	 */
	public function set_subtotal_tax_amount( $subtotal_tax_amount = null ) {
		$subtotal_tax_amount       = is_numeric( $subtotal_tax_amount ) ? $this->format_price( $subtotal_tax_amount ) : $this->discount_tax_amount;
		$this->subtotal_tax_amount = apply_filters( $this->get_filter_name( 'subtotal_tax_amount' ), $subtotal_tax_amount, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product type
	 *
	 * @param string $type Type of product.
	 *
	 * @return self
	 */
	public function set_type( $type = null ) {
		if ( ! $type ) {
			$meta_data = $this->coupon->get_meta( 'coupon_data', true );
			$type      = isset( $meta_data['discount_type'] ) ? $meta_data['discount_type'] : 'fixed_cart';
		}

		$this->type = apply_filters( $this->get_filter_name( 'type' ), $type, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product url
	 *
	 * @param string $product_url Product url.
	 *
	 * @return self
	 */
	public function set_product_url( $product_url = null ) {
		$this->product_url = apply_filters( $this->get_filter_name( 'product_url' ), $product_url, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product image url
	 *
	 * @param string $image_url Image url.
	 *
	 * @return self
	 */
	public function set_image_url( $image_url = null ) {
		$this->image_url = apply_filters( $this->get_filter_name( 'image_url' ), $image_url, $this->coupon );

		return $this;
	}

	/**
	 * Function to set product compatibility
	 *
	 * @return self
	 */
	public function set_compatibility() {
		$this->compatibility = apply_filters( $this->get_filter_name( 'compatibility' ), array(), $this->coupon );

		return $this;
	}
}
