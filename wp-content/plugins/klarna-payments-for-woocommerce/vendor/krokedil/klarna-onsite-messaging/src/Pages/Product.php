<?php
namespace Krokedil\KlarnaOnsiteMessaging\Pages;

use Krokedil\KlarnaOnsiteMessaging\Settings;

/**
 * Class for handling and displaying the placement on the Product page.
 */
class Product extends Page {

	/**
	 * Whether the custom widget is enabled.
	 *
	 * @var bool
	 */
	protected $custom_widget_enabled = false;
	/**
	 * The hook name for location of the custom placement.
	 *
	 * @var string
	 */
	protected $custom_widget_target = 'woocommerce_single_product_summary';

	/**
	 * The priority of the custom placement.
	 *
	 * @var int
	 */
	protected $custom_widget_priority = 35;

	/**
	 * The setting keys for the Product.
	 *
	 * @var array
	 */
	protected $properties = array(
		'enabled'                => 'onsite_messaging_enabled_product',
		'theme'                  => 'onsite_messaging_theme_product',
		'key'                    => 'placement_data_key_product',
		'client_id'              => 'data_client_id',
		'placement_id'           => 'placement_data_key_product',
		'priority'               => 'onsite_messaging_product_location',
		'custom_widget_enabled'  => 'custom_product_page_widget_enabled',
		'custom_widget_target'   => 'custom_product_page_placement_hook',
		'custom_widget_priority' => 'custom_product_page_placement_priority',
	);

	/**
	 * Class constructor.
	 *
	 * @param Settings $settings The KOSM settings.
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings, $this->properties );

		// The hook name for location of the placement.
		$this->target = 'woocommerce_single_product_summary';

		add_action( 'wp_head', array( $this, 'register_placement' ) );
	}

	/**
	 * Register hook for displaying the placement.
	 *
	 * @return void
	 */
	public function register_placement() {
		if ( $this->enabled && is_product() ) {
			$target   = apply_filters( 'klarna_onsite_messaging_product_target', $this->target );
			$priority = apply_filters( 'klarna_onsite_messaging_product_priority', $this->priority );
			add_action( $target, array( $this, parent::class . '::display_placement' ), $priority );
		}

		if ( $this->custom_widget_enabled ) {
			add_action( $this->custom_widget_target, array( $this, parent::class . '::display_placement' ), $this->custom_widget_priority );
		}
	}
}
