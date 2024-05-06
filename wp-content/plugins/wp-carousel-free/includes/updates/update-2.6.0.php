<?php
/**
 * Update options for the version 2.6.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_free
 * @subpackage WP_Carousel_free/includes/updates
 */

update_option( 'wp_carousel_free_version', '2.6.0' );
update_option( 'wp_carousel_free_db_version', '2.6.0' );

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
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : '';

		$old_wpcp_arrows = isset( $shortcode_data['wpcp_navigation'] ) ? $shortcode_data['wpcp_navigation'] : '';
		// Use database updater for the "carousel navigation" and "hide on mobile" options.
		switch ( $old_wpcp_arrows ) {
			case 'show':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '1';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '0';
				break;
			case 'hide':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '0';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '0';
				break;
			case 'hide_mobile':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '1';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '1';
				break;
		}

		$old_wpcp_dots = isset( $shortcode_data['wpcp_pagination'] ) ? $shortcode_data['wpcp_pagination'] : '';
		// Use database updater for the "carousel pagination" and "hide on mobile" options.
		switch ( $old_wpcp_dots ) {
			case 'show':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '1';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '0';
				break;
			case 'hide':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '0';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '0';
				break;
			case 'hide_mobile':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '1';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '1';
				break;
		}
		/* Section title margin */
		$old_section_title_margin = isset( $shortcode_data['section_title_margin_bottom'] ) && is_numeric( $shortcode_data['section_title_margin_bottom'] ) ? $shortcode_data['section_title_margin_bottom'] : '30';
		$section_title_margin     = isset( $shortcode_data['section_title_margin_bottom']['all'] ) && ! empty( $shortcode_data['section_title_margin_bottom']['all'] ) && ( $shortcode_data['section_title_margin_bottom']['all'] >= 0 ) ? $shortcode_data['section_title_margin_bottom']['all'] : $old_section_title_margin;

		$shortcode_data['wpcp_section_title_typography']['margin-bottom'] = $section_title_margin;

		/* Autoplay Speed */
		$old_autoplay_speed                         = isset( $shortcode_data['carousel_auto_play_speed'] ) && is_numeric( $shortcode_data['carousel_auto_play_speed'] ) ? $shortcode_data['carousel_auto_play_speed'] : '3000';
		$autoplay_speed                             = isset( $shortcode_data['carousel_auto_play_speed']['all'] ) && ! empty( $shortcode_data['carousel_auto_play_speed']['all'] ) ? $shortcode_data['carousel_auto_play_speed']['all'] : $old_autoplay_speed;
		$shortcode_data['carousel_auto_play_speed'] = $autoplay_speed;
		/* Carousel/Sliding Speed */
		$old_speed = isset( $shortcode_data['standard_carousel_scroll_speed'] ) && is_numeric( $shortcode_data['standard_carousel_scroll_speed'] ) ? $shortcode_data['standard_carousel_scroll_speed'] : '600';
		$speed     = isset( $shortcode_data['standard_carousel_scroll_speed']['all'] ) && ! empty( $shortcode_data['standard_carousel_scroll_speed']['all'] ) ? $shortcode_data['standard_carousel_scroll_speed']['all'] : $old_speed;
		$shortcode_data['standard_carousel_scroll_speed'] = $speed;

		$old_image_link_show = isset( $shortcode_data['wpcp_logo_link_show'] ) ? $shortcode_data['wpcp_logo_link_show'] : 'l_box';
		// Update the 'wpcp_logo_link_show' in the 'wpcp_click_action_type_group' array.
		$shortcode_data['wpcp_click_action_type_group'] = array(
			'wpcp_logo_link_show' => $old_image_link_show,
		);

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}// End of foreach.
}
