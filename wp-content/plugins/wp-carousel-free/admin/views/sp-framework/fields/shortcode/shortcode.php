<?php
/**
 *
 * Field: shortcode
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_shortcode' ) ) {

	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_shortcode extends SP_WPCF_Fields {



		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			// Get the Post ID.
			$post_id = get_the_ID();

			echo ( ! empty( $post_id ) ) ? '<div class="wpcf-scode-wrap"><span class="wpcf-sc-title">Shortcode:</span><span class="wpcf-shortcode-selectable">[sp_wpcarousel id="' . esc_attr( $post_id ) . '"]</span></div><div class="wpcf-scode-wrap"><span class="wpcf-sc-title">Template Include:</span><span class="wpcf-shortcode-selectable">&lt;?php echo do_shortcode(\'[sp_wpcarousel id="' . esc_attr( $post_id ) . '"]\'); ?&gt;</span></div><div class="spwpc-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
		}

	}
}
