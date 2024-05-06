<?php
/**
 * The file that defines the carousel post type.
 *
 * A class the that defines the carousel post type and make the plugins' menu.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post class to register the carousel.
 */
class WP_Carousel_Free_Post_Type {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.0.0
	 */
	private static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.0.0
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * WordPress carousel post type
	 */
	public function wp_carousel_post_type() {

		if ( post_type_exists( 'sp_wp_carousel' ) ) {
			return;
		}

		// Set the WordPress carousel post type labels.
		$labels = apply_filters(
			'sp_wp_carousel_post_type_labels',
			array(
				'name'               => esc_html_x( 'All Carousels', 'wp-carousel-free' ),
				'singular_name'      => esc_html_x( 'WP Carousel', 'wp-carousel-free' ),
				'add_new'            => esc_html__( 'Add New', 'wp-carousel-free' ),
				'add_new_item'       => esc_html__( 'Add New Carousel', 'wp-carousel-free' ),
				'edit_item'          => esc_html__( 'Edit Carousel', 'wp-carousel-free' ),
				'new_item'           => esc_html__( 'New Carousel', 'wp-carousel-free' ),
				'view_item'          => esc_html__( 'View Carousel', 'wp-carousel-free' ),
				'search_items'       => esc_html__( 'Search Carousels', 'wp-carousel-free' ),
				'not_found'          => esc_html__( 'No Carousels found.', 'wp-carousel-free' ),
				'not_found_in_trash' => esc_html__( 'No Carousels found in trash.', 'wp-carousel-free' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-carousel-free' ),
				'menu_name'          => esc_html__( 'WP Carousel', 'wp-carousel-free' ),
				'all_items'          => esc_html__( 'All Carousels', 'wp-carousel-free' ),
			)
		);

		// Base 64 encoded SVG image.
		$menu_icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 720 566" style="enable-background:new 0 0 720 566;" xml:space="preserve">
			<style type="text/css">
				.st0{fill:#A0A5AA;}
			</style>
			<g>
				<g>
					<g>
						<polygon class="st0" points="540.8,322 462.3,224.4 509.9,186 588.5,283.7    "/>
						<polygon class="st0" points="510.2,381.4 462.5,343.1 541.1,245.4 588.7,283.8    "/>
					</g>
					<g>
						<polygon class="st0" points="179.9,322 132.3,283.7 210.8,186 258.5,224.4    "/>
						<polygon class="st0" points="210.6,381.4 132,283.8 179.7,245.4 258.3,343.1    "/>
					</g>
				</g>
				<g>
					<path class="st0" d="M711.3,556.9H9.4V10.6h701.9L711.3,556.9L711.3,556.9z M88.7,477.6H632V89.9H88.7V477.6z"/>
				</g>
			</g>
			</svg>'
		);

		// Set the WordPress carousel post type arguments.
		$args = apply_filters(
			'sp_wp_carousel_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_ui'             => current_user_can( 'manage_options' ) ? true : false,
				'show_in_admin_bar'   => false,
				'menu_position'       => apply_filters( 'sp_wp_carousel_menu_position', 120 ),
				'menu_icon'           => $menu_icon,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array(
					'title',
				),
			)
		);

		register_post_type( 'sp_wp_carousel', $args );
	}
}
