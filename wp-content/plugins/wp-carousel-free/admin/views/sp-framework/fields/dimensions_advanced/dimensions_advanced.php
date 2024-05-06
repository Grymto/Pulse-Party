<?php
/**
 * Framework background field.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP Carousel
 * @subpackage WP_Carousel_free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_dimensions_advanced' ) ) {

	/**
	 * The Advanced Dimensions field class.
	 *
	 * @since 3.5
	 */
	class SP_WPCF_Field_dimensions_advanced extends SP_WPCF_Fields {

		/**
		 * Advanced Dimensions field constructor.
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
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'all_icon'           => '<i class="fa fa-arrows"></i>',
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
					'style'              => true,
					'styles'             => array( 'Soft-crop', 'Hard-crop' ),
					'unit'               => 'px',
					'min'                => '0',
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
				'min'    => '',
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
			$unit          = ! empty( $args['unit'] ) ? $args['unit'] : '';
			$is_unit       = ( ! empty( $unit ) ) ? ' wpcf--is-unit' : '';
			$value         = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="wpcf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';
				echo '<div class="wpcf--input wpcf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="wpcf--label wpcf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="wpcf-number' . esc_attr( $is_unit ) . '" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="wpcf--label wpcf--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
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

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="wpcf--input wpcf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="wpcf--label wpcf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="wpcf-number' . esc_attr( $is_unit ) . '" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="wpcf--label wpcf--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="wpcf--input wpcf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $args['styles'] as $style_prop ) {
					$selected = ( $value['style'] === $style_prop ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style_prop ) . '"' . esc_attr( $selected ) . '>' . esc_html( $style_prop ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . $default_value['color'] . '"' : '';
				echo '<div class="wpcf--input wpcf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="wpcf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
			}

			echo '</div>';
			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
