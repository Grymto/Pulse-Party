<?php
/**
 * Template filters dropdown
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
		$multiple            = 'multiple';
	} else {
		$data_multi_relation = '';
		$multiple            = '';
		global $wp;
		$current_screen_url = home_url( add_query_arg( array( wc_clean($_GET) ), $wp->request ) );
	}
	if( isset( $obj_info_block_filter_menu['tax_show_search_field'] ) ){
        $show_search = $obj_info_block_filter_menu['tax_show_search_field'];
    }else{
		$show_search = '0';
    }

}
if ( sizeof( $list_label_items ) > 0 ) {

	?>

    <div class="viwcpf_wrap_filter-content"
         data-filter_by="<?php echo esc_attr( $filter_for ); ?>"
         data-filter_type="<?php echo esc_attr( $key_filter ); ?>">
        <h4 class="viwcpf_filter-title <?php echo esc_attr( $toggle_class_name ); ?>"><?php esc_html_e( $filter_name, 'pofily-woo-product-filters' ); ?></h4>
        <div class="viwcpf_filter-wrap-items viwcpf_wrap_dropdown  <?php echo esc_attr( $toggle_class_name ); ?>">
			<?php
			if ( $show_clear_btn == 'yes' ) {
				$clear_link = $this->viwcpf_get_clear_url( $key_filter );
				if ( $clear_link != '' ) {
					?>
                    <a class="viwcpf_clear_block_filter_btn" href="<?php echo esc_url( $clear_link ); ?>" rel="nofollow"
                       role="button">
						<?php esc_html_e( 'clear', 'woo' ); ?>
                    </a>
					<?php
				}
			}
			?>
            <select class="viwcpf_filter-items viwcpf_filter-label viwcpf_dropdown"
                    placeholder="<?php echo esc_attr( 'Choose/Search...' ); ?>"
                    data-filter_multiple="<?php echo esc_attr( $multiselect ); ?>"
				<?php echo esc_attr( $data_multi_relation ); ?>
                    data-show_search="<?php echo esc_attr( $show_search ); ?>"
				<?php echo esc_attr( $multiple ); ?>
            >
				<?php
				if ( $multiselect == 'no' ) {
					$key_filter_arr = explode( ',', $obj_info_block_filter_menu['key_filter'] );
					$link_all       = $current_screen_url;
					foreach ( $key_filter_arr as $key_filter_item ) {
						$link_all = remove_query_arg( $key_filter_item, $link_all );
					}
					?>
                    <option class="viwcpf_filter-item select" data-filter_url="<?php echo esc_url( $link_all ); ?>" value=""><?php esc_html_e( 'All', 'pofily-woo-product-filters' ); ?></option>
					<?php
				}

				foreach ( $list_label_items as $label_item ) {
					if ( $obj_info_block_filter_menu['show_count_item'] ) {
						$render_count = '<small class="viwcpf_item-count"> (' . esc_html($label_item['count']) . ')</small>';
					}
					if ( $label_item['class_name'] == 'viwcpf_chosen' ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>

                    <option class="viwcpf_filter-item <?php echo esc_attr( $label_item['class_name'] ); ?>"
                            data-filter_url="<?php echo esc_url( $label_item['link'] ); ?>"
                            data-count=""
                            value="<?php echo esc_attr( $label_item['value'] ); ?>"
						<?php echo esc_attr( $selected ); ?>
                    ><?php echo wp_kses_post( $label_item['label'] ); ?><?php echo wp_kses_post( $render_count ); ?></option>
					<?php
				}
				?>
            </select>
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