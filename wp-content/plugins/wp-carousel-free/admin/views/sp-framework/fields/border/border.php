<?php
/**
 *
 * Field: border
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *  @package WP Carousel
	 * @subpackage wp-carousel-free/sp-framework
	 */
	class SP_WPCF_Field_border extends SP_WPCF_Fields {

		/**
		 * Create fields.
		 *
		 * @param  mixed $field field.
		 * @param  mixed $value value.
		 * @param  mixed $unique unique id.
		 * @param  mixed $where where to add.
		 * @param  mixed $parent parent.
		 * @return void
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
					'top_icon'           => '<i class="fa fa-long-arrow-alt-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-alt-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-alt-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-alt-right"></i>',
					'all_icon'           => '<i class="fa fa-arrows-alt"></i>',
					'top_placeholder'    => esc_html__( 'top', 'wp-carousel-free' ),
					'right_placeholder'  => esc_html__( 'right', 'wp-carousel-free' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'wp-carousel-free' ),
					'left_placeholder'   => esc_html__( 'left', 'wp-carousel-free' ),
					'all_placeholder'    => esc_html__( 'all', 'wp-carousel-free' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'hover_color'        => false,
					'style'              => true,
					'unit'               => 'px',
				)
			);

			$default_value = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'color'  => '',
				'style'  => 'solid',
				'all'    => '',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'wp-carousel-free' ),
				'dashed' => esc_html__( 'Dashed', 'wp-carousel-free' ),
				'dotted' => esc_html__( 'Dotted', 'wp-carousel-free' ),
				'double' => esc_html__( 'Double', 'wp-carousel-free' ),
				'inset'  => esc_html__( 'Inset', 'wp-carousel-free' ),
				'outset' => esc_html__( 'Outset', 'wp-carousel-free' ),
				'groove' => esc_html__( 'Groove', 'wp-carousel-free' ),
				'ridge'  => esc_html__( 'ridge', 'wp-carousel-free' ),
				'none'   => esc_html__( 'None', 'wp-carousel-free' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );
			echo '<div class="wpcf-option-above-title">';
			echo '<div class="wpcf--title"> ' . esc_html__( 'Width', 'wp-carousel-free' ) . ' </div>';
			echo '<div class="wpcf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="wpcf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="wpcf--label wpcf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . ' class="wpcf-input-number wpcf--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="wpcf--label wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
			} else {

				$properties = array();
				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {
					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="wpcf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="wpcf--label wpcf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . ' class="wpcf-input-number wpcf--is-unit" step="any" />';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo ( ! empty( $args['unit'] ) ) ? '<span class="wpcf--label wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';
				}
			}
			echo '</div>';
			echo '</div>';

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="wpcf-option-above-title">';
				echo '<div class="wpcf--title"> ' . esc_html__( 'Style', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="wpcf--color">';
				echo '<div class="wpcf--title">' . esc_html__( 'Color', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--color">';
				echo '<div class="wpcf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="wpcf-color"' . $default_color_attr . ' />';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="wpcf--color">';
				echo '<div class="wpcf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="wpcf-color"' . $default_color_attr . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				echo '</div>';
			}
			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// Properties.
			$top    = ( isset( $this->value['top'] ) && '' !== $this->value['top'] ) ? $this->value['top'] : '';
			$right  = ( isset( $this->value['right'] ) && '' !== $this->value['right'] ) ? $this->value['right'] : '';
			$bottom = ( isset( $this->value['bottom'] ) && '' !== $this->value['bottom'] ) ? $this->value['bottom'] : '';
			$left   = ( isset( $this->value['left'] ) && '' !== $this->value['left'] ) ? $this->value['left'] : '';
			$style  = ( isset( $this->value['style'] ) && '' !== $this->value['style'] ) ? $this->value['style'] : '';
			$color  = ( isset( $this->value['color'] ) && '' !== $this->value['color'] ) ? $this->value['color'] : '';
			$all    = ( isset( $this->value['all'] ) && '' !== $this->value['all'] ) ? $this->value['all'] : '';

			if ( ! empty( $this->field['all'] ) && ( '' !== $all || '' !== $color ) ) {

				$output  = $element . '{';
				$output .= ( '' !== $all ) ? 'border-width:' . $all . $unit . $important . ';' : '';
				$output .= ( '' !== $color ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( '' !== $style ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			} elseif ( '' !== $top || '' !== $right || '' !== $bottom || '' !== $left || '' !== $color ) {

				$output  = $element . '{';
				$output .= ( '' !== $top ) ? 'border-top-width:' . $top . $unit . $important . ';' : '';
				$output .= ( '' !== $right ) ? 'border-right-width:' . $right . $unit . $important . ';' : '';
				$output .= ( '' !== $bottom ) ? 'border-bottom-width:' . $bottom . $unit . $important . ';' : '';
				$output .= ( '' !== $left ) ? 'border-left-width:' . $left . $unit . $important . ';' : '';
				$output .= ( '' !== $color ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( '' !== $style ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			}
			$this->parent->output_css .= $output;
			return $output;
		}
	}
}
