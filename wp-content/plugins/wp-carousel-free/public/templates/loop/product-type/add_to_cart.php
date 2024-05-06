<?php
/**
 * Product add to cart button
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/add_to_cart.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

// Add to cart button.
$wpcp_cart = apply_filters( 'wpcp_filter_product_cart', do_shortcode( '[add_to_cart id="' . get_the_ID() . '" show_price="false" style="none"]' ) );
if ( $show_product_cart ) :
	?>
	<div class="wpcp-cart-button"><?php echo wp_kses_post( $wpcp_cart ); ?></div>
	<?php
endif;
