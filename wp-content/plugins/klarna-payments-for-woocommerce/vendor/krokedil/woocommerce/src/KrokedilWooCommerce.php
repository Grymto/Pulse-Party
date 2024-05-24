<?php
namespace Krokedil\WooCommerce;

use Krokedil\WooCommerce\Compatibility\Compatibility;
use Krokedil\WooCommerce\Compatibility\Giftcards\SmartCoupons;

/**
 * Main Krokedil WooCommerce Class
 *
 * @package KrokedilWooCommerce
 */
class KrokedilWooCommerce {
	/**
	 * Configuration array.
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * The slug of the plugin using the package.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * The price format for the plugin using the package.
	 *
	 * @var string
	 */
	protected $price_format;

	/**
	 * Default configuration values.
	 *
	 * @var array
	 */
	protected $defaults = array(
		'slug'         => 'krokedil_woocommerce',
		'price_format' => 'minor',
	);

	/**
	 * Compatibility class.
	 *
	 * @var Compatibility
	 */
	protected $compatibility;

	/**
	 * Initialize the package.
	 *
	 * @param array|null $config The configuration array for the package.
	 *
	 * @return void
	 */
	public function __construct( $config = null ) {
		$this->config       = $config ?? $this->defaults;
		$this->slug         = $this->config['slug'];
		$this->price_format = $this->config['price_format'];

		$this->compatibility = new Compatibility( $this );
	}

	/**
	 * Get the configuration array.
	 *
	 * @return array
	 */
	public function config() {
		return $this->config;
	}

	/**
	 * Get the slug of the plugin using the package.
	 *
	 * @return string
	 */
	public function slug() {
		return $this->slug;
	}

	/**
	 * Get the price format of the plugin using the package.
	 *
	 * @return string
	 */
	public function price_format() {
		return $this->price_format;
	}

	/**
	 * Get the compatibility class.
	 *
	 * @return Compatibility
	 */
	public function compatibility() {
		return $this->compatibility;
	}
}
