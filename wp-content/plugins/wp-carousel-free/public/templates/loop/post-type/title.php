<?php
/**
 * Post Title
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type/title.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( ( $show_img_title && ! empty( get_the_title() ) ) ) {
	?>
<h2 class="wpcp-post-title">
	<a href="<?php echo esc_url( apply_filters( 'wpcp_post_title_url', get_the_permalink() ) ); ?>">
		<?php the_title(); ?>
	</a>
</h2>
	<?php
}
