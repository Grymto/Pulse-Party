<?php
/**
 * Template filters color swatches
 * Only Filter for: Taxonomy
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

$list_label_items      = array();
$input_hidden          = array();
$obj_info_block_filter = $this->viwcpf_render_value_block_filter( $filter_name, $filter_for, $filter_data, $filter_setting );

$toggle_class_name = $this->class_show_as_toggle( $filter_setting );

if (
	is_array( $obj_info_block_filter ) &&
	sizeof( $obj_info_block_filter ) > 0
) {
	$class_display_type = $obj_info_block_filter['display_type'];
	$list_label_items   = $obj_info_block_filter['list_value'];
	$input_hidden       = $obj_info_block_filter['input_hidden'];
	$render_count       = '';
	$key_filter         = $obj_info_block_filter['key_filter'];
	$multiselect        = $obj_info_block_filter['multiselect'] ? 'yes' : 'no';
	$show_clear_btn     = $obj_info_block_filter['show_clear_btn'] ? 'yes' : 'no';
	if ( $obj_info_block_filter['multiselect'] ) {
		$data_multi_relation = 'data-filter_relation="' . esc_attr( $obj_info_block_filter['multi_relation'] ) . '"';
	} else {
		$data_multi_relation = '';
	}
	$arr_style           = $obj_info_block_filter['btn_style'];
	$btn_width           = $arr_style['btn_width'];
	$btn_height          = $arr_style['btn_height'];
	$btn_border_radius   = $arr_style['btn_border_radius'];
	$btn_color_separator = $arr_style['btn_color_separator'];
	$btn_str_style       = '';
	$show_view_more_btn  = $obj_info_block_filter['show_view_more'] ? 'yes' : 'no';
	if ( $show_view_more_btn == 'yes' ) {
		$class_view_more      = 'has_view_more';
		$data_view_more_limit = 'data-view_more=' . $obj_info_block_filter['view_more_limit'];
	} else {
		$class_view_more      = '';
		$data_view_more_limit = '';
	}
}
if ( sizeof( $list_label_items ) > 0 ) {
	?>

    <div class="viwcpf_wrap_filter-content"
         data-filter_by="<?php echo esc_attr( $filter_for ); ?>"
         data-filter_type="<?php echo esc_attr( $key_filter ); ?>"
         data-filter_multiple="<?php echo esc_attr( $multiselect ); ?>"
		<?php echo esc_attr( $data_multi_relation ); ?>
    >
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
            <ul class="viwcpf_filter-items viwcpf_filter-label <?php echo esc_attr( $class_display_type ); ?> <?php echo esc_attr( $class_view_more ); ?>" <?php echo esc_attr( $data_view_more_limit ); ?>>
				<?php
				foreach ( $list_label_items as $label_item ) {
					if ( $obj_info_block_filter['show_count_item'] ) {
						$render_count = '<small class="viwcpf_item-count">' . esc_html($label_item['count']) . '</small>';
					}
					$get_color_btn = $this->get_gradient_color( $label_item['color'], $btn_color_separator );

					$btn_str_style = 'width: ' . $btn_width . 'px; height: ' . $btn_height . 'px; border-radius: ' . $btn_border_radius . '; background:' . $get_color_btn . ';';

					?>
                    <li class="viwcpf_filter-item viwcpf_color_swatches">
                        <a
                                class="<?php echo esc_attr( $label_item['class_name'] ); ?>"
                                href="<?php echo esc_url( $label_item['link'] ); ?>"
                                rel="nofollow"
                                role="button"
                                data-value="<?php echo esc_attr( $label_item['value'] ); ?>"

							<?php echo esc_attr( $label_item['tooltip'] ); ?>
							<?php echo esc_attr( $label_item['data_attr'] ); ?>
                        >
                            <span class="viwcpf_border_wrap_color_item" style="border-radius:<?php echo esc_attr($btn_border_radius); ?>">
                                <span class="viwcpf_wrap_color_item" <?php echo( ' style="' . esc_attr( $btn_str_style ) . '"' ); ?>>
                                    <span class="viwcpf_term-label">
                                        <?php echo wp_kses_post( $label_item['label'] ); ?>
                                    </span>
                                    <?php echo wp_kses_post( $render_count ); ?>
                                </span>
                            </span>
                        </a>
                    </li>
					<?php
				}
				?>
            </ul>
			<?php
			if ( $show_view_more_btn == 'yes' ) {
				?>
                <a class="viwcpf_view_more_btn" href="#" rel="nofollow" role="button">
					<?php esc_html_e( 'View more', 'pofily-woo-product-filters' ); ?>
                </a>
				<?php
			}
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
	<?php
}
?>