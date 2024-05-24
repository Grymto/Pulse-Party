<?php
namespace Krokedil\WooCommerce\Compatibility\Abstracts;

use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;
use Krokedil\WooCommerce\Order\OrderLineCoupon;
use Krokedil\WooCommerce\OrderLineData;

/**
 * Abstract class to handle compatibility with gift card plugins.
 */
abstract class AbstractGiftCardCompatibility implements GiftCardCompatibilityInterface {
	/**
	 * The instance of the main package class.
	 *
	 * @var KrokedilWooCommerce
	 */
	private $package;

	/**
	 * The name for the giftcard.
	 *
	 * @var string
	 */
	protected $name = 'Gift card';

	/**
	 * The SKU for the giftcard.
	 *
	 * @var string
	 */
	protected $sku = 'gift_card';

	/**
	 * The type for the giftcard.
	 *
	 * @var string
	 */
	protected $type = 'gift_card';

	/**
	 * Initialize the class.
	 *
	 * @param KrokedilWooCommerce $package The main package class.
	 */
	public function __construct( $package ) {
		$this->package = $package;
	}

	/**
	 * Create the gift card object.
	 *
	 * @param string           $name The name of the gift card.
	 * @param string           $sku The SKU of the gift card.
	 * @param string           $type The type of the gift card.
	 * @param float|int|string $amount The amount of the gift card.
	 *
	 * @return OrderLineData
	 */
	protected function create_gift_card( $name, $sku, $type, $amount ) {
		$gift_card = new OrderLineCoupon( $this->package->config() );
		$gift_card->set_name( $name )
				->set_sku( $sku )
				->set_quantity( 1 )
				->set_unit_price( $amount )
				->set_subtotal_unit_price( $amount )
				->set_total_amount( $amount )
				->set_total_tax_amount( 0 )
				->set_tax_rate( 0 )
				->set_type( $type );

		return $gift_card;
	}
}
