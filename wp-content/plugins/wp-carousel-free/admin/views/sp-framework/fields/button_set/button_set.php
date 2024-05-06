<?php
/**
 *
 * Field: button_set
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_button_set' ) ) {
	/**
	 *
	 * Field: button_set
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_button_set extends SP_WPCF_Fields {

		/**
		 * Button set field constructor.
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
					'multiple'   => false,
					'options'    => array(),
					'query_args' => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : '';

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<div class="wpcf-siblings wpcf--button-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

					foreach ( $options as $key => $option ) {

						$type           = ( $args['multiple'] ) ? 'checkbox' : 'radio';
						$extra          = ( $args['multiple'] ) ? '[]' : '';
						$active         = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' wpcf--active' : '';
						$checked        = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';
						$pro_only_class = ( isset( $option['pro_only'] ) && $option['pro_only'] ) ? ' wpcf-pro-only' : '';

						echo '<div class="wpcf--sibling wpcf--button' . esc_attr( $active . $pro_only_class ) . '">';
						echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						if ( isset( $option['option_name'] ) && ! empty( $option['option_name'] ) ) {
							echo $option['option_name'];
						} else {
							echo $option;
						}
						echo '</div>';

					}

					echo '</div>';

				} else {

					echo  ! empty( $this->field['empty_message'] ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'wp-carousel-free' );

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
