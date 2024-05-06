<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/pofily-woo-product-filters/
 * @since      1.0.0
 *
 * @package    pofily-woo-product-filters
 * @subpackage pofily-woo-product-filters/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    pofily-woo-product-filters
 * @subpackage pofily-woo-product-filters/public
 * @author     Villatheme <support@villatheme.com>
 */
class VIWCPF_Woo_Product_Filters_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $viwcpf_woo_product_filters The ID of this plugin.
	 */
	private $viwcpf_woo_product_filters;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $viwcpf_woo_product_filters The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $viwcpf_woo_product_filters, $version ) {

		$this->viwcpf_woo_product_filters = $viwcpf_woo_product_filters;
		$this->version                    = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Product_Filters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Product_Filters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
//		wp_enqueue_style( $this->viwcpf_woo_product_filters . '-select2', VIWCPF_FREE_DIR_URL . 'assets/css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->viwcpf_woo_product_filters . '-vi_dropdown', VIWCPF_FREE_DIR_URL . 'assets/css/vi_dropdown.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->viwcpf_woo_product_filters . '-rangeSlide', VIWCPF_FREE_DIR_URL . 'assets/css/ion.rangeSlider.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->viwcpf_woo_product_filters . '-icon-filter', VIWCPF_FREE_DIR_URL . 'assets/css/viwcpf-icon-filter.css', array(), $this->version, 'all' );
		if ( WP_DEBUG ) {
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-off_canvas', VIWCPF_FREE_DIR_URL . 'public/css/off_canvas.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters, VIWCPF_FREE_DIR_URL . 'public/css/woo-product-filters-public.css', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->viwcpf_woo_product_filters . '-off_canvas', VIWCPF_FREE_DIR_URL . 'public/css/off_canvas.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->viwcpf_woo_product_filters, VIWCPF_FREE_DIR_URL . 'public/css/woo-product-filters-public.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Product_Filters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Product_Filters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->viwcpf_woo_product_filters . '-js-rangeSlide', VIWCPF_FREE_DIR_URL . 'assets/js/ion.rangeSlider.min.js', array( 'jquery' ) );
//		wp_enqueue_script( $this->viwcpf_woo_product_filters . '-select2', VIWCPF_FREE_DIR_URL . 'assets/js/select2.js', array( 'jquery' ) );
		wp_enqueue_script( $this->viwcpf_woo_product_filters . '-vi_dropdown', VIWCPF_FREE_DIR_URL . 'assets/js/vi_dropdown.js', array( 'jquery' ) );
		wp_enqueue_script( $this->viwcpf_woo_product_filters . '-off_canvas', VIWCPF_FREE_DIR_URL . 'public/js/off_canvas.js', array( 'jquery' ) );
		wp_enqueue_script( $this->viwcpf_woo_product_filters, VIWCPF_FREE_DIR_URL . 'public/js/woo-product-filters-public.js', array(
			'jquery',
			'accounting'
		), $this->version, false );
		$viwcpf_localize_args                    = array(
			'currency_format' => array(
				'symbol'    => get_woocommerce_currency_symbol(),
				'decimal'   => esc_attr( wc_get_price_decimal_separator() ),
				'thousand'  => esc_attr( wc_get_price_thousand_separator() ),
				'precision' => wc_get_price_decimals(),
				'format'    => esc_attr( str_replace( array( '%1$s', '%2$s' ), array(
					'%s',
					'%v'
				), get_woocommerce_price_format() ) ),
			),
			'php_int_max'     => PHP_INT_MAX,
		);
		$VIWCPF_Woo_Product_Filters_Data_default = new VIWCPF_Woo_Product_Filters_Data;
		$viwcpf_setting_params_default           = $VIWCPF_Woo_Product_Filters_Data_default->get_default();
		$viwcpf_setting_params                   = get_option( 'viwcpf_setting_params' ) ? get_option( 'viwcpf_setting_params' ) : $viwcpf_setting_params_default;
		if ( isset( $viwcpf_setting_params['modal']['auto_open'] ) ) {
			$viwcpf_localize_args['auto_open_modal'] = 'on';
		}
		if ( isset( $viwcpf_setting_params['modal']['style'] ) ) {
			$viwcpf_localize_args['modal_style'] = $viwcpf_setting_params['modal']['style'];
		}
		wp_localize_script( $this->viwcpf_woo_product_filters, 'viwcpf_localize_args', $viwcpf_localize_args );
		$custom_css = $this->viwcpf_custom_css();

		if ( ! empty( $custom_css ) ) {
			wp_add_inline_style( $this->viwcpf_woo_product_filters, $custom_css );
		}
	}

	/**
	 * @return string
	 */
	public function public_init() {
		$this->activate_viwcpf_shortcodes();
	}

	public function viwcpf_redirect_single_search_result() {
		return false;
	}

	/**
	 * Build custom CSS template, to be used in page header
	 *
	 * @return bool|string Custom CSS template, ro false when no content should be output.
	 */
	protected function viwcpf_custom_css() {
		$default_accent_color = apply_filters( 'viwcpf_default_accent_color', '#fe2740' );

		$variables = array();
		$options   = array(
			'area'  => array(
				'color' => array(
					'title'      => '#434343',
					'background' => '#ffffff',
					'accent'     => '#0c0c0c',
				),
			),
			'label' => array(
				'size'  => array(
					'font_size'     => '16px',
					'border_width'  => '1',
					'border_radius' => '4',
				),
				'color' => array(
					'background'        => '#ffffff',
					'background_hover'  => '#ebebeb',
					'background_active' => '#ebebeb',
					'text'              => '#000000',
					'text_hover'        => '#000000',
					'text_active'       => '#000000',
				),
			),
			'modal' => array(
				'icon' => array(
					'size'             => '1',
					'size_hover'       => '1',
					'icon_radius'      => '50%',
					'color'            => '#ffffff',
					'color_hover'      => '#78a938',
					'background'       => '#78a938',
					'background_hover' => '#ffffff',
					'horizontal'       => '25px',
					'vertical'         => '35px',
				),
			),
		);


		foreach ( $options as $variable => $settings ) {
			$option   = $variable;
			$variable = '--viwcpf-' . $variable;
			$value    = isset( get_option( 'viwcpf_setting_params' )[ $option ] ) ? get_option( 'viwcpf_setting_params' )[ $option ] : array();

			if ( empty( $value ) ) {
				continue;
			}

			if ( is_array( $value ) ) {
				foreach ( $value as $sub_variable => $sub_value ) {
					if ( is_array( $sub_value ) ) {
						foreach ( $sub_value as $sub_sub_variable => $sub_sub_value ) {
							$variables["{$variable}_{$sub_variable}_{$sub_sub_variable}"] = $sub_sub_value;
						}
					} else {
						$variables["{$variable}_{$sub_variable}"] = $sub_value;
					}

				}
			} else {
				$variables[ $variable ] = $value;
			}
		}

		if ( empty( $variables ) ) {
			return false;
		}

		// start CSS snippet.
		$template = ":root{\n";

		// cycles through variables.
		foreach ( $variables as $variable => $value ) {
			$template .= "\t{$variable}: {$value};\n";
		}

		// close :root directive.
		$template .= '}';

		return $template;
	}

	public function activate_viwcpf_shortcodes() {

		add_shortcode( "VIWCPF_SHORTCODE", array( $this, 'viwcpf_menu_shortcode' ) );

	}

	public function viwcpf_menu_shortcode( $param ) {

		ob_start();
		extract( shortcode_atts( array(
			'id_menu' => '',
		), $param ) );

		if ( $id_menu != '' ) {
			$viwcpf_filter_menu    = get_post_meta( $id_menu, 'viwcpf_filter_menu', true );
			$viwcpf_setting_params = get_option( 'viwcpf_setting_params' );
			if (
				$viwcpf_setting_params &&
				isset( $viwcpf_setting_params['option_style'] )
			) {
				$option_style = $viwcpf_setting_params['option_style'];
			} else {
				$option_style = '';
			}
			if ( $this->check_conditional_menu( $id_menu ) ) {
				if ( $viwcpf_filter_menu ) {
					$arr_block_id = explode( ",", $viwcpf_filter_menu['viwcpf_blocks_selected'] );
					if ( $viwcpf_filter_menu['viwcpf_using_ajax'] ) {
						$ajaxClassName = 'with-ajax';
					} else {
						$ajaxClassName = 'no-ajax';
					}
					if ( $viwcpf_filter_menu['viwcpf_show_button_submit'] ) {
						$submitBtnClassName = 'has-submit-btn';
					} else {
						$submitBtnClassName = '';
					}

					global $wp;
					do_action( 'viwcpf_before_menu_filters' );
					?>
                    <form class="viwcpf_form_filter <?php echo esc_attr( $option_style . ' ' . $ajaxClassName . ' ' . $submitBtnClassName ); ?>"
                          method="GET" action="<?php echo home_url( $wp->request ); ?>">
						<?php
						foreach ( $arr_block_id as $item_block_id ) {
							$viwcpf_filter_block = get_post_meta( $item_block_id, 'viwcpf_filter_block', true );

							if ( $viwcpf_filter_block ) {

								$this->viwcpf_get_template_by_type_show( $viwcpf_filter_block );
							}
						}
						if ( $viwcpf_filter_menu['viwcpf_show_button_submit'] ) {
							echo '<button class="btn btn-primary viwcpf-apply-filters">' . esc_html__( 'Apply filters', 'pofily-woo-product-filters' ) . '</button>';
						}
						?>
                    </form>
					<?php
					do_action( 'viwcpf_after_menu_filters' );
				} else {
					$viwcpf_filter_menu = array();
				}
			}

		} else {
			esc_html_e( 'Error loading menu filter', 'pofily-woo-product-filters' );
		}

		return ob_get_clean();
	}

	/*
    *
    * Function add custom query Pre get post for filter
    *
    *
    * @param $query
    *
    */
	public function pre_get_post_filter( $query ) {
		if (
			! is_admin() &&
			is_post_type_archive( 'product' ) &&
			$query->is_main_query()
		) {
			if (
				isset( $_GET['instock_filter'] ) &&
				sanitize_text_field( $_GET['instock_filter'] )
			) {
				$query->set(
					'meta_query',
					array(
						array(
							'key'     => '_stock_status',
							'value'   => 'instock',
							'compare' => '=',
						)
					)
				);
			}
			if (
				isset( $_GET['onsale_filter'] ) &&
				sanitize_text_field( $_GET['onsale_filter'] )
			) {
				$query->set(
					'meta_query',
					array(
						array(
							'relation' => 'OR',
							array(// Simple products type
								'key'     => '_sale_price',
								'value'   => 0,
								'compare' => '>',
								'type'    => 'numeric'
							),
							array(// Variable products type
								'key'     => '_min_variation_sale_price',
								'value'   => 0,
								'compare' => '>',
								'type'    => 'numeric'
							)
						)
					)
				);
			}
			if ( isset( $_GET ) ) {
				$viwcpf_arr_metakey        = array();
				$viwcpf_setting_params     = get_option( 'viwcpf_setting_params' ) ? get_option( 'viwcpf_setting_params' ) : array();
				$viwcpf_arr_metakey_option = isset( $viwcpf_setting_params['display_metakey'] ) ? $viwcpf_setting_params['display_metakey'] : array();
				foreach ( wc_clean( $_GET ) as $key_get => $value_get ) {
					if ( 0 === strpos( $key_get, 'viwcpf_metakey_type_' ) ) {
						$name_metakey = str_replace( 'viwcpf_metakey_type_', '', $key_get );
						array_push( $viwcpf_arr_metakey, $name_metakey );
					}
				}
				if ( sizeof( $viwcpf_arr_metakey ) > 0 ) {
					/*Create variable $viwcpf_metavalue_arr_query = array() */
					$viwcpf_metavalue_arr_query = array();
					/*If more than 1 metakey then add relation to array query*/
					if ( sizeof( $viwcpf_arr_metakey ) > 1 ) {
						$viwcpf_metavalue_arr_query['relation'] = 'AND';
					}
					/*For all metakey and add value to array meta query*/
					foreach ( $viwcpf_arr_metakey as $item_metakey ) {
						$viwcpf_metakey_filter = sanitize_text_field( $item_metakey );
						if ( in_array( $item_metakey, $viwcpf_arr_metakey_option ) ) {
							if (
								isset( $_GET[ 'viwcpf_metakey_' . $viwcpf_metakey_filter ] ) &&
								isset( $_GET[ 'viwcpf_metakey_type_' . $viwcpf_metakey_filter ] )
							) {
								$viwcpf_metakey_type_filter  = sanitize_text_field( $_GET[ 'viwcpf_metakey_type_' . $viwcpf_metakey_filter ] );
								$viwcpf_metakey_value_filter = sanitize_text_field( $_GET[ 'viwcpf_metakey_' . $viwcpf_metakey_filter ] );

								/*Check type of metadata*/
								switch ( $viwcpf_metakey_type_filter ) {
									case 'string':
										$viwcpf_metavalue_arr_query[] = array(
											'key'     => $viwcpf_metakey_filter,
											'value'   => $viwcpf_metakey_value_filter,
											'type'    => 'char',
											'compare' => '=',
										);

										break;
									case 'numberic':
										$viwcpf_metavalue_arr = explode( "-", $viwcpf_metakey_value_filter );
										if ( sizeof( $viwcpf_metavalue_arr ) == 1 ) {
											array_push( $viwcpf_metavalue_arr, PHP_INT_MAX );
										}
										$viwcpf_metavalue_arr_query[] = array(
											'key'     => $viwcpf_metakey_filter,
											'value'   => $viwcpf_metavalue_arr,
											'type'    => 'numeric',
											'compare' => 'BETWEEN'

										);
										break;
									default:
										break;
								}

							}
						}
					}
					/*Finaly set $viwcpf_metavalue_arr_query to meta query */
					$query->set( 'meta_query', $viwcpf_metavalue_arr_query );
				}
			}

		}
	}

	/*
    *
    * Function check conditional display for menu filter and return boolean true/false
    *
    *
    * @param  string $menu_id String ids menu filter.
    *
    * Return Boolean
    */
	public function check_conditional_menu( $menu_id = '' ) {

//		if ( $menu_id === '' ) {
//			return false;
//		}


//		$assign_page = ' return ( is_archive("product") );';

		return is_archive( "product" );
	}

	/*
    *
    * Function get template filter by type show
    *
    *
    * @param  array $viwcpf_filter_block Array save setting of block menu filter.
    *
    */
	public function viwcpf_get_template_by_type_show( $viwcpf_filter_block ) {
		if ( empty( $viwcpf_filter_block ) ) {
			return;
		}
		$filter_name    = $viwcpf_filter_block['name'];
		$filter_for     = $viwcpf_filter_block['filter_for'];
		$type_show      = $viwcpf_filter_block['filter_data']['type_show'];
		$filter_data    = $viwcpf_filter_block['filter_data'];
		$filter_setting = $viwcpf_filter_block['settings'];

		switch ( $type_show ) {
			case 'button':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-button.php';
				break;
			case 'checkbox':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-checkbox.php';
				break;
			case 'select':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-dropdown.php';
				break;
			case 'range_slide':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-range_slider.php';
				break;
			case 'search_field':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-search_field.php';
				break;
			case 'color_swatches':
				require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/template/viwcpf-template-color_swatches.php';
				break;

			default:
				break;
		}
	}

	/*
    *
    * Function render value for block filter
    *
    *
    * @param $filter_name
    * @param $filter_for
    * @param $filter_data    Array save data of block menu filter.
    * @param $filter_setting Array save setting of block menu filter.
    *
    * return array()
    */
	public function viwcpf_render_value_block_filter( $filter_name, $filter_for, $filter_data, $filter_setting ) {
		if (
			empty( $filter_for ) &&
			empty( $filter_data )
		) {
			return;
		}
		extract( $filter_data );
		extract( $filter_setting );

		global $wp;
		$render_array       = array(
			'key_filter'      => '',
			'list_value'      => array(),
			'btn_style'       => array(),
			'input_hidden'    => array(),
			'multiselect'     => $multiselect,
			'multi_relation'  => $multi_relation,
			'show_count_item' => $show_count_item,
			'show_clear_btn'  => $show_clear,
			'display_type'    => $display_type,
			'show_view_more'  => $show_view_more,
			'view_more_limit' => $view_more_limit
		);
		$list_label_items   = array();
		$input_hidden       = '';
		$link               = '';
		$base_link          = home_url( $wp->request );
		$current_url_param  = wc_clean( $_GET );
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();

		$multiselect = isset( $multiselect ) ? $multiselect : false;

		if ( $multiselect ) {
			$query_type = $multi_relation;
		} else {
			$query_type = 'AND';
		}

		switch ( $filter_for ) {
			case 'filter_by_taxonomy':
				$tax = $tax_name;
				if (
					( $tax_name == 'product_cat' ) ||
					( $tax_name == 'product_tag' )
				) {
					$param_url = '?' . $tax_name . '=';
				} else {
					$tax_name  = str_replace( "pa_", "filter_", $tax_name );
					$param_url = '?filter_' . $tax_name . '=';
				}

				$current_values = isset( $_chosen_attributes[ $tax ]['terms'] ) ? $_chosen_attributes[ $tax ]['terms'] : array();

				$list_terms   = ! empty( $filter_data['list_terms'] ) ? $filter_data['list_terms'] : array();
				$sorted_terms = $this->get_formatted_terms( $filter_data['tax_name'], $list_terms, $filter_data['order_by'], $filter_data['order_type'] );

				if ( ! empty( $sorted_terms ) ) {
					foreach ( $sorted_terms as $sorted_term_item ) {
						$label_item  = $customize_value[ $sorted_term_item ];
						$term_object = get_term_by( 'id', $sorted_term_item, $tax, OBJECT );

						$option_is_set = in_array( $term_object->slug, $current_values, true );

						$label   = empty( $label_item['new_label'] ) ? $label_item['old_label'] : $label_item['new_label'];
						$tooltip = ! empty( $label_item['tooltip'] ) ? $label_item['tooltip'] : '';
						if ( $tooltip != '' ) {
							$data_tooltip = 'data-tooltip=' . esc_attr( $tooltip ) . '';
						} else {
							$data_tooltip = '';
						}
						$id   = $term_object->term_id;
						$slug = $term_object->slug;

						$count = $this->get_filtered_term_product_counts( array( $id ), $tax, $multi_relation );

						if ( ! isset( $count[ $id ] ) ||
						     ( $count[ $id ] <= 0 )
						) {
							continue;
						}
						if ( $multiselect ) {
							/*Get current tax filter for multiselect=yes */
							if (
								( $query_type == 'AND' ) &&
								(
									( $tax_name == 'product_cat' ) ||
									( $tax_name == 'product_tag' )
								)
							) {
								$current_filter = isset( $_GET[ $tax_name ] ) ? explode( ' ', urldecode( wc_clean( wp_unslash( $_GET[ $tax_name ] ) ) ) ) : array();
							} else {
								$current_filter = isset( $_GET[ $tax_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $tax_name ] ) ) ) : array();
							}
							/*Add term value current product taxonomy page*/
							if ( is_product_taxonomy() ) {
								$current_page_query = get_queried_object();
								$current_page_tax   = $current_page_query->taxonomy ?? '';
								if ( $current_page_tax == $tax_name ) {
									if ( ! empty( $current_page_query->slug ) ) {

										$current_filter[] = $current_page_query->slug;
									}
								}

							}
							if ( ! in_array( $slug, $current_filter, true ) ) {
								$current_filter[] = $slug;
								$class_name       = '';
							} else {
								$class_name = 'viwcpf_chosen';
								foreach ( $current_filter as $keyy => $value ) {
									// Exclude query arg for current term archive term.
									if ( $value === $slug ) {
										unset( $current_filter[ $keyy ] );
									}

									// Exclude self so filter can be unset on click.
									if ( $option_is_set && $value === $slug ) {
										unset( $current_filter[ $keyy ] );
									}
								}
							}
							/*Remove query args current*/
							$link = remove_query_arg( $tax_name, $base_link );
							/*Remove current tax value in $current_url_param*/
							unset( $current_url_param[ $tax_name ] );

							$current_filter = array_map( 'sanitize_title', $current_filter );

							// Add current filters to URL.
							if ( ! empty( $current_filter ) ) {
								asort( $current_filter );
								//Check if is product_cat or product_tag query_type_ no need add, replace with "+" or ",". "+" is query_type = and; "," is query_type = or
								if (
									( $tax_name == 'product_cat' ) ||
									( $tax_name == 'product_tag' )
								) {
									$link = add_query_arg( $tax_name, implode( ',', $current_filter ), $link );

									// Add Query type Arg to URL.
									if ( 'OR' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
										$link = add_query_arg( $tax_name, implode( ',', $current_filter ), $link );
									} else {
										$link = add_query_arg( $tax_name, implode( '+', $current_filter ), $link );
									}
								} else {
									$link = add_query_arg( $tax_name, implode( ',', $current_filter ), $link );

									// Add Query type Arg to URL.
									if ( 'OR' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
										$link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $tax ), 'or', $link );
									}

								}


								/* $link = str_replace( '%2C', ',', $link );*/
							}
							if ( is_array( $current_url_param ) && sizeof( $current_url_param ) > 0 ) {
								foreach ( $current_url_param as $key_param => $value_param ) {
									$link = add_query_arg( $key_param, $value_param, $link );
								}
							}
						} else {
							/*Get current tax filter no multiselect */
							$current_filter = isset( $_GET[ $tax_name ] ) ? sanitize_text_field( $_GET[ $tax_name ] ) : '';
							/*Remove query args current*/
							$link = remove_query_arg( $tax_name, $base_link );
							/*Remove current tax value in $current_url_param*/
							unset( $current_url_param[ $tax_name ] );
							/*Update all another param to $link*/
							if ( is_array( $current_url_param ) && sizeof( $current_url_param ) > 0 ) {
								foreach ( $current_url_param as $key_param => $value_param ) {
									$link = add_query_arg( $key_param, $value_param, $link );
								}
							}
							/*Add new tax param to $link*/
							if ( $slug === $current_filter ) {
								$class_name = 'viwcpf_chosen';
							} else {
								$link       = add_query_arg( $tax_name, $slug, $link );
								$class_name = '';
							}
						}


						$list_label_items = array(
							'label'      => ( $label ),
							'value'      => ( $slug ),
							'tooltip'    => ( $data_tooltip ),
							'link'       => ( $link ),
							'count'      => ( $count[ $id ] ),
							'class_name' => ( $class_name ),
							'data_attr'  => 'data-term-id=' . esc_attr( $id ) . ' data-term-slug=' . esc_attr( $slug ) . ' data-filter_by=' . esc_attr( $filter_for ) . ''
						);

						if ( $type_show == 'color_swatches' ) {
							$color_value               = isset( $label_item['color'] ) ? wc_clean( $label_item['color'] ) : array();
							$list_label_items['color'] = $color_value;
						} else if ( $type_show == 'images' ) {
							$images                     = isset( $label_item['images'] ) ? wc_clean( $label_item['images'] ) : '';
							$list_label_items['images'] = $images;
						}
						array_push( $render_array['list_value'], $list_label_items );
					}
				}

				$current_filter = isset( $_GET[ $tax_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $tax_name ] ) ) ) : array();
				$input_hidden   = array(
					'class' => $filter_for . ' ' . $tax_name . ' ' . 'viwcpf_filter_value',
					'name'  => $tax_name,
					'value' => implode( ',', $current_filter ),
				);
				array_push( $render_array['input_hidden'], $input_hidden );
				if (
					( ( $tax_name !== 'product_cat' ) || ( $tax_name !== 'product_tag' ) ) && ( $multiselect )
				) {
					$input_hidden = array(
						'class' => 'query_type',
						'name'  => 'query_type_' . wc_attribute_taxonomy_slug( $tax ),
						'value' => strtolower( $query_type ),
					);
					array_push( $render_array['input_hidden'], $input_hidden );
				}

				$render_array['key_filter']            = $tax_name;
				$render_array['btn_style']             = $btn_style;
				$render_array['tax_show_search_field'] = $show_search_field;
				break;
			case 'filter_by_price':

				foreach ( $customize_value as $key => $label_item ) {
					$class_name = '';
					if ( ! isset( $label_item['limitless'] ) ) {
						$label = wc_price( apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] ) ) . esc_html__( ' - ', 'pofily-woo-product-filters' ) . wc_price( apply_filters( 'wmc_change_3rd_plugin_price', $label_item['max'] ) );
					} else {
						$label = wc_price( apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] ) ) . esc_html__( ' - & above', 'pofily-woo-product-filters' );
					}
					$tooltip = $label;
					if ( $tooltip != '' ) {
//                        $data_tooltip = 'data-tooltip='.esc_attr($tooltip).'';
						$data_tooltip = '';
					} else {
						$data_tooltip = '';
					}
					/*Count don't need to change multi currency*/
					$count = $this->viwcpf_count_product_by_price( $label_item['min'], $label_item['max'] );

					if ( $count <= 0 ) {
						continue;
					}
					$current_min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : 0;
					$current_max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : PHP_INT_MAX;
					$link              = remove_query_arg( 'min_price', $base_link );
					$link              = remove_query_arg( 'max_price', $link );
					unset( $current_url_param['min_price'] );
					unset( $current_url_param['max_price'] );

					if ( ! empty( $label_item['min'] ) ) {
						$label_item_min = apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] );
						$link           = add_query_arg( 'min_price', apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] ), $link );
					} else {
						$label_item_min = apply_filters( 'wmc_change_3rd_plugin_price', 0 );
						$link           = add_query_arg( 'min_price', '0', $link );
					}
					if ( ! empty( $label_item['max'] ) ) {
						$label_item_max = apply_filters( 'wmc_change_3rd_plugin_price', $label_item['max'] );
						$link           = add_query_arg( 'max_price', apply_filters( 'wmc_change_3rd_plugin_price', $label_item['max'] ), $link );
					} else {
						$label_item_max     = PHP_INT_MAX;
						$filtered_price     = $this->get_filtered_price();
						$filtered_max_price = apply_filters( 'wmc_change_3rd_plugin_price', $filtered_price->max_price );
						$link               = add_query_arg( 'max_price', ceil( floatval( $filtered_max_price ) ), $link );
					}
					if ( is_array( $current_url_param ) && sizeof( $current_url_param ) > 0 ) {
						foreach ( $current_url_param as $key_param => $value_param ) {
							$link = add_query_arg( $key_param, $value_param, $link );
						}
					}

					if (
						( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) &&
						( $label_item_min >= $current_min_price ) &&
						( $label_item_max <= $current_max_price )
					) {
						$class_name = 'viwcpf_chosen';
						$link       = remove_query_arg( 'min_price', $base_link );
						$link       = remove_query_arg( 'max_price', $link );
					}
					$list_label_items = array(
						'label'      => ( $label ),
						'value'      => ( $label_item_min . '-' . $label_item_max ),
						'tooltip'    => ( $data_tooltip ),
						'link'       => ( $link ),
						'count'      => ( $count ),
						'class_name' => $class_name,
						'data_attr'  => 'data-range-min=' . esc_attr( apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] ) ) . ' data-range-max=' . esc_attr( apply_filters( 'wmc_change_3rd_plugin_price', $label_item['min'] ) ) . ' data-filter_by=' . esc_attr( $filter_for ) . ''
					);
					array_push( $render_array['list_value'], $list_label_items );
				}

				$input_hidden = array(
					'class' => $filter_for . ' min_price',
					'name'  => 'min_price',
					'value' => isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '',
				);
				array_push( $render_array['input_hidden'], $input_hidden );

				$input_hidden = array(
					'class' => $filter_for . ' max_price',
					'name'  => 'max_price',
					'value' => isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '',
				);
				array_push( $render_array['input_hidden'], $input_hidden );

				$render_array['key_filter'] = 'min_price,max_price';
				break;
			case 'filter_by_review':
				$rating_filter = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wc_clean( wp_unslash( $_GET['rating_filter'] ) ) ) ) ) : array();
				for ( $rating = 1; $rating <= 5; $rating ++ ) {
					$label   = esc_html__( $rating . ' stars', 'pofily-woo-product-filters' );
					$tooltip = $rating . ' stars';
					if ( $tooltip != '' ) {
						$data_tooltip = 'data-tooltip=' . esc_attr( $tooltip ) . '';
					} else {
						$data_tooltip = '';
					}
					$count = $this->get_filtered_product_count( $rating );

					if ( $count <= 0 ) {
						continue;
					}
					if ( $show_icon_star ) {
						$width = 100 / 5 * $rating;
						$label = '<span class="viwcpf_star-rating"><span style="' . esc_attr( 'width:' . $width . '%;' ) . '"></span></span>' . $label;
					}
					if ( $multiselect ) {
						if ( in_array( $rating, $rating_filter, true ) ) {
							$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
						} else {
							$link_ratings = implode( ',', array_merge( $rating_filter, array( $rating ) ) );
						}
					} else {
						$link_ratings = $rating;
					}


					$class_name = in_array( $rating, $rating_filter, true ) ? 'viwcpf_chosen' : '';
					$link       = apply_filters( 'woocommerce_rating_filter_link', $link_ratings ? add_query_arg( 'rating_filter', $link_ratings, $link ) : remove_query_arg( 'rating_filter' ) );
					unset( $current_url_param['rating_filter'] );
					if ( is_array( $current_url_param ) && sizeof( $current_url_param ) > 0 ) {
						foreach ( $current_url_param as $key_param => $value_param ) {
							$link = add_query_arg( $key_param, $value_param, $link );
						}
					}
					$list_label_items = array(
						'label'      => ( $label ),
						'value'      => ( $rating ),
						'tooltip'    => $data_tooltip,
						'link'       => ( $link ),
						'count'      => ( $count ),
						'class_name' => ( $class_name ),
						'data_attr'  => 'data-filter_by=' . esc_attr( $filter_for ) . ''
					);
					array_push( $render_array['list_value'], $list_label_items );

				}

				$input_hidden = array(
					'class' => $filter_for . ' rating_filter',
					'name'  => 'rating_filter',
					'value' => implode( ',', $rating_filter ),
				);
				array_push( $render_array['input_hidden'], $input_hidden );

				$render_array['key_filter'] = 'rating_filter';

				break;
			case 'filter_by_name_product':
				$placeholder_text_field     = isset( $placeholder_search ) ? $placeholder_search : '';
				$search_value               = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
				$list_label_items           = array(
					'placeholder'  => $placeholder_text_field,
					'search_value' => $search_value
				);
				$render_array['list_value'] = array(
					'label'      => '',
					'value'      => $list_label_items,
					'tooltip'    => '',
					'link'       => '',
					'count'      => '',
					'class_name' => '',
					'data_attr'  => ''
				);

				$render_array['key_filter'] = 's';
				$input_hidden               = array(
					'class' => $filter_for . ' s',
					'name'  => 's',
					'value' => isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '',
				);
				array_push( $render_array['input_hidden'], $input_hidden );
				break;
			default:
				break;
		}

		return $render_array;
	}

	/*
    *
    * Function render value for block filter type ranger slide
    *
    * @param $filter_name
    * @param $filter_for
    * @param $filter_data    Array save data of block menu filter.
    * @param $filter_setting Array save setting of block menu filter.
    * @var $multiselect
    * return array()
    */
	public function viwcpf_render_value_block_range_slide( $filter_name, $filter_for, $filter_data, $filter_setting ) {
		if (
			empty( $filter_for ) &&
			empty( $filter_data )
		) {
			return;
		}
		extract( $filter_data );
		extract( $filter_setting );

		global $wp;
		$render_array = array(
			'key_filter'      => '',
			'list_value'      => array(),
			'input_hidden'    => array(),
			'multiselect'     => '',
			'show_clear_btn'  => $show_clear,
			'multi_relation'  => '',
			'show_count_item' => '',
			'display_type'    => '',
			'show_view_more'  => $show_view_more,
			'view_more_limit' => $view_more_limit
		);
		switch ( $filter_for ) {
			case 'filter_by_price':
				$filtered_price     = $this->get_filtered_price();
				$filtered_min_price = apply_filters( 'wmc_change_3rd_plugin_price', $filtered_price->min_price );
				$filtered_max_price = apply_filters( 'wmc_change_3rd_plugin_price', $filtered_price->max_price );
				$step_price         = ! empty( $customize_value['step_price'] ) ? $customize_value['step_price'] : 1;
				if ( class_exists( 'WOOMULTI_CURRENCY_Data' ) ) {
					$wmc = WOOMULTI_CURRENCY_Data::get_ins();

					$wmc_current_currency = $wmc->get_current_currency();
					$list_currencies      = $wmc->get_list_currencies();

					$current_rate = $list_currencies[ $wmc_current_currency ]['rate'];
					$step_price   = round( floatval( $current_rate ) * $step_price );

				} elseif ( class_exists( 'WOOMULTI_CURRENCY_F_Data' ) ) {
					$wmc = WOOMULTI_CURRENCY_F_Data::get_ins();

					$wmc_current_currency = $wmc->get_current_currency();
					$list_currencies      = $wmc->get_list_currencies();

					$current_rate = $list_currencies[ $wmc_current_currency ]['rate'];
					$step_price   = round( floatval( $current_rate ) * $step_price );

				}

				$min_price = ( $customize_value['min_price'] != '' ) ? apply_filters( 'wmc_change_3rd_plugin_price', $customize_value['min_price'] ) : $filtered_min_price;
				$max_price = ( $customize_value['max_price'] != '' ) ? apply_filters( 'wmc_change_3rd_plugin_price', $customize_value['max_price'] ) : $filtered_max_price;

				$min_price                  = floor( $min_price / $step_price ) * $step_price;
				$max_price                  = ceil( $max_price / $step_price ) * $step_price;
				$list_label_items           = array(
					'min_range_slide'  => $min_price,
					'max_range_slide'  => $max_price,
					'step_range_slide' => $step_price
				);
				$render_array['list_value'] = array(
					'label'      => '',
					'value'      => $list_label_items,
					'tooltip'    => '',
					'link'       => '',
					'count'      => '',
					'class_name' => '',
					'data_attr'  => 'data-min="' . esc_attr( $customize_value['min_price'] ) . '" data-max="' . esc_attr( $customize_value['max_price'] ) . '" data-step="' . esc_attr( $customize_value['step_price'] ) . '"'
				);

				$input_hidden = array(
					'class' => $filter_for . ' min_price',
					'name'  => 'min_price',
					'value' => isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '',
				);
				array_push( $render_array['input_hidden'], $input_hidden );

				$input_hidden = array(
					'class' => $filter_for . ' max_price',
					'name'  => 'max_price',
					'value' => isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '',
				);
				array_push( $render_array['input_hidden'], $input_hidden );

				$render_array['key_filter'] = 'min_price,max_price';
				break;
			default:
				break;
		}

		return $render_array;
	}

	/**
	 * Returns a formatted list of terms, matching current selection and according to hierarchy options
	 *
	 * @return array term_id
	 */
	public function get_formatted_terms( $tax_name, $arr_terms, $order_by, $order_type ) {
		if (
			empty( $tax_name ) ||
			empty( $arr_terms ) ||
			empty( $order_by ) ||
			empty( $order_type )
		) {
			return;
		}

		$result = array();

		$sorted_terms = get_terms(
			array(
				'taxonomy' => $tax_name,
				'include'  => $arr_terms,
				'orderby'  => $order_by,
				'order'    => strtoupper( $order_type ),
				'fields'   => 'ids',
			)
		);
		if ( ! empty( $sorted_terms ) ) {
			foreach ( $sorted_terms as $item_sorted_term ) {
				array_push( $result, $item_sorted_term );
			}
		}

		return $result;
	}

	/**
	 * Get current page URL with various filtering props supported by WC.
	 *
	 * @return string
	 */
	protected function viwcpf_get_current_page_url() {
		if ( is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
		}

		// Min/Max.
		if ( isset( $_GET['min_price'] ) ) {
			$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
		}

		if ( isset( $_GET['max_price'] ) ) {
			$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
		}

		// Order by.
		if ( isset( $_GET['orderby'] ) ) {
			$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
		}

		/**
		 * Search Arg.
		 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
		 */
		if ( get_search_query() ) {
			$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
		}

		// Post Type Arg.
		if ( isset( $_GET['post_type'] ) ) {
			$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

			// Prevent post type and page id when pretty permalinks are disabled.
			if ( is_shop() ) {
				$link = remove_query_arg( 'page_id', $link );
			}
		}

		// Min Rating Arg.
		if ( isset( $_GET['rating_filter'] ) ) {
			$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
		}

		// onsale
		if ( isset( $_GET['onsale_filter'] ) ) {
			$link = add_query_arg( 'onsale_filter', wc_clean( wp_unslash( $_GET['onsale_filter'] ) ), $link );
		}
		// instock
		if ( isset( $_GET['instock_filter'] ) ) {
			$link = add_query_arg( 'instock_filter', wc_clean( wp_unslash( $_GET['instock_filter'] ) ), $link );
		}


		// All current filters.
		if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
			foreach ( $_chosen_attributes as $name => $data ) {
				$filter_name = wc_attribute_taxonomy_slug( $name );
				if ( ! empty( $data['terms'] ) ) {
					$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
				}
				if ( 'or' === $data['query_type'] ) {
					$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
				}
			}
		}

		$exclude_taxonomy_name = 'product_visibility';
		if ( $_chosen_tax_query = WC_Query::get_main_tax_query() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found

			foreach ( $_chosen_tax_query as $data_tax_query ) {

				if (
					isset( $data_tax_query['taxonomy'] ) &&
					( $data_tax_query['taxonomy'] !== $exclude_taxonomy_name )
				) {
					$filter_name = $data_tax_query['taxonomy'];
					if ( isset( $_GET[ $filter_name ] ) ) {
						$link = add_query_arg( $filter_name, wc_clean( wp_unslash( $_GET[ $filter_name ] ) ), $link );
						if ( isset( $_GET[ 'query_type_' . $filter_name ] ) ) {
							$link = add_query_arg( 'query_type_' . $filter_name, wc_clean( wp_unslash( $_GET[ 'query_type_' . $filter_name ] ) ), $link );
						}
					}
				}
			}

		}

		if ( $_chosen_meta = WC_Query::get_main_meta_query() ) {
			foreach ( $_chosen_meta as $meta_data ) {

				if ( ! empty( $meta_data['value'] ) ) {
					$metakey_name = $meta_data['key'];
					if ( isset( $meta_data['type'] ) && $meta_data['type'] == 'numeric' ) {
						$meta_value = $meta_data['value'][0] . '-' . $meta_data['value'][1];
						$meta_type  = 'numberic';
					} else {
						$meta_value = $meta_data['value'];
						$meta_type  = 'string';
					}
					$link = add_query_arg( 'viwcpf_metakey_' . $metakey_name, $meta_value, $link );
					$link = add_query_arg( 'viwcpf_metakey_type_' . $metakey_name, $meta_type, $link );
				}
			}
		}

		return apply_filters( 'viwcpf_get_current_page_url', $link, $this );
	}

	/**
	 * Get clear link by $key_filter.
	 *
	 * @return string
	 */
	public function viwcpf_get_clear_url( $key_filters ) {
		if ( empty( $key_filters ) ) {
			return;
		}
		$base_link       = $link = $this->viwcpf_get_current_page_url();
		$arr_key_filters = explode( ',', $key_filters );
		$has_filter      = false;

		if ( sizeof( $arr_key_filters ) > 0 ) {
			foreach ( $arr_key_filters as $key_filter ) {
				if ( array_key_exists( $key_filter, $_GET ) ) {
					$has_filter = true;
				}
			}
			if ( $has_filter ) {
				$link = remove_query_arg( $arr_key_filters, $base_link );
			} else {
				$link = '';
			}

		} else {
			$link = '';
		}

		return apply_filters( 'viwcpf_get_clear_url', $link, $this );
	}

	/**
	 * Get current query filtered active.
	 *
	 * @return string html
	 */
	public function viwcpf_get_active_filter() {
		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$template              = '';
		$viwcpf_setting_params = get_option( 'viwcpf_setting_params' );
		if (
			$viwcpf_setting_params &&
			isset( $viwcpf_setting_params['option_style'] )
		) {
			$option_style = $viwcpf_setting_params['option_style'];
		} else {
			$option_style = '';
		}
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$_chosen_meta       = WC_Query::get_main_meta_query();
		$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0;
		$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0;
		$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wc_clean( wp_unslash( $_GET['rating_filter'] ) ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
		$onsale_filter      = isset( $_GET['onsale_filter'] ) ? wc_clean( wp_unslash( $_GET['onsale_filter'] ) ) : '';
		$instock_filter     = isset( $_GET['instock_filter'] ) ? wc_clean( wp_unslash( $_GET['instock_filter'] ) ) : '';
		$search_filter      = isset( $_GET['s'] ) ? wc_clean( wp_unslash( $_GET['s'] ) ) : '';
		$base_link          = $this->viwcpf_get_current_page_url();

		$get_main_tax_query = WC_Query::get_main_tax_query();
		$_chosen_cat_or_tag = array(
			'product_cat' => array(),
			'product_tag' => array(),
		);
		$arr_tax            = array( 'product_cat', 'product_tag' );
		foreach ( $get_main_tax_query as $tax_item ) {
			if ( is_array( $tax_item ) ) {
				if ( in_array( $tax_item['taxonomy'], $arr_tax ) ) {
					if ( $tax_item['taxonomy'] == 'product_cat' ) {
						$arr_tax_query = array(
							'terms' => $tax_item['terms']
						);
						array_push( $_chosen_cat_or_tag['product_cat'], $arr_tax_query );
					} else if ( $tax_item['taxonomy'] == 'product_tag' ) {
						$arr_tax_query = array(
							'terms' => $tax_item['terms']
						);
						array_push( $_chosen_cat_or_tag['product_tag'], $arr_tax_query );
					}
				}
			}

		}

		if (
			0 < count( $_chosen_attributes ) ||
			0 < count( $_chosen_cat_or_tag['product_cat'] ) ||
			0 < count( $_chosen_cat_or_tag['product_tag'] ) ||
			0 < count( $_chosen_meta ) ||
			0 < $min_price ||
			0 < $max_price ||
			! empty( $rating_filter ) ||
			! empty( $onsale_filter ) ||
			! empty( $instock_filter ) ||
			! empty( $search_filter )
		) {

			$template .= '<div class="viwcpf_active_filters ' . esc_attr( $option_style ) . '"><h4>' . esc_html__( 'Active filters', 'pofily-woo-product-filters' ) . '</h4><ul class="active_filter">';
			if ( ! empty( $_chosen_cat_or_tag['product_cat'] ) ) {
				foreach ( $_chosen_cat_or_tag['product_cat'] as $data ) {

					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, 'product_cat' );
						if ( ! $term ) {
							continue;
						}

						$filter_name = 'product_cat';
						if ( count( $_chosen_cat_or_tag['product_cat'] ) > 1 ) {
							/*That for query type "and"*/
							$current_filter = isset( $_GET[ $filter_name ] ) ? explode( '+', urlencode( wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						} else {
							/*That for query type "or"*/
							$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						}

						$current_filter = array_map( 'sanitize_title', $current_filter );
						$new_filter     = array_diff( $current_filter, array( $term_slug ) );

						$link = remove_query_arg( array(
							'add-to-cart',
							$filter_name,
							'query_type_' . $filter_name
						), $base_link );

						if ( count( $new_filter ) > 0 ) {
							$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
						}

						$template .= '<li class="' . esc_attr( 'viwcpf_chosen' ) . '"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . esc_html__( 'Product cat: ', 'pofily-woo-product-filters' ) . $term->name . '</a></li>';
					}
				}

			}

			if ( ! empty( $_chosen_cat_or_tag['product_tag'] ) ) {
				foreach ( $_chosen_cat_or_tag['product_tag'] as $data ) {
					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, 'product_tag' );
						if ( ! $term ) {
							continue;
						}

						$filter_name = 'product_tag';
						if ( count( $_chosen_cat_or_tag['product_tag'] ) > 1 ) {
							/*That for query type "and"*/
							$current_filter = isset( $_GET[ $filter_name ] ) ? explode( '+', urlencode( wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						} else {
							/*That for query type "or"*/
							$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						}
						$current_filter = array_map( 'sanitize_title', $current_filter );
						$new_filter     = array_diff( $current_filter, array( $term_slug ) );

						$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );

						if ( count( $new_filter ) > 0 ) {
							$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
						}

						$template .= '<li class="' . esc_attr( 'viwcpf_chosen' ) . '"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . esc_html__( 'Product tag: ', 'pofily-woo-product-filters' ) . $term->name . '</a></li>';
					}
				}

			}
			// Attributes.
			if ( ! empty( $_chosen_attributes ) ) {
				foreach ( $_chosen_attributes as $taxonomy => $data ) {

					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, $taxonomy );
						if ( ! $term ) {
							continue;
						}

						$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
						$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						$current_filter = array_map( 'sanitize_title', $current_filter );
						$new_filter     = array_diff( $current_filter, array( $term_slug ) );

						$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );
						$link = remove_query_arg( 'filter_' . str_replace( 'pa_', '', $taxonomy ), $base_link );
						$link = remove_query_arg( 'query_type_' . str_replace( 'pa_', '', $taxonomy ), $link );

						if ( count( $new_filter ) > 0 ) {
							$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
						}

						$filter_classes = array(
							'viwcpf_chosen',
							'viwcpf_chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) ),
							'viwcpf_chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) . '-' . $term_slug )
						);

						$template .= '<li class="' . esc_attr( implode( ' ', $filter_classes ) ) . '"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
					}
				}
			}

			if ( ! empty( $_chosen_meta ) ) {
				foreach ( $_chosen_meta as $meta_data ) {
					if ( ! empty( $meta_data['value'] ) ) {
						$metakey_name = $meta_data['key'];
						if ( isset( $meta_data['type'] ) && $meta_data['type'] == 'numeric' ) {
							$meta_max_value = $meta_data['value'][1];
							if ( $meta_max_value == PHP_INT_MAX ) {
								$meta_max_value = 'max';
							}
							$meta_value = $meta_data['value'][0] . ' - ' . $meta_max_value;
						} else {
							$meta_value = $meta_data['value'];
						}

						$link = remove_query_arg( array(
							'viwcpf_metakey_' . $metakey_name,
							'viwcpf_metakey_type_' . $metakey_name
						), $base_link );

						$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr( 'Remove filter' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $metakey_name . ':' . $meta_value ) . '</a></li>';
					}
				}
			}

			if ( $min_price ) {
				$link = remove_query_arg( 'min_price', $base_link );
				if ( ! $max_price ) {
					$link = remove_query_arg( 'max_price', $link );
				}
				/* translators: %s: minimum price */
				$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr( 'Remove filter' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Min %s', 'pofily-woo-product-filters' ), wc_price( $min_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}


			if ( $max_price ) {
				$link = remove_query_arg( 'max_price', $base_link );
				if ( ! $min_price ) {
					$link = remove_query_arg( 'min_price', $link );
				}
				/* translators: %s: maximum price */
				$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Max %s', 'pofily-woo-product-filters' ), wc_price( $max_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}

			if ( ! empty( $rating_filter ) ) {
				foreach ( $rating_filter as $rating ) {
					$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
					$link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );
					$width        = 100 / 5 * $rating;
					/* translators: %s: rating */
					$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '"><div class="viwcpf_star-rating" aria-label="' . sprintf( esc_html__( 'Rated %s out of 5', 'pofily-woo-product-filters' ), esc_html( $rating ) ) . '"><span style="' . esc_attr( 'width:' . $width . '%;' ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'pofily-woo-product-filters' ), esc_html( $rating ) ) . '</span></div></a></li>';
				}
			}
			if ( ! empty( $onsale_filter ) ) {

				$link_onsale = $onsale_filter;
				$link        = $link_onsale ? add_query_arg( 'onsale_filter', '1' ) : remove_query_arg( 'onsale_filter', $base_link );

				/* translators: %s: rating */
				$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . esc_html__( 'Onsale', 'pofily-woo-product-filters' ) . '</a></li>';

			}
			if ( ! empty( $instock_filter ) ) {

				$link_instock = $instock_filter;
				$link         = $link_instock ? add_query_arg( 'instock_filter', '1' ) : remove_query_arg( 'instock_filter', $base_link );

				/* translators: %s: rating */
				$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . esc_html__( 'Instock', 'pofily-woo-product-filters' ) . '</a></li>';

			}

			if ( ! empty( $search_filter ) ) {

				$link = remove_query_arg( 's', $base_link );

				/* translators: %s: $search_filter */
				$template .= '<li class="viwcpf_chosen"><a class="active_filter_label" rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'pofily-woo-product-filters' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Key search: %s', 'pofily-woo-product-filters' ), esc_html( $search_filter ) ) . '</a></li>';

			}

			$template .= '</ul></div>';

		}

		echo wp_kses_post( $template );
	}

	/**
	 * Returns an array of locations where items shown "Before products" should be hooked
	 *
	 * @param int $offset Integer used to offset hook priority.
	 *                    It is used when multiple templates are hooked to the same location, and you want to define a clear order.
	 *
	 * @return array Array of locations.
	 */
	public function get_before_product_locations( $offset = 0 ) {
		return apply_filters(
			'viwcpf_before_product_locations',
			array(
				// before shop.
				array(
					'hook'     => 'woocommerce_before_shop_loop',
					'priority' => 20 + $offset,
				),
				// before products shortcode.
				array(
					'hook'     => 'woocommerce_shortcode_before_products_loop',
					'priority' => 20 + $offset,
				),
				// before no_products template.
				array(
					'hook'     => 'woocommerce_no_products_found',
					'priority' => 15 + $offset,
				),
			)
		);
	}

	/**
	 * Hooks callback that will print list fo active filters
	 *
	 * @return void
	 */
	public function add_active_filters_list() {
		$viwcpf_setting_params   = get_option( 'viwcpf_setting_params' );
		$show_active_filters     = ! empty( $viwcpf_setting_params['show_active_labels'] ) ? 'yes' : 'no';
		$active_filters_position = isset( $viwcpf_setting_params['active_position'] ) ? $viwcpf_setting_params['active_position'] : 'before_filters';

		if ( $show_active_filters != 'yes' ) {
			return;
		}

		switch ( $active_filters_position ) {
			case 'before_filters':

				add_action( 'viwcpf_before_menu_filters', array( $this, 'viwcpf_get_active_filter' ) );
				break;
			case 'after_filters':
				add_action( 'viwcpf_after_menu_filters', array( $this, 'viwcpf_get_active_filter' ) );
				break;
			case 'before_products':
				$locations = $this->get_before_product_locations();

				if ( ! $locations ) {
					return;
				}

				foreach ( $locations as $location ) {
					add_action( $location['hook'], array( $this, 'viwcpf_get_active_filter' ), $location['priority'] );
				}
				break;
		}
	}

	/**
	 * Count product with current query filtered by price
	 *
	 * @return string html
	 */
	public function viwcpf_count_product_by_price( $min_price, $max_price ) {
		if ( empty( $min_price ) && empty( $max_price ) ) {
			return;
		}
		if ( is_admin() ) {
			return;
		}
		global $wpdb;
		$tax_query  = $this->get_main_tax_query();
		$meta_query = $this->get_main_meta_query();

		$current_min_price = $min_price ? $min_price : 0;
		$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

		$price_query_sql = array();


		$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

		$price_query_sql['where'] = $wpdb->prepare(
			' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
			$current_min_price,
			$current_max_price
		);


		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND ( {$wpdb->posts}.post_status = 'publish' )";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 *
	 * This query allows counts to be generated based on the viewed products, not all products.
	 *
	 * @param array $term_ids Term IDs.
	 * @param string $taxonomy Taxonomy.
	 * @param string $query_type Query Type.
	 *
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;
		if ( is_admin() ) {
			return array_map( 'absint', [] );
		}
		$tax_query  = $this->get_main_tax_query();
		$meta_query = $this->get_main_meta_query();

		$min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
		$max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';

		$current_min_price = $min_price ? $min_price : 0;
		$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

		$price_query_sql = array();


		$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

		$price_query_sql['where'] = $wpdb->prepare(
			' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
			$current_min_price,
			$current_max_price
		);
		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$term_ids_sql   = '(' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) AS term_count, terms.term_id AS term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']} {$price_query_sql['where']}
			AND terms.term_id IN $term_ids_sql";

		$search = $this->get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query_sql         = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query_sql );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$results                      = $wpdb->get_results( $query_sql, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;

		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}

	/**
	 * Count products after other filters have occurred by adjusting the main query.
	 *
	 * @param int $rating Rating.
	 *
	 * @return int
	 */
	protected function get_filtered_product_count( $rating ) {
		if ( is_admin() ) {
			return absint( 0 );
		}
		global $wpdb;
		$tax_query         = WC_Query::get_main_tax_query();
		$meta_query        = WC_Query::get_main_meta_query();
		$min_price         = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
		$max_price         = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';
		$current_min_price = $min_price ? $min_price : 0;
		$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

		$price_query_sql = array();

		$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

		$price_query_sql['where'] = $wpdb->prepare(
			' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
			$current_min_price,
			$current_max_price
		);

		// Unset current rating filter.
		foreach ( $tax_query as $key => $query ) {
			if ( ! empty( $query['rating_filter'] ) ) {
				unset( $tax_query[ $key ] );
				break;
			}
		}

		// Set new rating filter.
		$product_visibility_terms = wc_get_product_visibility_term_ids();
		$tax_query[]              = array(
			'taxonomy'      => 'product_visibility',
			'field'         => 'term_taxonomy_id',
			'terms'         => $product_visibility_terms[ 'rated-' . $rating ],
			'operator'      => 'IN',
			'rating_filter' => true,
		);

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Wrapper for WC_Query::get_main_search_query_sql() to ease unit testing.
	 *
	 * @return string
	 * @since 4.4.0
	 */
	protected function get_main_search_query_sql() {
		return WC_Query::get_main_search_query_sql();
	}

	/**
	 * Wrapper for WC_Query::get_main_search_queryget_main_meta_query to ease unit testing.
	 *
	 * @return array
	 * @since 4.4.0
	 */
	protected function get_main_meta_query() {
		return WC_Query::get_main_meta_query();
	}

	/**
	 * Wrapper for WC_Query::get_main_tax_query() to ease unit testing.
	 *
	 * @return array
	 * @since 4.4.0
	 */
	protected function get_main_tax_query() {
		return WC_Query::get_main_tax_query();
	}

	/**
	 * Return the currently viewed taxonomy name.
	 *
	 * @return string
	 */
	protected function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}

	/**
	 * Return the currently viewed term ID.
	 *
	 * @return int
	 */
	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}

	/**
	 * Return the currently viewed term slug.
	 *
	 * @return int
	 */
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}

	/**
	 * Get filtered min price for current products.
	 *
	 * @return int|object
	 */
	protected function get_filtered_price() {
		global $wpdb;

		if ( is_admin() ) {
			return (object) array(
				'min_price' => 0,
				'max_price' => 0
			);
		}


		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = WC()->query->get_main_tax_query();
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Function get maximum and minimum metavalue type numeric by metakey
	 *
	 * @param    $meta_key : meta_key -- Default ''
	 * @param    $end : min or max -- Default 'max'
	 *
	 * @return int
	 * @since    1.0.0
	 */
	public function viwcpf_get_min_max_meta_values( $meta_key = '', $end = 'max' ) {

		global $wpdb;

		if ( empty( $meta_key ) ) {
			return;
		}
		if (
			empty( $end ) &&
			( $end != 'max' ) &&
			( $end != 'min' )
		) {
			return;
		}
		if ( $end == 'max' ) {
			$query_value = $wpdb->prepare(
				"SELECT max(cast( meta_value as unsigned)) 
                FROM  {$wpdb->postmeta}
                WHERE meta_key = '%s' 
            ",
				$meta_key
			);
		} else if ( $end == 'min' ) {
			$query_value = $wpdb->prepare(
				"SELECT min(cast( meta_value as unsigned)) 
                FROM  {$wpdb->postmeta}
                WHERE meta_key = '%s' 
            ",
				$meta_key
			);
		}

		$result_value = $wpdb->get_var( $query_value );

		return $result_value;
	}

	/**
	 * Count how many on sale products match current filter
	 *
	 * @return int Count of matching products
	 */
	public function count_query_relevant_on_sale_products() {
		global $wpdb;
		$tax_query         = WC_Query::get_main_tax_query();
		$meta_query        = WC_Query::get_main_meta_query();
		$min_price         = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
		$max_price         = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';
		$current_min_price = $min_price ? $min_price : 0;
		$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

		$price_query_sql = array();

		$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

		$price_query_sql['where'] = $wpdb->prepare(
			' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
			$current_min_price,
			$current_max_price
		);

		$meta_query[] = array(
			'relation' => 'OR',
			array(// Simple products type
				'key'     => '_sale_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'numeric'
			),
			array(// Variable products type
				'key'     => '_min_variation_sale_price',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'numeric'
			)
		);

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Count how many in stock products match current filter
	 *
	 * @return int Count of matching products
	 */
	public function count_query_relevant_in_stock_products() {
		global $wpdb;
		$tax_query         = WC_Query::get_main_tax_query();
		$meta_query        = WC_Query::get_main_meta_query();
		$min_price         = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
		$max_price         = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';
		$current_min_price = $min_price ? $min_price : 0;
		$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

		$price_query_sql = array();

		$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

		$price_query_sql['where'] = $wpdb->prepare(
			' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
			$current_min_price,
			$current_max_price
		);

		$meta_query[] = array(
			array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			)
		);

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Count products by metadata current filter
	 *
	 * @param $metakey
	 * @param $metavalue
	 * @param $metatype
	 *
	 * @return int Count of matching products
	 */
	public function get_filtered_metadata_product_counts( $metakey, $metavalue, $metatype ) {
		global $wpdb;
		if (
			empty( $metakey ) &&
			empty( $metavalue ) &&
			empty( $metatype )
		) {
			return;
		}
		$tax_query       = WC_Query::get_main_tax_query();
		$meta_query      = WC_Query::get_main_meta_query();
		$min_price       = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
		$max_price       = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';
		$price_query_sql = array();
		if ( isset( $_GET['min_price'] ) ) {

			$current_min_price = $min_price ? $min_price : 0;
			$current_max_price = $max_price ? $max_price : PHP_INT_MAX;

			$price_query_sql['join'] = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";

			$price_query_sql['where'] = $wpdb->prepare(
				' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
				$current_min_price,
				$current_max_price
			);
		} else {
			$price_query_sql['join'] = "";

			$price_query_sql['where'] = "";
		}


		/*Check type of metadata*/
		switch ( $metatype ) {
			case 'string':
				$meta_query[] = array(
					'key'     => $metakey,
					'value'   => $metavalue,
					'compare' => '=',
				);

				break;
			case 'numberic':

				$viwcpf_metavalue_arr = explode( "-", $metavalue );
				if ( sizeof( $viwcpf_metavalue_arr ) == 1 ) {
					array_push( $viwcpf_metavalue_arr, PHP_INT_MAX );
				}
				$meta_query[] = array(
					'key'     => $metakey,
					'value'   => $viwcpf_metavalue_arr,
					'compare' => 'BETWEEN'
				);
				break;
			default:
				break;
		}


		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
	}

	/**
	 * Generator gradient color from color separator
	 *
	 * @param $colors
	 * @param $color_separator
	 *
	 * @return string value of background
	 */
	public function get_gradient_color( $colors = array(), $color_separator = '1' ) {
		$viwcpf_setting_params = get_option( 'viwcpf_setting_params' );
		$color_swatches        = $viwcpf_setting_params['color_swatches'];
		$color_default         = $color_swatches['color_default'];
		if ( empty( $colors ) ) {
			$result = $color_default;
		} else {
			if ( ( $count_colors = count( $colors ) ) === 1 ) {
				$result = $colors[0];
				$result = $result ?: $color_default;
			} else {
				$temp = (int) floor( 100 / $count_colors );
				switch ( $color_separator ) {
					case '2':
						$result = 'linear-gradient( ' . implode( ',', $colors ) . ' )';
						break;
					case '3':
						$result = 'linear-gradient(to bottom left, ' . implode( ',', $colors ) . ' )';
						break;
					case '4':
						$result = 'linear-gradient( to bottom right, ' . implode( ',', $colors ) . ' )';
						break;
					case '5':
						$result = 'linear-gradient(to right,' . $colors[0] . ' ' . $temp . '%';
						for ( $i = 1; $i < $count_colors; $i ++ ) {
							$result .= ' , ' . $colors[ $i ] . ' ' . ( $i * $temp ) . '% ' . ( ( $i + 1 ) * $temp ) . '%';
						}
						$result .= ' )';
						break;
					case '6':
						$result = 'linear-gradient(' . $colors[0] . ' ' . $temp . '%';
						for ( $i = 1; $i < $count_colors; $i ++ ) {
							$result .= ' , ' . $colors[ $i ] . ' ' . ( $i * $temp ) . '% ' . ( ( $i + 1 ) * $temp ) . '%';
						}
						$result .= ' )';
						break;
					case '7':
						$result = 'linear-gradient(to bottom left, ' . $colors[0] . ' ' . $temp . '%';
						for ( $i = 1; $i < $count_colors; $i ++ ) {
							$result .= ' , ' . $colors[ $i ] . ' ' . ( $i * $temp ) . '% ' . ( ( $i + 1 ) * $temp ) . '%';
						}
						$result .= ' )';
						break;
					case '8':
						$result = 'linear-gradient(to bottom right, ' . $colors[0] . ' ' . $temp . '%';
						for ( $i = 1; $i < $count_colors; $i ++ ) {
							$result .= ' , ' . $colors[ $i ] . ' ' . ( $i * $temp ) . '% ' . ( ( $i + 1 ) * $temp ) . '%';
						}
						$result .= ' )';
						break;
					default:
						$result = 'linear-gradient( to right, ' . implode( ',', $colors ) . ' )';
				}
			}
		}

		return $result;
	}

	/*
    *
    * Generator class name show as toggle block filter
    *
    * @param array $filter_setting
    * @return string class name
    */

	public function class_show_as_toggle( $filter_setting ) {
		$class_name = '';
		if (
			empty( $filter_setting ) ||
			empty( $filter_setting['show_as_toggle'] )
		) {
			return;
		}
		if ( $filter_setting['show_as_toggle'] ) {
			$class_name .= ' collapsable  ';
			if ( $filter_setting['toggle_style'] == 'toggle_style-opened' ) {
				$class_name .= 'opened';
			} else {
				$class_name .= 'closed';
			}
		}

		return $class_name;
	}

	/**
	 * Generator template off-canvas and append its to footer
	 *
	 * @return string template off-canvas
	 */

	public function template_off_canvas() {
		global $wpdb;
		$off_canvas_data   = isset( get_option( 'viwcpf_setting_params' )['off_canvas'] ) ? get_option( 'viwcpf_setting_params' )['off_canvas'] : array();
		$modal_data        = isset( get_option( 'viwcpf_setting_params' )['modal'] ) ? get_option( 'viwcpf_setting_params' )['modal'] : array();
		$modal_menu_arr_id = array();
		$modal_conditional = false;
		if ( isset( $modal_data['icon']['box_shadow'] ) ) {
			$box_shadow_class = 'viwcpf-off_canvas-icon-border-box';
		} else {
			$box_shadow_class = '';
		}
		/*Get all menu filter id*/

		$args_menu_filter = array(
			'post_type'      => 'viwcpf_filter_menu',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		);

		$menu_filter_query = new WP_Query( $args_menu_filter );
		/*remove search query in request custom wp_query when isset $_GET['s']*/
		if ( isset( $_GET['s'] ) ) {
			$menu_filter_query->request = "
            SELECT  {$wpdb->posts}.* 
            FROM {$wpdb->posts}
            WHERE 1=1  AND 
                  {$wpdb->posts}.post_type = 'viwcpf_filter_menu' AND 
                  (
                      ({$wpdb->posts}.post_status = 'publish')
                  )  
            ORDER BY {$wpdb->posts}.menu_order 
            ASC 
            ";
		}

		if ( $menu_filter_query->have_posts() ) :
			while ( $menu_filter_query->have_posts() ) : $menu_filter_query->the_post();
				$filter_menu_meta = get_post_meta( get_the_ID(), 'viwcpf_filter_menu', true );
				if ( $filter_menu_meta ) {
					if ( $filter_menu_meta['viwcpf_show_in_modal'] ) {
						$modal_menu_arr_id[] = get_the_ID();
					}
				}
			endwhile;
		endif;
		wp_reset_postdata();
		/*Check conditional display of all menu filter*/
		if ( ! empty( $modal_menu_arr_id ) ) {
			foreach ( $modal_menu_arr_id as $menu_id ) {
				if ( $this->check_conditional_menu( $menu_id ) ) {
					$modal_conditional = true;
				}
			}
		}
		if (
			isset( $modal_data['enabled'] ) &&
			( $modal_data['icon_position'] !== 'top_product_loop' ) &&
			$modal_conditional
		) {
			?>
            <div class="viwcpf-off_canvas-icon-wrap viwcpf-off_canvas-<?php echo esc_attr( $modal_data['icon_position'] ); ?> viwcpf-off_canvas-icon-wrap-click viwcpf-off_canvas-icon-wrap-open <?php echo esc_attr( $box_shadow_class ); ?>"
                 data-trigger="click">
                <div class="viwcpf-off_canvas-icon viwcpf-off_canvas-icon-1 " data-display_style="1">
                    <i class="viwcpf-icon-filter-3"></i>
                </div>
            </div>
			<?php
		}
		if (
			isset( $modal_data['enabled'] ) &&
			isset( $modal_data['style'] ) &&
			( $modal_data['style'] === 'off_canvas' ) &&
			$modal_conditional
		) {
			?>

            <div class="viwcpf-off_canvas-wrap">
                <div class="viwcpf-off_canvas-overlay viwcpf-disabled"></div>
                <div class="viwcpf-off_canvas viwcpf-off_canvas-icon-2 viwcpf-off_canvas-<?php echo esc_attr( $off_canvas_data['general']['position'] ); ?>"
                     data-position="<?php echo esc_attr( $off_canvas_data['general']['position'] ); ?>"
                     data-effect="<?php echo esc_attr( $off_canvas_data['general']['effect_open'] ); ?>"
                >

                    <div class="viwcpf-off_canvas-content-wrap  viwcpf-off_canvas-content-close ">
                        <div class="viwcpf-off_canvas-header-wrap">
                            <h6 class="viwcpf-off_canvas-header-title-wrap"><?php esc_html_e( 'Filters', 'pofily-woo-product-filters' ); ?></h6>
                            <div class="viwcpf-off_canvas-close-wrap">
                                <i class="viwcpf-icon-filter-close"></i>
                            </div>
                        </div>
                        <div class="viwcpf-off_canvas-content-wrap1">
							<?php
							foreach ( $modal_menu_arr_id as $menu_id ) {
								echo do_shortcode( '[VIWCPF_SHORTCODE id_menu="' . $menu_id . '"]' );
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	public function show_filter_icon_woocommerce_before_shop_loop( $template_name ) {

		if ( $template_name === 'loop/orderby.php' ) {
			global $wpdb;
			$modal_data        = isset( get_option( 'viwcpf_setting_params' )['modal'] ) ? get_option( 'viwcpf_setting_params' )['modal'] : array();
			$modal_conditional = false;
			$modal_menu_arr_id = array();
			/*Get all menu filter id*/

			$args_menu_filter = array(
				'post_type'      => 'viwcpf_filter_menu',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => - 1,
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
			);

			$menu_filter_query = new WP_Query( $args_menu_filter );
			/*remove search query in request custom wp_query when isset $_GET['s']*/
			if ( isset( $_GET['s'] ) ) {
				$menu_filter_query->request = "
            SELECT  {$wpdb->posts}.* 
            FROM {$wpdb->posts}
            WHERE 1=1  AND 
                  {$wpdb->posts}.post_type = 'viwcpf_filter_menu' AND 
                  (
                      ({$wpdb->posts}.post_status = 'publish')
                  )  
            ORDER BY {$wpdb->posts}.menu_order 
            ASC 
            ";
			}

			if ( $menu_filter_query->have_posts() ) :
				while ( $menu_filter_query->have_posts() ) : $menu_filter_query->the_post();
					$filter_menu_meta = get_post_meta( get_the_ID(), 'viwcpf_filter_menu', true );
					if ( $filter_menu_meta ) {
						if ( $filter_menu_meta['viwcpf_show_in_modal'] ) {
							$modal_menu_arr_id[] = get_the_ID();
						}
					}
				endwhile;
			endif;
			wp_reset_postdata();
			/*Check conditional display of all menu filter*/
			if ( ! empty( $modal_menu_arr_id ) ) {
				foreach ( $modal_menu_arr_id as $menu_id ) {
					if ( $this->check_conditional_menu( $menu_id ) ) {
						$modal_conditional = true;
					}
				}
			}

			if (
				isset( $modal_data['enabled'] ) &&
				isset( $modal_data['icon_position'] ) &&
				( $modal_data['icon_position'] === 'top_product_loop' ) &&
				$modal_conditional
			) {
				echo '<!--Open wrapp shop filter-->
            
                <div class="viwcpf-filter-trigger-box">
                    <button id="viwcpf-shop-filters" class="viwcpf-filter-trigger viwcpf-trigger-off_canvas-open ">
                        <span class="icon-filter">
                             <i class="viwcpf-icon-filter-3"></i>
                        </span> ' . esc_html__( 'Filters', 'pofily-woo-product-filters' ) . '
                    </button>
                </div>';
			}
		}

	}


}

