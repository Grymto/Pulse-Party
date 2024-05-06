<?php
/**
 * Update options for the version 2.4.12
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_free
 * @subpackage WP_Carousel_free/includes/updates
 */

update_option( 'wp_carousel_free_version', '2.4.12' );
update_option( 'wp_carousel_free_db_version', '2.4.12' );

/**
 * WP Carousel query for id.
 */
$args         = new WP_Query(
	array(
		'post_type'      => 'sp_wp_carousel',
		'post_status'    => 'any',
		'posts_per_page' => '3000',
	)
);
$carousel_ids = wp_list_pluck( $args->posts, 'ID' );

/**
 * Update metabox data along with previous data.
 */
if ( count( $carousel_ids ) > 0 ) {
	foreach ( $carousel_ids as $carousel_key => $carousel_id ) {
		$shortcode_data = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		$wpcp_layout    = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : '';
		if ( 'gallery' === $wpcp_layout ) {
				$shortcode_data['wpcp_layout'] = 'grid';
		}
		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}// End of foreach.
}
