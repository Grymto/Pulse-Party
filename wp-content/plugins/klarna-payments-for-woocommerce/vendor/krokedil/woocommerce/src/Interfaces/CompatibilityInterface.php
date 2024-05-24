<?php
namespace Krokedil\WooCommerce\Interfaces;

/**
 * Interface CompatibilityInterface
 *
 * @package Krokedil\WooCommerce\Interfaces
 */
interface CompatibilityInterface {
	/**
	 * Get the giftcard compatibility classes.
	 *
	 * @return array<string, GiftCardCompatibilityInterface>
	 */
	public function giftcards();

	/**
	 * Get the giftcard compatibility class.
	 *
	 * @param string $key The class to get.
	 *
	 * @return GiftCardCompatibilityInterface|false
	 */
	public function giftcard( $key );
}
