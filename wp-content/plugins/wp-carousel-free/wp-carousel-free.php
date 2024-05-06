<?php
/**
 * A carousel plugin for WordPress.
 *
 * @link    https://shapedplugin.com/
 * @since   2.0.0
 * @package WP_Carousel_Free
 *
 * Plugin Name:       WP Carousel
 * Plugin URI:        https://wordpresscarousel.com/
 * Description:       The most powerful and user-friendly carousel, slider, and gallery plugin for WordPress. Create unlimited beautiful carousels, sliders, and galleries in minutes using images, posts, WooCommerce products, etc.
 * Version:           2.6.5
 * Author:            ShapedPlugin LLC
 * Author URI:        https://shapedplugin.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-carousel-free
 * Domain Path:       /languages
 * WC requires at least: 4.0
 * WC tested up to: 8.8.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main class of the plugin
 *
 * @package WP_Carousel_Free
 * @author  Shamim Mia <shamhagh@gmail.com>
 */
class SP_WP_Carousel_Free {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    WP_Carousel_Free_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin textdomain.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $domain = 'wp-carousel-free';

	/**
	 * Minimum PHP version required
	 *
	 * @since 2.0.0
	 * @var   string
	 */
	private $min_php = '5.6';

	/**
	 * Plugin file.
	 *
	 * @var string
	 */
	private $file = __FILE__;

	/**
	 * Holds class object
	 *
	 * @var   object
	 * @since 2.0.0
	 */
	private static $instance;

