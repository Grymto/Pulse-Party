<?php
/**
 * The admin-specific of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin
 */

/**
 * The class for the admin-specific functionality of the plugin.
 */
class WP_Carousel_Free_Admin {
	/**
	 * Script and style suffix
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * The ID of the plugin.
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $plugin_name The ID of this plugin
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin
	 *
	 * @since 2.0.0
	 * @access protected
	 * @var string      $version The current version fo the plugin.
	 */
	protected $version;


	/**
	 * Initialize the class sets its properties.
	 *
	 * @since 2.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area of the plugin.
	 *
	 * @since  3.0.0
	 * @return void
	 */
	public function enqueue_admin_styles() {
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_carousel' === $the_current_post_type ) {
			wp_enqueue_style( 'font-awesome', WPCAROUSELF_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name . 'admin', WPCAROUSELF_URL . 'admin/css/wp-carousel-free-admin' . $this->suffix . '.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'sp_wp_carousel_tabbed_icons', WPCAROUSELF_URL . 'admin/css/fontello.css', array(), $this->version, 'all' );

		// Scripts.
		wp_enqueue_script( $this->plugin_name . 'admin', WPCAROUSELF_URL . 'admin/js/wp-carousel-free-admin' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Change Carousel updated messages.
	 *
	 * @since 2.0.0
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function wpcp_carousel_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['sp_wp_carousel'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Carousel updated.', 'wp-carousel-free' ) ),
			2  => '',
			3  => '',
			4  => __( 'Carousel updated.', 'wp-carousel-free' ),
			5  => isset( $_GET['revision'] ) ? sprintf( 'Carousel restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:disable WordPress.Security.NonceVerification.Recommended
			6  => sprintf( __( 'Carousel published.', 'wp-carousel-free' ) ),
			7  => __( 'Carousel saved.', 'wp-carousel-free' ),
			8  => sprintf( __( 'Carousel submitted.', 'wp-carousel-free' ) ),
			9  => sprintf( 'Carousel scheduled for: <strong>%1$s</strong>', date_i18n( __( 'M j, Y @ G:i', 'wp-carousel-free' ), strtotime( $post->post_date ) ) ),
			10 => sprintf( __( 'Carousel draft updated.', 'wp-carousel-free' ) ),
		);
		return $messages;
	}

	/**
	 * Add carousel admin columns.
	 *
	 * @return statement
	 */
	public function filter_carousel_admin_column() {
		$admin_columns['cb']            = '<input type="checkbox" />';
		$admin_columns['title']         = __( 'Title', 'wp-carousel-free' );
		$admin_columns['shortcode']     = __( 'Shortcode', 'wp-carousel-free' );
		$admin_columns['carousel_type'] = __( 'Source Type', 'wp-carousel-free' );
		$admin_columns['date']          = __( 'Date', 'wp-carousel-free' );

		return $admin_columns;
	}

	/**
	 * Display admin columns for the carousels.
	 *
	 * @since 2.0.0
	 * @param mix    $column The columns.
	 * @param string $post_id The post ID.
	 * @return void
	 */
	public function display_carousel_admin_fields( $column, $post_id ) {
		$upload_data     = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
		$carousels_types = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
		switch ( $column ) {
			case 'shortcode':
				$column_field = '<input style="max-width:100%;width: 270px; padding: 6px;cursor:pointer;" type="text" onClick="this.select();" readonly="readonly" value="[sp_wpcarousel id=&quot;' . esc_attr( $post_id ) . '&quot;]"/><div class="spwpc-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>';
				echo $column_field; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'carousel_type':
				echo ucwords( str_replace( '-', ' ', $carousels_types ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		} // end switch.
	}

	/**
	 * Add plugin action menu
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array  $links The action link.
	 * @param string $plugin_file The file.
	 * @since 2.0.0
	 * @return array
	 */
	public function add_plugin_action_links( $links, $plugin_file ) {

		if ( WPCAROUSELF_BASENAME === $plugin_file ) {
			$ui_links = sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=sp_wp_carousel' ), __( 'Add New', 'wp-carousel-free' ) );

			array_unshift( $links, $ui_links );

			$links['go_pro'] = sprintf( '<a target="_blank" href="%1$s" style="color: #35b747; font-weight: 700;">Go Pro!</a>', 'https://wordpresscarousel.com/pricing/?ref=1' );
		}

		return $links;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( WPCAROUSELF_BASENAME === $plugin_file ) {
			$row_meta = array(
				'docs' => '<a href="https://wordpresscarousel.com/wp-carousel-free-demo/" aria-label="' . esc_attr( __( 'Live Demo', 'wp-carousel-free' ) ) . '" target="_blank">' . __( 'Live Demo', 'wp-carousel-free' ) . '</a>',
				'ideo' => '<a href="https://docs.shapedplugin.com/docs/wordpress-carousel/introduction/" aria-label="' . esc_attr( __( 'View WP Carousel Video Tutorials', 'wp-carousel-free' ) ) . '" target="_blank">' . __( 'Docs & Video Tutorials', 'wp-carousel-free' ) . '</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	/**
	 * Bottom review notice.
	 *
	 * @since 2.0.0
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sp_wpcp_review_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_carousel' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/wp-carousel-free/reviews/?filter=5#new-post';
			$text = sprintf( 'Enjoying <strong>WP Carousel?</strong> Please rate us <span class="spwpcp-footer-text-star">★★★★★</span> <a href="%s" target="_blank">WordPress.org</a>. Your positive feedback will help us grow more. Thank you! 😊', $url );
		}

		return $text;
	}
	/**
	 * Bottom review notice.
	 *
	 * @since 2.0.0
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sp_wpcp_version_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_carousel' === $screen->post_type ) {
			$text = 'WP Carousel ' . $this->version;
		}

		return $text;
	}

	/**
	 * Declare the compatibility of WooCommerce High-Performance Order Storage (HPOS) feature.
	 *
	 * @since 2.5.7
	 *
	 * @return void
	 */
	public function declare_compatibility_with_woo_hpos_feature() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'wp-carousel-free/wp-carousel-free.php', true );
		}
	}
}
