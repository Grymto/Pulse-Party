<?php
/**
 * The admin-setup functionality of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF' ) ) {
	/**
	 *
	 * Setup Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF {

		// Default constants.

		/**
		 * Premium.
		 *
		 * @var string
		 */
		public static $premium = true;
		/**
		 * Version.
		 *
		 * @var string
		 */
		public static $version = WPCAROUSELF_VERSION;
		/**
		 * Dir.
		 *
		 * @var string
		 */
		public static $dir = '';
		/**
		 * Url.
		 *
		 * @var string
		 */
		public static $url = '';
		/**
		 * Css.
		 *
		 * @var string
		 */
		public static $css = '';

		/**
		 * File.
		 *
		 * @var string
		 */
		public static $file = '';
		/**
		 * Enqueue.
		 *
		 * @var string
		 */
		public static $enqueue = false;
		/**
		 * Init.
		 *
		 * @var array
		 */
		public static $inited = array();
		/**
		 * Field.
		 *
		 * @var array
		 */
		public static $fields = array();
		/**
		 * Args.
		 *
		 * @var array
		 */
		public static $args = array(
			'admin_options'   => array(),
			'metabox_options' => array(),
		);

		/**
		 * Shortcode instances.
		 *
		 * @var string
		 */
		private static $instance = null;

		/**
		 * Init
		 *
		 * @param  mixed $file file.
		 * @return statement
		 */
		public static function init( $file = __FILE__ ) {

			// Set file constant.
			self::$file = $file;

			// Set constants.
			self::constants();

			// Include files.
			self::includes();

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;

		}

		/**
		 * Initialize.
		 *
		 * @return void
		 */
		public function __construct() {

			// Init action.
			do_action( 'wpcf_init' );

			self::set_locale();

			add_action( 'after_setup_theme', array( 'SP_WPCF', 'setup' ) );
			add_action( 'init', array( 'SP_WPCF', 'setup' ) );
			add_action( 'switch_theme', array( 'SP_WPCF', 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( 'SP_WPCF', 'add_admin_enqueue_scripts' ) );
		}

		/**
		 * Helper function to generate plugin installation and activation process.
		 *
		 * @param string      $plugin_slug The slug of the plugin.
		 * @param string      $button_text The text for the install/activate button.
		 * @param string|null $activate_capability Optional capability required for activation.
		 * @param string|null $class_checks Check the plugin main class existed or not.
		 * @param string|null $slug The plugin slug.
		 *
		 * @return array Plugin data array containing link, status, and activate URL.
		 */
		public static function plugin_installation_activation( $plugin_slug, $button_text, $activate_capability = null, $class_checks = null, $slug = null ) {
			$plugin_link = add_query_arg(
				array(
					'tab'       => 'plugin-information',
					'plugin'    => $slug,
					'TB_iframe' => 'true',
					'width'     => '772',
					'height'    => '540',
				),
				admin_url( 'plugin-install.php' )
			);

			$get_plugins         = get_plugins();
			$activate_plugin_url = '';
			$has_plugin          = '';

			$is_plugin_active = false;
			// Check if any of the classes exist.
			foreach ( $class_checks as $class_check ) {
				if ( class_exists( $class_check ) ) {
					$is_plugin_active = true;
					break;
				}
			}

			// Check if the plugin is active using is_plugin_active().
			if ( $is_plugin_active || is_plugin_active( $plugin_slug ) ) {
				$has_plugin = ' activated';
			} elseif ( isset( $get_plugins[ $plugin_slug ] ) ) {
				if ( 'woo-quickview/woo-quick-view.php' === $plugin_slug ) {
					$has_plugin = ' activate_plugin';
				} else {
					$has_plugin = ' activate_brand';
				}
				$button_text = 'Activate Now';
				if ( $activate_capability && current_user_can( $activate_capability ) ) {
					$activate_plugin_url = add_query_arg(
						array(
							'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_slug ),
							'action'   => 'activate',
							'plugin'   => $plugin_slug,
						),
						network_admin_url( 'plugins.php' )
					);
				}
			}

			return compact( 'plugin_link', 'has_plugin', 'button_text', 'activate_plugin_url', 'slug' );
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the WP_Carousel_Free_I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since  2.0.0
		 */
		public static function set_locale() {
			include_once WPCAROUSELF_INCLUDES . '/class-wp-carousel-free-i18n.php';
			$plugin_i18n = new WP_Carousel_Free_I18n();
			$plugin_i18n->load_plugin_textdomain();
		}
		/**
		 * Setup frameworks
		 *
		 * @return void
		 */
		public static function setup() {

			// Welcome page.

			// Setup admin option framework.
			$params = array();
			if ( class_exists( 'SP_WPCF_Options' ) && ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						SP_WPCF_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							$value['output_css']                     = false;
							$value['enqueue_webfont']                = false;
							self::$args['customize_options'][ $key ] = $value;
							self::$inited[ $key ]                    = null;
						}
					}
				}
			}
			// Setup metabox option framework.
			$params = array();
			if ( class_exists( 'SP_WPCF_Metabox' ) && ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

							$params['args']       = $value;
							$params['sections']   = self::$args['sections'][ $key ];
							self::$inited[ $key ] = true;

							SP_WPCF_Metabox::instance( $key, $params );

					}
				}
			}
			do_action( 'wpcf_loaded' );

		}

		/**
		 * Create options
		 *
		 * @param  mixed $id id.
		 * @param  mixed $args array.
		 * @return void
		 */
		public static function createOptions( $id, $args = array() ) {
			self::$args['admin_options'][ $id ] = $args;
		}
		/**
		 * Create metabox options
		 *
		 * @param  mixed $id id.
		 * @param  mixed $args array.
		 * @return void
		 */
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metabox_options'][ $id ] = $args;
		}
		/**
		 * Create section.
		 *
		 * @param  mixed $id id.
		 * @param  mixed $sections Section.
		 * @return void
		 */
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		/**
		 * Set directory constants
		 *
		 * @return void
		 */
		public static function constants() {

			// We need this path-finder code for set URL of framework.
			$dirname         = str_replace( '//', '/', wp_normalize_path( dirname( dirname( self::$file ) ) ) );
			$theme_dir       = str_replace( '//', '/', wp_normalize_path( get_parent_theme_file_path() ) );
			$plugin_dir      = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
			$plugin_dir_hook = apply_filters( 'wpcp_plugin_dir_bitnami', true );
			if ( $plugin_dir_hook ) {
				$plugin_dir = str_replace( '/opt/bitnami', '/bitnami', $plugin_dir );
			}
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$foldername     = str_replace( $directory, '', $dirname );
			$protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
			$directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;

		}

		/**
		 * Include file helper.
		 *
		 * @param  mixed $file file.
		 * @param  mixed $load load.
		 * @return Statement.
		 */
		public static function include_plugin_file( $file, $load = true ) {
			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'wpcf_override', 'wpcf-override' );

			if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_parent_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists( self::$dir . '/' . $file ) ) {
				$path = self::$dir . '/' . $file;
			}

			if ( ! empty( $path ) && ! empty( $file ) && $load ) {

				global $wp_query;

				if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

					load_template( $path, true );

				} else {

					require_once $path;

				}
			} else {
				return self::$dir . '/' . $file;
			}

		}

		/**
		 * Is active plugin helper.
		 *
		 * @param  mixed $file file path.
		 * @return array.
		 */
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', array() ) );
		}

		/**
		 * Sanitize dirname
		 *
		 * @param  mixed $dirname dir name.
		 * @return statement
		 */
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		/**
		 * Set url constant.
		 *
		 * @param  mixed $file file url.
		 * @return url
		 */
		public static function include_plugin_url( $file ) {
			return esc_url( WPCAROUSELF_URL . '/admin/views/sp-framework' ) . '/' . ltrim( $file, '/' );
		}

		/**
		 * Include files
		 *
		 * @return void
		 */
		public static function includes() {

			// Helpers.
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// Includes free version classes.
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/admin-options.class.php' );
			self::include_plugin_file( 'classes/metabox-options.class.php' );

			// Include all framework fields.
			$fields = apply_filters(
				'wpcf_fields',
				array(
					'border',
					'button_set',
					'carousel_type',
					'checkbox',
					'code_editor',
					'color',
					'color_group',
					'custom_import',
					'column',
					'dimensions_advanced',
					'fieldset',
					'gallery',
					'heading',
					'image_select',
					'image_sizes',
					'media',
					'notice',
					'radio',
					'select',
					'shortcode',
					'spacing',
					'spinner',
					'subheading',
					'slider',
					'tabbed',
					'submessage',
					'switcher',
					'text',
					'typography',
					'preview',
				)
			);

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! class_exists( 'SP_WPCF_Field_' . $field ) && class_exists( 'SP_WPCF_Fields' ) ) {
						self::include_plugin_file( 'fields/' . $field . '/' . $field . '.php' );
					}
				}
			}

		}

		/**
		 * Set all of used fields
		 *
		 * @param  mixed $sections section.
		 * @return void
		 */
		public static function set_used_fields( $sections ) {

			if ( ! empty( $sections['fields'] ) ) {
				foreach ( $sections['fields'] as $field ) {
					if ( ! empty( $field['fields'] ) ) {
						self::set_used_fields( $field );
					}

					if ( ! empty( $field['tabs'] ) ) {
						self::set_used_fields( array( 'fields' => $field['tabs'] ) );
					}

					if ( ! empty( $field['accordions'] ) ) {
						self::set_used_fields( array( 'fields' => $field['accordions'] ) );
					}

					if ( ! empty( $field['type'] ) ) {
						self::$fields[ $field['type'] ] = $field;
					}
				}
			}
		}

		/**
		 * Enqueue admin and fields styles and scripts
		 *
		 * @return void
		 */
		public static function add_admin_enqueue_scripts() {

			// Loads scripts and styles only when needed.
			$wpscreen              = get_current_screen();
			$current_screen        = get_current_screen();
			$the_current_post_type = $current_screen->post_type;
			$settings_page_base    = 'sp_wp_carousel_page_wpcp_settings' === $current_screen->base;
			$tools_page_base       = 'sp_wp_carousel_page_wpcf_tools' === $current_screen->base;
			if ( 'sp_wp_carousel' === $the_current_post_type || $settings_page_base || $tools_page_base ) {
				if ( ! empty( self::$args['admin_options'] ) ) {
					foreach ( self::$args['admin_options'] as $argument ) {
						if ( substr( $wpscreen->id, -strlen( $argument['menu_slug'] ) ) === $argument['menu_slug'] ) {
							self::$enqueue = true;
						}
					}
				}
				if ( ! empty( self::$args['metabox_options'] ) ) {
					foreach ( self::$args['metabox_options'] as $argument ) {
						if ( in_array( $wpscreen->post_type, (array) $argument['post_type'] ) ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! apply_filters( 'wpcf_enqueue_assets', self::$enqueue ) ) {
					return;
				}

				// Check for developer mode.
				$min = ( self::$premium && SCRIPT_DEBUG ) ? '' : '.min';

				// Admin utilities.
				wp_enqueue_media();

				// Wp color picker.
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				// Font awesome 4 and 5 loader.
				if ( apply_filters( 'wpcf_fa4', true ) ) {
					wp_enqueue_style( 'wpcf-fa', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome' . $min . '.css', array(), '4.7.0', 'all' );
				} else {
					wp_enqueue_style( 'wpcf-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all' . $min . '.css', array(), '5.15.5', 'all' );
					wp_enqueue_style( 'wpcf-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/v4-shims' . $min . '.css', array(), '5.15.5', 'all' );
				}

				// Main style.
				wp_enqueue_style( 'wpcf', self::include_plugin_url( 'assets/css/style' . $min . '.css' ), array(), self::$version, 'all' );
				// Main RTL styles.
				if ( is_rtl() ) {
					wp_enqueue_style( 'wpcf-rtl', self::include_plugin_url( 'assets/css/style-rtl' . $min . '.css' ), array(), self::$version, 'all' );
				}

				// Main scripts.
				wp_enqueue_script( 'wpcf-plugins', self::include_plugin_url( 'assets/js/plugins' . $min . '.js' ), array(), self::$version, true );
				wp_enqueue_script( 'wpcf', self::include_plugin_url( 'assets/js/main' . $min . '.js' ), array( 'wpcf-plugins' ), self::$version, true );

				// Main variables.
				wp_localize_script(
					'wpcf',
					'wpcf_vars',
					array(
						'previewJS'     => WPCAROUSELF_URL . 'public/js/wp-carousel-free-public.min.js',
						'color_palette' => apply_filters( 'wpcf_color_palette', array() ),
						'i18n'          => array(
							'confirm'         => esc_html__( 'Are you sure?', 'wp-carousel-free' ),
							'typing_text'     => esc_html__( 'Please enter %s or more characters', 'wp-carousel-free' ),
							'searching_text'  => esc_html__( 'Searching...', 'wp-carousel-free' ),
							'no_results_text' => esc_html__( 'No results found.', 'wp-carousel-free' ),
						),
					)
				);

				// Enqueue fields scripts and styles.
				$enqueued = array();

				if ( ! empty( self::$fields ) ) {
					foreach ( self::$fields as $field ) {
						if ( ! empty( $field['type'] ) ) {
							$classname = 'SP_WPCF_Field_' . $field['type'];
							if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
								$instance = new $classname( $field );
								if ( method_exists( $classname, 'enqueue' ) ) {
									$instance->enqueue();
								}
								unset( $instance );
							}
						}
					}
				}
				// Load thickbox assets for quick view and brand plugin notice.
				add_thickbox();

				// Fix the CSS conflict of radio player with wp caroursel free.
				wp_dequeue_style( 'radio-player-admin' );

				do_action( 'wpcf_enqueue' );
			}

		}


		/**
		 * Add a new framework field.
		 *
		 * @param  array  $field field.
		 * @param  string $value string.
		 * @param  mixed  $unique unique id.
		 * @param  mixed  $where where to add.
		 * @param  mixed  $parent parent.
		 * @return void
		 */
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for disallow fields.
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field            = array();
				$field['content'] = esc_html__( 'Oops! Not allowed.', 'wp-carousel-free' ) . ' <strong>(' . $field_type . ')</strong>';
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend     = '';
			$visible    = '';
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$class      = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' wpcf-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';

			if ( ! empty( $field['dependency'] ) ) {

				$dependency      = $field['dependency'];
				$depend_visible  = '';
				$data_controller = '';
				$data_condition  = '';
				$data_value      = '';
				$data_global     = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition  = implode( '|', array_column( $dependency, 1 ) );
					$data_value      = implode( '|', array_column( $dependency, 2 ) );
					$data_global     = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible  = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = ( ! empty( $depend_visible ) ) ? ' wpcf-depend-visible' : ' wpcf-depend-hidden';

			}

				// These attributes has been sanitized above.
				echo wp_kses_post( '<div class="wpcf-field wpcf-field-' . $field_type . $is_pseudo . $class . $visible . '"' . $depend . '>' );
			if ( ! empty( $field_type ) ) {
				if ( ! empty( $field['fancy_title'] ) ) {
					echo '<div class="wpcf-fancy-title">' . wp_kses_post( $field['fancy_title'] ) . '</div>';
				}

				if ( ! empty( $field['title'] ) ) {
					echo '<div class="wpcf-title">';
					$help_title = ( ! empty( $field['title_help'] ) ) ? '
					<div class="wpcf-help wpcf-title-help">
					<span class="wpcf-help-text">' . $field['title_help'] . '</span>
					<img src="' . self::include_plugin_url( 'assets/images/info.svg' ) . '">
					</div>' : '';
					echo '<h4>' . wp_kses_post( $field['title'] . $help_title ) . '</h4>';
					echo ( ! empty( $field['subtitle'] ) ) ? '<div class="wpcf-subtitle-text">' . wp_kses_post( $field['subtitle'] ) . '</div>' : '';
					echo '</div>';

				}

				echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '<div class="wpcf-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				$classname = 'SP_WPCF_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'Field not found!', 'wp-carousel-free' ) . '</p>';
				}
			} else {
				echo '<p>' . esc_html__( 'Field not found!', 'wp-carousel-free' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';

		}

	}

}

SP_WPCF::init( __FILE__ );
