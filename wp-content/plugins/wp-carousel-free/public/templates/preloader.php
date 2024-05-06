<?php
/**
 * The image carousel template.
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! empty( $preloader_image ) ) {
	echo '<div id="wpcp-preloader-' . esc_attr( $post_id ) . '" class="wpcp-carousel-preloader">';
	echo '<img src="' . esc_url( $preloader_image ) . '" alt="Preloader Image"/>';
	echo '</div>';
}
