<?php
/**
 * Update version
 *
 * @link       https://shapedplugin.com/
 * @since      2.4.7
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/includes/updates
 */

/**
 * Update version.
 */
update_option( 'wp_carousel_free_version', '2.4.7' );
update_option( 'wp_carousel_free_db_version', '2.4.7' );

/**
 * Update Settings.
 */
$args = new \WP_Query(
	array(
		'post_type'      => array( 'page' ),
		'post_status'    => 'publish',
		'posts_per_page' => '300',
	)
);

$post_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $post_ids ) > 0 ) {
	add_filter( 'wp_revisions_to_keep', '__return_false' );
	foreach ( $post_ids as $post_key => $pid ) {
		$post_data    = get_post( $pid );
		$post_content = isset( $post_data->post_content ) ? $post_data->post_content : '';

		if ( ! empty( $post_content ) && ( strpos( $post_content, 'wp:sp-wp-carousel-free' ) !== false ) ) {
			$post_content = preg_replace( '/wp:sp-wp-carousel-free/i', 'wp:sp-wp-carousel-pro', $post_content );

			$gutenberg_post = array(
				'ID'           => $pid,
				'post_content' => $post_content,
			);
			// Update the post into the database.
			$pid = wp_update_post( $gutenberg_post );

		}

		$post_meta = get_post_meta( $pid, '_elementor_data', true );

		if ( ! empty( $post_meta ) && ( strpos( $post_meta, 'sp_wp_carousel_free_shortcode' ) !== false ) ) {
			$post_meta = preg_replace( '/sp_wp_carousel_free_shortcode/i', 'sp_wp_carousel_pro_shortcode', $post_meta );

			update_post_meta( $pid, '_elementor_data', $post_meta );
		}
	}
}
