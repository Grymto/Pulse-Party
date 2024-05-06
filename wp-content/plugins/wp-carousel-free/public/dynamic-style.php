<?php
/**
 * The style file for the WP Carousel.
 *
 * @since    3.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/public
 */

$section_title_dynamic_css = '';
$section_title             = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : '';
$carousel_type             = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
// Carousel Navigation settings.
$wpcp_arrows         = isset( $shortcode_data['wpcp_carousel_navigation']['wpcp_navigation'] ) ? $shortcode_data['wpcp_carousel_navigation']['wpcp_navigation'] : 'show';
$wpcp_hide_on_mobile = isset( $shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] ) ? $shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] : '';
// Carousel Pagination settings.
$wpcp_dots                      = isset( $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination'] ) ? $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination'] : 'show';
$wpcp_pagination_hide_on_mobile = isset( $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] ) ? $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] : '';
$wpcp_pagination                = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;
// Layout type.
$wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';

if ( $section_title ) {
	$_section_title_margin_bottom = isset( $shortcode_data['wpcp_section_title_typography']['margin-bottom'] ) ? $shortcode_data['wpcp_section_title_typography']['margin-bottom'] : '30';
	$section_title_dynamic_css   .= '
    .wpcp-wrapper-' . $post_id . ' .sp-wpcpro-section-title, .postbox .wpcp-wrapper-' . $post_id . ' .sp-wpcpro-section-title, #poststuff .wpcp-wrapper-' . $post_id . ' .sp-wpcpro-section-title {
        margin-bottom: ' . $_section_title_margin_bottom . 'px;
    }';
}

$slide_border           = isset( $shortcode_data['wpcp_slide_border'] ) ? $shortcode_data['wpcp_slide_border'] : '';
$old_slide_border_width = isset( $slide_border['width'] ) && ! empty( $slide_border['width'] ) ? $slide_border['width'] : '0';
$slide_border_width     = isset( $shortcode_data['wpcp_slide_border']['all'] ) && ! empty( $shortcode_data['wpcp_slide_border']['all'] ) ? $shortcode_data['wpcp_slide_border']['all'] : $old_slide_border_width;
$slide_border_style     = isset( $slide_border['style'] ) ? $slide_border['style'] : 'none';
$slide_border_color     = isset( $slide_border['color'] ) ? $slide_border['color'] : '';
/**
 * Image Zoom
 */
$image_zoom = isset( $shortcode_data['wpcp_image_zoom'] ) ? $shortcode_data['wpcp_image_zoom'] : 'zoom_in';

// Product Image Border.
$image_border_width     = isset( $shortcode_data['wpcp_product_image_border']['all'] ) && ! empty( $shortcode_data['wpcp_product_image_border']['all'] ) ? $shortcode_data['wpcp_product_image_border']['all'] : $old_slide_border_width;
$image_border_style     = isset( $shortcode_data['wpcp_product_image_border']['style'] ) ? $shortcode_data['wpcp_product_image_border']['style'] : '1';
$image_border_color     = isset( $shortcode_data['wpcp_product_image_border']['color'] ) ? $shortcode_data['wpcp_product_image_border']['color'] : '#ddd';
$show_quick_view_button = isset( $shortcode_data['quick_view'] ) ? $shortcode_data['quick_view'] : true;

if ( 'product-carousel' === $carousel_type ) {
	$wpcp_product_css = '#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-product-carousel .wpcp-slide-image {
		border: ' . $image_border_width . 'px ' . $image_border_style . ' ' . $image_border_color . ';
	}';
	if ( ! $show_quick_view_button ) {
		$wpcp_product_css = '#sp-wp-carousel-free-id-' . $post_id . '.wpcp-product-carousel .wpcp-cart-button #sp-wqv-view-button {
			display: none;
		}';
	}
} else {
	$wpcp_product_css = '#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .wpcp-single-item {
		border: ' . $slide_border_width . 'px ' . $slide_border_style . ' ' . $slide_border_color . ';
	}';
}

// Nav Style.
$nav_dynamic_style = '';
if ( $wpcp_arrows ) {
	$wpcp_nav_color       = isset( $shortcode_data['wpcp_nav_colors']['color1'] ) ? $shortcode_data['wpcp_nav_colors']['color1'] : '#aaa';
	$wpcp_nav_hover_color = isset( $shortcode_data['wpcp_nav_colors']['color2'] ) ? $shortcode_data['wpcp_nav_colors']['color2'] : '#fff';
	$nav_dynamic_style   .= '
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-prev,
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-next,
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-prev:hover,
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-next:hover {
		background: none;
		border: none;
		font-size: 30px;
	}
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-prev i,
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-next i {
		color: ' . $wpcp_nav_color . ';
	}
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-prev i:hover,
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .swiper-button-next i:hover {
		color: ' . $wpcp_nav_hover_color . ';
	}';
}

$pagination_dynamic_style = '';
if ( $wpcp_dots ) {
	$wpcp_dot_color           = isset( $shortcode_data['wpcp_pagination_color']['color1'] ) ? $shortcode_data['wpcp_pagination_color']['color1'] : '#ccc';
	$wpcp_dot_active_color    = isset( $shortcode_data['wpcp_pagination_color']['color2'] ) ? $shortcode_data['wpcp_pagination_color']['color2'] : '#52b3d9';
	$pagination_dynamic_style = '
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .wpcp-swiper-dots .swiper-pagination-bullet {
		background-color: ' . $wpcp_dot_color . ';
	}
	#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . ' .wpcp-swiper-dots .swiper-pagination-bullet.swiper-pagination-bullet-active {
		background-color: ' . $wpcp_dot_active_color . ';
	}
	';
}

