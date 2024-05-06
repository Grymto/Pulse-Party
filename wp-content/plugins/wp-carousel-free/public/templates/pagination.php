<?php
/**
 * Pagination.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/pagination.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ( 'post-carousel' === $carousel_type || 'product-carousel' === $carousel_type ) && ! empty( $total_pages ) && ( $total_pages > 1 ) ) {
	?>
<div class="wpcpro-post-pagination">
	<div class="wpcpro-post-pagination-number">
		<?php echo wp_kses_post( $page_links ); ?>
	</div>
</div>
	<?php
}
