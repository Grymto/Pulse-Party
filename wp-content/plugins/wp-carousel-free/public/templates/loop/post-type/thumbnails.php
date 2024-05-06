<?php
/**
 * Post thumbnails
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type/thumbnails.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( has_post_thumbnail() && $show_slide_image ) {
	$image_id             = get_post_thumbnail_id();
	$image_url            = wp_get_attachment_image_src( $image_id, $image_sizes );
	$image_url            = is_array( $image_url ) ? $image_url : array( '', '', '' );
	$image_alt_text       = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$the_image_title_attr = ' title="' . the_title_attribute( 'echo=0' ) . '"';
	$image_title_attr     = ( 'true' === $show_image_title_attr ) ? $the_image_title_attr : '';

	if ( ! empty( $image_url[0] ) ) {
		$post_thumb = WPCF_Helper::get_item_image( $lazy_load_image, $wpcp_layout, $image_url[0], $image_title_attr, $image_url[1], $image_url[2], $image_alt_text, $lazy_load_img );
		?>
	<div class="wpcp-slide-image">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php echo wp_kses_post( $post_thumb ); ?>
		</a>
	</div>
		<?php
	}
} // End of Has post thumbnail.
