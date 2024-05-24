<?php
namespace Krokedil\KlarnaExpressCheckout;

defined( 'ABSPATH' ) || exit;

/**
 * Settings class for the package.
 *
 * @package Krokedil\KlarnaExpressCheckout
 */
class Settings {
	/**
	 * If KEC is enabled or not.
	 *
	 * @var bool
	 */
	private $enabled;

	/**
	 * The options key to get the KEC settings from.
	 *
	 * @var string
	 */
	private $options_key;

	/**
	 * The options array from the options key.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Class constructor.
	 *
	 * @param string $options_key The options key to get the KEC settings from.
	 *
	 * @return void
	 */
	public function __construct( $options_key ) {
		// Automatically add the settings to the Klarna Payments settings page.
		add_filter( 'wc_gateway_klarna_payments_settings', array( $this, 'add_settings' ), 10 );

		// Set the options for where to get the KEC settings from.
		$this->options_key = $options_key;

		// Get the options array from the options key.
		$this->options = $this->get_settings();
	}

	/**
	 * Add the settings to a settings array passed.
	 *
	 * @param array $settings The settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings, $this->get_setting_fields() );

		return $settings;
	}

	/**
	 * Get the KEC settings.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = get_option( $this->options_key, array() );

		// Return only the KEC settings.
		return array(
			'kec_enabled'            => $settings['kec_enabled'] ?? 'no',
			'kec_credentials_secret' => $settings['kec_credentials_secret'] ?? '',
			'kec_theme'              => $settings['kec_theme'] ?? 'default',
			'kec_shape'              => $settings['kec_shape'] ?? 'default',
		);
	}

	/**
	 * Get the enabled status for KEC.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return 'yes' === $this->options['kec_enabled'] ?? 'no';
	}

	/**
	 * Get the credentials secret from Klarna for KEC.
	 *
	 * @return string
	 */
	public function get_credentials_secret() {
		return $this->options['kec_credentials_secret'] ?? '';
	}

	/**
	 * Get the theme for the Klarna Express Checkout.
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->options['kec_theme'] ?? 'default';
	}

	/**
	 * Get the shape for the Klarna Express Checkout.
	 *
	 * @return string
	 */
	public function get_shape() {
		return $this->options['kec_shape'] ?? 'default';
	}

	/**
	 * Get the setting fields.
	 *
	 * @return array
	 */
	public function get_setting_fields() {
		$portal_live_link = '<a href="https://portal.klarna.com/" target="_blank">' . __( 'here for production', 'klarna-express-checkout' ) . '</a>';
		$portal_test_link = '<a href="https://portal.playground.klarna.com/" target="_blank">' . __( 'here for playground', 'klarna-express-checkout' ) . '</a>';
		// translators: %1$s is the link to the Klarna Merchant Portal for production, %2$s is the link to the Klarna Merchant Portal for playground.
		$credentials_secret_desc = sprintf( __( 'Enter your Klarna Client Identifier. This can be found in the Klarna Merchant Portal %1$s and %2$s.', 'klarna-express-checkout' ), $portal_live_link, $portal_test_link );

		return array(
			'kec_settings'           => array(
				'title' => __( 'Klarna Express Checkout', 'klarna-express-checkout' ),
				'type'  => 'title',
				'desc'  => __( 'Klarna Express Checkout is a fast and easy way for customers to pay with Klarna.', 'klarna-express-checkout' ),
			),
			'kec_enabled'            => array(
				'title'   => __( 'Enable/Disable', 'klarna-express-checkout' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Klarna Express Checkout', 'klarna-express-checkout' ),
				'default' => 'no',
			),
			'kec_credentials_secret' => array(
				'title'       => __( 'Klarna Client Identifier', 'klarna-express-checkout' ),
				'type'        => 'text',
				'description' => $credentials_secret_desc,
				'desc_tip'    => false,
			),
			'kec_theme'              => array(
				'title'       => __( 'Theme', 'klarna-express-checkout' ),
				'type'        => 'select',
				'description' => __( 'Select the theme for the Klarna Express Checkout.', 'klarna-express-checkout' ),
				'desc_tip'    => false,
				'options'     => array(
					'default' => __( 'Default', 'klarna-express-checkout' ),
					'dark'    => __( 'Dark', 'klarna-express-checkout' ),
					'light'   => __( 'Light', 'klarna-express-checkout' ),
				),
				'default'     => 'default',
			),
			'kec_shape'              => array(
				'title'       => __( 'Shape', 'klarna-express-checkout' ),
				'type'        => 'select',
				'description' => __( 'Select the shape for the Klarna Express Checkout.', 'klarna-express-checkout' ),
				'desc_tip'    => false,
				'options'     => array(
					'default' => __( 'Default', 'klarna-express-checkout' ),
					'rect'    => __( 'Rectangular', 'klarna-express-checkout' ),
					'pill'    => __( 'Pill', 'klarna-express-checkout' ),
				),
				'default'     => 'default',
			),
		);
	}
}
