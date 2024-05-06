<?php
/**
 * Product image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type/image.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( has_post_thumbnail() && $show_slide_image ) {
	$product_thumb_id       = get_post_thumbnail_id();
	$product_thumb_alt_text = get_post_meta( $product_thumb_id, '_wp_attachment_image_alt', true );
	$image_url              = wp_get_attachment_image_src( $product_thumb_id, $image_sizes );
	$image_url              = is_array( $image_url ) ? $image_url : array( '', '', '' );

	$the_image_title_attr = ' title="' . get_the_title() . '"';
	$image_title_attr     = $show_image_title_attr ? $the_image_title_attr : '';

	// Product Thumbnail.
	$wpcp_product_thumb = '';
	if ( ! empty( $image_url[0] ) ) {
		$wpcp_product_thumb = WPCF_Helper::get_item_image( $lazy_load_image, $wpcp_layout, $image_url[0], $image_title_attr, $image_url[1], $image_url[2], $product_thumb_alt_text, $lazy_load_img );
		?>
	<div class="wpcp-slide-image">
		<a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( $wpcp_product_thumb ); ?></a>
	</div>
		<?php
	}
}