	/**
	 * Initialize the SP_WP_Carousel_Free() class
	 *
	 * @since  2.0.0
	 * @return object
	 */
	public static function init() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof SP_WP_Carousel_Free ) ) {
			self::$instance = new SP_WP_Carousel_Free();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 2.0.0
	 */
	public function setup() {
		$this->plugin_name = 'wp-carousel-free';
		$this->version     = '2.6.5';
		$this->define_constants();
		$this->includes();
		$this->load_dependencies();
		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Define plugin constants.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	private function define_constants() {
		define( 'WPCAROUSELF_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WPCAROUSELF_VERSION', $this->version );
		define( 'WPCAROUSELF_PATH', plugin_dir_path( __FILE__ ) );
		define( 'WPCAROUSELF_INCLUDES', WPCAROUSELF_PATH . '/includes' );
		define( 'WPCAROUSELF_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant Value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Included required files.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public function includes() {
		include_once WPCAROUSELF_INCLUDES . '/class-wp-carosuel-free-updates.php';
		include_once WPCAROUSELF_INCLUDES . '/class-wp-carousel-free-loader.php';
		include_once WPCAROUSELF_INCLUDES . '/class-wp-carousel-free-post-types.php';
		include_once WPCAROUSELF_PATH . '/admin/views/sp-framework/classes/setup.class.php';
		include_once WPCAROUSELF_PATH . '/admin/views/notices/review.php';
		include_once WPCAROUSELF_PATH . '/admin/views/notices/class-wp-carousel-free-promotion.php';
		include_once WPCAROUSELF_PATH . '/admin/views/metabox-config.php';
		include_once WPCAROUSELF_PATH . '/admin/views/option-config.php';
		include_once WPCAROUSELF_PATH . '/admin/views/tools-config.php';
		include_once WPCAROUSELF_INCLUDES . '/class-wp-carousel-free-shortcode.php';
		include_once WPCAROUSELF_INCLUDES . '/class-wp-carousel-free-import-export.php';
		include_once WPCAROUSELF_PATH . '/public/shortcode-deprecated.php';
		include_once WPCAROUSELF_PATH . '/public/WPCF_Helper.php';
		include_once WPCAROUSELF_PATH . '/public/class-wp-carousel-free-public.php';
		include_once WPCAROUSELF_PATH . '/admin/class-wp-carousel-free-admin.php';
		include_once WPCAROUSELF_PATH . '/admin/help-page/help.php';
		include_once WPCAROUSELF_PATH . '/admin/preview/class-wp-carousel-free-preview.php';
		include_once WPCAROUSELF_PATH . '/admin/class-wp-carousel-free-gutenberg-block.php';
		require_once WPCAROUSELF_PATH . '/admin/class-wp-carousel-free-elementor-block.php';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Carousel_Free_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Carousel_Free_I18n. Defines internationalization functionality.
	 * - WP_Carousel_Free_Admin. Defines all hooks for the admin area.
	 * - WP_Carousel_Free_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function load_dependencies() {
		$this->loader = new WP_Carousel_Free_Loader();
	}


	/**
	 * Register common hooks.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function define_common_hooks() {
		$plugin_cpt           = new WP_Carousel_Free_Post_Type( $this->get_plugin_name(), $this->get_version() );
		$plugin_review_notice = new WP_Carousel_Free_Review( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_cpt, 'wp_carousel_post_type', 11 );
		$this->loader->add_action( 'admin_notices', $plugin_review_notice, 'display_admin_notice' );
		$this->loader->add_action( 'wp_ajax_sp-wpcfree-never-show-review-notice', $plugin_review_notice, 'dismiss_review_notice' );
		add_action( 'wp_ajax_wp_ajax_install_plugin', 'wp_ajax_install_plugin' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WP_Carousel_Free_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_styles' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'wpcp_carousel_updated_messages', 10, 2 );
		$this->loader->add_filter( 'manage_sp_wp_carousel_posts_columns', $plugin_admin, 'filter_carousel_admin_column' );
		$this->loader->add_action( 'manage_sp_wp_carousel_posts_custom_column', $plugin_admin, 'display_carousel_admin_fields', 10, 2 );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'add_plugin_action_links', 10, 2 );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 2 );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'sp_wpcp_review_text', 10, 2 );
		$this->loader->add_filter( 'update_footer', $plugin_admin, 'sp_wpcp_version_text', 11 );
		$this->loader->add_action( 'before_woocommerce_init', $plugin_admin, 'declare_compatibility_with_woo_hpos_feature' );

		// Export and Import ajax call.
		$import_export = new Wp_Carousel_Free_Import_Export( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_wpcp_export_shortcodes', $import_export, 'export_shortcodes' );
		$this->loader->add_action( 'wp_ajax_wpcp_import_shortcodes', $import_export, 'import_shortcodes' );

		// Gutenberg block.
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new WP_Carousel_Free_Gutenberg_Block();
		}

		// Elementor shortcode block.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Wp_Carousel_Free_Element_Shortcode_Block();
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public = new WP_Carousel_Free_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'register_all_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'save_post', $plugin_public, 'delete_page_wp_carousel_option_on_save' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'admin_enqueue_scripts' );

		$plugin_shortcode = new WP_Carousel_Free_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( 'sp_wpcarousel', $plugin_shortcode, 'sp_wp_carousel_shortcode' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  2.0.0
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  2.0.0
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  2.0.0
	 * @return WP_Carousel_Free_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 2.0.0
	 */
	public function run() {
		$this->loader->run();
	}
} // SP_WP_Carousel_Free

/**
 * Main instance of WP Carousel Free
 *
 * Returns the main instance of the WP Carousel Free.
 *
 * @since  2.0.0
 * @return void
 */
function sp_wpcf() {
	$plugin = SP_WP_Carousel_Free::init();
	$plugin->loader->run();
}

/**
 * Load the main functionalities of the plugin.
 *
 * @return void
 */
function load_sp_wordpress_carousel_plugin() {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( ! ( is_plugin_active( 'wp-carousel-pro/wp-carousel-pro.php' ) || is_plugin_active_for_network( 'wp-carousel-pro/wp-carousel-pro.php' ) ) ) {
		// Launch it out .
		sp_wpcf();
	}
}

/**
 * Redirect after activation.
 *
 * @param string $plugin_file Path to the plugin file, relative to the plugin.
 * @return void
 */
function sp_wpcf_redirect_after_activation( $plugin_file ) {
	if ( plugin_basename( __FILE__ ) === $plugin_file && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		exit( esc_url( wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcf_help' ) ) ) );
	}
}

add_action( 'plugins_loaded', 'load_sp_wordpress_carousel_plugin' );
add_action( 'activated_plugin', 'sp_wpcf_redirect_after_activation' );
