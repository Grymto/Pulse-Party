<?php
/**
 * Framework tabbed field.
 *
 * @link https://shapedplugin.com
 * @since 2.6.0
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_tabbed' ) ) {
	/**
	 *
	 * Field: tabbed
	 *
	 * @since 2.6.0
	 * @version 2.6.0
	 */
	class SP_WPCF_Field_tabbed extends SP_WPCF_Fields {

		/**
		 * Field constructor.
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

			$unallows = array( 'tabbed' );
			echo wp_kses_post( $this->field_before() );

			echo '<div class="wpcf-tabbed-nav">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="wpcf--icon ' . $tab['icon'] . '"></i>' : '';
				$tabbed_active = ( empty( $key ) ) ? ' class="wpcf-tabbed-active"' : '';

				echo '<a href="#"' . esc_attr( $tabbed_active ) . '>' . wp_kses_post( $tabbed_icon . $tab['title'] ) . '</a>';
			}
			echo '</div>';

			echo '<div class="wpcf-tabbed-sections">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';
				echo '<div class="wpcf-tabbed-section' . esc_attr( $tabbed_hidden ) . '">';
				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows, true ) ) {
						$field['_notice'] = true;
					}
					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique : '';
					SP_WPCF::field( $field, $field_value, $unique_id, 'field/tabbed' );
				}
				echo '</div>';
			}
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
