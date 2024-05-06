<?php
/**
 * Gallery.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/gallery.php
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
<div class="wpcp-carousel-wrapper wpcp-wrapper-<?php echo esc_attr( $post_id ); ?>">
	<?php
	WPCF_Helper::section_title( $post_id, $section_title, $main_section_title );
	WPCF_Helper::preloader( $post_id, $preloader );
	?>
	<div id="sp-wp-carousel-free-id-<?php echo esc_attr( $post_id ); ?>" class="<?php echo esc_attr( $carousel_classes ); ?>" >
		<div class="wpcpro-row">
			<?php WPCF_Helper::get_item_loops( $upload_data, $shortcode_data, $carousel_type, $post_id ); ?>
		</div>
	</div>
	<?php WPCF_Helper::get_pagination( $upload_data, $shortcode_data, $post_id ); ?>
</div> <!-- // Carousel Wrapper. -->
