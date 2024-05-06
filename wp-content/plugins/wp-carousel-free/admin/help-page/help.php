<?php
/**
 * The help page for the WP Carousel Free
 *
 * @package WP Carousel Free
 * @subpackage wp-carousel-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the WP Carousel Free
 */
class WP_Carousel_Free_Help {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'wpcf_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp', 'wp-carousel-free' );

	/**
	 * Help Page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'wpcf_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'wpcf_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Help Page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-wp-carousel-help', WPCAROUSELF_URL . 'admin/help-page/css/help-page.min.css', array(), WPCAROUSELF_VERSION );
		wp_enqueue_style( 'sp-wp-carousel-fontello', WPCAROUSELF_URL . 'admin/help-page/css/fontello.min.css', array(), WPCAROUSELF_VERSION );

		wp_enqueue_script( 'sp-wp-carousel-help', WPCAROUSELF_URL . 'admin/help-page/js/help-page.min.js', array(), WPCAROUSELF_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wp_carousel',
			__( 'WP Carousel', 'wp-carousel-free' ),
			__( 'Recommended', 'wp-carousel-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_carousel&page=wpcf_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_carousel',
			__( 'WP Carousel', 'wp-carousel-free' ),
			__( 'Lite vs Pro', 'wp-carousel-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_carousel&page=wpcf_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_carousel',
			__( 'WP Carousel Help', 'wp-carousel-free' ),
			__( 'Get Help', 'wp-carousel-free' ),
			'manage_options',
			'wpcf_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spwpcp_ajax_help_page function.
	 *
	 * @return void
	 */
	public function spwpcp_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spwpcp_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'spwpcp_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'wp-carousel-free' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'wp-carousel-free' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'wp-carousel-free' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'wp-carousel-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'wp-carousel-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'wp-carousel-free' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'wp-carousel-free' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcf_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_wp_carousel' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The WP Carousel Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-wp-carousel-help">
			<!-- Header section start -->
			<section class="spwpcp__help header">
				<div class="spwpcp-header-area-top">
					<p>You’re currently using <b>WP Carousel Lite</b>. To access additional features, consider <a target="_blank" href="https://wordpresscarousel.com/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spwpcp-header-area">
					<div class="spwpcp-container">
						<div class="spwpcp-header-logo">
							<img src="<?php echo esc_url( WPCAROUSELF_URL . 'admin/help-page/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( WPCAROUSELF_VERSION ); ?></span>
						</div>
					</div>
					<div class="spwpcp-header-logo-shape">
						<img src="<?php echo esc_url( WPCAROUSELF_URL . 'admin/help-page/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spwpcp-header-nav">
					<div class="spwpcp-container">
						<div class="spwpcp-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_carousel&page=wpcf_help#get-start' ); ?>"><i class="spwpcp-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_carousel&page=wpcf_help#recommended' ); ?>" data-id="recommended-tab"><i class="spwpcp-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_carousel&page=wpcf_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spwpcp-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_carousel&page=wpcf_help#about-us' ); ?>" data-id="about-us-tab"><i class="spwpcp-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spwpcp__help start-page" id="get-start-tab">
				<div class="spwpcp-container">
					<div class="spwpcp-start-page-wrap">
						<div class="spwpcp-video-area">
							<h2 class='spwpcp-section-title'>Welcome to WP Carousel!</h2>
							<span class='spwpcp-normal-paragraph'>Thank you for installing WP Carousel! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/7kb94-CJp54?si=L7gnWecSoFBhg9mD" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spwpcp-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_wp_carousel' ); ?>">Create a Carousel</a></li>
								<li><a target="_blank" class='spwpcp-medium-btn' href="https://wordpresscarousel.com/wp-carousel-free-demo/">Live Demo</a></li>
								<li><a target="_blank" class='spwpcp-medium-btn arrow-btn' href="https://wordpresscarousel.com">Explore WP Carousel <i class="spwpcp-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spwpcp-start-page-sidebar">
							<div class="spwpcp-start-page-sidebar-info-box">
								<div class="spwpcp-info-box-title">
									<h4><i class="spwpcp-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spwpcp-normal-paragraph'>Explore WP Carousel plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spwpcp-small-btn' href="https://docs.shapedplugin.com/docs/wordpress-carousel/introduction/">Browse Now</a>
							</div>
							<div class="spwpcp-start-page-sidebar-info-box">
								<div class="spwpcp-info-box-title">
									<h4><i class="spwpcp-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spwpcp-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spwpcp-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spwpcp-start-page-sidebar-info-box">
								<div class="spwpcp-info-box-title">
									<h4><i class="spwpcp-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spwpcp-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spwpcp-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spwpcp__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spwpcp-container">
					<div class="spwpcp-call-to-action-top">
						<h2 class="spwpcp-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://wordpresscarousel.com/pricing/?ref=1" class='spwpcp-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spwpcp-lite-to-pro-wrap">
						<div class="spwpcp-features">
							<ul>
								<li class='spwpcp-header'>
									<span class='spwpcp-title'>FEATURES</span>
									<span class='spwpcp-free'>Lite</span>
									<span class='spwpcp-pro'><i class='spwpcp-icon-pro'></i> PRO</span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>All Free Version Features</span>
									<span class='spwpcp-free spwpcp-check-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Content Source Types (Images, Posts, Products, Content, Videos, Audios, Mix, External, etc.)</span>
									<span class='spwpcp-free'><b>3</b></span>
									<span class='spwpcp-pro'><b>7</b></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Layout Presets (Carousel, Grid, Tiles, Masonry, Justified, Thumbnails Slider, etc.) <i class="spwpcp-hot">Hot</i> </span>
									<span class='spwpcp-free'><b>2</b></span>
									<span class='spwpcp-pro'><b>10+</b></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Item/Card Styles and it's Content Positions <i class="spwpcp-new">New</i> <i class="spwpcp-hot">Hot</i> </span>
									<span class='spwpcp-free'><b>1</b></span>
									<span class='spwpcp-pro'><b>25+</b></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Floating or Moving Content/Caption Styles <i class="spwpcp-new">new</i></span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Display Posts, Pages from Custom Post Types, Taxonomies, Custom Taxonomies, etc.</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Filtering Different Product Types (Categories, On Sale,  Specific, Exclude Products, etc.)</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Supported Video Platforms: YouTube, Vimeo, Dailymotion, Self-hosted(MP4...), Wistia, etc. <i class="spwpcp-hot">Hot</i></span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Supported Audio Platforms: Self-hosted, SoundCloud, and Other Audio Sources</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Create External (Instagram & RSS feeds) & Mix-Content Carousel</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Carousel Modes (Ticker and Center) and Items in Random Order</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Item Click Action Types (Link and Lightbox) Lightbox</span>
									<span class='spwpcp-free spwpcp-check-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Scheduling Carousel/Gallery at Specific Time Intervals <i class="spwpcp-hot">Hot</i></span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Multiple Ajax Pagination Types (Number, Load More, Infinite, etc.) and Show Per Page & Click</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Content Vertical Alignment and Equal Height for All the Items</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Overlay Content Styles (Background Color, Visibility, etc.)</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Items Inner Padding, Content Box Padding, & Custom Background</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Show Item Caption and Description (Full, Word Limit, Read More, etc.)</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Image Custom Dimensions and Retina Ready Supported</span>
									<span class='spwpcp-free spwpcp-check-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Specific Image Height for Responsive Devices, Variable Width, Lazy Load, etc.</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Image Grayscale Modes and Custom Color</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Apply Watermark and Image Protection (Disabling Right-click)<i class="spwpcp-new">new</i> <i class="spwpcp-hot">Hot</i></span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>30+ Responsive Lightbox Gallery Options for Images <i class="spwpcp-hot">Hot</i></span>
									<span class='spwpcp-free spwpcp-check-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Powerful Carousel Settings (Slide to Scroll, Sliding Effects, Navigation, Pagination, etc.)</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Multi-Row Carousels and Vertical Carousel Orientation</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Stylize your Carousel/Gallery Typography with 1500+ Google Fonts</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
								<li class='spwpcp-body'>
									<span class='spwpcp-title'>Priority Top-notch Support</span>
									<span class='spwpcp-free spwpcp-close-icon'></span>
									<span class='spwpcp-pro spwpcp-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spwpcp-upgrade-to-pro">
							<h2 class='spwpcp-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='spwpcp-section-subtitle'>Already, <b>70000+</b> people are using WP Carousel on their websites to create beautiful carousels, sliders, and galleries; why won’t you!</span>
							<div class="spwpcp-upgrade-to-pro-btn">
								<div class="spwpcp-action-btn">
									<a target="_blank" href="https://wordpresscarousel.com/pricing/?ref=1" class='spwpcp-big-btn'>Upgrade to Pro Now!</a>
									<span class='spwpcp-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://wordpresscarousel.com" class='spwpcp-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://wordpresscarousel.com/simple-image-carousel/" class="spwpcp-big-btn-border spwpcp-pro-live-demo-btn">Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spwpcp-testimonial">
						<div class="spwpcp-testimonial-title-section">
							<span class='spwpcp-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spwpcp-section-title">Our Users Love WP Carousel Pro!</h2>
						</div>
						<div class="spwpcp-testimonial-wrap">
							<div class="spwpcp-testimonial-area">
								<div class="spwpcp-testimonial-content">
									<p>The plugin is super simple to use and had everything I needed. I wanted to get a little more advanced with it and make some edits via CSS, but wasn’t too confident on how to do so. I reached out...</p>
								</div>
								<div class="spwpcp-testimonial-info">
									<div class="spwpcp-img">
										<img src="<?php echo esc_url( WPCAROUSELF_URL . 'admin/help-page/img/matt.png' ); ?>" alt="">
									</div>
									<div class="spwpcp-info">
										<h3>Matt Foreman</h3>
										<div class="spwpcp-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwpcp-testimonial-area">
								<div class="spwpcp-testimonial-content">
									<p>I recently purchased this plugin, and i must say that i am very much satisfied with the plugin and the support that i received from the team. The team is eager to help you and provide out of bo...</p>
								</div>
								<div class="spwpcp-testimonial-info">
									<div class="spwpcp-img">
										<img src="<?php echo esc_url( WPCAROUSELF_URL . 'admin/help-page/img/shujashah.png' ); ?>" alt="">
									</div>
									<div class="spwpcp-info">
										<h3>Shujashah</h3>
										<div class="spwpcp-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwpcp-testimonial-area">
								<div class="spwpcp-testimonial-content">
									<p>This is a really really nice plugin. When reviewing the demo, you’ll see shortcodes starting with wcp or wcf. I didn’t pick up on this until about 30 minutes into working with the plugin but wcf demos...</p>
								</div>
								<div class="spwpcp-testimonial-info">
									<div class="spwpcp-img">
										<img src="<?php echo esc_url( WPCAROUSELF_URL . 'admin/help-page/img/daryl.png' ); ?>" alt="">
									</div>
									<div class="spwpcp-info">
										<h3>Daryl Lackey</h3>
										<div class="spwpcp-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="spwpcp-recommended-page">
				<div class="spwpcp-container">
					<h2 class="spwpcp-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spwpcp-wp-list-table plugin-install-php">
						<div class="spwpcp-recommended-plugins" id="the-list">
							<?php
								$this->spwpcp_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spwpcp__help about-page">
				<div class="spwpcp-container">
					<div class="spwpcp-about-box">
						<div class="spwpcp-about-info">
							<h3>The Most Powerful Multi-purpose Carousel, Slider, and Gallery plugin from the WP Carousel Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we have been searching for the best way to display various types of WordPress content, such as images, posts, products, videos, audio, and external sources on WordPress sites. Regrettably, we couldn't find any suitable plugin that is easy to use yet simple. Hence, we set out with a simple goal: to develop a powerful WordPress Carousel, Slider, and Gallery plugin that is both user-friendly and efficient.</p>
							<p>Our goal is to provide the easiest way to beautifully showcase diverse content types in WordPress. Explore it now and see the difference!</p>
							<div class="spwpcp-about-btn">
								<a target="_blank" href="https://wordpresscarousel.com" class='spwpcp-medium-btn'>Explore WP Carousel</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spwpcp-medium-btn spwpcp-arrow-btn'>More About Us <i class="spwpcp-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spwpcp-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spwpcp-our-plugin-list">
						<h3 class="spwpcp-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spwpcp-our-plugin-list-wrap">
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spwpcp-our-plugin-list-box">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spwpcp-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spwpcp-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spwpcp-footer">
				<div class="spwpcp-footer-top">
					<p><span>Made With <i class="spwpcp-icon-heart"></i> </span> By the Team <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a></p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spwpcp-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spwpcp-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spwpcp-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spwpcp-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

}

WP_Carousel_Free_Help::instance();
