<?php
/**
 * Promotion class
 *
 * For displaying limited time promotion in admin panel
 *
 * @since 2.1.14
 *
 * @package WP_Carousel_Free
 */
class WP_Carousel_Free_Promotion {

	/**
	 * Option key for limited time promo
	 *
	 * @var string
	 */
	public $promo_option_key = '_wp_carousel_limited_time_promo';

	/**
	 * WP_Carousel_Free_Promotion constructor
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'show_promotions' ) );
		add_action( 'wp_ajax_sp_wpcf_dismiss_promotional_notice', array( $this, 'dismiss_limited_time_promo' ) );
	}

	/**
	 * Shows promotions
	 */
	public function show_promotions() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$notices = array(
			array(
				'key'        => 'black-friday-2020',
				'start_date' => '2020-11-24 14:00:00 EST',
				'end_date'   => '2020-11-30 23:59:00 EST',
				'title'      => 'Enjoy Black Friday Deals <strong>30% OFF</strong> on <strong>WP Carousel Pro!</strong>',
				'content'    => ' Discount Code: <strong>BF2020</strong>',
				'link'       => 'https://shapedplugin.com/plugin/wordpress-carousel-pro/?utm_source=wordpress-wpcf&utm_medium=get-it-now&utm_campaign=BlackFriday2020',
			),
		);

		if ( empty( $notices ) ) {
			return;
		}

		$current_time_est = $this->get_current_time_est();
		$notice           = array();

		$already_displayed_promo = get_option( $this->promo_option_key, array() );

		foreach ( $notices as $ntc ) {
			if ( in_array( $ntc['key'], $already_displayed_promo, true ) ) {
				continue;
			}

			if ( strtotime( $ntc['start_date'] ) < strtotime( $current_time_est ) && strtotime( $current_time_est ) < strtotime( $ntc['end_date'] ) ) {
				$notice = $ntc;
			}
		}

		if ( empty( $notice ) ) {
			return;
		}

		?>
		<div class="notice sp-wpcf-promotional-notice">
			<div class="content">
				<h2><?php echo wp_kses_post( $notice['title'] . $notice['content'] ); ?></h2><a href="<?php echo esc_url( $notice['link'] ); ?>" class="button button-primary" target="_blank"><?php echo esc_html__( 'Get it Now', 'wp-carousel-free' ); ?></a>
			</div>
			<span class="promotional-close-icon notice-dismiss" data-key="<?php echo esc_attr( $notice['key'] ); ?>"></span>
			<div class="clear"></div>
		</div>

		<style>
			.sp-wpcf-promotional-notice {
				padding: 14px 18px;
				box-sizing: border-box;
				position: relative;
			}
			.sp-wpcf-promotional-notice .content {
				float: left;
				width: 75%;
			}
			.sp-wpcf-promotional-notice .content h2 {
				margin: 3px 0px 5px;
				font-size: 20px;
				font-weight: 400;
				color: #444;
				line-height: 25px;
				float: left;
				margin-right: 15px;
			}
			.sp-wpcf-promotional-notice .content a {
				border: none;
				box-shadow: none;
				height: 31px;
				line-height: 30px;
				border-radius: 3px;
				background: #18afb9;
				text-shadow: none;
				font-weight: 600;
				width: 95px;
				text-align: center;
				float: left;
			}
			.sp-wpcf-promotional-notice .content a:hover {
				background: #089fa9;
			}
		</style>

		<script type='text/javascript'>
			jQuery( document ).ready( function ( $ ) {
				$( 'body' ).on( 'click', '.sp-wpcf-promotional-notice span.promotional-close-icon', function ( e ) {
					e.preventDefault();

					var self = $( this ),
						key = self.data( 'key' );

					wp.ajax.send( 'sp_wpcf_dismiss_promotional_notice', {
						data: {
							sp_wpcf_promotion_dismissed: true,
							key: key,
							nonce: '<?php echo esc_attr( wp_create_nonce( 'wp_carousel_admin' ) ); ?>'
						},
						complete: function ( resp ) {
							self.closest( '.sp-wpcf-promotional-notice' ).fadeOut( 200 );
						}
					} );
				} );
			} );
		</script>

		<?php
	}

	/**
	 * Dismisses limited time promo notice
	 */
	public function dismiss_limited_time_promo() {
		$post_data = wp_unslash( $_POST );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( __( 'You have no permission to do that', 'wp-carousel-free' ) );
		}

		if ( ! wp_verify_nonce( $post_data['nonce'], 'wp_carousel_admin' ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'wp-carousel-free' ) );
		}

		if ( isset( $post_data['sp_wpcf_promotion_dismissed'] ) && $post_data['sp_wpcf_promotion_dismissed'] ) {
			$already_displayed_promo   = get_option( $this->promo_option_key, array() );
			$already_displayed_promo[] = $post_data['key'];

			update_option( $this->promo_option_key, $already_displayed_promo );
			wp_send_json_success();
		}
	}


	/**
	 * Gets current time and converts to EST timezone.
	 *
	 * @return string
	 */
	private function get_current_time_est() {
		$dt = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );
		$dt->setTimezone( new \DateTimeZone( 'EST' ) );

		return $dt->format( 'Y-m-d H:i:s T' );
	}

}
new WP_Carousel_Free_Promotion();
