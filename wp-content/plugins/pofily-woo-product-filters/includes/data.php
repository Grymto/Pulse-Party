<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'VIWCPF_Woo_Product_Filters_Data' ) ) {
	class VIWCPF_Woo_Product_Filters_Data {
		private $data_default;
		private $default_color;

		public function __construct() {
			$this->data_default = array(
				'modal'              => array(
					'icon'          => array(
						'box_shadow'       => 'on',
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
					'top_loop'      => array(
						'column' => 4
					),
					'style'         => 'off_canvas',
					'icon_position' => 'bottom_left',
					'enabled'       => 'on',
					'auto_open'     => 'on'
				),
				'off_canvas'         => array(
					'general' => array(
						'position'    => 'bottom_left',
						'effect_open' => 'slide',
					),

					'content' => array(),
				),
				'show_active_labels' => '',
				'active_position'    => 'before_filters',
				'option_style'       => 'custom_style',
				'area'               => array(
					'color' => array(
						'title'      => '#434343',
						'background' => '#ffffff',
						'accent'     => '#0c0c0c',
					),
				),
				'label'              => array(
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
				'color_swatches'     => array(
					'btn_style'     => array(
						'btn_width'           => 22,
						'btn_height'          => 22,
						'btn_border_radius'   => '50%',
						'btn_color_separator' => 1,
					),
					'color_default' => '#fe2740',
				),
				'key'                => '',
				'display_metakey'    => array(),
			);

			$this->default_color = array(
				'white'               => '#FFFFFF',
				'white-smoke'         => '#F5F5F5',
				'gainsboro'           => '#DCDCDC',
				'light-gray'          => '#D3D3D3',
				'light-grey'          => '#D3D3D3',
				'silver'              => '#C0C0C0',
				'dark-gray'           => '#A9A9A9',
				'dark-grey'           => '#A9A9A9',
				'gray'                => '#dcdcdc',
				'grey'                => '#dcdcdc',
				'dim-gray'            => '#696969',
				'dim-grey'            => '#696969',
				'black'               => '#333',
				'snow'                => '#FFFAFA',
				'azure'               => '#F0FFFF',
				'ivory'               => '#FFFFF0',
				'honeydew'            => '#F0FFF0',
				'ghost-white'         => '#F8F8FF',
				'alice-blue'          => '#F0F8FF',
				'floral-white'        => '#FFFAF0',
				'lavender'            => '#E6E6FA',
				'light-steel-blue'    => '#B0C4DE',
				'light-slate-gray'    => '#778899',
				'slate-gray'          => '#708090',
				'mint-cream'          => '#F5FFFA',
				'sea-shell'           => '#FFF5EE',
				'papaya-whip'         => '#FFEFD5',
				'old-lace'            => '#FDF5E6',
				'linen'               => '#FAF0E6',
				'lavender-blush'      => '#FFF0F5',
				'misty-rose'          => '#FFE4E1',
				'peach-puff'          => '#FFDAB9',
				'navajo-white'        => '#FFDEAD',
				'moccasin'            => '#FFE4B5',
				'rosy-brown'          => '#BC8F8F',
				'tan'                 => '#D2B48C',
				'burly-wood'          => '#DEB887',
				'sandy-brown'         => '#F4A460',
				'peru'                => '#CD853F',
				'chocolate'           => '#D2691E',
				'sienna'              => '#A0522D',
				'saddle-brown'        => '#8B4513',
				'light-yellow'        => '#FFFFE0',
				'light-golden'        => '#FAFAD2',
				'rod-yellow'          => '#FAFAD2',
				'lemon-chiffon'       => '#FFFACD',
				'corn-silk'           => '#FFF8DC',
				'wheat'               => '#F5DEB3',
				'blanched-almond'     => '#FFEBCD',
				'bisque'              => '#FFE4C4',
				'beige'               => '#f1d299',
				'antique-white'       => '#FAEBD7',
				'pink'                => '#ff74bc',
				'light-pink'          => '#FFB6C1',
				'hot-pink'            => '#FF69B4',
				'deep-pink'           => '#FF1493',
				'pale-violet-red'     => '#DB7093',
				'medium-violet-red'   => '#C71585',
				'orchid'              => '#DA70D6',
				'magenta'             => '#FF00FF',
				'fuchsia'             => '#FF00FF',
				'violet'              => '#EE82EE',
				'plum'                => '#DDA0DD',
				'thistle'             => '#D8BFD8',
				'purple'              => '#de6fff',
				'medium-orchid'       => '#BA55D3',
				'dark-orchid'         => '#9932CC',
				'dark-violet'         => '#9400D3',
				'dark-magenta'        => '#8B008B',
				'medium-purple'       => '#9370DB',
				'medium-slate-blue'   => '#7B68EE',
				'dark-slate-blue'     => '#483D8B',
				'slate-blue'          => '#6A5ACD',
				'indigo'              => '#4B0082',
				'blue-violet'         => '#8A2BE2',
				'royal-blue'          => '#4169E1',
				'dark-blue'           => '#00008B',
				'medium-blue'         => '#0000CD',
				'midnight-blue'       => '#191970',
				'light-sky-blue'      => '#87CEFA',
				'sky-blue'            => '#87CEEB',
				'light-blue'          => '#ADD8E6',
				'dodger-blue'         => '#1E90FF',
				'deep-sky-blue'       => '#00BFFF',
				'corn-flower-blue'    => '#6495ED',
				'steel-blue'          => '#4682B4',
				'cadet-blue'          => '#5F9EA0',
				'powder-blue'         => '#B0E0E6',
				'navy'                => '#414796',
				'blue'                => '#53afff',
				'aqua-marine'         => '#7FFFD4',
				'pale-turquoise'      => '#AFEEEE',
				'medium-turquoise'    => '#48D1CC',
				'turquoise'           => '#40E0D0',
				'dark-turquoise'      => '#00CED1',
				'light-cyan'          => '#E0FFFF',
				'cyan'                => '#00FFFF',
				'aqua'                => '#00FFFF',
				'dark-cyan'           => '#008B8B',
				'teal'                => '#ff7567',
				'dark-slate-gray'     => '#2F4F4F',
				'light-sea-green'     => '#20B2AA',
				'medium-sea-green'    => '#3CB371',
				'medium-aqua-marine'  => '#66CDAA',
				'sea-green'           => '#2E8B57',
				'spring-green'        => '#00FF7F',
				'medium-spring-green' => '#00FA9A',
				'dark-sea-green'      => '#8FBC8F',
				'pale-green'          => '#98FB98',
				'light green'         => '#90EE90',
				'lime-green'          => '#32CD32',
				'lime'                => '#00FF00',
				'forest-green'        => '#228B22',
				'green'               => '#9de16f',
				'dark-green'          => '#006400',
				'green-yellow'        => '#ADFF2F',
				'chart-reuse'         => '#7FFF00',
				'lawn-green'          => '#7CFC00',
				'olive-drab'          => '#6B8E23',
				'dark-olive green'    => '#556B2F',
				'yellow-green'        => '#9ACD32',
				'yellow'              => '#ffe272',
				'olive'               => '#808000',
				'khaki'               => '#F0E68C',
				'dark-khaki'          => '#BDB76B',
				'pale-golden-rod'     => '#EEE8AA',
				'golden-rod	'      => '#DAA520',
				'dark-golden-rod'     => '#B8860B',
				'gold'                => '#FFD700',
				'orange'              => '#ff9351',
				'dark-orange'         => '#FF8C00',
				'orange-red	'      => '#FF4500',
				'light-salmon'        => '#FFA07A',
				'salmon'              => '#FA8072',
				'dark-salmon'         => '#E9967A',
				'light-coral'         => '#F08080',
				'indian-red'          => '#CD5C5C',
				'coral'               => '#FF7F50',
				'tomato'              => '#FF6347',
				'red'                 => '#ff3737',
				'crimson'             => '#DC143C',
				'firebrick'           => '#B22222',
				'brown'               => '#986a33',
				'dark-red'            => '#8B0000',
				'maroon'              => '#a72626',
			);
		}

		public function get_default( $name = "" ) {
			if ( ! $name ) {
				return $this->data_default;
			} elseif ( isset( $this->data_default[ $name ] ) ) {
				return apply_filters( 'viwcpf_woo_product_filters_params_default-' . $name, $this->data_default[ $name ] );
			} else {
				return false;
			}
		}

		public function get_default_color() {
			return $this->default_color;

		}

		public function viwcpf_data_default_block_filter() {
			$arr_data_block_filter_default = array(
				array(
					'title'     => 'Filter by price',
					'meta_data' => array(
						'name'        => 'Filter by price',
						'filter_for'  => 'filter_by_price',
						'filter_data' => array(
							'type_filter'     => 'range_slide',
							'type_show'       => 'range_slide',
							'multiselect'     => false,
							'multi_relation'  => 'AND',
							'order_by'        => 'name',
							'order_type'      => 'asc',
							'customize_value' => array(
								'min_price'  => '',
								'max_price'  => '',
								'step_price' => '',
							),
							'show_count_item' => false,
						),

						'settings' => array(
							'display_type'    => 'vertical',
							'show_clear'      => false,
							'show_as_toggle'  => false,
							'toggle_style'    => 'toggle_style-opened',
							'show_view_more'  => false,
							'view_more_limit' => 10,
						)
					)

				),
				array(
					'title'     => 'Filter by review',
					'meta_data' => array(
						'name'        => 'FILTER BY REVIEW',
						'filter_for'  => 'filter_by_review',
						'filter_data' => array(
							'type_show'       => 'button',
							'multiselect'     => 1,
							'show_count_item' => 1,
							'show_icon_star'  => 1,
							'multi_relation'  => 'OR',
							'order_by'        => 'name',
							'order_type'      => 'asc',
						),

						'settings' => array(
							'display_type'    => 'vertical',
							'show_clear'      => false,
							'show_as_toggle'  => false,
							'toggle_style'    => 'toggle_style-opened',
							'show_view_more'  => false,
							'view_more_limit' => 10,
						)
					),
				),
				array(
					'title'     => 'Filter by product name',
					'meta_data' => array(
						'name'        => 'FILTER BY PRODUCT NAME',
						'filter_for'  => 'filter_by_name_product',
						'filter_data' => array(
							'placeholder_search' => 'Enter your product',
							'type_show'          => 'search_field',
							'multiselect'        => false,
							'show_count_item'    => false,
							'multi_relation'     => 'AND',
							'order_by'           => 'name',
							'order_type'         => 'asc',
						),

						'settings' => array(
							'display_type'    => 'vertical',
							'show_clear'      => false,
							'show_as_toggle'  => false,
							'toggle_style'    => 'toggle_style-opened',
							'show_view_more'  => false,
							'view_more_limit' => 10,
						)
					),
				),
			);

			return $arr_data_block_filter_default;
		}

		public function viwcpf_create_default_post() {

			$args_query_filter = array(
				'post_type'      => array( 'viwcpf_filter_menu', 'viwcpf_filter_block' ),
				'posts_per_page' => - 1
			);
			$check_exist       = get_posts( $args_query_filter );
			if ( empty( $check_exist ) ) {
				$arr_id_block_filter = array();
				$data_blocks_filters = self::viwcpf_data_default_block_filter();
				if ( ! empty( $data_blocks_filters ) ) {
					foreach ( $data_blocks_filters as $data_block ) {
						$arr_id_block_filter[] = wp_insert_post( array(
							'post_title'  => $data_block['title'],
							'post_status' => 'publish',
							'post_type'   => 'viwcpf_filter_block',
							'meta_input'  => array(
								'viwcpf_filter_block' => $data_block['meta_data']
							)
						) );
					}
					if ( ! empty( $arr_id_block_filter ) ) {
						$str_id_block_filter = implode( ',', $arr_id_block_filter );


						$data_menu_filters  = array(
							'viwcpf_blocks_selected'       => $str_id_block_filter,
							'viwcpf_using_ajax'            => false,
							'viwcpf_show_button_submit'    => false,
							'viwcpf_block_relation'        => 'AND',
							'viwcpf_show_in_modal'         => true,
							'viwcpf_show_reset_button'     => false,
							'viwcpf_reset_button_position' => 'before_filter',
							'viwcpf_display_conditions'    => array(
								array(
									'type'    => 'include',
									'archive' => 'all',
								)
							)
						);
						$new_menu_filter_id = wp_insert_post( array(
							'post_title'  => 'Preset menu filter shop page',
							'post_status' => 'publish',
							'post_type'   => 'viwcpf_filter_menu',
						) );
						update_post_meta( $new_menu_filter_id, 'viwcpf_filter_menu', $data_menu_filters );

					}
				}

			}


		}

		public function viwcpf_upgrade_button() {
			?>
			<a href="https://1.envato.market/kj9ZJn"
			   target="_blank"
			   class="vi-ui button yellow"><?php esc_html_e( 'Upgrade this feature', 'pofily-woo-product-filters' ) ?></a>
			<?php
		}
	}

}

