<?php
/**
 *  Enqueue public script for the WP Carousel
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/public
 */

/**
 * The public-facing functionality of the plugin.
 */
class WP_Carousel_Free_Public {

	/**
	 * Script and style suffix
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * The ID of the plugin.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $plugin_name The ID of this plugin
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $version The current version fo the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class sets its properties.
	 *
	 * @since 2.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the plugin.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		// Get the existing shortcode ids from the current page.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		if ( $found_generator_id ) {
			wp_enqueue_style( 'wpcf-swiper' );
			wp_enqueue_style( 'wp-carousel-free-fontawesome' );
			wp_enqueue_style( 'wpcf-fancybox-popup' );
			wp_enqueue_style( 'wp-carousel-free' );
			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_add_inline_style( 'wp-carousel-free', $dynamic_style['dynamic_css'] );
		}
	}

	/**
	 * Enqueue css and js files for live preview.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_carousel' === $the_current_post_type ) {
			// Enqueue css file.
			wp_enqueue_style( 'wpcf-swiper' );
			wp_enqueue_style( 'wp-carousel-free-fontawesome' );
			wp_enqueue_style( 'wp-carousel-free' );
			wp_enqueue_style( 'wpcf-fancybox-popup' );

			// Enqueue js file.
			wp_enqueue_script( 'wpcf-swiper-js' );
			wp_enqueue_script( 'wpcf-fancybox-popup' );
			wp_enqueue_script( 'wpcf-fancybox-config' );
		}
	}
	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		/**
		 * Register the stylesheets for the public-facing side of the plugin.
		 */
		if ( wpcf_get_option( 'wpcp_enqueue_swiper_css', true ) ) {
			wp_register_style( 'wpcf-swiper', WPCAROUSELF_URL . 'public/css/swiper-bundle.min.css', array(), $this->version, 'all' );
		}
		if ( wpcf_get_option( 'wpcp_enqueue_fa_css', true ) ) {
			wp_register_style( 'wp-carousel-free-fontawesome', WPCAROUSELF_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
		wp_register_style( 'wpcf-fancybox-popup', WPCAROUSELF_URL . 'public/css/jquery.fancybox.min.css', array(), $this->version, 'all' );
		wp_register_style( 'wp-carousel-free', WPCAROUSELF_URL . 'public/css/wp-carousel-free-public' . $this->suffix . '.css', array(), $this->version, 'all' );

		/**
		 * Register the JavaScript for the public-facing side of the plugin.
		 */
		wp_register_script( 'wpcp-preloader', WPCAROUSELF_URL . 'public/js/preloader' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-swiper-js', WPCAROUSELF_URL . 'public/js/swiper-bundle.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-swiper-config', WPCAROUSELF_URL . 'public/js/wp-carousel-free-public' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-ajax-theme', WPCAROUSELF_URL . 'public/js/ajax-theme' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-fancybox-popup', WPCAROUSELF_URL . 'public/js/fancybox.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcf-fancybox-config', WPCAROUSELF_URL . 'public/js/fancybox-config' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );

		$ajax_theme = wpcf_get_option( 'wpcp_ajax_js', false );

		wp_localize_script(
			'wpcf-ajax-theme',
			'wpcf_vars',
			array(
				'script_path' => WPCAROUSELF_URL . 'public/js',
				'ajaxTheme'   => $ajax_theme,
			)
		);

	}
	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_wp_carousel_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_wp_carousel_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'sp_wp_carousel_page_id' . $post_ID ) ) {
				delete_option( 'sp_wp_carousel_page_id' . $post_ID );
			}
		}
	}
	/**
	 * Minify output
	 *
	 * @param  string $html output.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_wp_carousel_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_wp_carousel_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $shortcode_data to push all options.
	 * @param  mixed $upload_data get upload option from the existing shortcode.
	 * @return array dynamic style use in the existing shortcodes in the current page.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_data = '', $upload_data = '' ) {
		$setting_options      = get_option( 'sp_testimonial_pro_options' );
		$the_wpcf_dynamic_css = '';
		// If multiple shortcode found in the current page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$upload_data    = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
					$shortcode_data = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
					include WPCAROUSELF_PATH . '/public/dynamic-style.php';
				}
			}
		} else {
			// If single shortcode found in the current page.
			$post_id = $found_generator_id;
			include WPCAROUSELF_PATH . '/public/dynamic-style.php';
		}
		// Include responsive breakpoints CSS.
		include WPCAROUSELF_PATH . '/public/responsive.php';
		// Custom css merge with dynamic style.
		$custom_css = trim( html_entity_decode( wpcf_get_option( 'wpcp_custom_css' ) ) );
		if ( ! empty( $custom_css ) ) {
			$the_wpcf_dynamic_css .= $custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => self::minify_output( $the_wpcf_dynamic_css ),
		);
		return $dynamic_style;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the the current page.
	 * @return void
	 */
	public static function wpf_db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}
}
