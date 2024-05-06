<?php
/**
 * Template filters search_field
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
$input_hidden               = array();
$obj_info_block_filter_menu = $this->viwcpf_render_value_block_filter( $filter_name, $filter_for, $filter_data, $filter_setting );
$toggle_class_name          = $this->class_show_as_toggle( $filter_setting );
$list_value                 = $obj_info_block_filter_menu['list_value'];
$arr_value                  = $list_value['value'];
$input_hidden               = $obj_info_block_filter_menu['input_hidden'];
$key_filter                 = $obj_info_block_filter_menu['key_filter'];
$show_clear_btn             = $obj_info_block_filter_menu['show_clear_btn'] ? 'yes' : 'no';
?>

<div class="viwcpf_wrap_filter-content" data-filter_by="<?php echo esc_attr( $filter_for ); ?>"
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
        <div class="viwcpf_filter viwcpf_search_field"
             data-filter_for="<?php echo esc_attr( $filter_for ); ?>">
            <a href="javascript:void(0);" class="viwcpf_search_reset" disabled="disabled"><span
                        class="dashicons dashicons-dismiss"></span></a>
            <input type="search" class="viwcpf_show_text_search viwcpf_text_search"
                   placeholder="<?php echo esc_attr( $arr_value['placeholder'] ); ?>" name=""
                   value="<?php echo esc_attr( $arr_value['search_value'] ); ?>">
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
