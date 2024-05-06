<?php
/**
 *
 * Field: gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_gallery' ) ) {
	/**
	 *
	 * Field: gallery
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_gallery extends SP_WPCF_Fields {

		/**
		 * Gallery field constructor.
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
					'add_title'   => esc_html__( 'Add Gallery', 'wp-carousel-free' ),
					'edit_title'  => esc_html__( 'Edit Gallery', 'wp-carousel-free' ),
					'clear_title' => esc_html__( 'Clear', 'wp-carousel-free' ),
				)
			);

			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo wp_kses_post( $this->field_before() );
			echo '<a href="#" class="button button-primary wpcf-button"><img src="' . esc_url( WPCAROUSELF_URL ) . 'admin/img/layouts/add-image.svg" alt="">' . esc_html( $args['add_title'] ) . '</a>';

			echo '<ul class="sp-gallery-images">';
			if ( ! empty( $this->value ) ) {

				$values = explode( ',', $this->value );
				foreach ( $values as $id ) {
					$attachment  = wp_get_attachment_image_src( $id, 'thumbnail' );
					$image_title = get_the_title( $id );
					echo '<li><img title="' . esc_attr( $image_title ) . '" src="' . esc_url( $attachment[0] ) . '" /></li>';
				}
			}
			echo '</ul>';
			echo '<ul> <li>';
			echo '<a href="#" class="button wpcf-edit-gallery' . esc_attr( $hidden ) . '"><i class="fa fa-pencil-square-o"></i>' . esc_html( $args['edit_title'] ) . '</a>';
			echo '</ul></li>';
			echo '<ul> <li>';
			echo '<a href="#" class="button wpcf-warning-primary wpcf-clear-gallery' . esc_attr( $hidden ) . '"><i class="fa fa-trash"></i>' . esc_html( $args['clear_title'] ) . '</a>';
			echo '</ul></li>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->field_attributes() is escaped before being passed in.

			echo wp_kses_post( $this->field_after() );

		}

	}
}
