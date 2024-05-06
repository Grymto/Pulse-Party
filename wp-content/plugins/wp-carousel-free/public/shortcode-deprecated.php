<?php
/**
 * Registering shortcode.
 *
 * @package WP Carousel
 */

if ( ! function_exists( 'wp_carousel_free_shortcode' ) ) {

	/**
	 * Shortcode main function.
	 *
	 * @param mixed $attr The attributes of the shortcode.
	 * @return statement
	 */
	function wp_carousel_free_shortcode( $attr ) {
		$post = get_post();

		static $instance = 0;
		$instance ++;

		if ( ! empty( $attr['ids'] ) ) {
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$output = apply_filters( 'sp_wcfgallery_shortcode', '', $attr );
		if ( '' !== $output ) {
			return $output;
		}

		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] ) {
				unset( $attr['orderby'] );
			}
		}

		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					'ids'                 => '',
					'items'               => '5',
					'items_desktop'       => '4',
					'items_desktop_small' => '3',
					'items_tablet'        => '2',
					'items_mobile'        => '1',
					'bullets'             => 'false',
					'bullets_mobile'      => 'false',
					'nav'                 => 'true',
					'nav_mobile'          => 'true',
					'auto_play'           => 'true',
					'autoplay_speed'      => '3000',
					'speed'               => '600',
					'infinite'            => 'true',
					'pause_on_hover'      => 'true',
					'swipe'               => 'true',
					'draggable'           => 'true',
					'size'                => 'medium',
					'include'             => '',
					'exclude'             => '',
					'carousel_direction'  => 'ltr',
				),
				$attr,
				'gallery'
			)
		);

		// Helper function to return shortcode regex match on instance occurring on page or post.
		if ( ! function_exists( 'get_match' ) ) {
			/**
			 * Find and match gallery shortcode
			 *
			 * @param mix $regex The regular expression.
			 * @param mix $content The regular expression content.
			 * @param mix $instance The regular expression match.
			 * @return statement
			 */
			function get_match( $regex, $content, $instance ) {
				preg_match_all( $regex, $content, $matches );

				return $matches[1][ $instance ];
			}
		}

		// Extract the shortcode arguments from the $page or $post.
		$shortcode_args = shortcode_parse_atts( get_match( '/\[wcfgallery\s(.*)\]/isU', $post->post_content, $instance - 1 ) );

		// get the ids specified in the shortcode call.
		if ( is_array( $ids ) ) {
			if ( isset( $shortcode_args['ids'] ) ) {
				$ids = $shortcode_args['ids'];
			}
		}

		$id      = uniqid();
		$order   = 'DESC';
		$orderby = 'title';

		if ( 'RAND' === $order ) {
			$orderby = 'none';
		}

		if ( ! empty( $ids ) ) {

			$_attachments = get_posts(
				array(
					'include'        => $ids,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $order,
					'orderby'        => $orderby,
				)
			);

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $exclude ) ) {
			$attachments = get_children(
				array(
					'post_parent'    => $id,
					'exclude'        => $exclude,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $order,
					'orderby'        => $orderby,
				)
			);
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			}

			return $output;
		}

		$gallery_style = '<style type="text/css">#wordpress-carousel-free-' . esc_attr( $id ) . '.wpcp-carousel-section .wpcp-swiper-dots .swiper-pagination-bullet.swiper-pagination-bullet-active {
			background: #178087;
		}</style>';
		$gallery_div   = '';

		// Carousel Configurations.

		wp_enqueue_style( 'wpcf-swiper' );
		wp_enqueue_style( 'wp-carousel-free-fontawesome' );
		wp_enqueue_style( 'wp-carousel-free' );
		wp_enqueue_script( 'wpcf-swiper-js' );
		wp_enqueue_script( 'wpcf-swiper-config' );
		if ( wpcf_get_option( 'wpcp_ajax_js', false ) ) {
			wp_enqueue_script( 'wpcf-ajax-theme' );
		}

		$wpcp_screen_sizes = wpcf_get_option( 'wpcp_responsive_screen_setting' );
		$desktop_size      = isset( $wpcp_screen_sizes['desktop'] ) && ! empty( $wpcp_screen_sizes['desktop'] ) ? $wpcp_screen_sizes['desktop'] : '1200';
		$laptop_size       = isset( $wpcp_screen_sizes['laptop'] ) && ! empty( $wpcp_screen_sizes['laptop'] ) ? $wpcp_screen_sizes['laptop'] : '980';
		$tablet_size       = isset( $wpcp_screen_sizes['tablet'] ) && ! empty( $wpcp_screen_sizes['tablet'] ) ? $wpcp_screen_sizes['tablet'] : '736';
		$mobile_size       = isset( $wpcp_screen_sizes['mobile'] ) && ! empty( $wpcp_screen_sizes['mobile'] ) ? $wpcp_screen_sizes['mobile'] : '480';

		$rtl = ( 'ltr' === $carousel_direction ) ? 'true' : 'false';

		$swipetoslide        = esc_attr( $swipe ) ? true : false;
		$wpcp_swiper_options = '{ "accessibility":true, "arrows":' . esc_attr( $nav ) . ', "autoplay":' . esc_attr( $auto_play ) . ', "autoplaySpeed":' . esc_attr( intval( $autoplay_speed ) ) . ', "dots":' . esc_attr( $bullets ) . ', "infinite":' . esc_attr( $infinite ) . ', "speed":' . esc_attr( intval( $speed ) ) . ', "pauseOnHover":' . esc_attr( $pause_on_hover ) . ', "spaceBetween": 20, "slidesToShow":{"lg_desktop":' . esc_attr( intval( $items ) ) . ', "desktop": ' . esc_attr( intval( $items_desktop ) ) . ', "laptop": ' . esc_attr( intval( $items_desktop_small ) ) . ', "tablet": ' . esc_attr( intval( $items_tablet ) ) . ', "mobile": ' . esc_attr( intval( $items_mobile ) ) . '}, "responsive":{"desktop":' . esc_attr( intval( $desktop_size ) ) . ', "laptop": ' . esc_attr( intval( $laptop_size ) ) . ', "tablet": ' . esc_attr( intval( $tablet_size ) ) . ', "mobile": ' . esc_attr( intval( $mobile_size ) ) . '}, "rtl":' . esc_attr( $rtl ) . ', "lazyLoad": false, "swipe": ' . esc_attr( $swipe ) . ', "draggable": ' . esc_attr( $draggable ) . ', "swipeToSlide":' . esc_attr( $swipetoslide ) . ', "freeMode": false }';

		$gallery_div = "<div class='wpcp-carousel-wrapper wpcp-wrapper-" . esc_attr( $id ) . "'><div id='wordpress-carousel-free-" . esc_attr( $id ) . "' class='wpcp-carousel-section wpcp-standard nav-vertical-center' data-swiper='" . esc_attr( $wpcp_swiper_options ) . "'><div class='swiper-wrapper'>";

		$output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

		foreach ( $attachments as $attach_id => $attachment ) {
			$wcf_image_url   = wp_get_attachment_image_src( $attach_id, $size, false );
			$wcf_image_title = $attachment->post_title;

			$output .= "
			<div class='swiper-slide'>
			<div class='wpcp-single-item'>
				<img src='" . esc_url( $wcf_image_url[0] ) . "' alt='" . esc_attr( $wcf_image_title ) . "' />
			</div>
			</div>";
		}
		$output .= '</div>';

		if ( $bullets ) {
			$output .= '<div class="wpcp-swiper-dots swiper-pagination"></div>';
		}
		if ( $nav ) {
			$output .= '<div class="wpcp-prev-button swiper-button-prev"><i class="fa fa-angle-left"></i></div>
			<div class="wpcp-next-button swiper-button-next"><i class="fa fa-angle-right"></i></div>';
		}
		$output .= "
		</div></div>\n";

		return $output;
	}

	add_shortcode( 'wcfgallery', 'wp_carousel_free_shortcode' );
}
