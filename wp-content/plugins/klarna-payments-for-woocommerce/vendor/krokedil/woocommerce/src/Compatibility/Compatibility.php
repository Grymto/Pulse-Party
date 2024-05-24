<?php
namespace Krokedil\WooCommerce\Compatibility;

use Krokedil\WooCommerce\Interfaces\CompatibilityInterface;
use Krokedil\WooCommerce\Interfaces\GiftCardCompatibilityInterface;
use Krokedil\WooCommerce\KrokedilWooCommerce;

/**
 * Class to handle compatibility for different third party plugins that the package has support for.
 */
class Compatibility implements CompatibilityInterface {
	/**
	 * Instance of the main package class.
	 *
	 * @var KrokedilWooCommerce
	 */
	private $package;

	/**
	 * Giftcard compatibility classes.
	 *
	 * @var array<string, GiftCardCompatibilityInterface>
	 */
	private $giftcards = array();

	/**
	 * Initialize the compatibility classes.
	 *
	 * @param KrokedilWooCommerce $package The main package class.
	 *
	 * @return void
	 */
	public function __construct( $package ) {
		$this->package = $package;
		$this->init_compatibility_classes();
	}

	/**
	 * Initialize the compatibility classes.
	 */
	private function init_compatibility_classes() {
		$this->giftcards[ Giftcards\WCGiftCards::class ]   = new Giftcards\WCGiftCards( $this->package );
		$this->giftcards[ Giftcards\PWGiftCards::class ]   = new Giftcards\PWGiftCards( $this->package );
		$this->giftcards[ Giftcards\YITHGiftCards::class ] = new Giftcards\YITHGiftCards( $this->package );
		$this->giftcards[ Giftcards\SmartCoupons::class ]  = new Giftcards\SmartCoupons( $this->package );
	}

	/**
	 * Get the giftcard compatibility classes.
	 *
	 * @return array<string, GiftCardCompatibilityInterface>
	 */
	public function giftcards() {
		return $this->giftcards;
	}

	/**
	 * Get the giftcard compatibility class.
	 *
	 * @param string $key The class to get.
	 *
	 * @return GiftCardCompatibilityInterface|false
	 */
	public function giftcard( $key ) {
		return $this->giftcards[ $key ] ?? false;
	}
}
