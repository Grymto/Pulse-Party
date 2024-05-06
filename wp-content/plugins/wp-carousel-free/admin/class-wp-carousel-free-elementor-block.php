<?php
/**
 * Elementor shortcode block.
 *
 * @since      2.4.1
 * @package   WordPress_Carousel_Free
 * @subpackage WordPress_Carousel_Free/admin
 */

/**
 * Wp_Carousel_Free_Element_Shortcode_Block
 */
class Wp_Carousel_Free_Element_Shortcode_Block {
	/**
	 * Script and style suffix
	 *
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Instance
	 *
	 * @since 2.4.1
	 *
	 * @access private
	 * @static
	 *
	 * @var Wp_Carousel_Free_Element_Shortcode_Block The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.4.1
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 2.4.1
	 *
	 * @access public
	 */
	public function __construct() {
		$this->suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'sp_wp_carousel_free_block_enqueue_style' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sp_wp_carousel_free_block_enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sp_wp_carousel_free_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.4.1
	 * @return void
	 */
	public function sp_wp_carousel_free_element_block_icon() {
		wp_enqueue_style( 'sp_wp_carousel_element_block_icon', WPCAROUSELF_URL . 'admin/css/fontello.css', array(), WPCAROUSELF_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.4.1
	 */
	public function sp_wp_carousel_free_block_enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in carousel_Free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The carousel_Free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'wpcf-swiper-js' );
		wp_enqueue_script( 'wpcf-fancybox-popup' );
		wp_enqueue_script( 'wpcf-swiper-config' );
	}
	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.4.1
	 */
	public function sp_wp_carousel_free_block_enqueue_style() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in carousel_Free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The carousel_Free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wpcf-swiper' );
		wp_enqueue_style( 'wp-carousel-free-fontawesome' );
		wp_enqueue_style( 'wpcf-fancybox-popup' );
		wp_enqueue_style( 'wp-carousel-free' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.4.1
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.4.1
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.4.1
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		require_once WPCAROUSELF_PATH . '/admin/ElementAddons/Wp_Carousel_Shortcode_Widget.php';
		\Elementor\Plugin::instance()->widgets_manager->register( new Wp_Carousel_Shortcode_Widget() );
	}

}

Wp_Carousel_Free_Element_Shortcode_Block::instance();
