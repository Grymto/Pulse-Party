<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/pofily-woo-product-filters/
 * @since      1.0.0
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/includes
 * @author     Villatheme <support@villatheme.com>
 */
class VIWCPF_Woo_Product_Filters {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      VIWCPF_Woo_Product_Filters_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $viwcpf_woo_product_filters The string used to uniquely identify this plugin.
	 */
	protected $viwcpf_woo_product_filters;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'VIWCPF_FREE_VERSION' ) ) {
			$this->version = VIWCPF_FREE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->viwcpf_woo_product_filters = 'pofily-woo-product-filters';

		$this->load_dependencies();
		$this->set_locale();
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - VIWCPF_Woo_Product_Filters_Loader. Orchestrates the hooks of the plugin.
	 * - VIWCPF_Woo_Product_Filters_i18n. Defines internationalization functionality.
	 * - VIWCPF_Woo_Product_Filters_Admin. Defines all hooks for the admin area.
	 * - VIWCPF_Woo_Product_Filters_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/data.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-product-filters-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-product-filters-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-product-filters-admin.php';
		/**
		 * The class responsible for registering the widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/widget/viwcpf.widget_menu_filter.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-product-filters-public.php';

		$this->loader = new VIWCPF_Woo_Product_Filters_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the VIWCPF_Woo_Product_Filters_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new VIWCPF_Woo_Product_Filters_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new VIWCPF_Woo_Product_Filters_Admin( $this->get_viwcpf_woo_product_filters(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		$this->loader->add_filter( 'plugin_action_links_' . VIWCPF_FREE_BASE_NAME, $plugin_admin, 'viwcpf_add_action_links' );

		$this->loader->add_action( 'init', $plugin_admin, 'viwcpf_save_settings' );
		$this->loader->add_action( 'init', $plugin_admin, 'viwcpf_register_post_type' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'viwcpf_admin_menu', 20 );

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widget' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'viwcpf_filter_blocks_meta_box' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'viwcpf_filter_menu_meta_box' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'shortcode_after_title_detail_filter_menu' );
		$this->loader->add_action( 'wp_ajax_viwcpf_search_term', $plugin_admin, 'viwcpf_search_term' );
		$this->loader->add_action( 'wp_ajax_viwcpf_refresh_block_filter', $plugin_admin, 'viwcpf_refresh_block_filter' );
		$this->loader->add_action( 'wp_ajax_viwcpf_ajax_update_filterBlock', $plugin_admin, 'viwcpf_ajax_update_filterBlock' );
		$this->loader->add_action( 'save_post_viwcpf_filter_block', $plugin_admin, 'viwcpf_save_detail_filterBlock' );
		$this->loader->add_action( 'save_post_viwcpf_filter_menu', $plugin_admin, 'viwcpf_save_detail_filterMenu' );

		$this->loader->add_filter( 'manage_viwcpf_filter_menu_posts_columns', $plugin_admin, 'custom_post_columns' );
		$this->loader->add_action( 'manage_viwcpf_filter_menu_posts_custom_column', $plugin_admin, 'show_custom_columns' );

		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'viwcpf_duplicate_block_filter_link', 20, 2 );
		$this->loader->add_action( 'admin_action_viwcpf_duplicate_block_as_draft', $plugin_admin, 'viwcpf_duplicate_block_as_draft' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new VIWCPF_Woo_Product_Filters_Public( $this->get_viwcpf_woo_product_filters(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'public_init' );
		$this->loader->add_action( 'init', $plugin_public, 'add_active_filters_list' );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'pre_get_post_filter' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'template_off_canvas' );
		$this->loader->add_action( 'woocommerce_before_template_part', $plugin_public, 'show_filter_icon_woocommerce_before_shop_loop' );
		/*Disable redirect to single product */
		$this->loader->add_filter( 'woocommerce_redirect_single_search_result', $plugin_public, 'viwcpf_redirect_single_search_result' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_viwcpf_woo_product_filters() {
		return $this->viwcpf_woo_product_filters;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    VIWCPF_Woo_Product_Filters_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
	/**
	 * Register Filter Menu widget
	 *
	 *
	 */

}
