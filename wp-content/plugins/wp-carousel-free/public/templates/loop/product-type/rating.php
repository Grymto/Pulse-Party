<?php
/**
 * Product ratting
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/rating.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

$av_rating      = $product->get_average_rating();
$average_rating = ( $av_rating / 5 ) * 100;
if ( $average_rating > 0 && $show_product_rating ) {
	$rating_text = __( 'Rated ', 'wp-carousel-free' ) . $av_rating . __( ' out of 5', 'wp-carousel-free' );

	do_action( 'wpcp_before_product_rating_woocommerce' );
	?>
	<div class="wpcp-product-rating woocommerce">
		<div class="woocommerce-product-rating">
			<div class="star-rating" title="<?php echo esc_attr( $rating_text ); ?>">
				<span style="width:<?php echo esc_attr( $average_rating ); ?>%"></span>
			</div>
		</div>
	</div>
	<?php
	do_action( 'wpcp_after_product_rating_woocommerce' );
}
