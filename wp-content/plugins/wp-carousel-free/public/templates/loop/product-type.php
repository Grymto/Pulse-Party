<?php
/**
 * The product carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/product-type.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item">
		<?php
		require WPCF_Helper::wpcf_locate_template( 'loop/product-type/image.php' );
		?>
		<div class="wpcp-all-captions">
			<?php
			require WPCF_Helper::wpcf_locate_template( 'loop/product-type/brands-name.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/product-type/name.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/product-type/price.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/product-type/rating.php' );
			require WPCF_Helper::wpcf_locate_template( 'loop/product-type/add_to_cart.php' );
			if ( $show_quick_view_button ) {
				do_action( 'sp_wps_after_product_details_inner' );
			}
			?>
		</div>
	</div>
</div>
<?php
