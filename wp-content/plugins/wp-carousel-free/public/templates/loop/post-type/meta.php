<?php
/**
 * Post meta
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type/meta.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( $show_post_date || $show_post_author ) {
	?>
	<ul class="wpcp-post-meta">
		<?php if ( $show_post_author ) { ?>
			<li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php printf( __( 'By %s', 'wp-carousel-free' ), get_the_author() ); ?></a></li>
		<?php } ?>
		<?php if ( $show_post_date ) { ?>
			<li><time class="entry-date published updated" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"> <?php printf( __( 'On %s', 'wp-carousel-free' ), get_the_date() ); ?></time></li>
			<?php } ?>
	</ul>
	<?php
}
