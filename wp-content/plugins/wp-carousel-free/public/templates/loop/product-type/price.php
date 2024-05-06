<?php
/**
 * Product price
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/price.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

$price_html = $product->get_price_html();
if ( $price_html && $show_product_price ) {
	?>
	<div class="wpcp-product-price">
		<?php echo wp_kses_post( $price_html ); ?>
	</div>
	<?php
}
