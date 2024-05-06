<?php
/**
 * Template filters range slide
 *
 * @author  Villatheme
 * @package Pofily Woo Product Filter
 * @version 1.0.0
 */

/**
 * Variables available for this template:
 *
 * @var $filter_name
 * @var $filter_for
 * @var $filter_data
 * @var $filter_setting
 */
$obj_info_block_filter_menu = $this->viwcpf_render_value_block_range_slide( $filter_name, $filter_for, $filter_data, $filter_setting );

$toggle_class_name          = $this->class_show_as_toggle( $filter_setting );
$list_value                 = $obj_info_block_filter_menu['list_value'];
if( empty($list_value) ){ return; }
$key_filter                 = $obj_info_block_filter_menu['key_filter'];
$input_hidden               = $obj_info_block_filter_menu['input_hidden'];
$show_clear_btn             = $obj_info_block_filter_menu['show_clear_btn'] ? 'yes' : 'no';
$arr_value                  = $list_value['value'];
if ( $filter_for === 'filter_by_price' ) {
	$current_min_range = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( wc_clean($_GET['min_price']) ) ) / $arr_value['step_range_slide'] ) * $arr_value['step_range_slide'] : $arr_value['min_range_slide']; // WPCS: input var ok, CSRF ok.
	$current_max_range = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( wc_clean($_GET['max_price']) ) ) / $arr_value['step_range_slide'] ) * $arr_value['step_range_slide'] : $arr_value['max_range_slide']; // WPCS: input var ok, CSRF ok.
} else if ( $filter_for === 'filter_by_metabox' ) {
	$get_min_range = '';
	$get_max_range = '';
	if (
		isset( $_GET[ 'viwcpf_metakey_' . $arr_value['name_metakey'] ] ) &&
		isset( $_GET[ 'viwcpf_metakey_type_' . $arr_value['name_metakey'] ] )
	) {
		$viwcpf_metakey_value_filter = sanitize_text_field($_GET[ 'viwcpf_metakey_' . $arr_value['name_metakey'] ]); // get value meta value; display type is - min_num-max_num
		$viwcpf_metavalue_arr        = explode( "-", $viwcpf_metakey_value_filter );

		if ( sizeof( $viwcpf_metavalue_arr ) == 1 ) {
			if ( $viwcpf_metavalue_arr[0] > $arr_value['max_range_slide'] ) {
				array_push( $viwcpf_metavalue_arr, $arr_value['max_range_slide'] );
			} else {
				array_push( $viwcpf_metavalue_arr, $viwcpf_metavalue_arr[0] );
			}
		}

		$get_min_range = $viwcpf_metavalue_arr[0];
		$get_max_range = $viwcpf_metavalue_arr[1];
	}


	$current_min_range = ! empty( $get_min_range ) ? floor( floatval( wp_unslash( $get_min_range ) ) / $arr_value['step_range_slide'] ) * $arr_value['step_range_slide'] : $arr_value['min_range_slide']; // WPCS: input var ok, CSRF ok.
	$current_max_range = ! empty( $get_max_range ) ? ceil( floatval( wp_unslash( $get_max_range ) ) / $arr_value['step_range_slide'] ) * $arr_value['step_range_slide'] : $arr_value['max_range_slide']; // WPCS: input var ok, CSRF ok.

}

?>

<div class="viwcpf_wrap_filter-content"
     data-filter_by="<?php echo esc_attr( $filter_for ); ?>"
     data-filter_type="<?php echo esc_attr( $key_filter ); ?>">
    <h4 class="viwcpf_filter-title <?php echo esc_attr( $toggle_class_name ); ?>"><?php esc_html_e( $filter_name, 'pofily-woo-product-filters' ); ?></h4>
    <div class="viwcpf_filter-wrap-items  <?php echo esc_attr( $toggle_class_name ); ?>">
		<?php
		if ( $show_clear_btn == 'yes' ) {
			$clear_link = $this->viwcpf_get_clear_url( $key_filter );
			if ( $clear_link != '' ) {
				?>
                <a class="viwcpf_clear_block_filter_btn" href="<?php echo esc_url( $clear_link ); ?>" rel="nofollow"
                   role="button">
					<?php esc_html_e( 'clear', 'pofily-woo-product-filters' ); ?>
                </a>
				<?php
			}
		}
		?>
        <div class="viwcpf_filter viwcpf_range_slider"
             data-filter_for="<?php echo esc_attr( $filter_for ); ?>"
             data-min="<?php echo esc_attr( $arr_value['min_range_slide'] ); ?>"
             data-max="<?php echo esc_attr( $arr_value['max_range_slide'] ); ?>"
             data-step="<?php echo esc_attr( $arr_value['step_range_slide'] ); ?>"
        >
            <div class="viwcpf-range-slider-ui"></div>
            <input type="hidden" class="range-slider-min" name="" value="<?php echo esc_attr( $current_min_range ); ?>">
            <input type="hidden" class="range-slider-max" name="" value="<?php echo esc_attr( $current_max_range ); ?>">
			<?php
			foreach ( $input_hidden as $item ) {
				?>
                <input type="hidden" class="input_filter_hidden <?php echo esc_attr( $item['class'] ); ?>"
                       name="<?php echo esc_attr( $item['name'] ); ?>" value="<?php echo esc_attr( $item['value'] ); ?>"
                       disabled>
				<?php
			}

			?>
        </div>
    </div>
</div>
