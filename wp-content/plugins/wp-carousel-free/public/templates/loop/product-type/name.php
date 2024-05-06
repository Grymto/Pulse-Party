<?php
/**
 * Product name
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/name.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( $show_product_name && ! empty( get_the_title() ) ) {
	?>
<h2 class="wpcp-product-title">
	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h2>
	<?php
}
