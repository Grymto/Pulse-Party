<?php
namespace Krokedil\KlarnaOnsiteMessaging;

/**
 * Class for the WP widget.
 */
class Widget extends \WP_Widget {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct(
			'klarna_osm', // Base ID.
			__( 'Klarna On-Site Messaging', 'klarna-onsite-messaging-for-woocommerce' ), // Name.
			array( 'description' => __( 'Displays a Klarna banner in your store.', 'klarna-onsite-messaging-for-woocommerce' ) ) // Description.
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_script( 'klarna_onsite_messaging_sdk' );

		$title = apply_filters( 'widget_title', $instance['title'] );

		// Remove any empty elements so that the defaults can be used instead.
		$instance = array_filter(
			$instance,
			function ( $property ) {
				return ! empty( $property );
			}
		);

		$instance = wp_parse_args(
			$instance,
			array(
				'key'             => 'homepage-promotion-wide',
				'theme'           => '',
				'purchase-amount' => '',
			)
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		echo esc_attr( Utility::print_placement( $instance ) );

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$data_key   = ! empty( $instance['key'] ) ? $instance['key'] : '';
		$data_theme = ! empty( $instance['theme'] ) ? $instance['theme'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'key' ) ); ?>"><?php esc_html_e( 'Placement Key' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'key' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'key' ) ); ?>" type="text"
				value="<?php echo esc_attr( $data_key ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"><?php esc_html_e( 'Placement Theme:' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'theme' ) ); ?>">
				<option value="default" <?php selected( $data_theme, 'default' ); ?>>Default</option>
				<option value="dark" <?php selected( $data_theme, 'dark' ); ?>>Dark</option>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['theme'] = ( ! empty( $new_instance['theme'] ) ) ? wp_strip_all_tags( $new_instance['theme'] ) : '';
		$instance['key']   = ( ! empty( $new_instance['key'] ) ) ? wp_strip_all_tags( $new_instance['key'] ) : '';

		return $instance;
	}
}
