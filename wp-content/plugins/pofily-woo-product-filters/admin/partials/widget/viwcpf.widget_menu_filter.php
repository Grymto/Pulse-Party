<?php /**
 * Class VIWCPF_Widget_Menu_Filter
 *
 */
class VIWCPF_Free_Widget_Filter_Menu extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'VIWCPF_Widget_Filter_Menu_class',
			'description' => esc_html__( 'Preset filter menu', 'pofily-woo-product-filters' )
		);
		parent::__construct( 'VIWCPF_Widget_Filter_Menu', esc_html__( '(Pofily) Preset filter menu', 'pofily-woo-product-filters' ), $widget_ops );
	}

	public function form( $instance ) {
		$defaults = array(
			'title'                 => '',
			'viwcpf_filter_menu_id' => ''
		);
		@$instance = wp_parse_args( (array) $instance, $defaults );

		$title                 = $instance['title'];
		$viwcpf_filter_menu_id = $instance['viwcpf_filter_menu_id'];

		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>

        <p>
            <label><?php esc_html_e( 'Choose filter menu', 'pofily-woo-product-filters' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'viwcpf_filter_menu_id' ) ); ?>" class="widefat">
				<?php
				$args_block           = array(
					'post_type'      => 'viwcpf_filter_menu',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
				);
				$filters_blocks_query = new WP_Query( $args_block );

				if ( $filters_blocks_query->have_posts() ):
					// The Loop
					while ( $filters_blocks_query->have_posts() ) : $filters_blocks_query->the_post();
						?>
                        <option value="<?php echo esc_attr( get_the_ID() ); ?>" <?php selected( $viwcpf_filter_menu_id, get_the_ID() ) ?>>
							<?php esc_html_e( get_the_title(), 'pofily-woo-product-filters' ); ?>
                        </option>
					<?php
					endwhile;
				endif;
				// Reset Post Data
				wp_reset_postdata();
				?>
            </select>
        </p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                          = $old_instance;
		$instance['title']                 = sanitize_text_field( $new_instance['title'] );
		$instance['viwcpf_filter_menu_id'] = sanitize_text_field( $new_instance['viwcpf_filter_menu_id'] );

		return $instance;
	}

	public function widget( $args, $instance ) {

		$title                 = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$viwcpf_filter_menu_id = isset( $instance['viwcpf_filter_menu_id'] ) ? trim( $instance['viwcpf_filter_menu_id'] ) : '';

		echo wp_kses_post( $args['before_widget'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo do_shortcode( '[VIWCPF_SHORTCODE id_menu="' . $viwcpf_filter_menu_id . '"]' );

		echo wp_kses_post( $args['after_widget'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