if ( $wpcp_pagination_hide_on_mobile ) {
	$the_wpcf_dynamic_css .= '
	@media screen and (max-width: 479px) {
	#sp-wp-carousel-free-id-' . $post_id . ' .wpcp-swiper-dots {
			display: none;
		}
	}';
}

// grid pagination styles.
if ( $wpcp_pagination && 'grid' === $wpcp_layout && 'image-carousel' !== $carousel_type ) { // Load grid pagination's styles if layout is grid, Source type is not image carousel and pagination is enabled.
	$pagination_alignment = isset( $shortcode_data['pagination_alignment'] ) ? $shortcode_data['pagination_alignment'] : 'center'; // button allignment.
	$pagination_colors    = isset( $shortcode_data['pagination_color'] ) ? $shortcode_data['pagination_color'] : array(
		'color'        => '#5e5e5e',
		'hover_color'  => '#ffffff',
		'bg'           => '#ffffff',
		'hover_bg'     => '#178087',
		'border'       => '#dddddd',
		'hover_border' => '#178087',
	); // pagination all colors.

	$pagination_dynamic_style .= '
	.wpcp-carousel-wrapper.wpcp-wrapper-' . $post_id . ' .wpcpro-post-pagination{
		text-align: ' . $pagination_alignment . ';
	}
	.wpcp-wrapper-' . $post_id . ' .wpcpro-post-pagination .page-numbers{
		color: ' . $pagination_colors['color'] . ';
		border-color: ' . $pagination_colors['border'] . ';
		background:  ' . $pagination_colors['bg'] . ';
	}
	.wpcp-wrapper-' . $post_id . ' .wpcpro-post-pagination .page-numbers:hover,
	.wpcp-wrapper-' . $post_id . ' .wpcpro-post-pagination .page-numbers.current,
	.wpcp-wrapper-' . $post_id . ' .wpcpro-post-pagination .page-numbers.current{
		color: ' . $pagination_colors['hover_color'] . ';
		border-color: ' . $pagination_colors['hover_border'] . ';
		background:  ' . $pagination_colors['hover_bg'] . ';
	}';
}

/**
 * The Dynamic Style CSS.
 */

$the_wpcf_dynamic_css .= $wpcp_product_css;
$the_wpcf_dynamic_css .= $section_title_dynamic_css;
$the_wpcf_dynamic_css .= $nav_dynamic_style;
$the_wpcf_dynamic_css .= $pagination_dynamic_style;

// Image zoom css.
switch ( $image_zoom ) {
	case 'zoom_in':
		$the_wpcf_dynamic_css .= '
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-image-carousel .wpcp-single-item:hover img,
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-post-carousel .wpcp-single-item:hover img,
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-product-carousel .wpcp-single-item:hover img{
				-webkit-transform: scale(1.2);
				-moz-transform: scale(1.2);
				transform: scale(1.2);
			}';
		break;
	case 'zoom_out':
		$the_wpcf_dynamic_css .= '

		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-image-carousel .wpcp-single-item img,
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-post-carousel .wpcp-single-item img,
	 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-product-carousel .wpcp-single-item img{
				-webkit-transform: scale(1.2);
				-moz-transform: scale(1.2);
				transform: scale(1.2);
		}

		#sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-image-carousel .wpcp-single-item:hover img,
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-post-carousel .wpcp-single-item:hover img,
		 #sp-wp-carousel-free-id-' . $post_id . '.sp-wpcp-' . $post_id . '.wpcp-product-carousel .wpcp-single-item:hover img{
				-webkit-transform: scale(1);
				-moz-transform: scale(1);
				transform: scale(1);
			}';
		break;
}

if ( 'post-carousel' === $carousel_type ) {
	$the_wpcf_dynamic_css .= '
	.wpcp-carousel-wrapper #sp-wp-carousel-free-id-' . $post_id . '.wpcp-post-carousel .wpcp-single-item {
		background: ' . ( isset( $shortcode_data['wpcp_slide_background'] ) ? $shortcode_data['wpcp_slide_background'] : '#f9f9f9' ) . ';
	}';
}
if ( ! $wpcp_arrows ) {
	$the_wpcf_dynamic_css .= '
		#sp-wp-carousel-free-id-' . $post_id . '.nav-vertical-center {
			padding: 0;
			margin:0;
	}';
}
if ( $wpcp_hide_on_mobile ) {
	$the_wpcf_dynamic_css .= '
	@media screen and (max-width: 479px) {
		#sp-wp-carousel-free-id-' . $post_id . '.nav-vertical-center {
			padding: 0;
			margin:0;
		}
		#sp-wp-carousel-free-id-' . $post_id . '.nav-vertical-center .wpcp-next-button,#sp-wp-carousel-free-id-' . $post_id . '.nav-vertical-center .wpcp-prev-button {
			display: none;
		}
	}';
}
$item_gap              = isset( $shortcode_data['wpcp_slide_margin'] ) ? $shortcode_data['wpcp_slide_margin'] : array(
	'top'   => '20',
	'right' => '20',
);
$the_wpcf_dynamic_css .= '#sp-wp-carousel-free-id-' . $post_id . ' .wpcpro-row>[class*="wpcpro-col-"] {
    padding: 0 ' . (int) $item_gap['top'] / 2 . 'px;
    padding-bottom: ' . $item_gap['right'] . 'px;
}';

