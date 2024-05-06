<?php
/**
 *
 * Field: typography
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_typography' ) ) {

	/**
	 *
	 * Field: typography
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_typography extends SP_WPCF_Fields {

		/**
		 * Chosen
		 *
		 * @var bool
		 */
		public $chosen = false;

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

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

			echo wp_kses_post( $this->field_before() );

			$args = wp_parse_args(
				$this->field,
				array(
					'font_family'        => true,
					'font_weight'        => true,
					'font_style'         => true,
					'font_size'          => true,
					'line_height'        => true,
					'letter_spacing'     => true,
					'text_align'         => true,
					'text_transform'     => true,
					'color'              => true,
					'hover_color'        => false,
					'chosen'             => true,
					'preview'            => true,
					'subset'             => false,
					'multi_subset'       => false,
					'extra_styles'       => false,
					'backup_font_family' => false,
					'font_variant'       => false,
					'word_spacing'       => false,
					'text_decoration'    => false,
					'custom_style'       => false,
					'compact'            => false,
					'exclude'            => '',
					'unit'               => 'px',
					'line_height_unit'   => '',
					'preview_text'       => 'The quick brown fox jumps over the lazy dog',
					'margin_bottom'      => '',
				)
			);

			if ( $args['compact'] ) {
				$args['text_transform'] = false;
				$args['text_align']     = false;
				$args['font_size']      = false;
				$args['line_height']    = false;
				$args['letter_spacing'] = false;
				$args['preview']        = false;
				$args['color']          = false;
			}

			$default_value = array(
				'font-family'        => '',
				'font-weight'        => '',
				'font-style'         => '',
				'font-variant'       => '',
				'font-size'          => '',
				'line-height'        => '',
				'letter-spacing'     => '',
				'word-spacing'       => '',
				'text-align'         => '',
				'text-transform'     => '',
				'text-decoration'    => '',
				'backup-font-family' => '',
				'color'              => '',
				'hover_color'        => '',
				'custom-style'       => '',
				'type'               => '',
				'subset'             => '',
				'margin-bottom'      => '',
				'extra-styles'       => array(),
			);

			$default_value    = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value      = wp_parse_args( $this->value, $default_value );
			$this->chosen     = $args['chosen'];
			$chosen_class     = ( $this->chosen ) ? ' wpcf--chosen' : '';
			$line_height_unit = ( ! empty( $args['line_height_unit'] ) ) ? $args['line_height_unit'] : $args['unit'];

			echo '<div class="wpcf--typography' . esc_attr( $chosen_class ) . '" data-depend-id="' . esc_attr( $this->field['id'] ) . '" data-unit="' . esc_attr( $args['unit'] ) . '" data-line-height-unit="' . esc_attr( $line_height_unit ) . '" data-exclude="' . esc_attr( $args['exclude'] ) . '">';
			echo '<div class="wpcf--blocks wpcf--blocks-selects">';

			// Font Family.
			if ( ! empty( $args['font_family'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Family', 'wp-carousel-free' ) . '</div>';
				echo '<select disabled name="sp_wpcp_shortcode_options[wpcp_section_title_typography][font-family]" class="wpcf--font-family" data-placeholder="Select a font" style="display: none;">
				<option>Open Sans</option>
				</select>';
				echo '</div>';
			}

			// Backup Font Family.
			if ( ! empty( $args['backup_font_family'] ) ) {
				echo '<div class="wpcf--block wpcf--block-backup-font-family hidden">';
				echo '<div class="wpcf--title">' . esc_html__( 'Backup Font Family', 'wp-carousel-free' ) . '</div>';
				echo $this->create_select( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->create_select() is escaped before being passed in.
					apply_filters(
						'wpcf_field_typography_backup_font_family',
						array(
							'Arial, Helvetica, sans-serif',
							"'Arial Black', Gadget, sans-serif",
							"'Comic Sans MS', cursive, sans-serif",
							'Impact, Charcoal, sans-serif',
							"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
							'Tahoma, Geneva, sans-serif',
							"'Trebuchet MS', Helvetica, sans-serif'",
							'Verdana, Geneva, sans-serif',
							"'Courier New', Courier, monospace",
							"'Lucida Console', Monaco, monospace",
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
					'backup-font-family',
					esc_html__( 'Default', 'wp-carousel-free' )
				);
				echo '</div>';
			}

			// Font Style and Extra Style Select.
			if ( ! empty( $args['font_weight'] ) || ! empty( $args['font_style'] ) ) {

				// Font Style Select.
				echo '<div class="wpcf--block wpcf--block-font-style hidden">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Style', 'wp-carousel-free' ) . '</div>';
				echo '<select disabled class="wpcf--font-style-select" data-placeholder="Default">';
				echo '<option disabled value="">' . esc_html( ! $this->chosen ? __( 'Default', 'wp-carousel-free' ) : '' ) . '</option>';
				if ( ! empty( $this->value['font-weight'] ) || ! empty( $this->value['font-style'] ) ) {
					echo '<option disabled value="' . esc_attr( strtolower( $this->value['font-weight'] . $this->value['font-style'] ) ) . '" selected></option>';
				}
				echo '</select>';
				echo '<input disabled type="hidden" name="' . esc_attr( $this->field_name( '[font-weight]' ) ) . '" class="wpcf--font-weight" value="' . esc_attr( $this->value['font-weight'] ) . '" />';
				echo '<input disabled type="hidden" name="' . esc_attr( $this->field_name( '[font-style]' ) ) . '" class="wpcf--font-style" value="' . esc_attr( $this->value['font-style'] ) . '" />';

				// Extra Font Style Select.
				if ( ! empty( $args['extra_styles'] ) ) {
					echo '<div class="wpcf--block-extra-styles hidden">';
					echo ( ! $this->chosen ) ? '<div class="wpcf--title">' . esc_html__( 'Load Extra Styles', 'wp-carousel-free' ) . '</div>' : '';
					$placeholder = ( $this->chosen ) ? esc_html__( 'Load Extra Styles', 'wp-carousel-free' ) : esc_html__( 'Default', 'wp-carousel-free' );
					echo $this->create_select( $this->value['extra-styles'], 'extra-styles', $placeholder, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->create_select() is escaped before being passed in.
					echo '</div>';
				}

				echo '</div>';
			}

			// Subset.
			if ( ! empty( $args['subset'] ) ) {
				echo '<div class="wpcf--block wpcf--block-subset hidden">';
				echo '<div class="wpcf--title">' . esc_html__( 'Subset', 'wp-carousel-free' ) . '</div>';
				$subset = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
				echo '<select disabled name="sp_wpcp_shortcode_options[wpcp_section_title_typography][subset]" class="wpcf--subset" data-placeholder="Default" style="display: none;"><option value="default"></option></select>';
				echo '</div>';
			}

			// Text Align.
			if ( ! empty( $args['text_align'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Text Align', 'wp-carousel-free' ) . '</div>';
				echo '<select disabled name="sp_wpcp_shortcode_options[wpcp_section_title_typography][text-align]" class="wpcf--text-align" data-placeholder="Center" style="display: none;"><option value="">Center</option></select>';
				echo '</div>';
			}

			// Font Variant.
			if ( ! empty( $args['font_variant'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Variant', 'wp-carousel-free' ) . '</div>';
				echo $this->create_select( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->create_select() is escaped before being passed in.
					array(
						'normal'         => esc_html__( 'Normal', 'wp-carousel-free' ),
						'small-caps'     => esc_html__( 'Small Caps', 'wp-carousel-free' ),
						'all-small-caps' => esc_html__( 'All Small Caps', 'wp-carousel-free' ),
					),
					'font-variant',
					esc_html__( 'Default', 'wp-carousel-free' )
				);
				echo '</div>';
			}

			// Text Transform.
			if ( ! empty( $args['text_transform'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Text Transform', 'wp-carousel-free' ) . '</div>';
				echo '<select disabled name="sp_wpcp_shortcode_options[wpcp_title_typography][text-align]" class="wpcf--text-align" data-placeholder="Default" style="display: none;"><option value="none">None</option></select>';
				echo '</div>';
			}

			// Text Decoration.
			if ( ! empty( $args['text_decoration'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Text Decoration', 'wp-carousel-free' ) . '</div>';
				echo $this->create_select( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $this->create_select() is escaped before being passed in.
					array(
						'none'               => esc_html__( 'None', 'wp-carousel-free' ),
						'underline'          => esc_html__( 'Solid', 'wp-carousel-free' ),
						'underline double'   => esc_html__( 'Double', 'wp-carousel-free' ),
						'underline dotted'   => esc_html__( 'Dotted', 'wp-carousel-free' ),
						'underline dashed'   => esc_html__( 'Dashed', 'wp-carousel-free' ),
						'underline wavy'     => esc_html__( 'Wavy', 'wp-carousel-free' ),
						'underline overline' => esc_html__( 'Overline', 'wp-carousel-free' ),
						'line-through'       => esc_html__( 'Line-through', 'wp-carousel-free' ),
					),
					'text-decoration',
					esc_html__( 'Default', 'wp-carousel-free' )
				);
				echo '</div>';
			}
			echo '</div>';
			echo '<div class="wpcf--blocks wpcf--blocks-inputs">';

			// Font Size.
			if ( ! empty( $args['font_size'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Size', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input-wrap">';
				echo '<input disabled type="text" name="' . esc_attr( $this->field_name( '[font-size]' ) ) . '" class="wpcf--font-size wpcf--input wpcf-number" value="' . esc_attr( $this->value['font-size'] ) . '" />';
				echo '<span class="wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			// Line Height.
			if ( ! empty( $args['line_height'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Line Height', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[line-height]' ) ) . '" disabled class="wpcf--line-height wpcf--input wpcf-input-number" value="' . esc_attr( $this->value['line-height'] ) . '" step="any" />';
				echo '<span class="wpcf--unit">' . esc_attr( $line_height_unit ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			// Letter Spacing.
			if ( ! empty( $args['letter_spacing'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Letter Spacing', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[letter-spacing]' ) ) . '" class="wpcf--letter-spacing wpcf--input wpcf-input-number" disabled value="' . esc_attr( $this->value['letter-spacing'] ) . '" step="any" />';
				echo '<span class="wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			// Word Spacing.
			if ( ! empty( $args['word_spacing'] ) ) {
				echo '<div class="wpcf--block">';
				echo '<div class="wpcf--title">' . esc_html__( 'Word Spacing', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[word-spacing]' ) ) . '" class="wpcf--word-spacing wpcf--input wpcf-input-number" disabled value="' . esc_attr( $this->value['word-spacing'] ) . '" step="any" />';
				echo '<span class="wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';

			// Font Color.
			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="wpcf--block wpcf--block-font-color">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Color', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf-field-color">';
				echo '<input  type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" class="wpcf-color wpcf--color" value="' . esc_attr( $this->value['color'] ) . '"' . $default_color_attr . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $default_color_attr is escaped before being passed in.
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="wpcf--block wpcf--block-font-color">';
				echo '<div class="wpcf--title">' . esc_html__( 'Font Hover Color', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" class="wpcf-color wpcf--color" value="' . esc_attr( $this->value['hover_color'] ) . '"' . $default_color_attr . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $default_color_attr is escaped before being passed in.
				echo '</div>';
				echo '</div>';
			}

			// Custom style.
			if ( ! empty( $args['custom_style'] ) ) {
				echo '<div class="wpcf--block wpcf--block-custom-style">';
				echo '<div class="wpcf--title">' . esc_html__( 'Custom Style', 'wp-carousel-free' ) . '</div>';
				echo '<textarea name="' . esc_attr( $this->field_name( '[custom-style]' ) ) . '" class="wpcf--custom-style">' . esc_attr( $this->value['custom-style'] ) . '</textarea>';
				echo '</div>';
			}

			// Margin Bottom.
			if ( ! empty( $args['margin_bottom'] ) ) {
				echo '<div class="wpcf--block wpcf--margin-bottom">';
				echo '<div class="wpcf--title">' . esc_html__( 'Margin Bottom', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input-wrap">';
				echo '<div class="wpcf--margin-icon"><i class="fa fa-long-arrow-down"></i></div>';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-bottom]' ) ) . '" class="wpcf--margin-bottom wpcf--input wpcf-input-number" value="' . esc_attr( $this->value['margin-bottom'] ) . '" step="any" />';
				echo '<span class="wpcf--unit wpcf--margin-unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			// Preview.
			$always_preview = ( 'always' !== $args['preview'] ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {
				echo '<div class="wpcf--block wpcf--block-preview' . esc_attr( $always_preview ) . '">';
				echo '<div class="wpcf--toggle fa fa-toggle-off"></div>';
				echo '<div class="wpcf--preview">' . esc_attr( $args['preview_text'] ) . '</div>';
				echo '</div>';
			}

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[type]' ) ) . '" class="wpcf--type" value="' . esc_attr( $this->value['type'] ) . '" />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[unit]' ) ) . '" class="wpcf--unit-save" value="' . esc_attr( $args['unit'] ) . '" />';
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Create_select
		 *
		 * @param  array  $options options.
		 * @param  string $name  name.
		 * @param  string $placeholder placeholder.
		 * @param  bool   $is_multiple is multiple.
		 * @return statement
		 */
		public function create_select( $options, $name, $placeholder = '', $is_multiple = false ) {

			$multiple_name = ( $is_multiple ) ? '[]' : '';
			$multiple_attr = ( $is_multiple ) ? ' multiple data-multiple="true"' : '';
			$chosen_rtl    = ( $this->chosen && is_rtl() ) ? ' chosen-rtl' : '';

			$output  = '<select name="' . esc_attr( $this->field_name( '[' . $name . ']' . $multiple_name ) ) . '" class="wpcf--' . esc_attr( $name ) . esc_attr( $chosen_rtl ) . '" data-placeholder="' . esc_attr( $placeholder ) . '"' . $multiple_attr . '>';
			$output .= ( ! empty( $placeholder ) ) ? '<option value="">' . esc_attr( ( ! $this->chosen ) ? $placeholder : '' ) . '</option>' : '';
			if ( ! empty( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					if ( $is_multiple ) {
						$selected = ( in_array( $option_value, $this->value[ $name ] ) ) ? ' selected' : '';
						$output  .= '<option value="' . esc_attr( $option_value ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					} else {
						$option_key = ( is_numeric( $option_key ) ) ? $option_value : $option_key;
						$selected   = ( $option_key === $this->value[ $name ] ) ? ' selected' : '';
						$output    .= '<option value="' . esc_attr( $option_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					}
				}
			}
			$output .= '</select>';
			return $output;
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'wpcf-webfontloader' ) ) {
				SP_WPCF::include_plugin_file( 'fields/typography/google-fonts.php' );
				wp_enqueue_script( 'wpcf-webfontloader', 'https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.min.js', array( 'wpcf' ), '1.6.28', true );
				$webfonts = array();
				$customwebfonts = apply_filters( 'wpcf_field_typography_customwebfonts', array() );
				if ( ! empty( $customwebfonts ) ) {
					$webfonts['custom'] = array(
						'label' => esc_html__( 'Custom Web Fonts', 'wp-carousel-free' ),
						'fonts' => $customwebfonts,
					);
				}

				$webfonts['safe'] = array(
					'label' => esc_html__( 'Safe Web Fonts', 'wp-carousel-free' ),
					'fonts' => apply_filters(
						'wpcf_field_typography_safewebfonts',
						array(
							'Arial',
							'Arial Black',
							'Helvetica',
							'Times New Roman',
							'Courier New',
							'Tahoma',
							'Verdana',
							'Impact',
							'Trebuchet MS',
							'Comic Sans MS',
							'Lucida Console',
							'Lucida Sans Unicode',
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
				);

				$webfonts['google'] = array(
					'label' => esc_html__( 'Google Web Fonts', 'wp-carousel-free' ),
					'fonts' => apply_filters(
						'wpcf_field_typography_googlewebfonts',
						''
					),
				);

				$defaultstyles = apply_filters( 'wpcf_field_typography_defaultstyles', array( 'normal', 'italic', '700', '700italic' ) );
				$googlestyles  = apply_filters(
					'wpcf_field_typography_googlestyles',
					array(
						'100'       => 'Thin 100',
						'100italic' => 'Thin 100 Italic',
						'200'       => 'Extra-Light 200',
						'200italic' => 'Extra-Light 200 Italic',
						'300'       => 'Light 300',
						'300italic' => 'Light 300 Italic',
						'normal'    => 'Normal 400',
						'italic'    => 'Normal 400 Italic',
						'500'       => 'Medium 500',
						'500italic' => 'Medium 500 Italic',
						'600'       => 'Semi-Bold 600',
						'600italic' => 'Semi-Bold 600 Italic',
						'700'       => 'Bold 700',
						'700italic' => 'Bold 700 Italic',
						'800'       => 'Extra-Bold 800',
						'800italic' => 'Extra-Bold 800 Italic',
						'900'       => 'Black 900',
						'900italic' => 'Black 900 Italic',
					)
				);

				$webfonts = apply_filters( 'wpcf_field_typography_webfonts', $webfonts );

				wp_localize_script(
					'wpcf',
					'wpcf_typography_json',
					array(
						'webfonts'      => $webfonts,
						'defaultstyles' => $defaultstyles,
						'googlestyles'  => $googlestyles,
					)
				);
			}
		}
	}
}
