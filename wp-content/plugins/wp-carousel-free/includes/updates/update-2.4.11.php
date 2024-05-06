<?php
/**
 * Update options for the version 2.4.11
 *
 * @link       https://shapedplugin.com
 * @since      2.4.11
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/includes/updates
 */

/**
 * Update version.
 */
update_option( 'wp_carousel_free_version', '2.4.11' );
update_option( 'wp_carousel_free_db_version', '2.4.11' );

/**
 * Update new Setting options along with old options.
 */
$existing_options = get_option( 'sp_wpcp_settings', true );

$new_options = array(
	'wpcp_enqueue_swiper_css' => true,
	'wpcp_swiper_js'          => true,
);

$all_options = array_merge( $existing_options, $new_options );

$plugin_options = update_option( 'sp_wpcp_settings', $all_options );
