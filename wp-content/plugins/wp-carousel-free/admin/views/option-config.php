<?php
/**
 * The options configuration
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_wpcp_settings';

//
// Create options.
//
SP_WPCF::createOptions(
	$prefix,
	array(
		'menu_title'         => __( 'Settings', 'wp-carousel-free' ),
		'menu_slug'          => 'wpcp_settings',
		'menu_parent'        => 'edit.php?post_type=sp_wp_carousel',
		'menu_type'          => 'submenu',
		'ajax_save'          => true,
		'save_defaults'      => true,
		'show_reset_all'     => false,
		'framework_title'    => __( 'Settings', 'wp-carousel-free' ),
		'framework_class'    => 'sp-wpcp-options',
		'theme'              => 'light',
		// menu extras.
		'show_bar_menu'      => false,
		'show_sub_menu'      => false,
		'show_network_menu'  => false,
		'show_in_customizer' => false,
		'show_search'        => false,
		'show_footer'        => false,
		'show_reset_section' => true,
		'show_all_options'   => false,
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$prefix,
	array(
		'title'  => 'Advanced Settings',
		'icon'   => 'fa fa-cogs',
		'fields' => array(
			array(
				'id'         => 'wpcf_delete_all_data',
				'type'       => 'checkbox',
				'title'      => __( 'Clean-up Data on Plugin Deletion', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-short-content">' . __( 'Check to remove plugin\'s data when plugin is uninstalled or deleted.', 'wp-carousel-free' ) . '</div>',
				'default'    => false,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Styles', 'wp-carousel-free' ),
			),
			array(
				'id'         => 'wpcp_enqueue_swiper_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'wp-carousel-free' ),
				'text_on'    => __( 'Enqueue', 'wp-carousel-free' ),
				'text_off'   => __( 'Dequeue', 'wp-carousel-free' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_enqueue_fa_css',
				'type'       => 'switcher',
				'title'      => __( 'Font Awesome CSS', 'wp-carousel-free' ),
				'text_on'    => __( 'Enqueue', 'wp-carousel-free' ),
				'text_off'   => __( 'Dequeue', 'wp-carousel-free' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'type'    => 'subheading',
				'content' => __( 'Scripts', 'wp-carousel-free' ),
			),
			array(
				'id'         => 'wpcp_swiper_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'wp-carousel-free' ),
				'text_on'    => __( 'Enqueue', 'wp-carousel-free' ),
				'text_off'   => __( 'Dequeue', 'wp-carousel-free' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_ajax_js',
				'type'       => 'switcher',
				'title'      => __( 'Load Script for Ajax Theme', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-short-content">' . __( 'Enable this option for ajax theme so that the WP Carousel works perfectly with that theme.', 'wp-carousel-free' ) . '</div>',
				'text_on'    => __( 'Enqueue', 'wp-carousel-free' ),
				'text_off'   => __( 'Dequeue', 'wp-carousel-free' ),
				'text_width' => 95,
				'default'    => false,
			),
		),
	)
);
// Watermark settings option section.
SP_WPCF::createSection(
	$prefix,
	array(
		'title'  => __( 'Watermark Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-copyright',
		'fields' => array(
			array(
				'id'       => 'wm_watermark_type',
				'title'    => __( 'Watermark Type', 'wp-carousel-free' ),
				'type'     => 'button_set',
				'sanitize' => 'sanitize_text_field',
				'class'    => 'only_pro_settings',
				'options'  => array(
					'logo' => __( 'Logo', 'wp-carousel-free' ),
					'text' => __( 'Text', 'wp-carousel-free' ),
				),
				'default'  => 'logo',
			),
			array(
				'id'         => 'wm_image',
				'class'      => 'only_pro_settings',
				'title'      => __( 'Watermark Image', 'wp-carousel-free' ),
				'type'       => 'media',
				'library'    => array( 'image' ),
				'url'        => false,
				'preview'    => true,
				'dependency' => array( 'wm_watermark_type', '==', 'logo' ),
			),
			array(
				'id'      => 'wm_position',
				'title'   => __( 'Position', 'wp-carousel-free' ),
				'type'    => 'select',
				'class'   => 'only_pro_settings',
				'options' => array(
					'lt' => __( 'Left Top', 'wp-carousel-free' ),
					'lm' => __( 'Left Center', 'wp-carousel-free' ),
					'lb' => __( 'Left Bottom', 'wp-carousel-free' ),
					'rt' => __( 'Right top', 'wp-carousel-free' ),
					'rm' => __( 'Right Center', 'wp-carousel-free' ),
					'rb' => __( 'Right Bottom', 'wp-carousel-free' ),
					'mb' => __( 'Center Bottom', 'wp-carousel-free' ),
					'mm' => __( 'Center Center', 'wp-carousel-free' ),
					'mt' => __( 'Center Top', 'wp-carousel-free' ),
				),
				'default' => 'rb',
			),
			array(
				'id'      => 'wm_margin',
				'type'    => 'spacing',
				'class'   => 'only_pro_settings',

				'title'   => __( 'Margin', 'wp-carousel-free' ),
				'all'     => true,
				'default' => array(
					'all'  => '10',
					'unit' => '%',
				),
				'units'   => array(
					'px',
					'%',
				),
			),
			array(
				'id'      => 'wm_opacity',
				'type'    => 'spinner',
				'class'   => 'only_pro_settings',
				'title'   => __( 'Opacity', 'wp-carousel-free' ),
				'default' => '0.5',
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
			),
			array(
				'id'         => 'wm_custom',
				'class'      => 'wm_custom',
				'type'       => 'switcher',
				'class'      => 'only_pro_settings wpcf_show_hide',
				'title'      => __( 'Custom Size', 'wp-carousel-free' ),
				'title_help' => __( 'Set watermark custom size related to image (horizontally/vertically)', 'wp-carousel-free' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'      => 'wm_quality',
				'type'    => 'spinner',
				'class'   => 'only_pro_settings',
				'title'   => __( 'Image Quality', 'wp-carousel-free' ),
				'default' => '100',
				'min'     => 5,
				'max'     => 100,
				'step'    => 1,
			),
			array(
				'id'           => 'wm_clean',
				'class'        => 'wm_clean_cache',
				'type'         => 'media',
				'class'        => 'only_pro_settings',
				'title'        => __( 'Clean Watermark Cache', 'wp-carousel-free' ),
				'button_title' => esc_html__( 'Clean', 'wp-carousel-free' ),
				'url'          => false,
				'preview'      => true,
			),
			array(
				'type'    => 'notice',
				'style'   => 'normal',
				'class'   => 'watermark-pro-notice',
				'content' => __( 'To unlock Essential Watermark Settings', 'wp-carousel-free' ) . ', <a href="https://wordpresscarousel.com/pricing/?ref=1" target="_blank"><b>' . __( 'Upgrade To Pro', 'wp-carousel-free' ) . '!</b></a>',
			),
		),
	)
);

// Responsive section.
//
SP_WPCF::createSection(
	$prefix,
	array(
		'title'  => __( 'Responsive Breakpoints', 'wp-carousel-free' ),
		'icon'   => 'fa fa-tablet',
		'fields' => array(
			array(
				'id'           => 'wpcp_responsive_screen_setting',
				'type'         => 'column',
				'title'        => __( 'Minimum Screen Width', 'wp-carousel-free' ),
				'min'          => '300',
				'unit'         => true,
				'units'        => array(
					'px',
				),
				'lg_desktop'   => false,
				'desktop_icon' => __( 'Desktop', 'wp-carousel-free' ),
				'laptop_icon'  => __( 'Laptop', 'wp-carousel-free' ),
				'tablet_icon'  => __( 'Tablet', 'wp-carousel-free' ),
				'mobile_icon'  => __( 'Mobile', 'wp-carousel-free' ),
				'default'      => array(
					'desktop' => '1200',
					'laptop'  => '980',
					'tablet'  => '736',
					'mobile'  => '480',
				),
			),
		),
	)
);

//
// Custom CSS Fields.
//
SP_WPCF::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Custom CSS', 'wp-carousel-free' ),
		'icon'   => 'fa fa-css3',
		'fields' => array(
			array(
				'id'       => 'wpcp_custom_css',
				'type'     => 'code_editor',
				'title'    => __( 'Custom CSS', 'wp-carousel-free' ),
				'settings' => array(
					'mode'  => 'css',
					'theme' => 'monokai',
				),
			),
		),
	)
);
