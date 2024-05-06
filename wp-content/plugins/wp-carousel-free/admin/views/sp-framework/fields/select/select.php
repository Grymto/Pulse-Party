<?php
/**
 *
 * Field: select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_select' ) ) {
	/**
	 *
	 * Field: select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_select extends SP_WPCF_Fields {

		/**
		 * Select field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'placeholder' => '',
					'chosen'      => false,
					'multiple'    => false,
					'sortable'    => false,
					'ajax'        => false,
					'settings'    => array(),
					'query_args'  => array(),
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				if ( ! empty( $args['ajax'] ) ) {
					$args['settings']['data']['type']  = $args['options'];
					$args['settings']['data']['nonce'] = wp_create_nonce( 'wpcf_chosen_ajax_nonce' );
					if ( ! empty( $args['query_args'] ) ) {
						$args['settings']['data']['query_args'] = $args['query_args'];
					}
				}

				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_sortable  = ( $args['chosen'] && $args['sortable'] ) ? ' wpcf-chosen-sortable' : '';
				$chosen_ajax      = ( $args['chosen'] && $args['ajax'] ) ? ' wpcf-chosen-ajax' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
				$field_class      = ( $args['chosen'] ) ? ' class="wpcf-chosen' . esc_attr( $chosen_rtl . $chosen_sortable . $chosen_ajax ) . '"' : '';
				$field_name       = $this->field_name( $multiple_name );
				$field_attr       = $this->field_attributes();
				$maybe_options    = $this->field['options'];
				$chosen_data_attr = ( $args['chosen'] && ! empty( $args['settings'] ) ) ? ' data-chosen-settings="' . esc_attr( json_encode( $args['settings'] ) ) . '"' : '';

				if ( is_string( $maybe_options ) && ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) {
					$options = $this->field_wp_query_data_title( $maybe_options, $this->value );
				} elseif ( is_string( $maybe_options ) ) {
					$options = $this->field_data( $maybe_options, false, $args['query_args'] );
				} else {
					$options = $maybe_options;
				}

				if ( ( is_array( $options ) && ! empty( $options ) ) || ( ! empty( $args['chosen'] ) && ! empty( $args['ajax'] ) ) ) {

					if ( ! empty( $args['chosen'] ) && ! empty( $args['multiple'] ) ) {

						echo '<select name="' . esc_attr( $field_name ) . '" class="wpcf-hide-select hidden"' . $multiple_attr . $field_attr . '>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  $multiple_attr , $field_attr are escaped before being passed in
						foreach ( $this->value as $option_key ) {
							echo '<option value="' . esc_attr( $option_key ) . '" selected>' . esc_attr( $option_key ) . '</option>';
						}
						echo '</select>';
						$field_name = '_pseudo';
						$field_attr = '';
					}

					// These attributes has been serialized above.
					echo '<select name="' . esc_attr( $field_name ) . '"' . $field_class . $multiple_attr . $placeholder_attr . $field_attr . $chosen_data_attr . '>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $field_class , $multiple_attr , $placeholder_attr , $field_attr , $chosen_data_attr are escaped before being passed in

					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
								echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_attr( $args['placeholder'] ) . '</option>';
						}
					}
					$selected_value = '';
					foreach ( $options as $option_key => $option ) {

						// if ( is_array( $option ) && ! empty( $option ) ) {

						// echo '<optgroup label="' . esc_attr( $option_key ) . '">';

						// foreach ( $option as $sub_key => $sub_value ) {
						// $selected = ( in_array( $sub_key, $this->value ) ) ? ' selected' : '';
						// echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $sub_value ) . '</option>';
						// }

						// echo '</optgroup>';

						// } else {
							$pro_only_value = isset( $option['pro_only'] ) ? $option['pro_only'] : '';
							$pro_only       = $pro_only_value ? ' disabled' : '';
							$option_text    = isset( $option['text'] ) ? $option['text'] : $option;
							$selected       = ( in_array( $option_key, $this->value, true ) ) ? ' selected' : '';

						if ( in_array( $option_key, $this->value, true ) ) {
							$selected_value = $option_key;
						}
							echo '<option' . esc_attr( $pro_only ) . ' value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $option_text ) . '</option>';
						// }
					}

					echo '</select>';
					if ( isset( $args['preview'] ) && $args['preview'] ) {
						echo '<img src="' . esc_url( SP_WPCF::include_plugin_url( 'assets/images/carousel-navigation/' . $selected_value . '.svg' ) ) . '" class="icon_preview">';
					}
				} else {

					echo ! empty( $this->field['empty_message'] ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'wp-carousel-free' );
				}
			}

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}
	}
}
