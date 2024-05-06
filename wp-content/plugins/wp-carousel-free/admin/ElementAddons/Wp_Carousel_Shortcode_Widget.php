<?php
/**
 * Elementor wp carousel shortcode Widget.
 *
 * @since 2.4.1
 */
class Wp_Carousel_Shortcode_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 2.4.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sp_wp_carousel_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.4.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WP Carousel Free', 'wp-carousel-Free' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.4.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wpcf-icon-block';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.4.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 2.4.1
	 * @return array
	 */
	public function sp_wp_carousel_free_post_list() {
		$post_list            = array();
		$sp_wp_carousel_posts = new \WP_Query(
			array(
				'post_type'      => 'sp_wp_carousel',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);
		$posts                = $sp_wp_carousel_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'wp-carousel-free' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_wp_carousel_pro_shortcode',
			array(
				'label'       => __( 'WP Carousel Shortcode(s)', 'wp-carousel-free' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_wp_carousel_free_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render wp carousel shortcode widget output on the frontend.
	 *
	 * @since 2.4.1
	 * @access protected
	 */
	protected function render() {

		$settings                 = $this->get_settings_for_display();
		$sp_wp_carousel_shortcode = $settings['sp_wp_carousel_pro_shortcode'];

		if ( '' === $sp_wp_carousel_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$post_id = (int) $sp_wp_carousel_shortcode;

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			// Preset Layouts.
			$upload_data        = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
			$shortcode_data     = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
			$main_section_title = get_the_title( $post_id );
			// Load dynamic style for the existing shortcode.
			$dynamic_style = WP_Carousel_Free_Public::load_dynamic_style( $post_id, $shortcode_data, $upload_data );
			echo '<style id="wp_carousel_dynamic_css' . esc_attr( $post_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>';
			WP_Carousel_Free_Shortcode::wpcf_html_show( $upload_data, $shortcode_data, $post_id, $main_section_title );
			?>
			<script>
				jQuery('#wpcp-preloader-' + <?php echo intval( $post_id ); ?>).animate({ opacity: 0 }, 600).remove();
				jQuery('#sp-wp-carousel-free-id-' + <?php echo intval( $post_id ); ?>).animate({ opacity: 1 }, 600);
			</script>
			<script src="<?php echo esc_url( WPCAROUSELF_URL . 'public/js/wp-carousel-free-public.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( WPCAROUSELF_URL . 'public/js/fancybox-config.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( ' [sp_wpcarousel id="' . $post_id . '"]' );
		}

	}

}
