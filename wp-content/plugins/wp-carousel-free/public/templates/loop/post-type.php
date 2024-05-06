<?php
/**
 * The post carousel template.
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item">
		<?php
			require WPCF_Helper::wpcf_locate_template( 'loop/post-type/thumbnails.php' );
		?>
		<div class="wpcp-all-captions">
		<?php
			require WPCF_Helper::wpcf_locate_template( 'loop/post-type/meta.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/post-type/title.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/post-type/content.php' );
		?>
		</div>
	</div>
</div>
