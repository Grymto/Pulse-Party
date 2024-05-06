<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.4.1
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Carousel_Free_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class WP_Carousel_Free_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			require_once WPCAROUSELF_PATH . '/admin/GutenbergBlock/class-wp-carousel-free-gutenberg-block-init.php';
			new WP_Carousel_Free_Gutenberg_Block_Init();
		}
	}
}
