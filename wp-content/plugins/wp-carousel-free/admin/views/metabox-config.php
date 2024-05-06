<?php
/**
 * The Metabox  configuration
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

//
// Metabox of the uppers section / Upload section.
// Set a unique slug-like ID.
//
$wpcp_carousel_content_source_settings = 'sp_wpcp_upload_options';

$smart_brand_plugin_link = 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php';
$smart_brand_plugin_data = SP_WPCF::plugin_installation_activation(
	$smart_brand_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'ShapedPlugin\SmartBrands\SmartBrands',
		'ShapedPlugin\SmartBrandsPro\SmartBrandsPro',
	),
	'smart-brands-for-woocommerce'
);

// Woo quick view Plugin.
$quick_view_plugin_link = 'woo-quickview/woo-quick-view.php';
$quick_view_plugin_data = SP_WPCF::plugin_installation_activation(
	$quick_view_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'SP_Woo_Quick_View',
		'SP_Woo_Quick_View_Pro',
	),
	'woo-quickview'
);

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SP_WPCF::createMetabox(
	'sp_wpcf_live_preview',
	array(
		'title'        => __( 'Live Preview', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);

SP_WPCF::createSection(
	'sp_wpcf_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_carousel_content_source_settings,
	array(
		'title'        => __( 'WordPress Carousel', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$wpcp_carousel_content_source_settings,
	array(
		'fields' => array(
			array(
				'type'  => 'heading',
				'image' => plugin_dir_url( __DIR__ ) . 'img/wpcp-logo.svg',
				'after' => '<i class="fa fa-life-ring"></i> Support',
				'link'  => 'https://shapedplugin.com/support/?user=lite',
				'class' => 'wpcp-admin-header',
			),
			array(
				'id'      => 'wpcp_carousel_type',
				'type'    => 'carousel_type',
				'title'   => __( 'Source Type', 'wp-carousel-free' ),
				'options' => array(
					'image-carousel'    => array(
						'icon' => 'fa fa-image',
						'text' => __( 'Image', 'wp-carousel-free' ),
					),
					'post-carousel'     => array(
						'icon' => 'dashicons dashicons-admin-post',
						'text' => __( 'Post', 'wp-carousel-free' ),
					),
					'product-carousel'  => array(
						'image' => WPCAROUSELF_URL . 'admin/img/layouts/woo-icon.svg',
						'text'  => __( 'Product', 'wp-carousel-free' ),
					),
					'content-carousel'  => array(
						'icon'     => 'fa fa-file-text-o',
						'text'     => __( 'Content', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'video-carousel'    => array(
						'icon'     => 'fa fa-play-circle-o',
						'text'     => __( 'Video', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'mix-content'       => array(
						'icon'     => 'dashicons dashicons-randomize',
						'text'     => __( 'Mix-Content', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'external-carousel' => array(
						'icon'     => 'dashicons dashicons-external',
						'text'     => __( 'External', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default' => 'image-carousel',
			),
			array(
				'id'          => 'wpcp_gallery',
				'type'        => 'gallery',
				'title'       => __( 'Images', 'wp-carousel-free' ),
				'wrap_class'  => 'wpcp-gallery-filed-wrapper',
				'add_title'   => __( 'ADD IMAGE', 'wp-carousel-free' ),
				'edit_title'  => __( 'EDIT IMAGE', 'wp-carousel-free' ),
				'clear_title' => __( 'REMOVE ALL', 'wp-carousel-free' ),
				'dependency'  => array( 'wpcp_carousel_type', '==', 'image-carousel' ),
			),
			array(
				'id'         => 'wpcp_post_type',
				'type'       => 'select',
				'title'      => __( 'Post Type', 'wp-carousel-free' ),
				'options'    => array(
					'post'       => array(
						'text' => __( 'Posts', 'wp-carousel-free' ),
					),
					'page'       => array(
						'text'     => __( 'Pages (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'custom'     => array(
						'text'     => __( 'Custom Post Types (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'multi_post' => array(
						'text'     => __( 'Multiple Post Types (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'post',
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel' ),
			),
			array(
				'id'         => 'wpcp_display_posts_from',
				'type'       => 'select',
				'title'      => __( 'Filter Posts', 'wp-carousel-free' ),
				'options'    => array(
					'latest'        => array(
						'text' => __( 'Latest', 'wp-carousel-free' ),
					),
					'taxonomy'      => array(
						'text'     => __( 'Taxonomy (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'specific_post' => array(
						'text'     => __( 'Specific (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			array(
				'id'         => 'number_of_total_posts',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-free' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			// Product Carousel.
			array(
				'id'         => 'wpcp_display_product_from',
				'type'       => 'select',
				'title'      => __( 'Filter Products', 'wp-carousel-free' ),
				'options'    => array(
					'latest'            => array(
						'text' => __( 'Latest', 'wp-carousel-free' ),
					),
					'taxonomy'          => array(
						'text'     => __( 'Category (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'specific_products' => array(
						'text'     => __( 'Specific (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),

			array(
				'id'         => 'wpcp_total_products',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-free' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
		), // End of fields array.
	)
);

//
// Metabox for the Carousel Post Type.
// Set a unique slug-like ID.
//
$wpcp_carousel_shortcode_settings = 'sp_wpcp_shortcode_options';

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'        => __( 'Shortcode Section', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'nav'          => 'inline',
		'theme'        => 'light',
		'class'        => 'sp_wpcp_shortcode_generator',
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'General Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-cog',
		'fields' => array(
			array(
				'id'       => 'wpcp_layout',
				'class'    => 'wpcp_layout',
				'type'     => 'image_select',
				'title'    => __( 'Layout Type', 'wp-carousel-free' ),
				'subtitle' => __( 'Choose a layout type.', 'wp-carousel-free' ),
				'desc'     => __( 'Want to take your content to the next level with stunning <a href="https://wordpresscarousel.com/layout-types/" target="_blank"><b>layouts</b></a></a> and advanced customizations? <b><a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank">Upgrade To Pro!</b></a></br>', 'wp-carousel-free' ),
				'options'  => array(
					'carousel'          => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/layouts/carousel.svg',
						'text'  => __( 'Carousel', 'wp-carousel-free' ),
					),
					'grid'              => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/layouts/grid.svg',
						'text'  => __( 'Grid', 'wp-carousel-free' ),
					),
					'tiles'             => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/layouts/tiles.svg',
						'text'     => __( 'Tiles', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'masonry'           => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/layouts/masonry.svg',
						'text'     => __( 'Masonry', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'justified'         => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/layouts/justified.svg',
						'text'     => __( 'Justified', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'thumbnails-slider' => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/layouts/thumbnails-slider.svg',
						'text'     => __( 'Thumbs Slider', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'  => 'carousel',
			),
			array(
				'id'         => 'wpcp_carousel_mode',
				'class'      => 'wpcf_carousel_mode',
				'type'       => 'image_select',
				'title'      => __( 'Carousel Mode', 'wp-carousel-free' ),
				'title_help' => __( '<div class="sp_wpcp-info-label">Carousel Mode</div><div class="sp_wpcp-short-content">Choose <b>Standard</b> for a classic display, <b>Ticker</b> for continuous scrolling, or <b>Center</b> for a focused and immersive view.</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-carousel-mode/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/carousel-modes/" target="_blank">Live Demo</a>', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set a mode for the carousel.', 'wp-carousel-free' ),
				'options'    => array(
					'standard' => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/carousel_standard.svg',
						'text'  => __( 'Standard', 'wp-carousel-free' ),
					),
					'ticker'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/carousel_ticker.svg',
						'text'     => __( 'Ticker', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'center'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/carousel_center.svg',
						'text'     => __( 'Center', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'standard',
				'dependency' => array( 'wpcp_layout', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpcp_slide_margin',
				'class'      => 'wpcp-slide-margin',
				'type'       => 'spacing',
				'title'      => __( 'Space', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set a space between the items.', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/space.svg" alt="Space"></div><div class="sp_wpcp-info-label">' . __( 'Space', 'wp-carousel-free' ) . '</div>',
				'sanitize'   => 'wpcf_sanitize_number_array_field',
				'right'      => true,
				'top'        => true,
				'left'       => false,
				'bottom'     => false,
				'right_text' => 'Vertical Gap',
				'top_text'   => 'Gap',
				'right_icon' => '<i class="fa fa-arrows-v"></i>',
				'top_icon'   => '<i class="fa fa-arrows-h"></i>',
				'unit'       => true,
				'units'      => array( 'px' ),
				'default'    => array(
					'top'   => '20',
					'right' => '20',
				),
			),
			array(
				'id'         => 'wpcp_number_of_columns',
				'type'       => 'column',
				'class'      => 'wpcp_number_of_columns',
				'title'      => __( 'Column(s)', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set number of column on devices.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_number_array_field',
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/column.svg" alt="Column(s)"></div><div class="sp_wpcp-info-label">' . __( 'Column(s)', 'wp-carousel-free' ) . '</div>',
				'default'    => array(
					'lg_desktop' => '5',
					'desktop'    => '4',
					'laptop'     => '3',
					'tablet'     => '2',
					'mobile'     => '1',
				),
				'help'       => '<i class="fa fa-television"></i><b> LARGE DESKTOP </b> - Screens larger than 1280px.<br/>
				<i class="fa fa-desktop"></i><b> DESKTOP </b> - Screens larger than 1280px.<br/>
				<i class="fa fa-laptop"></i><b> LAPTOP </b> - Screens smaller than 980px.<br/>
				<i class="fa fa-tablet"></i><b> TABLET </b> - Screens smaller than 736px.<br/>
				<i class="fa fa-mobile"></i><b> MOBILE </b> - Screens smaller than 480px.<br/>',
				'min'        => '0',
			),
			array(
				'id'     => 'wpcp_click_action_type_group',
				'class'  => 'wp-carousel-click-action-type',
				'type'   => 'fieldset',
				'fields' => array(
					array(
						'id'         => 'wpcp_logo_link_show',
						'type'       => 'image_select',
						'class'      => 'wpcp_logo_link_show_class',
						'title'      => __( 'Click Action Type', 'wp-carousel-free' ),
						'options'    => array(
							'link'  => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/url.svg',
								'pro_only' => true,
							),
							'l_box' => array(
								'image' => plugin_dir_url( __DIR__ ) . 'img/lightbox.svg',
							),
							'none'  => array(
								'image' => plugin_dir_url( __DIR__ ) . 'img/disabled.svg',
							),
						),
						'subtitle'   => __( 'Select a linking type for the images.', 'wp-carousel-free' ),
						'default'    => 'l_box',
						'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
					),
				),
			),
			array(
				'id'         => 'wpcp_image_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set an order by option.', 'wp-carousel-free' ),
				'options'    => array(
					'menu_order' => __( 'Drag & Drop', 'wp-carousel-free' ),
					'rand'       => __( 'Random', 'wp-carousel-free' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select an order by option.', 'wp-carousel-free' ),
				'options'    => array(
					'ID'         => __( 'ID', 'wp-carousel-free' ),
					'date'       => __( 'Date', 'wp-carousel-free' ),
					'rand'       => __( 'Random', 'wp-carousel-free' ),
					'title'      => __( 'Title', 'wp-carousel-free' ),
					'modified'   => __( 'Modified', 'wp-carousel-free' ),
					'menu_order' => __( 'Menu Order', 'wp-carousel-free' ),
				),
				'default'    => 'date',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order',
				'type'       => 'select',
				'title'      => __( 'Order', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select an order option.', 'wp-carousel-free' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'wp-carousel-free' ),
					'DESC' => __( 'Descending', 'wp-carousel-free' ),
				),
				'default'    => 'DESC',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_scheduler',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Scheduling', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable it to schedule sliders/galleries to show at specific time intervals.', 'wp-carousel-free' ),
				'title_help' => __( '<div class="sp_wpcp-info-label">Scheduling</div><div class="sp_wpcp-short-content">Enable the scheduling feature to set the specific date and time for your carousel sliders or galleries to be displayed (perfect for highlighting time-sensitive content).</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-scheduling-feature/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/scheduled-carousel/" target="_blank">Live Demo</a>', 'wp-carousel-free' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'         => 'wpcp_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-carousel-free' ),
				'subtitle'   => __( 'Items will be hidden until page load completed.', 'wp-carousel-free' ),
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'default'    => true,
			),
			// Pagination.
			array(
				'type'       => 'subheading',
				'content'    => __( 'Pagination', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_layout', '==', 'grid', true ),
			),
			array(
				'id'         => 'wpcp_source_pagination_pro',
				'class'      => 'wpcf_show_hide',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Pagination', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to show pagination.', 'wp-carousel-free' ),
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'wpcp_source_pagination',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Pagination', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to show pagination.', 'wp-carousel-free' ),
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', 'any|==', 'post-carousel,product-carousel|grid', true ),
			),
			array(
				'id'         => 'wpcp_post_pagination_type',
				'class'      => 'wpcp_post_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-free' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button (Pro)', 'wp-carousel-free' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll (Pro)', 'wp-carousel-free' ),
					'ajax_number'     => __( 'Ajax Number Pagination (Pro)', 'wp-carousel-free' ),
					'normal'          => __( 'No Ajax (Normal Pagination)', 'wp-carousel-free' ),
				),
				'default'    => 'normal',
				'dependency' => array( 'wpcp_carousel_type|wpcp_source_pagination|wpcp_layout', 'any|==|==', 'post-carousel,product-carousel|true|grid', true ),
			),
			array(
				'id'         => 'wpcp_pagination_type',
				'class'      => 'pro_only_field',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-free' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button (Ajax)', 'wp-carousel-free' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll (Ajax)', 'wp-carousel-free' ),
					'ajax_number'     => __( 'Number Pagination (Ajax)', 'wp-carousel-free' ),
				),
				'default'    => 'load_more_btn',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'post_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Item(s) To Show Per Page', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set item(s) to show per page.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'post_per_page_pro',
				'type'       => 'spinner',
				'class'      => 'pro_only_field',
				'title'      => __( 'Item(s) To Show Per Page (Pro)', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set item(s) to show per page.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination_pro', '==|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'post_per_click_pro',
				'class'      => 'pro_only_field',
				'type'       => 'spinner',
				'title'      => __( 'Item(s) To Show Per Click (Pro)', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set item(s) to show per click.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination_pro', '==|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose pagination alignment.', 'wp-carousel-free' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'pagination_alignment_pro',
				'type'       => 'button_set',
				'class'      => 'pro_only_field',
				'title'      => __( 'Alignment', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose pagination alignment.', 'wp-carousel-free' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'pagination_color',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set pagination color.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_color_group_field',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
				'options'    => array(
					'color'        => __( 'Color', 'wp-carousel-free' ),
					'hover_color'  => __( 'Hover Color', 'wp-carousel-free' ),
					'bg'           => __( 'Background', 'wp-carousel-free' ),
					'hover_bg'     => __( 'Hover Background', 'wp-carousel-free' ),
					'border'       => __( 'Border', 'wp-carousel-free' ),
					'hover_border' => __( 'Hover Border', 'wp-carousel-free' ),
				),
				'default'    => array(
					'color'        => '#5e5e5e',
					'hover_color'  => '#ffffff',
					'bg'           => '#ffffff',
					'hover_bg'     => '#178087',
					'border'       => '#dddddd',
					'hover_border' => '#178087',
				),
			),
			array(
				'id'         => 'pagination_color_pro',
				'type'       => 'color_group',
				'class'      => 'pro_only_field',
				'title'      => __( 'Color', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set pagination color.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_color_group_field',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
				'options'    => array(
					'color'        => __( 'Color', 'wp-carousel-free' ),
					'hover_color'  => __( 'Hover Color', 'wp-carousel-free' ),
					'bg'           => __( 'Background', 'wp-carousel-free' ),
					'hover_bg'     => __( 'Hover Background', 'wp-carousel-free' ),
					'border'       => __( 'Border', 'wp-carousel-free' ),
					'hover_border' => __( 'Hover Border', 'wp-carousel-free' ),
				),
				'default'    => array(
					'color'        => '#5e5e5e',
					'hover_color'  => '#ffffff',
					'bg'           => '#ffffff',
					'hover_bg'     => '#178087',
					'border'       => '#dddddd',
					'hover_border' => '#178087',
				),
			),
			array(
				'type'       => 'notice',
				'style'      => 'normal',
				'class'      => 'sp-settings-pro-notice',
				'content'    => __( 'Want to unleash the power of Ajax Paginations and take your website UX to the next level? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_layout|wpcp_source_pagination', '==|==', 'grid|true', true ),
			),
		), // Fields array end.
	)
); // End of Upload section.


//
// Style settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Style Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-paint-brush',
		'fields' => array(
			array(
				'id'         => 'section_title',
				'type'       => 'switcher',
				'title'      => __( 'Section Title', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide the section title.', 'wp-carousel-free' ),
				'default'    => false,
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
			),

			array(
				'id'         => 'wpcp_content_style',
				'class'      => 'wpcp_content_style',
				'type'       => 'image_select',
				'title'      => __( 'Items Style', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select an item or card style for the title, description, meta etc.', 'wp-carousel-free' ),
				'desc'       => __( 'Want to unlock amazing <a href="https://wordpresscarousel.com/item-styles/" target="_blank"><b>Item Styles</b></a> and unleash your creativity? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
				'options'    => array(
					'default'          => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/default/default-bottom.svg',
						'text'  => __( 'Default', 'wp-carousel-free' ),
					),
					'with_overlay'     => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/item-style/overlay-full.svg',
						'text'     => __( 'Overlay', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'caption_full'     => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/item-style/content-style-caption.svg',
						'text'     => __( 'Caption Full', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'caption_partial'  => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/item-style/content-style-partial.svg',
						'text'     => __( 'Caption Partial', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'content_diagonal' => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/item-style/content-style-diagonal.svg',
						'text'     => __( 'Diagonal', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'content_box'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/item-style/content-style-box.svg',
						'text'     => __( 'Content Box', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'default',
				'dependency' => array( 'wpcp_carousel_type', 'any|==', 'image-carousel,post-carousel,product-carousel,external-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_detail_position',
				'class'      => 'wpcp_content_position',
				'type'       => 'image_select',
				'title'      => __( 'Content Position', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set a position for the content.', 'wp-carousel-free' ),
				'options'    => array(
					'bottom'   => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/default/default-bottom.svg',
						'text'  => __( 'Bottom', 'wp-carousel-free' ),
					),
					'top'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/default/default-top.svg',
						'text'     => __( 'Top', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'on_right' => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/default/default-right.svg',
						'text'     => __( 'Right', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'on_left'  => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/default/default-left.svg',
						'text'     => __( 'Left', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'bottom',
				'dependency' => array( 'wpcp_carousel_type|wpcp_content_style', 'any|==', 'image-carousel,post-carousel,product-carousel,external-carousel|default', true ),
			),
			array(
				'id'         => 'item_same_height',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Enable Equal Height', 'wp-carousel-free' ),
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to make all items or slides equal to the tallest one.', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/equal-height.svg" alt="Equal Height"></div><div class="sp_wpcp-info-label">' . __( 'Equal Height', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-enable-equal-height/" target="_blank">Open Docs</a>',
				'text_width' => 100,
				'default'    => false,
				'dependency' => array( 'wpcp_layout|wpcp_content_style', 'not-any|==', 'thumbnails-slider,justified,masonry,tiles|default', true ),
			),

			array(
				'id'         => 'wpcp_slide_border',
				'type'       => 'border',
				'title'      => __( 'Item Border', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set border for the items.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_border_field',
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/slider-border.svg" alt="Items Border"></div><div class="sp_wpcp-info-label">' . __( 'Items Border', 'wp-carousel-free' ) . '</div>',
				'all'        => true,
				'default'    => array(
					'all'   => '1',
					'style' => 'solid',
					'color' => '#dddddd',
				),
				'dependency' => array( 'wpcp_carousel_type', '!=', 'product-carousel', true ),
			),

			array(
				'id'         => 'wpcp_slide_background',
				'type'       => 'color',
				'title'      => __( 'Slide Background', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set background color for the slide.', 'wp-carousel-free' ),
				'default'    => '#f9f9f9',
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'wpcp_image_caption',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Caption', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide image caption.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
			),

			array(
				'id'         => 'wpcp_image_desc',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Description', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide description.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel,video-carousel', true ),
			),
			// Post Settings.
			array(
				'id'         => 'wpcp_post_title',
				'type'       => 'switcher',
				'title'      => __( 'Post Title', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide post title.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			array(
				'id'         => 'wpcp_post_content_show',
				'type'       => 'switcher',
				'title'      => __( 'Post Content', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide post content.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_content_type',
				'type'       => 'select',
				'title'      => __( 'Content Display Type', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select a content display type.', 'wp-carousel-free' ),
				'options'    => array(
					'excerpt'            => array(
						'text' => __( 'Excerpt', 'wp-carousel-free' ),
					),
					'content'            => array(
						'text'     => __( 'Full Content (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'content_with_limit' => array(
						'text'     => __( 'Content with Limit (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'excerpt',
				'dependency' => array( 'wpcp_carousel_type|wpcp_post_content_show', '==|==', 'post-carousel|true', true ),
			),

			array(
				'type'       => 'subheading',
				'content'    => __( 'Post Meta', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			array(
				'id'         => 'wpcp_post_date_show',
				'type'       => 'switcher',
				'title'      => __( 'Date', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide post date.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_author_show',
				'type'       => 'switcher',
				'title'      => __( 'Author', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide post author name.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			// Product.
			array(
				'type'       => 'subheading',
				'content'    => __( 'Product', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_product_name',
				'type'       => 'switcher',
				'title'      => __( 'Product Name', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide product name.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_product_price',
				'type'       => 'switcher',
				'title'      => __( 'Product Price', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide product price.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_product_rating',
				'type'       => 'switcher',
				'title'      => __( 'Product Rating', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide product rating.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_product_cart',
				'type'       => 'switcher',
				'title'      => __( 'Add to Cart Button', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide add to cart button.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_social_show',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Social Share', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide post social share.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Product Brands', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'show_product_brands',
				'type'       => 'switcher',
				'title'      => __( 'Show Brands', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide product brands.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'type'       => 'submessage',
				'style'      => 'info',
				'content'    => __( 'To Enable Product Brands feature, you must Install and Activate the <a class="thickbox open-plugin-details-modal" href="' . esc_url( $smart_brand_plugin_data['plugin_link'] ) . '">Smart Brands for WooCommerce</a> plugin. <a href="#" class="brand-plugin-install' . $smart_brand_plugin_data['has_plugin'] . '" data-url="' . $smart_brand_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '"> ' . $smart_brand_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a>', 'wp-carousel-free' ),
				'dependency' => array( 'show_product_brands|wpcp_carousel_type', '==|==', 'true|product-carousel', true ),
			),
			array(
				'type'       => 'subheading',
				'content'    => __( 'Quick View Button', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'quick_view',
				'type'       => 'switcher',
				'title'      => __( 'Show Quick View Button', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide quick view button.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'type'       => 'submessage',
				'style'      => 'info',
				'content'    => __( 'To Enable Quick view feature, you must Install and Activate the <a class="thickbox open-plugin-details-modal" href="' . esc_url( $quick_view_plugin_data['plugin_link'] ) . '">Quick View for WooCommerce</a> plugin. <a href="#" class="quick-view-install' . $quick_view_plugin_data['has_plugin'] . '" data-url="' . $quick_view_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '"> ' . $quick_view_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a> ', 'wp-carousel-free' ),
				'dependency' => array( 'quick_view|wpcp_carousel_type', '==|==', 'true|product-carousel', true ),
			),
		), // End of fields array.
	)
); // Style settings section end.

// Image settings section.
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Image Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-picture-o',
		'fields' => array(
			array(
				'id'         => 'show_image',
				'type'       => 'switcher',
				'title'      => __( 'Image', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide slide image.', 'wp-carousel-free' ),
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_image_sizes',
				'type'       => 'image_sizes',
				'chosen'     => true,
				'title'      => __( 'Image Dimensions', 'wp-carousel-free' ),
				'default'    => 'medium',
				'subtitle'   => __( 'Sets the width and height dimensions for the image or slide.', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
			),
			array(
				'id'                => 'wpcp_image_crop_size',
				'type'              => 'dimensions_advanced',
				'title'             => __( 'Custom Size', 'wp-carousel-free' ),
				'class'             => 'wpcp_carousel_row_pro_only',
				'subtitle'          => __( 'Set width and height of the image.', 'wp-carousel-free' ),
				'chosen'            => true,
				'bottom'            => false,
				'left'              => false,
				'color'             => false,
				'top_icon'          => '<i class="fa fa-arrows-h"></i>',
				'right_icon'        => '<i class="fa fa-arrows-v"></i>',
				'top_placeholder'   => 'width',
				'right_placeholder' => 'height',
				'styles'            => array(
					'Soft-crop',
					'Hard-crop',
				),
				'default'           => array(
					'top'   => '600',
					'right' => '400',
					'style' => 'Soft-crop',
					'unit'  => 'px',
				),
				'attributes'        => array(
					'min' => 0,
				),
				'dependency'        => array( 'wpcp_carousel_type|wpcp_image_sizes|show_image', 'any|==|==', 'image-carousel,post-carousel,product-carousel|custom|true', true ),
			),
			array(
				'id'         => 'load_2x_image',
				'class'      => 'wpcf_show_hide',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __(
					'Load 2x Resolution Image in Retina Display
				',
					'wp-carousel-free'
				),
				'subtitle'   => __(
					'You should upload 2x sized images to show in retina display.
				',
					'wp-carousel-free'
				),
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type|wpcp_image_sizes|show_image', 'any|==|==', 'image-carousel,post-carousel,product-carousel|custom|true', true ),
			),
			array(
				'id'         => '_variable_width',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Variable Width', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable/Disable variable width. Number of column(s) depends on image width.', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/variable-width.svg" alt="Variable Width"></div><div class="sp_wpcp-info-label">' . __( 'Variable Width', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-enable-the-variable-width/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/variable-width/" target="_blank">Live Demo</a>',
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'       => 'wpcp_image_gray_scale',
				'type'     => 'select',
				'class'    => 'wpcp_image_gray_scale_pro',
				'title'    => __( 'Image Mode', 'wp-carousel-free' ),
				'subtitle' => __( 'Set a mode for the images.', 'wp-carousel-free' ),
				'options'  => array(
					''  => __( 'Original', 'wp-carousel-free' ),
					'1' => array(
						'text'     => __( 'Grayscale and original on hover (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'2' => array(
						'text'     => __( 'Grayscale on hover (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'3' => array(
						'text'     => __( 'Always grayscale (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'4' => array(
						'text'     => __( 'Custom Color (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'  => '',
				'class'    => 'chosen',
			),
			array(
				'id'         => 'wpcp_image_lazy_load',
				'type'       => 'button_set',
				'title'      => __( 'Lazy Load', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set lazy load option for the image.', 'wp-carousel-free' ),
				'options'    => array(
					'false'    => __( 'Off', 'wp-carousel-free' ),
					'ondemand' => __( 'On Demand', 'wp-carousel-free' ),
				),
				'radio'      => true,
				'default'    => 'false',
				'dependency' => array( 'wpcp_carousel_type|wpcp_carousel_mode|show_image|wpcp_layout', 'any|!=|==', 'image-carousel,post-carousel,product-carousel|ticker|true|carousel', true ),
			),
			array(
				'id'         => 'wpcp_image_zoom',
				'type'       => 'select',
				'title'      => __( 'Zoom', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set a zoom effect on hover the image.', 'wp-carousel-free' ),
				'title_help' => __( '<div class="sp_wpcp-info-label">Zoom</div><div class="sp_wpcp-short-content">This feature lets you choose a specific zoom effect when hovering over an image for an engaging experience.</div><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/post-carousel-zoom-image-modes/" target="_blank">Live Demo</a>', 'wp-carousel-free' ),
				'options'    => array(
					''         => __( 'None', 'wp-carousel-free' ),
					'zoom_in'  => __( 'Zoom In', 'wp-carousel-free' ),
					'zoom_out' => __( 'Zoom Out', 'wp-carousel-free' ),
				),
				'default'    => 'zoom_in',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
			),
			array(
				'id'         => 'wpcp_product_image_border',
				'type'       => 'border',
				'title'      => __( 'Image Border', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set border for the product image.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_border_field',
				'all'        => true,
				'default'    => array(
					'all'   => '1',
					'style' => 'solid',
					'color' => '#dddddd',
				),
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_watermark',
				'class'      => 'wpcf_show_hide',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Watermark', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable/Disable watermark for the image.', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/watermark.svg" alt="Watermark"></div><div class="sp_wpcp-info-label">' . __( 'Watermark', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-watermark/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/watermark-protection/" target="_blank">Live Demo</a>',
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
			),
			array(
				'id'         => 'wpcp_img_protection',
				'class'      => 'wpcf_show_hide',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Image Protection', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to protect image downloading from right-click.', 'wp-carousel-free' ),
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),

			),
			array(
				'id'         => '_image_title_attr',
				'type'       => 'switcher',
				'text_on'    => __( 'Show', 'wp-carousel-free' ),
				'text_off'   => __( 'Hide', 'wp-carousel-free' ),
				'title'      => __( 'Image Title Attribute', 'wp-carousel-free' ),
				'subtitle'   => __( 'Show/Hide image title attribute.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
			),
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'image-settings-tab-notice',
				'content' => __( 'Want to take your image editing experience to the next level with <b>Image Variable Width, Watermark, Protection from Right-click, Grayscale, Custom Color, and Custom Size? </b><a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),

			),
		),
	)
);

//
// Lightbox settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Lightbox Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-search',
		'fields' => array(

			array(
				'type'  => 'tabbed',
				'class' => 'wp-carousel-lightbox-settings-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'General (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-general',
						'fields' => array(// Navigation.
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'wpc-lightbox-general',
								'content' => __( 'Want to unleash the full potential of your images with <b>28+ Pro Lightbox</b> options? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
							),
							array(
								'id'         => 'l_box_autoplay',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'AutoPlay ', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable to automatically start slideshow.', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
							),

							array(
								'id'              => 'l_box_autoplay_speed',
								'class'           => 'pro_only_field',
								'type'            => 'spacing',
								'title'           => __( 'Speed', 'wp-carousel-free' ),
								'subtitle'        => __( 'The timeout between sliding to the next slide in milliseconds.', 'wp-carousel-free' ),
								'sanitize'        => 'wpcf_sanitize_number_array_field',
								'all'             => true,
								'all_text'        => false,
								'all_placeholder' => 'speed',
								'default'         => array(
									'all' => '4000',
								),
								'units'           => array(
									'ms',
								),
								'attributes'      => array(
									'min' => 0,
								),
							),
							array(
								'id'         => 'l_box_loop',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Loop', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable infinite gallery navigation.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_keyboard_nav',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Keyboard Navigation', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable keyboard navigation for the lightbox image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'       => 'l_box_nav_arrow_color',
								'class'    => 'pro_only_field',
								'type'     => 'color_group',
								'title'    => __( 'Lightbox Navigation Arrow', 'wp-carousel-free' ),
								'subtitle' => __( 'Set navigation color for the lightbox.', 'wp-carousel-free' ),
								'sanitize' => 'wpcf_sanitize_color_group_field',
								'options'  => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
									'color3' => __( 'Background', 'wp-carousel-free' ),
									'color4' => __( 'Hover Background', 'wp-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#ccc',
									'color2' => '#fff',
									'color3' => '#1e1e1e',
									'color4' => '#1e1e1e',
								),
							),
							array(
								'id'       => 'wpcp_img_lb_overlay_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Overlay Background Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Set overlay background color for lightbox.', 'wp-carousel-free' ),
								'default'  => '#0b0b0b',
							),
							array(
								'id'         => 'l_box_outside_close',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Overlay/Outside Close', 'wp-carousel-free' ),
								'subtitle'   => __( 'Close when clicked outside of the image and content or dark overlay.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
						),
					),
					array(
						'title'  => __( 'Lightbox Icons (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-icon',
						'fields' => array(// Navigation.
							array(
								'id'       => 'l_box_icon_style',
								'class'    => 'l_box_icon_style pro_only_field',
								'type'     => 'button_set',
								'title'    => __( 'Lightbox Icon Style', 'wp-carousel-free' ),
								'subtitle' => __( 'Choose a icon on hover image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'search'      => '<i class="fa fa-search"></i>',
									'plus'        => '<i class="fa fa-plus"></i>',
									'zoom'        => '<i class="fa fa-search-plus"></i>',
									'eye'         => '<i class="fa fa-eye"></i>',
									'info'        => '<i class="fa fa-info"></i>',
									'expand'      => '<i class="fa fa-expand"></i>',
									'arrow_alt'   => '<i class="fa fa-arrows-alt"></i>',
									'plus_square' => '<i class="fa fa-plus-square-o"></i>',
									'none'        => array(
										'option_name' => __( 'none', 'wp-carousel-free' ),
										'pro_only'    => true,
									),
								),
								'default'  => array( 'search' ),
							),
							array(
								'id'         => 'l_box_icon_position',
								'type'       => 'image_select',
								'title'      => __( 'Icon Display Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a icon display position on image.', 'wp-carousel-free' ),
								'class'      => 'wpcp_content_position',
								'title'      => __( 'Thumbnail Position', 'wp-carousel-free' ),
								'options'    => array(
									'middle'       => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/lightbox-thumbnail-position/lightbox-icon-middle.svg',
										'text'  => __( 'Middle', 'wp-carousel-free' ),
									),
									'top_right'    => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-thumbnail-position/lightbox-icon-top-right.svg',
										'text'     => __( 'Top Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'top_left'     => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-thumbnail-position/lightbox-icon-top-left.svg',
										'text'     => __( 'Top Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_right' => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-thumbnail-position/lightbox-icon-bottom-right.svg',
										'text'     => __( 'Bottom Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_left'  => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-thumbnail-position/lightbox-icon-bottom-left.svg',
										'text'     => __( 'Bottom Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => array( 'middle' ),
								'dependency' => array( 'wpcp_carousel_type|wpcp_logo_link_show|l_box_icon_style|wpcp_post_detail_position', 'any|==|!=|!=', 'image-carousel,mix-content,external-carousel|l_box|none|with_overlay', true ),
							),
							array(
								'id'       => 'l_box_icon_size',
								'class'    => 'border_radius_around pro_only_field',
								'type'     => 'spinner',
								'title'    => __( 'Icon Size', 'wp-carousel-free' ),
								'subtitle' => __( 'Set icon size for image.', 'wp-carousel-free' ),
								'default'  => 16,
								'unit'     => 'px',
							),
							array(
								'id'       => 'l_box_icon_color',
								'type'     => 'color_group',
								'class'    => 'pro_only_field',
								'title'    => __( 'Icon Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Set color for the lightbox icon.', 'wp-carousel-free' ),
								'sanitize' => 'wpcf_sanitize_color_group_field',
								'options'  => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
									'color3' => __( 'Background', 'wp-carousel-free' ),
									'color4' => __( 'Hover Background', 'wp-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#fff',
									'color2' => '#fff',
									'color3' => 'rgba(0, 0, 0, 0.5)',
									'color4' => 'rgba(0, 0, 0, 0.8)',
								),
							),
						),
					),
					array(
						'title'  => __( 'Image & Thumbs (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-image-and-thumbnail',
						'fields' => array(// Navigation.
							array(
								'id'         => 'l_box_icon_overlay_color',
								'class'      => 'pro_only_field',
								'type'       => 'color',
								'title'      => __( 'Image Icon Overlay Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set icon overlay color for image.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/image-icon-overlay-color.svg" alt="Image Icon Overlay Color"></div><div class="sp_wpcp-info-label">' . __( 'Image Icon Overlay Color', 'wp-carousel-free' ) . '</div>',
								'default'    => 'rgba(0,0,0,0.5)',
							),
							array(
								'id'         => 'wpcp_l_box_image_caption',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Image Caption', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image caption for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'       => 'wpcp_lb_caption_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Caption Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Change the color for lightbox image caption.', 'wp-carousel-free' ),
								'default'  => '#ffffff',
							),
							array(
								'id'         => 'l_box_desc',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Image Description', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image description for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'       => 'l_box_desc_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Description Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Change the color for lightbox image description.', 'wp-carousel-free' ),
								'default'  => '#ffffff',
							),
							array(
								'id'         => 'wpcp_image_counter',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Image Counter', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image counter for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'       => 'l_box_hover_img_on_mobile',
								'class'    => 'pro_only_field',
								'type'     => 'checkbox',
								'title'    => __( 'Disable Image Hover Overlay on the Mobile Devices', 'wp-carousel-free' ),
								'subtitle' => __( 'Check to disable image hover overlay on the mobile devices.', 'wp-carousel-free' ),
								'default'  => false,
							),
							array(
								'id'         => 'wpcp_thumbnails_gallery',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Lightbox Bottom Thumbnails Gallery Icon', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide bottom thumbnails gallery icon for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_thumb_visibility',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Bottom Thumbnails Gallery Visibility', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide bottom thumbnails gallery visibility for lightbox.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/lightbox-thumbnail.svg" alt="Bottom Thumbnails Gallery Visibility"></div><div class="sp_wpcp-info-label">' . __( 'Bottom Thumbnail Gallery Visibility', 'wp-carousel-free' ) . '</div>',
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_protect_image',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Protect Images', 'wp-carousel-free' ),
								'subtitle'   => __( 'Protect an image downloading from right-click.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
							),
						),
					),
					array(
						'title'  => __( 'Toolbar (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-toolbar',
						'fields' => array(// Toolbar.
							array(
								'id'         => 'l_box_zoom_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Zoom Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide zoom button for lightbox image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_full_screen_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Full-Screen Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide full-screen button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_slideshow_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Slideshow Play Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide slideshow play button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_social_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Social Share Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide social share button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_download_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Download Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide download button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_close_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Close Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide close bottom for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
						),
					),
					array(
						'title'  => __( 'Animations (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-animation',
						'fields' => array(// Navigation.
							array(
								'id'       => 'l_box_sliding_effect',
								'class'    => 'pro_only_field',
								'type'     => 'select',
								'title'    => __( 'Transition Effect Between Slides', 'wp-carousel-free' ),
								'subtitle' => __( 'Select a transition effect between slides for lightbox image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'fade'        => __( 'Fade', 'wp-carousel-free' ),
									'slide'       => __( 'Slide', 'wp-carousel-free' ),
									'circular'    => __( 'Circular', 'wp-carousel-free' ),
									'tube'        => __( 'Tube', 'wp-carousel-free' ),
									'zoom-in-out' => __( 'Zoom-in-out', 'wp-carousel-free' ),
									'rotate'      => __( 'Rotate', 'wp-carousel-free' ),
									'none'        => __( 'None', 'wp-carousel-free' ),
								),
								'default'  => array( 'fade' ),
							),
							array(
								'id'       => 'l_box_open_close_effect',
								'class'    => 'pro_only_field',
								'type'     => 'select',
								'title'    => __( 'Open/Close Animation Type', 'wp-carousel-free' ),
								'subtitle' => __( 'Select an animation type for opening/closing lightbox image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'zoom'        => __( 'Zoom', 'wp-carousel-free' ),
									'fade'        => __( 'Fade', 'wp-carousel-free' ),
									'slide'       => __( 'Slide', 'wp-carousel-free' ),
									'circular'    => __( 'Circular', 'wp-carousel-free' ),
									'tube'        => __( 'Tube', 'wp-carousel-free' ),
									'zoom-in-out' => __( 'Zoom-in-out', 'wp-carousel-free' ),
									'rotate'      => __( 'Rotate', 'wp-carousel-free' ),
									'none'        => __( 'None', 'wp-carousel-free' ),
								),
								'default'  => array( 'zoom' ),
							),
						),
					),

				),
			),
		), // End of fields array.
	)
); // Style settings section end.

//
// Carousel settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Carousel Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-sliders',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'wp-carousel-display-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'General', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-general',
						'fields' => array(
							array(
								'id'         => 'wpcp_carousel_orientation',
								'type'       => 'button_set',
								'class'      => 'wpcp_carousel_orientation',
								'title'      => __( 'Carousel Orientation', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose a carousel orientation.', 'wp-carousel-free' ),
								'title_help' => __(
									'<div class="sp_wpcp-info-label">Carousel Orientation</div><div class="sp_wpcp-short-content">Choose the carousel slide movement:<br>
									<strong style="font-weight: 700;">Horizontal</strong>: If you want the slides to transition horizontally, select <b>Horizontal</b>.<br>
									<strong style="font-weight: 700;">Vertical</strong>:  If you want the slides to transition vertically, select <b>Vertical</b></div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-carousel-orientation/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/carousel-orientations/" target="_blank">Live Demo</a>',
									'wp-carousel-free'
								),
								'options'    => array(
									'horizontal' => __( 'Horizontal', 'wp-carousel-free' ),
									'vertical'   => array(
										'option_name' => __( 'Vertical', 'wp-carousel-free' ),
										'pro_only'    => true,
									),
								),
								'radio'      => true,
								'default'    => 'horizontal',
							),
							array(
								'id'         => 'wpcp_carousel_auto_play',
								'type'       => 'switcher',
								'title'      => __( 'AutoPlay', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable auto play.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'carousel_auto_play_speed',
								'type'       => 'slider',
								'sanitize'   => 'wpcf_sanitize_number_field',
								'title'      => __( 'AutoPlay Delay Time', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set auto play delay time in millisecond.', 'wp-carousel-free' ),
								'title_help' => __(
									'<div class="sp_wpcp-info-label">AutoPlay Delay Time</div><div class="sp_wpcp-short-content">Set autoplay delay or interval time. The amount of time to delay between automatically carousel item. e.g. 1000 milliseconds(ms) = 1 second.</div>',
									'wp-carousel-free'
								),
								'unit'       => __( 'ms', 'wp-carousel-free' ),
								'step'       => 100,
								'min'        => 100,
								'max'        => 50000,
								'default'    => 3000,
								'dependency' => array(
									'wpcp_carousel_auto_play',
									'==',
									'true',
								),
							),
							array(
								'id'         => 'standard_carousel_scroll_speed',
								'type'       => 'slider',
								'sanitize'   => 'wpcf_sanitize_number_field',
								'title'      => __( 'Carousel Speed', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set autoplay scroll speed in millisecond.', 'wp-carousel-free' ),
								'title_help' => __( '<div class="sp_wpcp-info-label">Carousel Speed</div><div class="sp_wpcp-short-content">Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.</div>', 'wp-carousel-free' ),
								'unit'       => __( 'ms', 'wp-carousel-free' ),
								'step'       => 50,
								'min'        => 100,
								'max'        => 20000,
								'default'    => 600,
							),

							array(
								'id'         => 'carousel_pause_on_hover',
								'type'       => 'switcher',
								'title'      => __( 'Pause on Hover', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable carousel pause on hover.', 'wp-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'dependency' => array( 'wpcp_carousel_auto_play', '==', 'true', true ),
							),
							array(
								'id'         => 'carousel_infinite',
								'type'       => 'switcher',
								'title'      => __( 'Infinite Loop', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'wpcp_carousel_direction',
								'type'       => 'button_set',
								'title'      => __( 'Carousel Direction', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set carousel direction as you need.', 'wp-carousel-free' ),
								'options'    => array(
									'rtl' => __( 'Right to Left', 'wp-carousel-free' ),
									'ltr' => __( 'Left to Right', 'wp-carousel-free' ),
								),
								'radio'      => true,
								'default'    => 'rtl',
								'dependency' => array( 'wpcp_carousel_orientation', '==', 'horizontal', true ),
							),
							array(
								'id'         => 'wpcp_carousel_row',
								'class'      => 'wpcp_carousel_row_pro_only',
								'type'       => 'column',
								'title'      => __( 'Carousel Row', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set number of carousel row on device.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_number_array_field',
								'lg_desktop' => true,
								'desktop'    => true,
								'laptop'     => true,
								'tablet'     => true,
								'mobile'     => true,
								'default'    => array(
									'lg_desktop' => '1',
									'desktop'    => '1',
									'laptop'     => '1',
									'tablet'     => '1',
									'mobile'     => '1',
								),
							),
							array(
								'id'         => 'wpcp_slider_animation',
								'class'      => 'wpcp_slider_animation',
								'type'       => 'select',
								'title'      => __( 'Slide Effect', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a sliding effect.', 'wp-carousel-free' ),
								'title_help' => __(
									'<div class="sp_wpcp-info-label">Slide Effect</div><div class="sp_wpcp-short-content">Enhance your slide transition with charming Slide Effects to add elegance and dynamic motion to your slides.</div><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/slider-sliding-effects/" target="_blank">Live Demo</a>',
									'wp-carousel-free'
								),
								'options'    => array(
									''          => __( 'Slide', 'wp-carousel-free' ),
									'fade'      => array(
										'text'     => __( 'Fade (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'coverflow' => array(
										'text'     => __( 'Coverflow (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'flip'      => array(
										'text'     => __( 'Flip (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'cube'      => array(
										'text'     => __( 'Cube (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'kenburn'   => array(
										'text'     => __( 'Kenburn (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => 'slide',
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'watermark-pro-notice sp-settings-pro-notice',
								'content' => __( 'Ready to fascinate your audience with beautiful image transitions, like <b>Fade, Coverflow, Flip, Cube, Kenburn,</b> and create <b>Vertical</b> and <b>Multi-row Sliders</b>? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
							),
						),
					),
					array(
						'title'  => __( 'Navigation', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-navigation',
						'fields' => array(// Navigation.

							array(
								'id'     => 'wpcp_carousel_navigation',
								'class'  => 'wpcf-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'wpcp_navigation',
										'type'       => 'switcher',
										'class'      => 'wpcp_navigation',
										'title'      => __( 'Navigation', 'wp-carousel-free' ),
										'subtitle'   => __( 'Show/Hide carousel navigation.', 'wp-carousel-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-carousel-free' ),
										'text_off'   => __( 'Hide', 'wp-carousel-free' ),
										'text_width' => 80,
										'dependency' => array( 'wpcp_carousel_mode', '!=', 'ticker', true ),
									),
									array(
										'id'         => 'wpcp_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'wpcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'wp-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_navigation', '!=|==', 'ticker|true', true ),
									),
								),
							),
							array(
								'id'         => 'wpcp_carousel_nav_position',
								'type'       => 'select',
								'class'      => 'chosen wpcp-carousel-nav-position',
								'preview'    => true,
								'title'      => __( 'Select Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a position for the navigation arrows.', 'wp-carousel-free' ),
								'options'    => array(
									'vertical_outer'  => __( 'Vertical Outer', 'wp-carousel-free' ),
									'vertical_center_inner' => array(
										'text'     => __( 'Vertical Inner', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'vertical_center' => array(
										'text'     => __( 'Vertical Center', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'top_right'       => array(
										'text'     => __( 'Top Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'top_center'      => array(
										'text'     => __( 'Top Center', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'top_left'        => array(
										'text'     => __( 'Top Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_left'     => array(
										'text'     => __( 'Bottom Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_center'   => array(
										'text'     => __( 'Bottom Center', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_right'    => array(
										'text'     => __( 'Bottom Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),

								),
								'default'    => 'vertical_outer',
								'dependency' => array( 'wpcp_navigation|wpcp_carousel_mode', '!=|!=', 'false|ticker', true ),
							),
							array(
								'id'         => 'wpcp_visible_on_hover',
								'type'       => 'checkbox',
								'title'      => __( 'Show On Hover', 'wp-carousel-free' ),
								'class'      => 'pro_only_field carousel-nav-pro-options',
								'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'wp-carousel-free' ),
								'default'    => false,
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode|wpcp_carousel_nav_position',
									'!=|!=|any',
									'false|ticker|vertical_center,vertical_center_inner,vertical_outer',
									true,
								),
							),
							array(
								'id'         => 'navigation_icons',
								'type'       => 'button_set',
								'title'      => __( 'Navigation Arrow Style', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose a carousel navigation arrow icon.', 'wp-carousel-free' ),
								'class'      => 'wpcf_navigation_icons',
								'options'    => array(
									'right_open'         => '<i class="wpcf-icon-right-open"></i>',
									'angle'              => '<i class="wpcf-icon-angle-right"></i>',
									'chevron_open_big'   => '<i class="wpcf-icon-right-open-big"></i>',
									'chevron'            => '<i class="wpcf-icon-right-open-1"></i>',
									'right_open_3'       => '<i class="wpcf-icon-right-open-3"></i>',
									'right_open_outline' => '<i class="wpcf-icon-right-open-outline"></i>',
									'arrow'              => '<i class="wpcf-icon-right"></i>',
									'triangle'           => '<i class="wpcf-icon-arrow-triangle-right"></i>',
								),
								'default'    => 'right_open',
								'radio'      => true,
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),

							array(
								'id'         => 'navigation_icons_size',
								'type'       => 'spacing',
								'class'      => 'standard_width_of_spacing_field carousel-nav-pro-options',
								'title'      => __( 'Icon Size', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set a size for the nav arrow icon.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_number_array_field',
								'style'      => false,
								'color'      => false,
								'all'        => true,
								'units'      => array( 'px' ),
								'default'    => array(
									'all' => '20',
								),
								'attributes' => array(
									'min' => 0,
								),
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),

							array(
								'id'         => 'wpcp_nav_bg',
								'type'       => 'color_group',
								'class'      => 'carousel-nav-pro-options',
								'title'      => __( 'Background', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel navigation arrow.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => 'transparent',
									'color2' => '#178087',
								),
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode|wpcp_hide_nav_bg_border',
									'!=|!=|==',
									'false|ticker|false',
									true,
								),
							),
							array(
								'id'         => 'wpcp_nav_colors',
								'type'       => 'color_group',
								'title'      => __( 'Navigation Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel navigation.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#aaa',
									'color2' => '#178087',
								),
								'dependency' => array( 'wpcp_navigation', '!=', 'false' ),
							),
							array(
								'type'       => 'notice',
								'style'      => 'normal',
								'class'      => 'watermark-pro-notice sp-settings-pro-notice',
								'content'    => __( 'Want even more fine-tuned control over your <b>Carousel Navigation</b> display? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_navigation', '!=', 'false' ),
							),
						),
					),

					array(
						'title'  => __( 'Pagination', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-pagination',
						'fields' => array(// Pagination.
							array(
								'id'     => 'wpcp_carousel_pagination',
								'class'  => 'wpcf-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'wpcp_pagination',
										'type'       => 'switcher',
										'class'      => 'wpcp_pagination',
										'title'      => __( 'Pagination', 'wp-carousel-free' ),
										'subtitle'   => __( 'Show/Hide carousel pagination.', 'wp-carousel-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-carousel-free' ),
										'text_off'   => __( 'Hide', 'wp-carousel-free' ),
										'text_width' => 80,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '!=|==', 'ticker|carousel', true ),
									),
									array(
										'id'         => 'wpcp_pagination_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'wpcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'wp-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_layout|wpcp_pagination', '!=|==|==', 'ticker|carousel|true', true ),
									),
								),
							),

							array(
								'id'         => 'wpcp_carousel_pagination_type',
								'type'       => 'image_select',
								'class'      => 'wpcp_carousel_pagination_width',
								'title'      => __( 'Pagination Style', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select carousel pagination type.', 'wp-carousel-free' ),
								'options'    => array(
									'dots'      => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/bullets.svg',
										'text'  => __( 'Bullets', 'wp-carousel-free' ),
									),
									'dynamic'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/dynamic.svg',
										'text'  => __( 'Dynamic', 'wp-carousel-free' ),
									),
									'strokes'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/strokes.svg',
										'text'  => __( 'Strokes', 'wp-carousel-free' ),
									),
									'scrollbar' => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/scrollbar.svg',
										'text'  => __( 'Scrollbar', 'wp-carousel-free' ),
									),
									'fraction'  => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/numbers.svg',
										'text'  => __( 'Fraction', 'wp-carousel-free' ),
									),
									'numbers'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/custom-numbers.svg',
										'text'  => __( 'Numbers', 'wp-carousel-free' ),
									),
								),
								'radio'      => true,
								'default'    => 'dots',
								'dependency' => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'         => 'wpcp_carousel_pagination_position',
								'type'       => 'button_set',
								'class'      => 'wpcp_carousel_pagination_pro_options',
								'title'      => __( 'Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a position for the pagination.', 'wp-carousel-free' ),
								'options'    => array(
									'outside' => __( 'Outside', 'wp-carousel-free' ),
									'inside'  => __( 'Inside', 'wp-carousel-free' ),
								),
								'radio'      => true,
								'default'    => 'outside',
								'dependency' => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'          => 'wpcp_pagination_margin',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'wp-carousel-free' ),
								'subtitle'    => __( 'Set margin for carousel pagination.', 'wp-carousel-free' ),
								'output_mode' => 'margin',
								'unit_text'   => 'Unit',
								'sanitize'    => 'wpcf_sanitize_number_array_field',
								'class'       => 'wpcp_carousel_pagination_pro_options',
								'min'         => '-200',
								'default'     => array(
									'top'    => '40',
									'right'  => '0',
									'bottom' => '0',
									'left'   => '0',
									'unit'   => 'px',
								),
								'dependency'  => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'         => 'wpcp_pagination_color',
								'type'       => 'color_group',
								'title'      => __( 'Pagination Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel pagination dots.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Active Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#cccccc',
									'color2' => '#178087',
								),
								'dependency' => array( 'wpcp_pagination', '!=', 'false' ),
							),
							array(
								'type'       => 'notice',
								'style'      => 'normal',
								'class'      => 'watermark-pro-notice sp-settings-pro-notice',
								'content'    => __( 'Want even more fine-tuned control over your <b>Carousel Pagination</b> display? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_pagination', '!=', 'false' ),
							),
						),
					),

					array(
						'title'  => __( 'Miscellaneous', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-miscellaneous',
						'fields' => array(// Miscellaneous.
							array(
								'id'         => 'slider_swipe',
								'type'       => 'switcher',
								'title'      => __( 'Touch Swipe', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'slider_draggable',
								'type'       => 'switcher',
								'title'      => __( 'Mouse Draggable', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'slider_swipe', '==', 'true' ),
							),
							array(
								'id'         => 'free_mode',
								'type'       => 'switcher',
								'title'      => __( 'Free Mode', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable free mode slider.', 'wp-carousel-free' ),
								'title_help' => __( '<div class="sp_wpcp-info-label">Free Mode</div><div class="sp_wpcp-short-content">Enable this feature to allow users to freely scroll and position the slides at anywhere instead of specific positions.</div><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/free-mode-carousel/" target="_blank">Live Demo</a>', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
							),
							array(
								'id'         => 'carousel_swipetoslide',
								'type'       => 'switcher',
								'title'      => __( 'Swipe To Slide', 'wp-carousel-free' ),
								'subtitle'   => __( 'Allow users to drag or swipe directly to a slide irrespective of slides to scroll.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'slider_swipe', '==', 'true' ),
							),
						),
					),
				),
			),
		),
	)
); // Carousel settings section end.



//
// Typography section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'           => __( 'Typography', 'wp-carousel-free' ),
		'icon'            => 'fa fa-font',
		'enqueue_webfont' => false,
		'fields'          => array(
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'watermark-pro-notice typography-pro-notice',
				'content' => __( 'Want to customize everything <b>(Colors and Typography)</b> easily? <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>Upgrade To Pro!</b></a>', 'wp-carousel-free' ),
			),
			array(
				'id'         => 'section_title_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Load Section Title Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the section title.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
			),
			array(
				'id'            => 'wpcp_section_title_typography',
				'class'         => 'disable-color-picker',
				'type'          => 'typography',
				'title'         => __( 'Section Title Font', 'wp-carousel-free' ),
				'subtitle'      => __( 'Set the section title font properties.', 'wp-carousel-free' ),
				'margin_bottom' => true,
				'default'       => array(
					'color'          => '#444444',
					'font-family'    => 'Open Sans',
					'font-weight'    => '600',
					'font-size'      => '24',
					'line-height'    => '28',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'none',
					'type'           => 'google',
					'unit'           => 'px',
					'margin-bottom'  => '30',
					'Set the section title font properties.' => 'px',
				),
				'preview'       => 'always',
				'preview_text'  => 'Section Title',
			),
			array(
				'id'         => 'wpcp_image_caption_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Load Caption Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the image caption.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
			),
			array(
				'id'           => 'wpcp_image_caption_typography',
				'class'        => 'disable-color-picker',
				'type'         => 'typography',
				'title'        => __( 'Caption Font', 'wp-carousel-free' ),
				'subtitle'     => __( 'Set caption font properties.', 'wp-carousel-free' ),
				'class'        => 'disable-color-picker',
				'default'      => array(
					'color'          => '#333',
					'font-family'    => 'Open Sans',
					'font-weight'    => '600',
					'font-size'      => '15',
					'line-height'    => '23',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'capitalize',
					'type'           => 'google',
				),
				'preview_text' => 'The image caption',
				'dependency'   => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
			),
			array(
				'id'         => 'wpcp_image_desc_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Load Description Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the image description.', 'wp-carousel-free' ),
				'text_width' => 80,
				'default'    => false,
				'dependency' => array( 'wpcp_carousel_type|wpcp_post_title', '==|==', 'image-carousel|true', true ),
			),
			array(
				'id'         => 'wpcp_image_desc_typography',
				'class'      => 'disable-color-picker',
				'type'       => 'typography',
				'title'      => __( 'Description Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set description font properties.', 'wp-carousel-free' ),
				'class'      => 'disable-color-picker',
				'default'    => array(
					'color'          => '#333',
					'font-family'    => 'Open Sans',
					'font-weight'    => '400',
					'font-style'     => 'normal',
					'font-size'      => '14',
					'line-height'    => '21',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'type'           => 'google',
				),
				'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
			),
			// Post Typography.
			array(
				'id'         => 'wpcp_title_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Load Title Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the slide title.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'           => 'wpcp_title_typography',
				'class'        => 'disable-color-picker',
				'type'         => 'typography',
				'title'        => __( 'Post Title Font', 'wp-carousel-free' ),
				'subtitle'     => __( 'Set title font properties.', 'wp-carousel-free' ),
				'default'      => array(
					'color'          => '#444',
					'hover_color'    => '#555',
					'font-family'    => 'Open Sans',
					'font-style'     => '600',
					'font-size'      => '20',
					'line-height'    => '30',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'text-transform' => 'capitalize',
					'type'           => 'google',
				),
				'hover_color'  => true,
				'preview_text' => 'The Post Title',
				'dependency'   => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			array(
				'id'         => 'wpcp_post_content_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Post Content Font Load', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for post the content.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_content_typography',
				'class'      => 'disable-color-picker',
				'type'       => 'typography',
				'title'      => __( 'Post Content Font', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set post content font properties.', 'wp-carousel-free' ),
				'default'    => array(
					'color'          => '#333',
					'font-family'    => 'Open Sans',
					'font-style'     => '400',
					'font-size'      => '16',
					'line-height'    => '26',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'type'           => 'google',
				),
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_meta_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Post Meta Font Load', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the post meta.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'           => 'wpcp_post_meta_typography',
				'class'        => 'disable-color-picker',
				'type'         => 'typography',
				'title'        => __( 'Post Meta Font', 'wp-carousel-free' ),
				'subtitle'     => __( 'Set post meta font properties.', 'wp-carousel-free' ),
				'default'      => array(
					'color'          => '#999',
					'font-family'    => 'Open Sans',
					'font-style'     => '400',
					'font-size'      => '14',
					'line-height'    => '24',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'type'           => 'google',
				),
				'preview_text' => 'Post Meta', // Replace preview text with any text you like.
				'dependency'   => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),

			// // Product Typography.
			array(
				'id'         => 'wpcp_product_name_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Product Name Font Load', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the product name.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'           => 'wpcp_product_name_typography',
				'class'        => 'disable-color-picker',
				'type'         => 'typography',
				'title'        => __( 'Product Name Font', 'wp-carousel-free' ),
				'subtitle'     => __( 'Set product name font properties.', 'wp-carousel-free' ),
				'default'      => array(
					'color'          => '#444',
					'hover_color'    => '#555',
					'font-family'    => 'Open Sans',
					'font-style'     => '400',
					'font-size'      => '15',
					'line-height'    => '23',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'type'           => 'google',
				),
				'hover_color'  => true,
				'preview_text' => 'Product Name', // Replace preview text.
				'dependency'   => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_product_price_font_load',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Product Price Font Load', 'wp-carousel-free' ),
				'subtitle'   => __( 'On/Off google font for the product price.', 'wp-carousel-free' ),
				'default'    => false,
				'text_width' => 80,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'           => 'wpcp_product_price_typography',
				'class'        => 'disable-color-picker',
				'type'         => 'typography',

				'title'        => __( 'Product Price Font', 'wp-carousel-free' ),
				'subtitle'     => __( 'Set product price font properties.', 'wp-carousel-free' ),
				'default'      => array(
					'color'          => '#222',
					'font-family'    => 'Open Sans',
					'font-style'     => '700',
					'font-size'      => '14',
					'line-height'    => '26',
					'letter-spacing' => '0',
					'text-align'     => 'center',
					'type'           => 'google',
				),
				'preview_text' => '$49.00', // Replace preview text with any text you like.
				'dependency'   => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
		), // End of fields array.
	)
); // Style settings section end.


//
// Metabox of the footer section / shortocde section.
// Set a unique slug-like ID.
//
$wpcp_display_shortcode = 'sp_wpcp_display_shortcode';

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_display_shortcode,
	array(
		'title'        => __( 'WordPress Carousel', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$wpcp_display_shortcode,
	array(
		'fields' => array(
			array(
				'type'  => 'shortcode',
				'class' => 'wpcp-admin-footer',
			),
		),
	)
);
