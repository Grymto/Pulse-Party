<?php
/* The file has a Filter Block Detail page template
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/admin/partials
 * */

wp_nonce_field( 'viwcpf_save_filter_block', '_viwcpf_filter_block_nonce' );
$viwcpf_filter_block   = get_post_meta( $post->ID, 'viwcpf_filter_block', true );
$viwcpf_setting_params = get_option( 'viwcpf_setting_params' );


?>
<div class="vi-ui grid segment detail_wrap_setting">
    <div class="vi-ui row two no-wrap">
        <!--Filter Name-->
        <div class="column eight wide">
            <div class="vi-ui field">
                <div class="vi-ui labeled input fluid">
                    <div class="vi-ui label label-setting"><?php esc_html_e( 'Filter Name*:', 'pofily-woo-product-filters' ) ?></div>
                    <input type="text" name="filter_block_name" id="filter_block_name"
                           placeholder="<?php esc_attr_e( 'Name of filter', 'pofily-woo-product-filters' ) ?>"
                           value="<?php esc_html_e( isset( $viwcpf_filter_block['name'] ) ? $viwcpf_filter_block['name'] : '' ) ?>"
                           required>
                </div>
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Name of filter', 'pofily-woo-product-filters' ); ?></span>
        </div>
        <!--Filter For-->
        <div class="column eight wide">
            <div class="vi-ui left labeled dropdown button fluid">
                <div class="vi-ui label label-setting"><?php esc_html_e( 'Filter For:', 'pofily-woo-product-filters' ) ?></div>

                <select name="viwcpf_filter_for" id="viwcpf_filter_for"
                        class="fluid viwcpf_filter_block_select viwcpf_filter_for">
                    <option value="filter_by_taxonomy" <?php if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_taxonomy' ) {
						echo esc_attr( 'selected' );
					} ?>>
						<?php esc_html_e( 'Filter By Taxonomy', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="filter_by_price" <?php if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_price' ) {
						echo esc_attr( 'selected' );
					} ?>>
						<?php esc_html_e( 'Filter By Price', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="filter_by_review" <?php if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_review' ) {
						echo esc_attr( 'selected' );
					} ?>>
						<?php esc_html_e( 'Filter By Review', 'pofily-woo-product-filters' ) ?>
                    </option>

                    <option value="filter_by_name_product" <?php if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_name_product' ) {
						echo esc_attr( 'selected' );
					} ?>>
						<?php esc_html_e( 'Filter By Name', 'pofily-woo-product-filters' ) ?>
                    </option>
                </select>
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Choose filter for', 'pofily-woo-product-filters' ); ?></span>

        </div>

    </div>
    <!--Filter By Item - Filter by Taxonomy-->
    <div class="vi-ui grid filter_by_item active" data-type="filter_by_taxonomy">
		<?php
		if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_taxonomy' ) {
			$filter_data          = $viwcpf_filter_block['filter_data'];
			$tax_name             = $filter_data['tax_name'];
			$arr_term_id          = isset( $filter_data['list_terms'] ) ? $filter_data['list_terms'] : array();
			$customize_terms_data = isset( $filter_data['customize_value'] ) ? $filter_data['customize_value'] : array();
			$tax_type_show        = $filter_data['type_show'];
			if ( ! empty( $arr_term_id ) ) {
				$arr_term_data = get_terms(
					array(
						'taxonomy' => $tax_name,
						'orderby'  => 'name',
						'order'    => 'ASC',
						'include'  => $arr_term_id
					)
				);
			} else {
				$arr_term_data = array();
			}

			$tax_multiselect       = $filter_data['multiselect'];
			$tax_multi_relation    = $filter_data['multi_relation'];
			$tax_show_count_item   = $filter_data['show_count_item'];
			$tax_show_search_field = $filter_data['show_search_field'];
			$tax_btn_style         = $filter_data['btn_style'];
			$tax_order_by          = $filter_data['order_by'];
			$tax_order_type        = $filter_data['order_type'];
		} else {
			$tax_name              = '';
			$arr_term_data         = array();
			$arr_term_id           = array();
			$customize_terms_data  = array();
			$tax_type_show         = '';
			$tax_multiselect       = false;
			$tax_multi_relation    = 'AND';
			$tax_show_count_item   = false;
			$tax_show_search_field = false;
			$tax_btn_style         = array();
			$tax_order_by          = 'name';
			$tax_order_type        = 'asc';
		}
		if ( get_option( 'viwcpf_setting_params' ) ) {
			$default_data_color_swatches = get_option( 'viwcpf_setting_params' )['color_swatches'];
			$default_btn_style           = $default_data_color_swatches['btn_style'];
			$default_btn_color_default   = $default_data_color_swatches['color_default'];
			$default_btn_style_width     = $default_btn_style['btn_width'];
			$default_btn_style_height    = $default_btn_style['btn_height'];
			$default_btn_border_radius   = $default_btn_style['btn_border_radius'];
			$default_btn_color_separator = $default_btn_style['btn_color_separator'];
		} else {
			$default_btn_style_width     = '';
			$default_btn_style_height    = '';
			$default_btn_border_radius   = '';
			$default_btn_color_separator = '';
			$default_btn_color_default   = '';
		}
		?>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="viwcpf_filter_tax"><?php esc_html_e( 'Choose taxonomy:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
				<?php
				//get all taxonomies of post type product
				$product_taxonomies = get_object_taxonomies( array( 'post_type' => 'product' ), 'objects' );
				//exclude special some taxonomy
				$exclude = array( 'product_type', 'product_visibility', 'product_shipping_class' );

				$array_product_tax = array();
				foreach ( $product_taxonomies as $key => $value ):
					if ( in_array( $key, $exclude ) ) {
						continue;
					}
					$array_product_tax["$key"] = array(
						'label' => $value->labels->name,
						'total' => wp_count_terms(
							array(
								'taxonomy'   => $key,
								'hide_empty' => true,
							)
						),
					);

				endforeach;
				?>
                <select name="viwcpf_filter_tax" id="viwcpf_filter_tax"
                        class="vi-ui dropdown fluid viwcpf_filter_block_select"
                        data-counts='<?php echo json_encode( $array_product_tax ); ?>'>
					<?php
					foreach ( $array_product_tax as $key => $value ):
						if ( $tax_name == esc_html( $key ) ) {
							$selected = 'selected';
						} else {
							$selected = '';
						}
						echo '<option value="' . esc_html( $key ) . '" ' . esc_attr($selected) . '>' . esc_html( $value['label'] ) . '</option>';
					endforeach;
					?>
                </select>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="viwcpf_input_search_term"><?php esc_html_e( 'Choose term:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <select class="viwcpf_input_search_term" id="viwcpf_input_search_term"
                        name="viwcpf_input_search_term[]"
                        multiple="multiple">
					<?php
					foreach ( $arr_term_data as $term_item ) {
						echo '<option value="' . esc_html( $term_item->term_id ) . '"  selected>' . esc_html( $term_item->name ) . '</option>';
					}
					?>
                </select>
                <button class="vi-ui green button mini"
                        id="tax-select_all"><?php esc_html_e( 'Add all', 'pofily-woo-product-filters' ) ?></button>
                <button class="vi-ui red button mini"
                        id="tax-remove_all"><?php esc_html_e( 'Remove all', 'pofily-woo-product-filters' ) ?></button>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="tax-type_show"><?php esc_html_e( 'Show type:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <select name="viwcpf_tax-type_show" id="tax-type_show"
                        class="vi-ui dropdown fluid viwcpf_filter_block_select">
                    <option value="button"
						<?php
						if ( $tax_type_show == 'button' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Button', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="checkbox"
						<?php
						if ( $tax_type_show == 'checkbox' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Checkbox', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="select"
						<?php
						if ( $tax_type_show == 'select' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Select', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="color_swatches"
						<?php
						if ( $tax_type_show == 'color_swatches' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Color Swatches', 'pofily-woo-product-filters' ) ?>
                    </option>

                </select>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select hidden " data-select="viwcpf_tax-type_show"
             data-type_show="color_swatches,images">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="tax-filter_type"><?php esc_html_e( 'Button template:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="tax-wrap_button_template">
                    <div class="item_style">
                        <label class="label-setting"
                               for="tax-btn_width"><?php esc_html_e( 'Width:', 'pofily-woo-product-filters' ) ?></label>
                        <div class="vi-ui right labeled fluid input">
                            <input type="number"
                                   name="tax-btn_width"
                                   id="tax-btn_width"
                                   placeholder="<?php esc_attr_e( 'Enter width', 'pofily-woo-product-filters' ) ?>"
                                   value="<?php echo esc_attr( isset( $tax_btn_style['btn_width'] ) ? $tax_btn_style['btn_width'] : $default_btn_style_width ); ?>"
                                   min="0"
                            >
                            <div class="vi-ui basic label"><?php esc_html_e( 'px', 'pofily-woo-product-filters' ) ?></div>
                        </div>
                    </div>
                    <div class="item_style">
                        <label class="label-setting"
                               for="tax-btn_height"
                        >
							<?php esc_html_e( 'Height:', 'pofily-woo-product-filters' ) ?>
                        </label>
                        <div class="vi-ui right labeled fluid input">
                            <input type="number"
                                   name="tax-btn_height"
                                   id="tax-btn_height"
                                   placeholder="<?php esc_attr_e( 'Enter height', 'pofily-woo-product-filters' ) ?>"
                                   min="0"
                                   value="<?php echo esc_attr( isset( $tax_btn_style['btn_height'] ) ? $tax_btn_style['btn_height'] : $default_btn_style_height ); ?>"
                            >
                            <div class="vi-ui basic label"><?php esc_html_e( 'px', 'pofily-woo-product-filters' ) ?></div>
                        </div>
                    </div>
                    <div class="item_style">
                        <label class="label-setting"
                               for="tax-btn_border_radius"
                        >
							<?php esc_html_e( 'Border radius:', 'pofily-woo-product-filters' ) ?>
                        </label>
                        <div class="vi-ui right fluid input">
                            <input type="text"
                                   name="tax-btn_border_radius"
                                   id="tax-btn_border_radius"
                                   placeholder="<?php esc_attr_e( 'Example: 10px or 10%', 'pofily-woo-product-filters' ) ?>"
                                   min="0"
                                   value="<?php echo esc_attr( isset( $tax_btn_style['btn_border_radius'] ) ? $tax_btn_style['btn_border_radius'] : $default_btn_border_radius ); ?>"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for=""><?php esc_html_e( 'Customize term:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="terms_wrap">
                    <div id="loading_table_term" class="vi-ui inverted dimmer">
                        <div class="vi-ui text loader"><?php esc_html_e( 'Loading', 'pofily-woo-product-filters' ) ?></div>
                    </div>
                    <table id="terms_list" class="vi-ui celled table" style="width:100%">
                        <thead>
                        <tr>
                            <th><?php esc_html_e( 'Name', 'pofily-woo-product-filters' ) ?></th>
                            <th><?php esc_html_e( 'New Label', 'pofily-woo-product-filters' ) ?></th>
                            <th><?php esc_html_e( 'Tooltip', 'pofily-woo-product-filters' ) ?> </th>
                            <th class="terms_item_option title_choose_color option_select hidden"
                                data-select="viwcpf_tax-type_show"
                                data-type_show="color_swatches"><?php esc_html_e( 'Choose Color', 'pofily-woo-product-filters' ) ?> </th>

                        </tr>
                        </thead>
                        <tbody>
						<?php
						if (
							( $arr_term_id != '' ) &&
							( sizeof( $arr_term_id ) > 0 ) &&
							( $customize_terms_data != '' ) &&
							( sizeof( $customize_terms_data ) > 0 )
						) {
							foreach ( $arr_term_id as $term_item_id ) {
								$item_term_label        = $customize_terms_data[ $term_item_id ]['old_label'];
								$item_term_new_label    = $customize_terms_data[ $term_item_id ]['new_label'];
								$item_term_tooltip      = $customize_terms_data[ $term_item_id ]['tooltip'];
								$item_term_color        = '';

								$item_term_color_output = '
                                        <div class="field">
                                            <div class="vi-ui input">
                                                <span class="color-picker"></span>
                                                <input type="text" 
                                                        class="color-text" 
                                                        name="viwcpf_term_color[' . esc_attr( $term_item_id ) . '][]" 
                                                        id="viwcpf_term' . esc_attr( $term_item_id ) . 'color1" 
                                                        placeholder="' . esc_attr__( 'Choose color', 'pofily-woo-product-filters' ) . '" 
                                                        value="' . esc_attr( $default_btn_color_default ) . '" 
                                                        data-color_default="' . esc_attr( $default_btn_color_default ) . '" 
                                                >
                                            </div>
                                            <div class="viwcpf_add_input">
                                                <a href="#" class="vi-ui addinputs icon green button mini compact"> <i class="plus icon"></i></a>
                                                <a href="#" class="vi-ui delinputs icon red button disabled mini compact"> <i class="minus icon"></i></a>
                                            </div>
                                        </div>';
								if (
									( $tax_type_show == 'color_swatches' )
								) {
									if ( $tax_type_show == 'color_swatches' ) {
										$item_term_color = isset( $customize_terms_data[ $term_item_id ]['color'] ) ? $customize_terms_data[ $term_item_id ]['color'] : array();
										if (
											( ! empty( $item_term_color ) ) &&
											( sizeof( $item_term_color ) > 0 )
										) {
											/*Reset value $item_term_color_output*/
											$item_term_color_output = '';
											$color_value = $item_term_color[0];
											$color_count = 1;
											if ( $color_value == '' ) {
												$color_value = $default_btn_color_default;
											}
											$item_term_color_output = '
                                                <div class="field">
                                                    <div class="vi-ui input">
                                                        <span class="color-picker"></span>
                                                        <input type="text" 
                                                               class="color-text" 
                                                               name="viwcpf_term_color[' . esc_attr( $term_item_id ) . '][]" 
                                                               id="viwcpf_term' . esc_attr( $term_item_id ) . 'color' . esc_attr( $color_count ) . '" 
                                                               placeholder="' . esc_attr__( 'Choose color', 'pofily-woo-product-filters' ) . '" 
                                                               value="' . esc_attr( $color_value ) . '"
                                                               data-color_default="' . esc_attr( $default_btn_color_default ) . '"
                                                            >
                                                    </div>
                                                  
                                                </div>
                                            ';

										}
									}

								}
								?>
                                <tr data-term_id="<?php esc_html_e( $term_item_id ); ?>"
                                    data-term_name="<?php esc_html_e( $item_term_label ); ?>">
                                    <td class="reorder sorting_1">
                                        <div class="wrap_td">
                                            <label class="label-setting"><?php esc_html_e( $item_term_label ); ?></label>
                                            <input type="hidden"
                                                   name="viwcpf_term<?php esc_html_e( $term_item_id ); ?>_old_label"
                                                   id="viwcpf_term<?php echo esc_attr( $term_item_id ); ?>_old_label"
                                                   value="<?php echo esc_attr( $item_term_label ); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="wrap_td">
                                            <div class="vi-ui input">
                                                <input type="text"
                                                       name="viwcpf_term<?php esc_html_e( $term_item_id ); ?>_new_label"
                                                       id="viwcpf_term<?php echo esc_attr( $term_item_id ); ?>_new_label"
                                                       value="<?php echo esc_attr( $item_term_new_label ); ?>"
                                                       placeholder="<?php esc_attr_e( 'New Label', 'pofily-woo-product-filters' ) ?>"
                                                >
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="vi-ui input ">
                                            <input type="text"
                                                   name="viwcpf_term<?php echo esc_attr( $term_item_id ); ?>_tooltip"
                                                   id="viwcpf_term<?php echo esc_attr( $term_item_id ); ?>_tooltip"
                                                   value="<?php echo esc_attr( $item_term_tooltip ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Enter Tooltip', 'pofily-woo-product-filters' ) ?>"
                                            >
                                        </div>
                                    </td>
                                    <td class="choose_color option_select hidden" data-select="viwcpf_tax-type_show"
                                        data-type_show="color_swatches">
										<?php echo wp_kses( $item_term_color_output, $this->expanded_alowed_tags() ); ?>
                                    </td>

                                </tr>
								<?php
							}
						}
						?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th><?php esc_html_e( 'Name', 'pofily-woo-product-filters' ) ?></th>
                            <th><?php esc_html_e( 'New Label', 'pofily-woo-product-filters' ) ?></th>
                            <th><?php esc_html_e( 'Tooltip', 'pofily-woo-product-filters' ) ?> </th>
                            <th class="terms_item_option title_choose_color option_select hidden"
                                data-select="viwcpf_tax-type_show"
                                data-type_show="color_swatches"><?php esc_html_e( 'Choose Color', 'pofily-woo-product-filters' ) ?> </th>

                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select hidden" data-select="viwcpf_tax-type_show"
             data-type_show="select">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="tax-show_search_field"><?php esc_html_e( 'Show Search Field:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" name="tax-show_search_field"
                           id="tax-show_search_field" <?php if ( $tax_show_search_field ) {
						echo esc_attr( 'checked' );
					} ?>>
                    <label></label>
                </div>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column eight wide">
                <div class="vi-ui two column grid no-wrap">
                    <div class="column four wide column_label column_label_child">
                        <label class="label-setting"
                               for="tax-order_by"><?php esc_html_e( 'Order by:', 'pofily-woo-product-filters' ) ?></label>
                    </div>
                    <div class="column twelve wide column_field column_field_child">
                        <select name="viwcpf_tax-order_by" id="tax-order_by"
                                class="vi-ui dropdown fluid viwcpf_filter_block_select">
                            <option value="name"
								<?php
								if ( $tax_order_by == 'name' ) {
									echo esc_attr( 'selected' );
								}
								?>
                            >
								<?php esc_html_e( 'Name', 'pofily-woo-product-filters' ) ?>
                            </option>
                            <option value="slug"
								<?php
								if ( $tax_order_by == 'slug' ) {
									echo esc_attr( 'selected' );
								}
								?>
                            >
								<?php esc_html_e( 'Slug', 'pofily-woo-product-filters' ) ?>
                            </option>
                            <option value="id"
								<?php
								if ( $tax_order_by == 'id' ) {
									echo esc_attr( 'selected' );
								}
								?>
                            >
								<?php esc_html_e( 'Id', 'pofily-woo-product-filters' ) ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column eight wide">
                <div class="vi-ui two column grid no-wrap">
                    <div class="column four wide column_label column_label_child">
                        <label class="label-setting"
                               for="tax-order_type"><?php esc_html_e( 'Order type:', 'pofily-woo-product-filters' ) ?></label>
                    </div>
                    <div class="column twelve wide column_field column_field_child">
                        <select name="viwcpf_tax-order_type" id="tax-order_type"
                                class="vi-ui dropdown fluid viwcpf_filter_block_select">
                            <option value="asc"
								<?php
								if ( $tax_order_type == 'asc' ) {
									echo esc_attr( 'selected' );
								}
								?>
                            >
								<?php esc_html_e( 'ASC', 'pofily-woo-product-filters' ) ?>
                            </option>
                            <option value="desc"
								<?php
								if ( $tax_order_type == 'desc' ) {
									echo esc_attr( 'selected' );
								}
								?>
                            >
								<?php esc_html_e( 'DESC', 'pofily-woo-product-filters' ) ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="vi-ui row two no-wrap item_row option_select" data-select="viwcpf_tax-type_show"
             data-type_show="button,checkbox,color_swatches">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="tax-show_count_items"><?php esc_html_e( 'Show count of items:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" name="tax-show_count_items"
                           id="tax-show_count_items" <?php if ( $tax_show_count_item ) {
						echo esc_attr( 'checked' );
					} ?>>
                    <label></label>
                </div>
            </div>
        </div>
        <!--div class="vi-ui row two no-wrap item_row option_select hidden" data-select="viwcpf_tax-type_show" data-type_show="checkbox">
            <div class="column three wide column_label">
                <label class="label-setting"><?php esc_html_e( 'Show hierarchy:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column twelve wide column_field">
                <div class="field">
                    <div class="vi-ui radio checkbox">
                        <input type="radio" name="tax-hierarchy" id="tax-hierarchy-no_show_all" value="tax-hierarchy-no_show_all">
                        <label for="tax-hierarchy-no_show_all">No, show all terms in same level</label>
                    </div>
                </div>
                <div class="field">
                    <div class="vi-ui radio checkbox">
                        <input type="radio" name="tax-hierarchy" id="tax-hierarchy-no_show_parent" value="tax-hierarchy-no_show_parent">
                        <label for="tax-hierarchy-no_show_parent">No, show only parent terms</label>
                    </div>
                </div>
                <div class="field">
                    <div class="vi-ui radio checkbox">
                        <input type="radio" name="tax-hierarchy" id="tax-hierarchy-yes_show_collapsed" value="tax-hierarchy-yes_show_collapsed">
                        <label for="tax-hierarchy-yes_show_collapsed">Yes, with terms collapsed</label>
                    </div>
                </div>
                <div class="field">
                    <div class="vi-ui radio checkbox">
                        <input type="radio" name="tax-hierarchy" id="tax-hierarchy-yes_show_expanded" value="tax-hierarchy-yes_show_expanded">
                        <label for="tax-hierarchy-yes_show_expanded">Yes, with terms expanded</label>
                    </div>
                </div>
            </div>
        </div-->
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="tax-multi_select"><?php esc_html_e( 'Allow multiple selection:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" name="tax-multi_select" id="tax-multi_select" <?php if ( $tax_multiselect ) {
						echo esc_attr( 'checked' );
					} ?> >
                    <label></label>
                </div>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select" data-select="tax-multi_select" data-type_show="true">
            <div class="column three wide column_label">
                <label class="label-setting"><?php esc_html_e( 'Multiselect relation:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field vi-ui form">
                <div class="grouped fields">
                    <div class="field">
                        <div class="vi-ui toggle checkbox">
                            <input type="radio" name="tax-multi_relation" id="tax-multi_relation-and"
                                   value="AND" <?php if ( $tax_multi_relation == "AND" ) {
								echo esc_attr( 'checked' );
							} ?> >
                            <label for="tax-multi_relation-and"><?php esc_html_e( 'And', 'pofily-woo-product-filters' ) ?></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="vi-ui toggle checkbox">
                            <input type="radio" name="tax-multi_relation" id="tax-multi_relation-or"
                                   value="OR" <?php if ( $tax_multi_relation == "OR" ) {
								echo esc_attr( 'checked' );
							} ?>>
                            <label for="tax-multi_relation-or"><?php esc_html_e( 'Or', 'pofily-woo-product-filters' ) ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Filter By Item - Filter by Price-->
    <div class="vi-ui grid filter_by_item " data-type="filter_by_price">
		<?php
		if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_price' ) {
			$filter_data           = $viwcpf_filter_block['filter_data'];
			$price_type_filter     = $filter_data['type_filter'];
			$price_type_show       = $filter_data['type_show'];
			$price_show_count_item = $filter_data['show_count_item'];
			if ( $price_type_filter == 'price_range' ) {
				$price_customize = $filter_data['customize_value'];
			} else {
				$price_customize = $filter_data['customize_value'];
			}
		} else {
			$price_type_filter     = '';
			$price_type_show       = '';
			$price_customize       = array();
			$price_show_count_item = false;
		}
		?>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="price-type_filter"><?php esc_html_e( 'Type Filter:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <select name="viwcpf_price-type_filter" id="price-type_filter"
                        class="vi-ui dropdown fluid viwcpf_filter_block_select">
                    <option value="range_slide" selected >
						<?php esc_html_e( 'Price Range Slider', 'pofily-woo-product-filters' ) ?>
                    </option>

                </select>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select hidden" data-select="viwcpf_price-type_filter"
             data-type_show="range_slide">
			<?php
			if ( $price_type_filter == 'range_slide' ) {
				$min_price  = isset( $price_customize['min_price'] ) ? $price_customize['min_price'] : '';
				$max_price  = isset( $price_customize['max_price'] ) ? $price_customize['max_price'] : '';
				$step_price = isset( $price_customize['step_price'] ) ? $price_customize['step_price'] : '';
			} else {
				$min_price  = '';
				$max_price  = '';
				$step_price = '';
			}
			?>
            <div class="column three wide column_label">
                <label class="label-setting"
                       for=""><?php esc_html_e( 'Price range slider value:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="price-wrap_slider_value">
                    <div class="item_style">
                        <div class="vi-ui left labeled fluid input">
                            <label class="vi-ui label label-setting"
                                   for="price-slide_min"><?php esc_html_e( 'Min (' . get_woocommerce_currency_symbol() . '):', 'pofily-woo-product-filters' ) ?></label>
							<?php ?>
                            <input type="number" name="price-slide_min" id="price-slide_min"
                                   placeholder="<?php esc_attr_e( 'Amount', 'pofily-woo-product-filters' ) ?>"
                                   min="0" step="0.1"
                                   value="<?php echo esc_attr( $min_price ); ?>">
                        </div>
                    </div>
                    <div class="item_style">
                        <div class="vi-ui left labeled fluid input">
                            <label class="vi-ui label label-setting"
                                   for="price-slide_max"><?php esc_html_e( 'Max (' . get_woocommerce_currency_symbol() . '):', 'pofily-woo-product-filters' ) ?></label>
                            <input type="number" name="price-slide_max" id="price-slide_max"
                                   placeholder="<?php esc_attr_e( 'Amount', 'pofily-woo-product-filters' ) ?>"
                                   min="0" step="0.1"
                                   value="<?php echo esc_attr( $max_price ); ?>">
                        </div>
                    </div>
                    <div class="item_style">
                        <div class="vi-ui left labeled fluid input">
                            <label class="vi-ui label label-setting"
                                   for="price-slide_step"><?php esc_html_e( 'Price Range Slider step:', 'pofily-woo-product-filters' ) ?></label>
                            <input type="number" name="price-slide_step" id="price-slide_step"
                                   placeholder="<?php esc_attr_e( 'Amount', 'pofily-woo-product-filters' ) ?>"
                                   min="0" step="0.1"
                                   value="<?php echo esc_attr( $step_price ); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Filter By Item - Filter by Review-->
    <div class="vi-ui grid filter_by_item " data-type="filter_by_review">
		<?php
		if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_review' ) {
			$filter_data            = $viwcpf_filter_block['filter_data'];
			$review_type_show       = $filter_data['type_show'];
			$review_show_count_item = $filter_data['show_count_item'];
			if ( $review_type_show == 'icon_star' ) {
				$review_show_icon_star = false;
			} else {
				$review_show_icon_star = $filter_data['show_count_item'];
			}
		} else {
			$review_type_show       = '';
			$review_show_count_item = false;
			$review_show_icon_star  = false;
		}
		?>
        <div class="vi-ui row two no-wrap item_row">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="review-type_show"><?php esc_html_e( 'Show type:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <select name="viwcpf_review-type_show" id="review-type_show"
                        class="vi-ui dropdown fluid viwcpf_filter_block_select">
                    <option value="button"
						<?php
						if ( $review_type_show == 'button' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Button', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="select"
						<?php
						if ( $review_type_show == 'select' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Select', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <!--option value="icon_star"
                        <?php
					if ( $review_type_show == 'icon_star' ) {
						echo esc_attr( 'selected' );
					}
					?>
                    >
                        <?php esc_html_e( 'Star icon', 'pofily-woo-product-filters' ) ?>
                    </option-->
                </select>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select" data-select="viwcpf_review-type_show"
             data-type_show="button">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="review-show_icon_star"><?php esc_html_e( 'Show star icon:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" name="review-show_icon_star"
                           id="review-show_icon_star" <?php if ( $review_show_icon_star ) {
						echo esc_attr( 'checked' );
					} ?> >
                    <label></label>
                </div>
            </div>
        </div>
        <div class="vi-ui row two no-wrap item_row option_select" data-select="viwcpf_review-type_show"
             data-type_show="button,select">
            <div class="column three wide column_label">
                <label class="label-setting"
                       for="review-show_count_items"><?php esc_html_e( 'Show count of items:', 'pofily-woo-product-filters' ) ?></label>
            </div>
            <div class="column fourteen wide column_field">
                <div class="vi-ui toggle checkbox">
                    <input type="checkbox" name="review-show_count_items"
                           id="review-show_count_items" <?php if ( $review_show_count_item ) {
						echo esc_attr( 'checked' );
					} ?> >
                    <label></label>
                </div>
            </div>
        </div>
    </div>
    <!--Filter By Item - Filter by Name-->
    <div class="vi-ui grid filter_by_item " data-type="filter_by_name_product">
		<?php
		if ( ! empty( $viwcpf_filter_block['filter_for'] ) && $viwcpf_filter_block['filter_for'] == 'filter_by_name_product' ) {
			$filter_data        = $viwcpf_filter_block['filter_data'];
			$placeholder_search = $filter_data['placeholder_search'];
		} else {
			$placeholder_search = '';
		}
		?>
        <div class="vi-ui row no-wrap">
            <div class="column wide">
                <div class="vi-ui labeled input fluid">
                    <div class="vi-ui label label-setting"><?php esc_html_e( 'Placeholder text:', 'pofily-woo-product-filters' ) ?></div>
                    <input type="text" name="viwcpf-name-placeholder" id="viwcpf-name-placeholder"
                           placeholder="<?php esc_attr_e( 'Enter placeholder', 'pofily-woo-product-filters' ) ?>"
                           value="<?php echo esc_attr( $placeholder_search ); ?>">
                </div>
                <span class="explanatory-text"><?php esc_html_e( 'Enter the placeholder for search field, it will be displayed on frontend', 'pofily-woo-product-filters' ) ?></span>
            </div>
        </div>
    </div>

   <!--General Setting-->
    <!--Display type-->
	<?php
	$viwcpf_filter_block_setting = isset( $viwcpf_filter_block['settings'] ) ? $viwcpf_filter_block['settings'] : '';
	if ( $viwcpf_filter_block_setting != '' ) {
		$display_type    = $viwcpf_filter_block_setting['display_type'];
		$show_clear      = $viwcpf_filter_block_setting['show_clear'];
		$show_view_more  = $viwcpf_filter_block_setting['show_view_more'];
		$view_more_limit = $viwcpf_filter_block_setting['view_more_limit'];
		$show_as_toggle  = $viwcpf_filter_block_setting['show_as_toggle'];
		$toggle_style    = $viwcpf_filter_block_setting['toggle_style'];
	} else {
		$display_type    = '';
		$show_clear      = false;
		$show_view_more  = false;
		$view_more_limit = 10;
		$show_as_toggle  = false;
		$toggle_style    = 'toggle_style-opened';
	}
	?>
    <div class="vi-ui row two no-wrap global_setting_conditional">
        <div class="column three wide column_label">
            <label class="label-setting"
                   for="viwcpf_display-type"><?php esc_html_e( 'Display type:', 'pofily-woo-product-filters' ) ?></label>
        </div>
        <div class="column fourteen wide column_field">
            <select name="viwcpf_display-type" id="viwcpf_display-type"
                    class="vi-ui dropdown fluid viwcpf_filter_block_select">
                <option value="vertical"
					<?php
					if ( $display_type == 'vertical' ) {
						echo esc_attr( 'selected' );
					}
					?>
                >
					<?php esc_html_e( 'Vertical', 'pofily-woo-product-filters' ) ?>
                </option>
                <option value="horizontal"
					<?php
					if ( $display_type == 'horizontal' ) {
						echo esc_attr( 'selected' );
					}
					?>
                >
					<?php esc_html_e( 'Horizontal', 'pofily-woo-product-filters' ) ?>
                </option>
            </select>
            <span class="explanatory-text"><?php esc_html_e( 'Choose the display type for your filter block', 'pofily-woo-product-filters' ) ?></span>
        </div>
    </div>
    <!--Show clear-->
    <div class="vi-ui row two no-wrap ">
        <div class="column three wide column_label">
            <label class="label-setting"
                   for="viwcpf-show_clear"><?php esc_html_e( 'Show "clear" button:', 'pofily-woo-product-filters' ) ?></label>
        </div>
        <div class="column fourteen wide column_field">
            <div class="vi-ui toggle checkbox">
                <input type="checkbox" name="viwcpf-show_clear" id="viwcpf-show_clear" <?php if ( $show_clear ) {
					echo esc_attr( 'checked' );
				} ?>>
                <label></label>
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Enable to show the "Clear" link above the filter block', 'pofily-woo-product-filters' ) ?></span>
        </div>
    </div>
    <!--Show as toggle-->
    <div class="vi-ui row two no-wrap">
        <div class="column eight wide">
            <div class="vi-ui two column grid no-wrap">
                <div class="column four wide column_label column_label_child">
                    <label class="label-setting"
                           for="viwcpf-show_clear"><?php esc_html_e( 'Show as toggle:', 'pofily-woo-product-filters' ) ?></label>
                </div>
                <div class="column twelve wide column_field column_field_child">
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox" name="viwcpf-show_as_toggle"
                               id="viwcpf-show_as_toggle" <?php if ( $show_as_toggle ) {
							echo esc_attr( 'checked' );
						} ?>>
                        <label></label>
                    </div>
                    <span class="explanatory-text"><?php esc_html_e( 'Enable if you want to show this filter as a toggle', 'pofily-woo-product-filters' ); ?></span>
                </div>
            </div>
        </div>
        <!--Toggle style-->
        <div class="column eight wide option_select hidden" data-select="viwcpf-show_as_toggle" data-type_show="true">
            <div class="vi-ui left labeled dropdown button fluid">
                <div class="vi-ui label label-setting"><?php esc_html_e( 'Toggle style:', 'pofily-woo-product-filters' ) ?></div>
                <select name="viwcpf_toggle_style"
                        id="viwcpf_toggle_style"
                        class=" fluid viwcpf_filter_block_select viwcpf_toggle_style">
                    <option value="toggle_style-opened"
						<?php
						if ( $toggle_style == 'toggle_style-opened' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Opened by default', 'pofily-woo-product-filters' ) ?>
                    </option>
                    <option value="toggle_style-closed"
						<?php
						if ( $toggle_style == 'toggle_style-closed' ) {
							echo esc_attr( 'selected' );
						}
						?>
                    >
						<?php esc_html_e( 'Closed by default', 'pofily-woo-product-filters' ) ?>
                    </option>
                </select>
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Choose if toggle has to closed or opened by default', 'pofily-woo-product-filters' ); ?></span>
        </div>
    </div>
    <!--Show view more-->
    <div class="vi-ui row two no-wrap global_setting_conditional">
        <div class="column eight wide">
            <div class="vi-ui two column grid no-wrap">
                <div class="column four wide column_label column_label_child">
                    <label class="label-setting"
                           for="viwcpf-show_view_more"><?php esc_html_e( 'Show "view more" button:', 'pofily-woo-product-filters' ) ?></label>
                </div>
                <div class="column twelve wide column_field column_field_child">
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox" name="viwcpf-show_view_more"
                               id="viwcpf-show_view_more" <?php if ( $show_view_more ) {
							echo esc_attr( 'checked' );
						} ?>>
                        <label></label>
                    </div>
                    <span class="explanatory-text"><?php esc_html_e( 'Show view more item (Only works if show type is button or checkbox)', 'pofily-woo-product-filters' ) ?></span>
                </div>
            </div>
        </div>
        <!--Label display limit-->
        <div class="column eight wide option_select hidden" data-select="viwcpf-show_view_more" data-type_show="true">
            <div class="vi-ui left labeled input fluid">
                <label class="vi-ui label label-setting"
                       for="viwcpf-label_limit"><?php esc_html_e( 'Label display limit:', 'pofily-woo-product-filters' ) ?></label>
                <input type="number" name="viwcpf-label_limit" id="viwcpf-label_limit"
                       placeholder="<?php esc_attr_e( 'Default value is 10', 'pofily-woo-product-filters' ); ?>"
                       min="1"
                       value="<?php echo esc_attr( $view_more_limit ); ?>">
            </div>
            <span class="explanatory-text"><?php esc_html_e( 'Enter items number limit you want to display', 'pofily-woo-product-filters' ) ?></span>
        </div>
    </div>
</div>
<!--<div class="vi-ui grid segment detail_wrap_preview">-->
<!---->
<!--</div>-->

