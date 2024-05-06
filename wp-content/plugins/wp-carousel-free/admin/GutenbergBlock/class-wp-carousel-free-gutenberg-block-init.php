<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.4.1
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Carousel_Free_Gutenberg_Block_Init' ) ) {
	/**
	 * WP_Carousel_Free_Gutenberg_Block_Init class.
	 */
	class WP_Carousel_Free_Gutenberg_Block_Init {
		/**
		 * Script and style suffix
		 *
		 * @since 2.4.1
		 * @access protected
		 * @var string
		 */
		protected $suffix;
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
			add_action( 'init', array( $this, 'sp_wp_carousel_free_gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'sp_wp_carousel_free_block_editor_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function sp_wp_carousel_free_block_editor_assets() {
			wp_enqueue_script(
				'sp-wp-carousel-free-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				WPCAROUSELF_VERSION,
				true
			);

			/**
			 * Register block editor css file enqueue for backend.
			 */
			wp_enqueue_style( 'wpcf-swiper' );
			wp_enqueue_style( 'wp-carousel-free-fontawesome' );
			wp_enqueue_style( 'wp-carousel-free' );
			wp_enqueue_style( 'wpcf-fancybox-popup' );
		}
		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function sp_wp_carousel_free_post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'sp_wp_carousel',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function sp_wp_carousel_free_gutenberg_shortcode_block() {
			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( 'wpcf-swiper-gb-config', WPCAROUSELF_URL . 'public/js/wp-carousel-free-public' . $this->suffix . '.js', array( 'jquery' ), WPCAROUSELF_VERSION, true );
			wp_register_script( 'wpcf-fancybox-popup', WPCAROUSELF_URL . 'public/js/fancybox.min.js', array( 'jquery' ), WPCAROUSELF_VERSION, true );

			wp_localize_script(
				'wpcf-swiper-gb-config',
				'sp_wp_carousel_free',
				array(
					'url'                => WPCAROUSELF_URL,
					'loadScript'         => WPCAROUSELF_URL . 'public/js/wp-carousel-free-public.min.js',
					'loadFancyBoxScript' => WPCAROUSELF_URL . 'public/js/fancybox-config.min.js',
					'link'               => admin_url( 'post-new.php?post_type=sp_wp_carousel' ),
					'shortCodeList'      => $this->sp_wp_carousel_free_post_list(),
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-wp-carousel-pro/shortcode',
				array(
					'attributes'      => array(
						'shortcodelist'      => array(
							'type'    => 'object',
							'default' => '',
						),
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'wpcp-preloader',
						'wpcf-swiper-js',
						'wpcf-swiper-gb-config',
						'wpcf-fancybox-popup',
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'sp_wp_carousel_free_render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_wp_carousel_free_render_shortcode( $attributes ) {
			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = 'class="' . esc_attr( $attributes['className'] ) . '"';
			}
			if ( ! $attributes['is_admin'] ) {
				return '<div ' . $class_name . '>' . do_shortcode( '[sp_wpcarousel id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}
			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );

			return '<div id="' . uniqid() . '" ' . $class_name . ' ><a href="' . $edit_page_link . '" target="_blank" class="sp_wp_carousel_block_edit_button">Edit View</a>' . do_shortcode( '[sp_wpcarousel id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
