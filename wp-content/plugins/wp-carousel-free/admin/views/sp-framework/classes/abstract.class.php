<?php
/**
 *
 * Abstract Class
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Abstract' ) ) {
	/**
	 *
	 * Abstract Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SP_WPCF_Abstract {

		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = '';
		/**
		 * Output_css
		 *
		 * @var string
		 */
		public $output_css = '';

		/**
		 * Abstract construct
		 *
		 * @return void
		 */
		public function __construct() {

			// Collect output css and typography.
			if ( ! empty( $this->args['output_css'] ) || ! empty( $this->args['enqueue_webfont'] ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'collect_output_css_and_typography' ), 10 );
				SP_WPCF::$css = apply_filters( "wpcf_{$this->unique}_output_css", SP_WPCF::$css, $this );
			}

		}

		/**
		 * Collect_output_css_and_typography
		 *
		 * @return void
		 */
		public function collect_output_css_and_typography() {
			$this->recursive_output_css( $this->pre_fields );
		}

		/**
		 * Recursive_output_css
		 *
		 * @param  array $fields fields.
		 * @param  array $combine_field combine field.
		 * @return void
		 */
		public function recursive_output_css( $fields = array(), $combine_field = array() ) {

			if ( ! empty( $fields ) ) {

				foreach ( $fields as $field ) {

					$field_id     = ( ! empty( $field['id'] ) ) ? $field['id'] : '';
					$field_type   = ( ! empty( $field['type'] ) ) ? $field['type'] : '';
					$field_output = ( ! empty( $field['output'] ) ) ? $field['output'] : '';
					$field_check  = ( 'typography' === $field_type || $field_output ) ? true : false;
					$field_class  = 'SP_WPCF_Field_' . $field_type;

					if ( $field_type && $field_id ) {

						if ( 'fieldset' === $field_type ) {
							if ( ! empty( $field['fields'] ) ) {
								$this->recursive_output_css( $field['fields'], $field );
							}
						}

						if ( 'accordion' === $field_type ) {
							if ( ! empty( $field['accordions'] ) ) {
								foreach ( $field['accordions'] as $accordion ) {
									$this->recursive_output_css( $accordion['fields'], $field );
								}
							}
						}

						if ( 'tabbed' === $field_type ) {
							if ( ! empty( $field['tabs'] ) ) {
								foreach ( $field['tabs'] as $accordion ) {
									$this->recursive_output_css( $accordion['fields'], $field );
								}
							}
						}

						if ( class_exists( $field_class ) ) {

							if ( method_exists( $field_class, 'output' ) || method_exists( $field_class, 'enqueue_google_fonts' ) ) {

								$field_value = '';

								if ( $field_check && ( 'options' === $this->abstract || 'customize' === $this->abstract ) ) {

									if ( ! empty( $combine_field ) ) {

										$field_value = ( isset( $this->options[ $combine_field['id'] ][ $field_id ] ) ) ? $this->options[ $combine_field['id'] ][ $field_id ] : '';

									} else {

										$field_value = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';

									}
								} elseif ( $field_check && ( 'metabox' === $this->abstract && is_singular() || 'taxonomy' === $this->abstract && is_archive() ) ) {

									if ( ! empty( $combine_field ) ) {

										$meta_value  = $this->get_meta_value( $combine_field );
										$field_value = ( isset( $meta_value[ $field_id ] ) ) ? $meta_value[ $field_id ] : '';

									} else {

										$meta_value  = $this->get_meta_value( $field );
										$field_value = ( isset( $meta_value ) ) ? $meta_value : '';

									}
								}

								$instance = new $field_class( $field, $field_value, $this->unique, 'wp/enqueue', $this );

								// Output css.
								if ( $field_output && $this->args['output_css'] ) {
									SP_WPCF::$css .= $instance->output();
								}

								unset( $instance );

							}
						}
					}
				}
			}

		}

	}
}
