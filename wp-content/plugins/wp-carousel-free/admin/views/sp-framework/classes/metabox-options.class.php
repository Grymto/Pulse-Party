<?php
/**
 *
 * Metabox Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Metabox' ) ) {

	/**
	 *
	 * Metabox Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Metabox extends SP_WPCF_Abstract {

		// Constants.
		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = 'metabox';
		/**
		 * Pre_fields
		 *
		 * @var array
		 */
		public $pre_fields = array();
		/**
		 * Sections
		 *
		 * @var array
		 */
		public $sections = array();
		/**
		 * Post_type
		 *
		 * @var array
		 */
		public $post_type = array();
		/**
		 * Post_formats
		 *
		 * @var array
		 */
		public $post_formats = array();
		/**
		 * Page_templates
		 *
		 * @var array
		 */
		public $page_templates = array();
		/**
		 * Args
		 *
		 * @var array
		 */
		public $args = array(
			'title'              => '',
			'post_type'          => 'post',
			'data_type'          => 'serialize',
			'context'            => 'advanced',
			'priority'           => 'default',
			'exclude_post_types' => array(),
			'page_templates'     => '',
			'post_formats'       => '',
			'show_reset'         => false,
			'show_restore'       => false,
			'enqueue_webfont'    => true,
			'async_webfont'      => false,
			'output_css'         => true,
			'nav'                => 'normal',
			'theme'              => 'dark',
			'class'              => '',
			'defaults'           => array(),
		);

		/**
		 * Run metabox construct
		 *
		 * @param  string $key key.
		 * @param  array  $params params.
		 * @return void
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique         = $key;
			$this->args           = apply_filters( "wpcf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections       = apply_filters( "wpcf_{$this->unique}_sections", $params['sections'], $this );
			$this->post_type      = ( is_array( $this->args['post_type'] ) ) ? $this->args['post_type'] : array_filter( (array) $this->args['post_type'] );
			$this->post_formats   = ( is_array( $this->args['post_formats'] ) ) ? $this->args['post_formats'] : array_filter( (array) $this->args['post_formats'] );
			$this->page_templates = ( is_array( $this->args['page_templates'] ) ) ? $this->args['page_templates'] : array_filter( (array) $this->args['page_templates'] );
			$this->pre_fields     = $this->pre_fields( $this->sections );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );
			add_action( 'edit_attachment', array( $this, 'save_meta_box' ) );

			if ( ! empty( $this->page_templates ) || ! empty( $this->post_formats ) || ! empty( $this->args['class'] ) ) {
				foreach ( $this->post_type as $post_type ) {
					add_filter( 'postbox_classes_' . $post_type . '_' . $this->unique, array( $this, 'add_metabox_classes' ) );
				}
			}

			// wp enqueue for typography and output css.
			parent::__construct();

		}


		/**
		 * Instance
		 *
		 * @param  string $key key.
		 * @param  array  $params params.
		 * @return array
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		/**
		 * Pre_fields
		 *
		 * @param  mixed $sections sections.
		 * @return array
		 */
		public function pre_fields( $sections ) {
			$result = array();
			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}
			return $result;
		}

		/**
		 * Add_metabox_classes
		 *
		 * @param  mixed $classes Classes.
		 * @return string
		 */
		public function add_metabox_classes( $classes ) {

			global $post;
			if ( ! empty( $this->post_formats ) ) {

				$saved_post_format = ( is_object( $post ) ) ? get_post_format( $post ) : false;
				$saved_post_format = ( ! empty( $saved_post_format ) ) ? $saved_post_format : 'default';

				$classes[] = 'wpcf-post-formats';

				// Sanitize post format for standard to default.
				if ( ( $key = array_search( 'standard', $this->post_formats ) ) !== false ) {
					$this->post_formats[ $key ] = 'default';
				}

				foreach ( $this->post_formats as $format ) {
					$classes[] = 'wpcf-post-format-' . $format;
				}

				if ( ! in_array( $saved_post_format, $this->post_formats ) ) {
					$classes[] = 'wpcf-metabox-hide';
				} else {
					$classes[] = 'wpcf-metabox-show';
				}
			}

			if ( ! empty( $this->page_templates ) ) {

				$saved_template = ( is_object( $post ) && ! empty( $post->page_template ) ) ? $post->page_template : 'default';

				$classes[] = 'wpcf-page-templates';

				foreach ( $this->page_templates as $template ) {
					$classes[] = 'wpcf-page-' . preg_replace( '/[^a-zA-Z0-9]+/', '-', strtolower( $template ) );
				}

				if ( ! in_array( $saved_template, $this->page_templates ) ) {
					$classes[] = 'wpcf-metabox-hide';
				} else {
					$classes[] = 'wpcf-metabox-show';
				}
			}

			if ( ! empty( $this->args['class'] ) ) {
				$classes[] = $this->args['class'];
			}
			return $classes;
		}

		/**
		 * Add metabox.
		 *
		 * @param  mixed $post_type post type.
		 * @return void
		 */
		public function add_meta_box( $post_type ) {

			if ( ! in_array( $post_type, $this->args['exclude_post_types'] ) ) {
				add_meta_box( $this->unique, $this->args['title'], array( $this, 'add_meta_box_content' ), $this->post_type, $this->args['context'], $this->args['priority'], $this->args );
			}
		}

		/**
		 * Get default value
		 *
		 * @param  mixed $field field.
		 * @return string
		 */
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;
			return $default;
		}

		/**
		 * Get meta value.
		 *
		 * @param  mixed $field field.
		 * @return statement
		 */
		public function get_meta_value( $field ) {

			global $post;
			$value = null;
			if ( is_object( $post ) && ! empty( $field['id'] ) ) {

				if ( 'serialize' !== $this->args['data_type'] ) {
					$meta  = get_post_meta( $post->ID, $field['id'] );
					$value = ( isset( $meta[0] ) ) ? $meta[0] : null;
				} else {
					$meta  = get_post_meta( $post->ID, $this->unique, true );
					$value = ( isset( $meta[ $field['id'] ] ) ) ? $meta[ $field['id'] ] : null;
				}
			} elseif ( 'tabbed' === $field['type'] ) {
				$value = get_post_meta( $post->ID, $this->unique, true );
			}

			$default = ( isset( $field['id'] ) ) ? $this->get_default( $field ) : '';
			$value   = ( isset( $value ) ) ? $value : $default;

			return $value;
		}

		/**
		 * Add metabox content
		 *
		 * @param mixed $post post.
		 * @param  mixed $callback call back function.
		 * @return void
		 */
		public function add_meta_box_content( $post, $callback ) {

			global $post;
			$has_nav   = ( count( $this->sections ) > 1 && 'side' !== $this->args['context'] ) ? true : false;
			$show_all  = ( ! $has_nav ) ? ' wpcf-show-all' : '';
			$post_type = ( is_object( $post ) ) ? $post->post_type : '';
			$errors    = ( is_object( $post ) ) ? get_post_meta( $post->ID, '_wpcf_errors_' . $this->unique, true ) : array();
			$errors    = ( ! empty( $errors ) ) ? $errors : array();
			$theme     = ( $this->args['theme'] ) ? ' wpcf-theme-' . $this->args['theme'] : '';
			$nav_type  = ( 'inline' === $this->args['nav'] ) ? 'inline' : 'normal';

			if ( is_object( $post ) && ! empty( $errors ) ) {
				delete_post_meta( $post->ID, '_wpcf_errors_' . $this->unique );
			}

			wp_nonce_field( 'wpcf_metabox_nonce', 'wpcf_metabox_nonce' . $this->unique );
			echo '<div class="wpcf wpcf-metabox' . esc_attr( $theme ) . '">';
			echo '<div class="wpcf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="wpcf-nav wpcf-nav-' . esc_attr( $nav_type ) . ' wpcf-nav-metabox" data-unique="' . esc_attr( $this->unique ) . '">';

				echo '<ul>';

				$tab_key = 0;
				foreach ( $this->sections as $section ) {

					if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
						continue;
					}

					$tab_error = ( ! empty( $errors['sections'][ $tab_key ] ) ) ? '<i class="wpcf-label-error wpcf-error">!</i>' : '';
					$tab_icon  = ( ! empty( $section['icon'] ) ) ? '<i class="wpcf-tab-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

					echo '<li><a href="#" data-section="' . esc_attr( $this->unique ) . '_' . esc_attr( $tab_key ) . '">' . wp_kses_post( $tab_icon . $section['title'] . $tab_error ) . '</a></li>';

					$tab_key++;
				}

				echo '</ul>';
				echo '</div>';
			}

			echo '<div class="wpcf-content">';
			echo '<div class="wpcf-sections">';
			$section_key = 0;

			foreach ( $this->sections as $section ) {

				if ( ! empty( $section['post_type'] ) && ! in_array( $post_type, array_filter( (array) $section['post_type'] ) ) ) {
					continue;
				}

				$section_onload = ( ! $has_nav ) ? ' wpcf-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="wpcf-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';

				echo '<div id="wpcf-section-' . esc_attr( $this->unique ) . '_' . esc_attr( $section_key ) . '" class="wpcf-section hidden' . esc_attr( $section_onload . $section_class ) . '" >';

				echo ( $section_title || $section_icon ) ? '<div class="wpcf-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="wpcf-field wpcf-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {

						if ( ! empty( $field['id'] ) && ! empty( $errors['fields'][ $field['id'] ] ) ) {
							$field['_error'] = $errors['fields'][ $field['id'] ];
						}

						if ( ! empty( $field['id'] ) ) {
							$field['default'] = $this->get_default( $field );
						}
						SP_WPCF::field( $field, $this->get_meta_value( $field ), $this->unique, 'metabox' );
					}
				} else {
					echo '<div class="wpcf-no-option">' . esc_html__( 'No data available.', 'wpcf' ) . '</div>';
				}

				echo '</div>';
				$section_key++;
			}

			echo '</div>';

			echo '<a class="btn btn-success" id="sp__wpcf-show-preview" data-id="' . esc_attr( $post->ID ) . '" href=""> <i class="fa fa-eye" aria-hidden="true"></i> Show Preview</a>';
			echo '<div class="spwpc-pagination-not-work"><i class="fa fa-check-circle"></i>  The pagination will work in the frontend well. </div>';

			if ( ! empty( $this->args['show_restore'] ) || ! empty( $this->args['show_reset'] ) ) {

				echo '<div class="wpcf-sections-reset">';
				echo '<label>';
				echo '<input type="checkbox" name="' . esc_attr( $this->unique ) . '[_reset]" />';
				echo '<span class="button wpcf-button-reset">' . esc_html__( 'Reset', 'wpcf' ) . '</span>';
				echo '<span class="button wpcf-button-cancel">' . sprintf( '<small>( %s )</small> %s', esc_html__( 'update post', 'wpcf' ), esc_html__( 'Cancel', 'wpcf' ) ) . '</span>';
				echo '</label>';
				echo '</div>';

			}
			echo '</div>';
			echo ( $has_nav && 'normal' === $nav_type ) ? '<div class="wpcf-nav-background"></div>' : '';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';
		}

		/**
		 * Save metabox.
		 *
		 * @param  int $post_id id.
		 * @return mixed
		 */
		public function save_meta_box( $post_id ) {

			$count    = 1;
			$data     = array();
			$errors   = array();
			$noncekey = 'wpcf_metabox_nonce' . $this->unique;
			$nonce    = ( ! empty( $_POST[ $noncekey ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ $noncekey ] ) ) : '';

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! wp_verify_nonce( $nonce, 'wpcf_metabox_nonce' ) ) {
				return $post_id;
			}

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach.
			$request = ( ! empty( $_POST[ $this->unique ] ) ) ? $_POST[ $this->unique ] : array();  // phpcs:ignore

			if ( ! empty( $request ) ) {
				foreach ( $this->sections as $section ) {
					if ( ! empty( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							if ( 'tabbed' === $field['type'] ) {
								$tabs = $field['tabs'];
								foreach ( $tabs as $fields ) {
									$fields = $fields['fields'];
									foreach ( $fields as $field ) {
										$field_id    = ! empty( $field['id'] ) ? sanitize_key( $field['id'] ) : '';
										$field_value = isset( $request[ $field_id ] ) ? $request[ $field_id ] : '';

										// Sanitize "post" request of field.
										if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
											$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );
										} else {
											if ( is_array( $field_value ) ) {
												$data[ $field_id ] = wp_kses_post_deep( $field_value );
											} else {
												$data[ $field_id ] = wp_kses_post( $field_value );
											}
										}

										// Validate "post" request of field.
										if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
											$has_validated = call_user_func( $field['validate'], $field_value );

											if ( ! empty( $has_validated ) ) {
												$errors['sections'][ $count ]  = true;
												$errors['fields'][ $field_id ] = $has_validated;
												$data[ $field_id ]             = $this->get_meta_value( $field );
											}
										}
									}
								}
							} elseif ( ! empty( $field['id'] ) ) {

								$field_id    = sanitize_key( $field['id'] );
								$field_value = isset( $request[ $field_id ] ) ? $request[ $field_id ] : '';
								// Sanitize "post" request of field.
								if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
										$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );
								} else {
									if ( is_array( $field_value ) ) {
										$data[ $field_id ] = wp_kses_post_deep( $field_value );
									} else {
										$data[ $field_id ] = wp_kses_post( $field_value );
									}
								}

								// Validate "post" request of field.
								if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
									$has_validated = call_user_func( $field['validate'], $field_value );
									if ( ! empty( $has_validated ) ) {
										$errors['sections'][ $count ]  = true;
										$errors['fields'][ $field_id ] = $has_validated;
										$data[ $field_id ]             = $this->get_meta_value( $field );
									}
								}
							}
						}
					}
					$count++;
				}
			}

			$data = apply_filters( "wpcf_{$this->unique}_save", $data, $post_id, $this );
			do_action( "wpcf_{$this->unique}_save_before", $data, $post_id, $this );

			if ( empty( $data ) || ! empty( $request['_reset'] ) ) {
				if ( 'serialize' !== $this->args['data_type'] ) {
					foreach ( $data as $key => $value ) {
						delete_post_meta( $post_id, $key );
					}
				} else {
					delete_post_meta( $post_id, $this->unique );
				}
			} else {

				if ( 'serialize' !== $this->args['data_type'] ) {
					foreach ( $data as $key => $value ) {
						update_post_meta( $post_id, $key, $value );
					}
				} else {
					update_post_meta( $post_id, $this->unique, $data );
				}

				if ( ! empty( $errors ) ) {
					update_post_meta( $post_id, '_wpcf_errors_' . $this->unique, $errors );
				}
			}

			do_action( "wpcf_{$this->unique}_saved", $data, $post_id, $this );
			do_action( "wpcf_{$this->unique}_save_after", $data, $post_id, $this );
		}
	}
}
