<?php
/* The file has a Filter Menu Detail page template
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/admin/partials
 * */

wp_nonce_field( 'viwcpf_save_filter_menu', '_viwcpf_filter_menu_nonce' );
$viwcpf_filter_menu = get_post_meta( $post->ID, 'viwcpf_filter_menu', true );

$VIWCPF_Woo_Product_Filters_Data_default = new VIWCPF_Woo_Product_Filters_Data;
if (
	$post->post_status != 'auto-draft'
) {
	$viwcpf_blocks_selected    = $viwcpf_filter_menu['viwcpf_blocks_selected'];
	$viwcpf_using_ajax         = $viwcpf_filter_menu['viwcpf_using_ajax'];
	$viwcpf_show_button_submit = $viwcpf_filter_menu['viwcpf_show_button_submit'];
	$viwcpf_block_relation     = $viwcpf_filter_menu['viwcpf_block_relation'];
	$viwcpf_show_in_modal      = $viwcpf_filter_menu['viwcpf_show_in_modal'];
	$viwcpf_display_conditions = $viwcpf_filter_menu['viwcpf_display_conditions'];

	if ( $viwcpf_blocks_selected != '' ) {
		$arr_blocks_selected = explode( ",", $viwcpf_blocks_selected );
	} else {
		$arr_blocks_selected = array( '' );
	}
} else {
	$arr_blocks_selected       = array( '' );
	$viwcpf_blocks_selected    = '';
	$viwcpf_using_ajax         = true;
	$viwcpf_block_relation     = 'AND';
	$viwcpf_show_button_submit = false;
	$viwcpf_show_in_modal      = true;
	$viwcpf_display_conditions = array();

}
?>
<div class="vi-ui grid segment detail_wrap_setting">
    <!--Filter Name-->
    <div class="vi-ui row two no-wrap">
        <div class="column three wide column_label">
            <label class="label-setting"><?php esc_html_e( 'Choose Filter Block:', 'pofily-woo-product-filters' ) ?></label>
        </div>
        <div class="column twelve wide">
            <div id="wrap_choose_block_menu" class="wrap_choose_block_menu">
                <div class="wrap_choose_block_menu_column">
                    <div id="dragable_block_select" class="vi-ui ">
						<?php
						$args_block1           = array(
							'post_type'      => 'viwcpf_filter_block',
							'post_status'    => 'publish',
							'post__not_in'   => $arr_blocks_selected,
							'posts_per_page' => - 1,
						);
						$filters_blocks_query1 = new WP_Query( $args_block1 );

						if ( $filters_blocks_query1->have_posts() ):
							// The Loop
							while ( $filters_blocks_query1->have_posts() ) : $filters_blocks_query1->the_post();
								?>
                                <div class="vi-ui segment item_block add_block"
                                     data-block_id="<?php echo esc_attr( get_the_ID() ); ?>"
                                     data-block_name="<?php echo esc_attr( get_the_title() ); ?>"
                                     data-block_url="<?php echo esc_attr( get_edit_post_link() ); ?>">
                                    <h4><?php esc_html_e( get_the_title(), 'pofily-woo-product-filters' ); ?></h4>
                                    <div class="wrapp_btn">
                                        <a href="<?php echo get_edit_post_link(); ?>"
                                           class="vi-ui edit_block icon blue button mini compact"> <i
                                                    class="edit icon"></i></a>
                                    </div>
                                </div>
							<?php
							endwhile;
						endif;
						// Reset Post Data
						wp_reset_postdata();
						?>
                    </div>
                </div>
                <i class="vi-ui icon big angle double right viwcpf_arrow_detail_menu"></i>
                <div class="wrap_choose_block_menu_column">
                    <div id="sortable_block_selected" class="vi-ui">
						<?php
						$args_block           = array(
							'post_type'      => 'viwcpf_filter_block',
							'post_status'    => 'publish',
							'post__in'       => $arr_blocks_selected,
							'orderby'        => 'post__in',
							'posts_per_page' => - 1,
						);
						$filters_blocks_query = new WP_Query( $args_block );

						if ( $filters_blocks_query->have_posts() ):
							// The Loop
							while ( $filters_blocks_query->have_posts() ) : $filters_blocks_query->the_post();
								?>
                                <div class="vi-ui segment item_block block_selected"
                                     data-block_id="<?php echo esc_attr( get_the_ID() ); ?>"
                                     data-block_name="<?php echo esc_attr( get_the_title() ); ?>"
                                     data-block_url="<?php echo esc_attr( get_edit_post_link() ); ?>">
                                    <h4><?php esc_html_e( get_the_title(), 'pofily-woo-product-filters' ); ?></h4>
                                    <div class="wrapp_btn">
                                        <a href="<?php echo get_edit_post_link(); ?>"
                                           class="vi-ui edit_block icon blue button mini compact"> <i
                                                    class="edit icon"></i></a>
                                        <a href="#" class="vi-ui del_block icon red button mini compact"> <i
                                                    class="minus icon"></i></a>
                                    </div>
                                </div>
							<?php
							endwhile;
						endif;
						// Reset Post Data
						wp_reset_postdata();
						?>
                    </div>
                    <input type="hidden" name="viwcpf_blocks_selected" id="viwcpf_blocks_selected"
                           value="<?php esc_html_e( $viwcpf_blocks_selected, 'pofily-woo-product-filters' ); ?>">
                </div>
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Choose block filter you want display', 'pofily-woo-product-filters' ) ?></span>
            <a href="#" class="vi-ui refresh_blocks_filter icon green button mini compact">
                <i class="redo icon"></i>
				<?php esc_html_e( 'Refresh', 'pofily-woo-product-filters' ) ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=viwcpf_filter_block' ) ); ?>"
               target="_blank" class="vi-ui create_blocks_filter icon green button mini compact">
                <i class="plus icon"></i><?php esc_html_e( 'Create block filter', 'pofily-woo-product-filters' ) ?>
            </a>

        </div>

    </div>

    <!--General Setting-->

    <!--Filter by submit button-->
    <div class="vi-ui row two no-wrap item_row">
        <div class="column eight wide">
            <div class="vi-ui two column grid no-wrap">
                <div class="column seven wide column_label">
                    <label class="label-setting"
                           for="viwcpf-show_button_submit"><?php esc_html_e( 'Using button to filter:', 'pofily-woo-product-filters' ) ?></label>
                </div>
                <div class="column nine wide column_field">
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox" name="viwcpf-show_button_submit"
                               id="viwcpf-show_button_submit" <?php if ( $viwcpf_show_button_submit ) {
							echo esc_attr( 'checked' );
						} ?>>
                        <label></label>
                    </div>
                    <span class="explanatory-text"><?php esc_html_e( 'Choose show a button "Apply Filter" to apply all filters', 'pofily-woo-product-filters' ); ?></span>
                </div>
            </div>
        </div>
        <div class="column eight wide">
            <div class="vi-ui two column grid no-wrap">
				<?php
				$modal_enabled = isset( get_option( 'viwcpf_setting_params' )['modal']['enabled'] ) ? true : false;
				if ( $modal_enabled ):
					?>

                    <div class="column seven wide column_label">
                        <label class="label-setting"><?php esc_html_e( 'Show menu in modal:', 'pofily-woo-product-filters' ) ?></label>
                    </div>
                    <div class="column nine wide column_field">
                        <div class="vi-ui toggle checkbox ">
                            <input type="checkbox" name="viwcpf-show_in_modal"
                                   id="viwcpf-show_in_modal" <?php if ( $viwcpf_show_in_modal ) {
								echo esc_attr( 'checked' );
							} ?> >
                            <label></label>
                        </div>
                        <span class="explanatory-text"><?php esc_html_e( 'Enable to show the menu filter in content modal', 'pofily-woo-product-filters' ) ?></span>
                    </div>

				<?php
				endif;
				?>
            </div>
        </div>

    </div>

    <div class="vi-ui row two no-wrap item_row">
        <div class="column three wide column_label">
            <label class="label-setting"
                   for=""><?php esc_html_e( 'Display Conditions:', 'pofily-woo-product-filters' ) ?></label>
        </div>
        <div class="column twelve wide column_field">
            <div class="conditions_wrap" id="conditions_list">
	            <?php
	            $VIWCPF_Woo_Product_Filters_Data_default->viwcpf_upgrade_button();
	            ?>
                <input type="hidden"
                       name="viwcpf-display_conditions[0][type]" value="include">
                <input type="hidden"

                       name="viwcpf-display_conditions[0][archive]" value="<?php echo esc_attr( 'all' ) ?>">


            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Add rule display conditions you want', 'pofily-woo-product-filters' ) ?></span>
        </div>
    </div>
</div>
