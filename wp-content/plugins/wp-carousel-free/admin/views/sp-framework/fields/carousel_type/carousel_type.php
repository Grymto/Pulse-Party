<?php
/**
 *
 * Field: carousel_type
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_carousel_type' ) ) {
	/**
	 *
	 * Field: carousel_type
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_carousel_type extends SP_WPCF_Fields {

		/**
		 * Carousel type field constructor.
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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="wpcf-siblings wpcf--image-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type          = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra         = ( $args['multiple'] ) ? '[]' : '';
					$active        = ( in_array( $key, $value ) ) ? ' wpcf--active' : '';
					$checked       = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only      = isset( $option['pro_only'] ) ? ' disabled' : '';
					$pro_only_text = isset( $option['pro_only'] ) ? '<strong class="ct-pro-only">' . esc_html__( 'PRO', 'wp-carousel-free' ) . '</strong>' : '';
					echo '<div class="wpcf--sibling wpcf--image' . $active . '">';
					// echo '<img src="' . $option . '" alt="img-' . $num++ . '" />';
					echo '<label><input' . esc_attr( $pro_only ) . ' type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>' . $pro_only_text;
					if ( isset( $option['image'] ) ) {
						echo '<img  src="' . $option['image'] . '" class="svg-icon">';
					} else {

						echo '<i class="' . esc_attr( $option['icon'] ) . '"></i>';
					}
					echo '<p class="sp-carousel-type">' . esc_html( $option['text'] ) . '</p></label>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</div>';

				}

				echo '</div>';

			}

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$bg_image  = array();
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$elements  = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
				$output = $elements . '{background-image:url(' . $this->value . ')' . $important . ';}';
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
