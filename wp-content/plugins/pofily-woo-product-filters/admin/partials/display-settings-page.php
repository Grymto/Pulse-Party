<?php
/* The file has a General Settings Page template
 *
 * @package    VIWCPF_Woo_Product_Filters
 * @subpackage VIWCPF_Woo_Product_Filters/admin/partials
 * */
?>
    <div class="wrap woo-viwcpf">
        <h1><?php esc_html_e( 'Woocommerce Product Filters Settings', 'pofily-woo-product-filters' ) ?></h1>
        <form method="POST" action="" class="vi-ui form">
			<?php
			$VIWCPF_Woo_Product_Filters_Data_default = new VIWCPF_Woo_Product_Filters_Data;
			$viwcpf_setting_params_default           = $VIWCPF_Woo_Product_Filters_Data_default->get_default();
			$viwcpf_setting_params                   = get_option( 'viwcpf_setting_params' ) ? get_option( 'viwcpf_setting_params' ) : $viwcpf_setting_params_default;
			/* outputs false */
			wp_nonce_field( 'viwcpf_save_filter_setting', '_viwcpf_filter_setting_nonce' );

			?>
            <div class="vi-ui top attached tabular menu">
                <div class="item active" data-tab="general">
                    <label><?php esc_html_e( 'General', 'pofily-woo-product-filters' ) ?></label>
                </div>
                <div class="item" data-tab="style">
                    <label><?php esc_html_e( 'Customize', 'pofily-woo-product-filters' ) ?></label>
                </div>
            </div>
            <div class="vi-ui bottom attached segment tab active" data-tab="general">

                <h2><?php esc_html_e( 'General setting', 'pofily-woo-product-filters' ) ?></h2>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Filter Modal', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox"
                                       class="viwcpf_modal"
                                       name="viwcpf_setting[modal][enabled]"
                                       id="viwcpf_filter_modal_enabled"
									<?php
									if ( isset( $viwcpf_setting_params['modal']['enabled'] ) ) {
										echo esc_attr( 'checked' );
									}
									?>
                                >
                                <label for="viwcpf_filter_modal_enabled"></label>
                            </div>
                            <span class="explanatory-text"><?php esc_html_e( 'Enable model for filter', 'pofily-woo-product-filters' ); ?></span>
                        </td>
                    </tr>
                    <tr class="option_select " data-select="viwcpf_modal" data-type_show="true">
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Modal auto open', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox"
                                       name="viwcpf_setting[modal][auto_open]"
									<?php if ( ! empty( $viwcpf_setting_params['modal']['auto_open'] ) ) {
										echo esc_attr( 'checked' );
									} ?>
                                >
                                <label for="show_active_labels"></label>
                            </div>
                            <span class="explanatory-text"><?php esc_html_e( 'Auto open filter modal after page load if this was opened before', 'pofily-woo-product-filters' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Show active filters', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox" class="show_active_labels"
                                       name="viwcpf_setting[show_active_labels]"
                                       id="show_active_labels"
									<?php if ( ! empty( $viwcpf_setting_params['show_active_labels'] ) ) {
										echo esc_attr( 'checked' );
									} ?>
                                >
                                <label for="show_active_labels"></label>
                            </div>
                            <span class="explanatory-text"><?php esc_html_e( 'Show active filters as labels', 'pofily-woo-product-filters' ); ?></span>
                        </td>
                    </tr>
                    <tr class="option_select hidden" data-select="show_active_labels" data-type_show="true">
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Active filters labels position', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <select name="viwcpf_setting[active_position]"
                                    class="vi-ui dropdown  viwcpf_filter_block_select ">
                                <option value="<?php echo esc_attr( 'before_filters' ) ?>"
									<?php
									if (
										isset( $viwcpf_setting_params['active_position'] ) &&
										( $viwcpf_setting_params['active_position'] === 'before_filters' )
									) {
										echo esc_attr( 'selected' );
									}
									?>
                                >
									<?php esc_html_e( 'Before filters (default)', 'pofily-woo-product-filters' ) ?>
                                </option>
                                <option value="<?php echo esc_attr( 'after_filters' ) ?>"
									<?php
									if (
										isset( $viwcpf_setting_params['active_position'] ) &&
										( $viwcpf_setting_params['active_position'] === 'after_filters' )
									) {
										echo esc_attr( 'selected' );
									}
									?>
                                >
									<?php esc_html_e( 'After filters', 'pofily-woo-product-filters' ) ?>
                                </option>
                                <option value="<?php echo esc_attr( 'before_products' ) ?>"
									<?php
									if (
										isset( $viwcpf_setting_params['active_position'] ) &&
										( $viwcpf_setting_params['active_position'] === 'before_products' )
									) {
										echo esc_attr( 'selected' );
									}
									?>
                                >
									<?php esc_html_e( 'Above products list', 'pofily-woo-product-filters' ) ?>
                                </option>

                            </select>
                            <span class="explanatory-text"><?php esc_html_e( 'Choose active filters labels position', 'pofily-woo-product-filters' ); ?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="vi-ui bottom attached segment tab" data-tab="style">
                <h2><?php esc_html_e( 'Customize setting', 'pofily-woo-product-filters' ) ?></h2>
                <table class="form-table option_select " data-select="viwcpf_modal" data-type_show="true">
                    <tbody>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Modal style', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui form">
                                <div class="five fields">
                                    <div class="field viwcpf_setting_filter_style">
										<?php
										$VIWCPF_Woo_Product_Filters_Data_default->viwcpf_upgrade_button();
										?>
                                        <input type="radio"
                                               class="viwcpf_modal_filter_style" style="display: none !important;"
                                               name="viwcpf_setting[modal][style]"
                                               id="viwcpf_filter_style1"
                                               value="off_canvas"
                                               checked
                                        >
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Icon position', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui form">
                                <div class="six fields">
                                    <div class="field viwcpf_setting_filter_style">
                                        <img src="<?php echo esc_attr( VIWCPF_FREE_ADMIN_IMG_URL . 'top-left.png' ); ?>">
                                        <div class="vi-ui toggle checkbox checked center aligned segment ">
                                            <input type="radio"
                                                   name="viwcpf_setting[modal][icon_position]"
                                                   id="viwcpf_filter_icon_position1"
                                                   value="top_left"
												<?php
												if ( isset( $viwcpf_setting_params['modal']['icon_position'] ) && $viwcpf_setting_params['modal']['icon_position'] == 'top_left' ) {
													echo esc_attr( 'checked' );
												}
												?>
                                            >
                                            <label for="viwcpf_filter_icon_position1"><?php esc_html_e( 'Top left', 'pofily-woo-product-filters' ); ?></label>
                                        </div>
                                    </div>
                                    <div class="field viwcpf_setting_filter_style">
                                        <img src="<?php echo esc_attr( VIWCPF_FREE_ADMIN_IMG_URL . 'bottom-left.png' ); ?>">
                                        <div class="vi-ui toggle checkbox checked center aligned segment ">
                                            <input type="radio"
                                                   name="viwcpf_setting[modal][icon_position]"
                                                   id="viwcpf_filter_icon_position2"
                                                   value="bottom_left"
												<?php
												if ( isset( $viwcpf_setting_params['modal']['icon_position'] ) && $viwcpf_setting_params['modal']['icon_position'] == 'bottom_left' ) {
													echo esc_attr( 'checked' );
												}
												?>
                                            >
                                            <label for="viwcpf_filter_icon_position2"><?php esc_html_e( 'Bottom left', 'pofily-woo-product-filters' ); ?></label>
                                        </div>
                                    </div>
                                    <div class="field viwcpf_setting_filter_style">
                                        <img src="<?php echo esc_attr( VIWCPF_FREE_ADMIN_IMG_URL . 'top-right.png' ); ?>">
                                        <div class="vi-ui toggle checkbox checked center aligned segment ">
                                            <input type="radio"
                                                   name="viwcpf_setting[modal][icon_position]"
                                                   id="viwcpf_filter_icon_position3"
                                                   value="top_right"
												<?php
												if ( isset( $viwcpf_setting_params['modal']['icon_position'] ) && $viwcpf_setting_params['modal']['icon_position'] == 'top_right' ) {
													echo esc_attr( 'checked' );
												}
												?>
                                            >
                                            <label for="viwcpf_filter_icon_position3"><?php esc_html_e( 'Top right', 'pofily-woo-product-filters' ); ?></label>
                                        </div>
                                    </div>
                                    <div class="field viwcpf_setting_filter_style">
                                        <img src="<?php echo esc_attr( VIWCPF_FREE_ADMIN_IMG_URL . 'bottom-right.png' ); ?>">
                                        <div class="vi-ui toggle checkbox checked center aligned segment ">
                                            <input type="radio"
                                                   name="viwcpf_setting[modal][icon_position]"
                                                   id="viwcpf_filter_icon_position4"
                                                   value="bottom_right"
												<?php
												if ( isset( $viwcpf_setting_params['modal']['icon_position'] ) && $viwcpf_setting_params['modal']['icon_position'] == 'bottom_right' ) {
													echo esc_attr( 'checked' );
												}
												?>
                                            >
                                            <label for="viwcpf_filter_icon_position4"><?php esc_html_e( 'Bottom right', 'pofily-woo-product-filters' ); ?></label>
                                        </div>
                                    </div>
                                    <div class="field viwcpf_setting_filter_style">
                                        <img src="<?php echo esc_attr( VIWCPF_FREE_ADMIN_IMG_URL . 'top-product-loop.png' ); ?>">
                                        <div class="vi-ui toggle checkbox checked center aligned segment ">
                                            <input type="radio"
                                                   name="viwcpf_setting[modal][icon_position]"
                                                   id="viwcpf_filter_icon_position5"
                                                   value="top_product_loop"
												<?php
												if ( isset( $viwcpf_setting_params['modal']['icon_position'] ) && $viwcpf_setting_params['modal']['icon_position'] == 'top_product_loop' ) {
													echo esc_attr( 'checked' );
												}
												?>
                                            >
                                            <label for="viwcpf_filter_icon_position5"><?php esc_html_e( 'Top product loop', 'pofily-woo-product-filters' ); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <span class="explanatory-text"><?php esc_html_e( 'Choose icon open modal position', 'pofily-woo-product-filters' ); ?></span>
                            </div>


                        </td>
                    </tr>
                    <tr class="option_select hidden" data-select="viwcpf_modal_filter_style"
                        data-type_show="off_canvas">
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Off canvas Position', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
							<?php
							$VIWCPF_Woo_Product_Filters_Data_default->viwcpf_upgrade_button();
							?>
                            <input type="hidden" name="viwcpf_setting[off_canvas][general][position]"
                                   class="vi-ui dropdown  viwcpf_filter_block_select off_canvas_position"
                                   value="top_left">
                        </td>
                    </tr>
                    <tr class="option_select hidden" data-select="viwcpf_modal_filter_style"
                        data-type_show="off_canvas">
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Off canvas effect open', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
							<?php
							$VIWCPF_Woo_Product_Filters_Data_default->viwcpf_upgrade_button();
							?>
                            <input type="hidden" name="viwcpf_setting[off_canvas][general][effect_open]"
                                   class="vi-ui dropdown viwcpf_filter_block_select " value="slide">

                        </td>
                    </tr>

                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Modal icon style', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui vertical segment">
								<?php
								preg_match( '!\d+!', $viwcpf_setting_params['modal']['icon']['horizontal'], $value_horizontal );
								preg_match( '!\d+!', $viwcpf_setting_params['modal']['icon']['vertical'], $value_vertical );
								if (
									! empty( $value_horizontal ) &&
									is_array( $value_horizontal )
								) {
									$horizontal_number            = $value_horizontal[0];
									$modal_icon_symbol_horizontal = str_replace( $horizontal_number, '', $viwcpf_setting_params['modal']['icon']['horizontal'] );
								} else {
									$horizontal_number            = 0;
									$modal_icon_symbol_horizontal = '%';
								}
								if (
									! empty( $value_vertical ) &&
									is_array( $value_vertical )
								) {
									$vertical_number            = $value_vertical[0];
									$modal_icon_symbol_vertical = str_replace( $vertical_number, '', $viwcpf_setting_params['modal']['icon']['vertical'] );
								} else {
									$vertical_number            = 0;
									$modal_icon_symbol_vertical = '%';
								}
								?>
                                <!--Default-->
                                <div class="vi-ui segment">

                                    <label class="vi-ui top attached label"> <?php esc_html_e( 'Default', 'pofily-woo-product-filters' ) ?></label>

                                    <div class="vi-ui basic segment">
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field">
                                                <label><?php esc_html_e( 'Modal icon shadow', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="checkbox"
                                                           id="viwcpf_modal_icon_shadown"
                                                           name="viwcpf_setting[modal][icon][box_shadow]" <?php if ( isset( $viwcpf_setting_params['modal']['icon']['box_shadow'] ) ) {
														echo esc_attr( 'checked' );
													} ?>>
                                                    <label for="viwcpf_modal_icon_shadown"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Border radius', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled  input">
                                                    <input type="number" min="0" max="50" class=""
                                                           name="viwcpf_setting[modal][icon][icon_radius]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['icon_radius'] ) ?>">
                                                    <div class="vi-ui label viwcpf-right-input-label"><?php esc_html_e( 'Px', 'pofily-woo-product-filters' ) ?></div>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_modal_icon">
                                                <label> <?php esc_html_e( 'Icon horizontal', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled  input">
                                                    <input type="number"
                                                           placeholder="<?php esc_attr_e( 'Enter number horizontal', 'pofily-woo-product-filters' ); ?>"
                                                           class="modal_number_value"
                                                           name=""
                                                           data-symbol="<?php echo esc_attr( $modal_icon_symbol_horizontal ) ?>"
                                                           value="<?php echo esc_attr( $horizontal_number ) ?>"
                                                    >
                                                    <div class="vi-ui dropdown label symbol">
                                                        <div class="text"><?php echo esc_attr( $modal_icon_symbol_horizontal ) ?></div>
                                                        <input type="hidden"
                                                               class="modal_icon_symbol_horizontal"
                                                               name="modal_icon_symbol_horizontal"
                                                               value="<?php echo esc_attr( $modal_icon_symbol_horizontal ) ?>"
                                                        >
                                                        <i class="dropdown icon"></i>
                                                        <div class="menu">
                                                            <div class="item"
                                                                 data-value="%"><?php echo esc_attr( '%' ) ?></div>
                                                            <div class="item"
                                                                 data-value="px"><?php echo esc_attr( 'px' ) ?></div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden"
                                                           class="modal_hidden_value"
                                                           name="viwcpf_setting[modal][icon][horizontal]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['horizontal'] ) ?>"
                                                    >
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_modal_icon">
                                                <label> <?php esc_html_e( 'Icon vertical', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled input">
                                                    <input type="number"
                                                           placeholder="<?php esc_attr_e( 'Enter number vertical', 'pofily-woo-product-filters' ); ?>"
                                                           class="modal_number_value"
                                                           name=""
                                                           data-symbol="<?php echo esc_attr( $modal_icon_symbol_vertical ) ?>"
                                                           value="<?php echo esc_attr( $vertical_number ) ?>"
                                                    >
                                                    <div class="vi-ui dropdown label symbol">
                                                        <div class="text"><?php echo esc_attr( $modal_icon_symbol_vertical ) ?></div>
                                                        <input type="hidden"
                                                               class="off_canvas_icon_symbol_vertical"
                                                               name="off_canvas_icon_symbol_vertical"
                                                               value="<?php echo esc_attr( $modal_icon_symbol_vertical ); ?>"
                                                        >
                                                        <i class="dropdown icon"></i>
                                                        <div class="menu">
                                                            <div class="item"><?php echo esc_attr( '%' ) ?></div>
                                                            <div class="item"><?php echo esc_attr( 'px' ) ?></div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden"
                                                           class="modal_hidden_value"
                                                           name="viwcpf_setting[modal][icon][vertical]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['vertical'] ); ?>"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Icon size', 'pofily-woo-product-filters' ); ?></label>
                                                <div class="vi-ui right input">
                                                    <input type="number" min="1" step="0.01" class=""
                                                           name="viwcpf_setting[modal][icon][size]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['size'] ); ?>">
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Color', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon input">
                                                    <span class="color-picker"></span>
                                                    <input type="text" name="viwcpf_setting[modal][icon][color]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#fe2740"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['color'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Background', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon  input">
                                                    <span class="color-picker"></span>
                                                    <input type="text"
                                                           name="viwcpf_setting[modal][icon][background]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#ffffff"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['background'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Hover-->
                                <div class="vi-ui segment">

                                    <label class="vi-ui top attached label"> <?php esc_html_e( 'Hover', 'pofily-woo-product-filters' ) ?></label>

                                    <div class="vi-ui basic segment">
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Icon size', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right input">
                                                    <input type="number" min="1" step="0.01" class=""
                                                           name="viwcpf_setting[modal][icon][size_hover]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['size_hover'] ) ?>">
                                                </div>

                                            </div>
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Color', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon  input">
                                                    <span class="color-picker"></span>
                                                    <input type="text"
                                                           name="viwcpf_setting[modal][icon][color_hover]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#fe2740"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['color_hover'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field">
                                                <label> <?php esc_html_e( 'Background', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon  input">
                                                    <span class="color-picker"></span>
                                                    <input type="text"
                                                           name="viwcpf_setting[modal][icon][background_hover]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#ffffff"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['modal']['icon']['background_hover'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Options style', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <select name="viwcpf_setting[option_style]"
                                    class="vi-ui dropdown  viwcpf_filter_block_select ">
                                <option value="<?php echo esc_attr( 'custom_style' ) ?>"
									<?php
									if (
										isset( $viwcpf_setting_params['option_style'] ) &&
										( $viwcpf_setting_params['option_style'] === 'custom_style' )
									) {
										echo esc_attr( 'selected' );
									}
									?>
                                >
									<?php esc_html_e( 'Custom style', 'pofily-woo-product-filters' ) ?>
                                </option>
                                <option value="<?php echo esc_attr( 'theme_style' ) ?>"
									<?php
									if (
										isset( $viwcpf_setting_params['option_style'] ) &&
										( $viwcpf_setting_params['option_style'] === 'theme_style' )
									) {
										echo esc_attr( 'selected' );
									}
									?>
                                >
									<?php esc_html_e( 'Theme style', 'pofily-woo-product-filters' ) ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Filters area colors', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="equal width fields">
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Titles', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui icon fluid input">
                                        <span class="color-picker"></span>
                                        <input type="text" name="viwcpf_setting[area][color][title]" class="color-text"
                                               placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                               data-default-color="#333333"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['area']['color']['title'] ) ?>">
                                        <i class="undo link icon reset_color"></i>
                                    </div>
                                </div>
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Background', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui icon fluid input">
                                        <span class="color-picker"></span>
                                        <input type="text" name="viwcpf_setting[area][color][background]"
                                               class="color-text"
                                               placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                               data-default-color="#333333"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['area']['color']['background'] ) ?>">
                                        <i class="undo link icon reset_color"></i>
                                    </div>
                                </div>
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Accent color', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui icon fluid input">
                                        <span class="color-picker"></span>
                                        <input type="text" name="viwcpf_setting[area][color][accent]" class="color-text"
                                               placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                               data-default-color="#333333"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['area']['color']['accent'] ) ?>">
                                        <i class="undo link icon reset_color"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Color swatches/Image button default size', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="equal width fields">
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Width', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui right labeled fluid input">
                                        <input type="number"
                                               name="viwcpf_setting[color_swatches][btn_style][btn_width]"
                                               id="default-btn_width"
                                               placeholder="<?php esc_attr_e( 'Enter Width', 'pofily-woo-product-filters' ); ?>"
                                               min="0"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['color_swatches']['btn_style']['btn_width'] ); ?>"
                                        >
                                        <div class="vi-ui basic label"><?php esc_html_e( 'px', 'pofily-woo-product-filters' ) ?></div>
                                    </div>
                                </div>
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Height', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui right labeled fluid input">
                                        <input type="number"
                                               name="viwcpf_setting[color_swatches][btn_style][btn_height]"
                                               id="default-btn_height"
                                               placeholder="<?php esc_attr_e( 'Enter Height', 'pofily-woo-product-filters' ); ?>"
                                               min="0"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['color_swatches']['btn_style']['btn_height'] ); ?>"
                                        >
                                        <div class="vi-ui basic label"><?php esc_html_e( 'px', 'pofily-woo-product-filters' ) ?></div>
                                    </div>
                                </div>
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Border radius', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui right fluid input">
                                        <input type="text"
                                               name="viwcpf_setting[color_swatches][btn_style][btn_border_radius]"
                                               id="default-btn_border_radius"
                                               placeholder="<?php esc_attr_e( 'Enter Border Radius', 'pofily-woo-product-filters' ); ?>"
                                               min="0"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['color_swatches']['btn_style']['btn_border_radius'] ); ?>"
                                        >
                                    </div>
                                </div>
                                <div class="field choose_color setting_color_field">
                                    <label> <?php esc_html_e( 'Color separator', 'pofily-woo-product-filters' ) ?></label>
                                    <div class="vi-ui right fluid input">
                                        <select class="vi-ui dropdown  "
                                                name="viwcpf_setting[color_swatches][btn_style][btn_color_separator]"
                                                id="default-color_separator"
                                        >
                                            <option value="1"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '1' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Basic horizontal', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="2"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '2' ) {
													echo esc_attr( 'selected' );
												}
												?>>
												<?php esc_html_e( 'Basic vertical', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="3"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '3' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Basic diagonal left', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="4"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '4' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Basic diagonal right', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="5"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '5' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Hard lines horizontal', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="6"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '6' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Hard lines vertical', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="7"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '7' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Hard lines diagonal left', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                            <option value="8"
												<?php
												if ( $viwcpf_setting_params['color_swatches']['btn_style']['btn_color_separator'] == '8' ) {
													echo esc_attr( 'selected' );
												}
												?>
                                            >
												<?php esc_html_e( 'Hard lines diagonal right', 'pofily-woo-product-filters' ) ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Color swatches default color', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="equal width fields">
                                <div class="field choose_color setting_color_field">
                                    <div class="vi-ui icon fluid input">
                                        <span class="color-picker"></span>
                                        <input type="text" name="viwcpf_setting[color_swatches][color_default]"
                                               class="color-text"
                                               placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                               data-default-color="#fe2740"
                                               value="<?php echo esc_attr( $viwcpf_setting_params['color_swatches']['color_default'] ) ?>">
                                        <i class="undo link icon reset_color"></i>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <div class="viwcpf_table_th">
								<?php esc_html_e( 'Label styles', 'pofily-woo-product-filters' ) ?>
                            </div>
                        </th>
                        <td>
                            <div class="vi-ui vertical segment">
                                <!--Default-->
                                <div class="vi-ui segment">
									<?php
									preg_match( '!\d+!', $viwcpf_setting_params['label']['size']['font_size'], $value_label_font_size );

									if (
										! empty( $value_label_font_size ) &&
										is_array( $value_label_font_size )
									) {
										$value_label_font_size_number = $value_label_font_size[0];
										$value_label_font_size_symbol = str_replace( $value_label_font_size_number, '', $viwcpf_setting_params['label']['size']['font_size'] );
									} else {
										$value_label_font_size_number = $viwcpf_setting_params_default['label']['size']['font_size'];
										$value_label_font_size_symbol = 'px';
									}

									?>
                                    <label class="vi-ui top attached label"> <?php esc_html_e( 'Default', 'pofily-woo-product-filters' ) ?></label>

                                    <div class="vi-ui basic segment">
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Font size', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled fluid input">
                                                    <input type="number" min="0" class="label_number_value"
                                                           data-symbol="<?php echo esc_attr( $value_label_font_size_symbol ) ?>"
                                                           name=""
                                                           value="<?php echo esc_attr( $value_label_font_size_number ) ?>">
                                                    <div class="vi-ui label viwcpf-right-input-label"><?php esc_html_e( 'Px', 'pofily-woo-product-filters' ) ?></div>
                                                    <input type="hidden"
                                                           class="label_hidden_value"
                                                           name="viwcpf_setting[label][size][font_size]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['size']['font_size'] ) ?>">
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Border width', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled fluid input">
                                                    <input type="number" min="0" class=""
                                                           name="viwcpf_setting[label][size][border_width]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['size']['border_width'] ) ?>">
                                                    <div class="vi-ui label viwcpf-right-input-label"><?php esc_html_e( 'Px', 'pofily-woo-product-filters' ) ?></div>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Border radius', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui right labeled fluid input">
                                                    <input type="number" min="0" class=""
                                                           name="viwcpf_setting[label][size][border_radius]"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['size']['border_radius'] ) ?>"">
                                                    <div class="vi-ui label viwcpf-right-input-label"><?php esc_html_e( 'Px', 'pofily-woo-product-filters' ) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Background', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text" name="viwcpf_setting[label][color][background]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['background'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Text', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text" name="viwcpf_setting[label][color][text]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['text'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Hover-->
                                <div class="vi-ui segment">

                                    <label class="vi-ui top attached label"> <?php esc_html_e( 'Hover', 'pofily-woo-product-filters' ) ?></label>

                                    <div class="vi-ui basic segment">
                                        <div class="equal width fields">

                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Background', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text"
                                                           name="viwcpf_setting[label][color][background_hover]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['background_hover'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Text', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text" name="viwcpf_setting[label][color][text_hover]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['text_hover'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Active-->
                                <div class="vi-ui segment">
                                    <label class="vi-ui top attached label"> <?php esc_html_e( 'Active', 'pofily-woo-product-filters' ) ?></label>

                                    <div class="vi-ui basic segment">
                                        <div class="equal width fields">
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Background active', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text"
                                                           name="viwcpf_setting[label][color][background_active]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['background_active'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                            <div class="field choose_color setting_color_field setting_label_styles">
                                                <label> <?php esc_html_e( 'Text active', 'pofily-woo-product-filters' ) ?></label>
                                                <div class="vi-ui icon fluid input">
                                                    <span class="color-picker"></span>
                                                    <input type="text" name="viwcpf_setting[label][color][text_active]"
                                                           class="color-text"
                                                           placeholder="<?php esc_attr_e( 'Choose color', 'pofily-woo-product-filters' ); ?>"
                                                           data-default-color="#333333"
                                                           value="<?php echo esc_attr( $viwcpf_setting_params['label']['color']['text_active'] ) ?>">
                                                    <i class="undo link icon reset_color"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <p class="<?php echo esc_attr( 'save-settings-container' ); ?>">
                <button type="submit"
                        class="vi-ui button icon left labeled primary save-settings"
                        name="viwcpf-save-settings"
                >
                    <i class="save icon"></i><?php esc_html_e( 'Save Settings', 'pofily-woo-product-filters' ) ?>
                </button>
                <button type="submit"
                        class="vi-ui red button icon left labeled reset-default"
                        name="viwcpf-reset-default"
                >
                    <i class="history icon"></i><?php esc_html_e( 'Reset Default', 'pofily-woo-product-filters' ) ?>
                </button>
            </p>
        </form>
		<?php do_action( 'villatheme_support_pofily-woo-product-filters' ); ?>
    </div>
<?php

