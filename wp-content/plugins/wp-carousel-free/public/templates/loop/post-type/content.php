<?php
/**
 * Post Content
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type/content.php.
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( $show_post_content ) {
	?>
<div class='wpcp-post-content'><?php the_excerpt(); ?></div>
<?php } ?>
