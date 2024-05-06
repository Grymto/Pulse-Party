<?php
/**
 * Product Brands Name template file.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/brands-name.php
 *
 * @since   2.5.8
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

$show_product_brands = isset( $shortcode_data['show_product_brands'] ) ? $shortcode_data['show_product_brands'] : false;

if ( $show_product_brands ) {
	if ( class_exists( 'ShapedPlugin\SmartBrands\SmartBrands' ) ) {
		do_action( 'sp_wps_brands_after_product' );
	}
}
