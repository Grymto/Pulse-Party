<?php
/**
 * Template filters checkbox
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

$list_label_items           = array();
$input_hidden               = array();
$obj_info_block_filter_menu = $this->viwcpf_render_value_block_filter( $filter_name, $filter_for, $filter_data, $filter_setting );
$toggle_class_name          = $this->class_show_as_toggle( $filter_setting );


if (
	is_array( $obj_info_block_filter_menu ) &&
	sizeof( $obj_info_block_filter_menu ) > 0
) {
	$class_display_type = $obj_info_block_filter_menu['display_type'];
	$list_label_items   = $obj_info_block_filter_menu['list_value'];
	$input_hidden       = $obj_info_block_filter_menu['input_hidden'];
	$render_count       = '';
	$key_filter         = $obj_info_block_filter_menu['key_filter'];
	$multiselect        = $obj_info_block_filter_menu['multiselect'] ? 'yes' : 'no';
	$show_clear_btn     = $obj_info_block_filter_menu['show_clear_btn'] ? 'yes' : 'no';
	if ( $obj_info_block_filter_menu['multiselect'] ) {

		$data_multi_relation = 'data-filter_relation="' . esc_attr( $obj_info_block_filter_menu['multi_relation'] ) . '"';
	} else {
		$data_multi_relation = '';
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

            <ul class="viwcpf_filter-items viwcpf_filter-label  <?php echo esc_attr( $class_display_type ); ?>">
				<?php
				foreach ( $list_label_items as $label_item ) {
					if ( $obj_info_block_filter_menu['show_count_item'] ) {
						$render_count = '<small class="viwcpf_item-count"> (' . esc_html($label_item['count']) . ')</small>';
					}
					?>
                    <li class="viwcpf_filter-item viwcpf_checkbox">
                        <label>
                            <div class="viwcpf_wrap_checkbox <?php echo esc_attr( $label_item['class_name'] ); ?>">
                                <input class="viwcpf_checkbox" type="checkbox" name=""
									<?php
									if ( $label_item['class_name'] == 'viwcpf_chosen' ) {
										echo esc_attr( 'checked' );
									}
									?>
                                >
                                <span class="viwcpf_checkbox_checkmark"></span>
                            </div>
                            <a
                                    class="viwcpf_link_checkbox <?php echo esc_attr( $label_item['class_name'] ); ?>"
                                    href="<?php echo esc_url( $label_item['link'] ); ?>"
                                    rel="nofollow"
                                    role="button"
                                    data-value="<?php echo esc_attr( $label_item['value'] ); ?>"
								<?php echo wp_kses_post( $label_item['tooltip'] ); ?>
								<?php echo esc_attr( $label_item['data_attr'] ); ?>
                            >

                                <span class="viwcpf_term-label">
                                    <?php echo wp_kses_post( $label_item['label'] ); ?>
                                </span>
								<?php echo wp_kses_post( $render_count ); ?>
                            </a>
                        </label>
                    </li>
					<?php
				}
				?>
            </ul>
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
	<?php
}
?>