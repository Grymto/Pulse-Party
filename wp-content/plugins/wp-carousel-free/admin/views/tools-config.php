<?php
/**
 * The admin tools menu of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_wpcf_tools';

//
// Create options.
//
SP_WPCF::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Tools', 'wp-carousel-free' ),
		'menu_slug'        => 'wpcf_tools',
		'menu_parent'      => 'edit.php?post_type=sp_wp_carousel',
		'menu_type'        => 'submenu',
		'ajax_save'        => false,
		'show_bar_menu'    => false,
		'save_defaults'    => false,
		'show_reset_all'   => false,
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'show_buttons'     => false, // Custom show button option added for hide save button in tools page.
		'theme'            => 'light',
		'framework_title'  => __( 'Tools', 'wp-carousel-free' ),
		'framework_class'  => 'sp-wpcp-options wpcp_tools',
	)
);
SP_WPCF::createSection(
	$prefix,
	array(
		'title'  => __( 'Export', 'wp-carousel-free' ),
		'fields' => array(
			array(
				'id'       => 'wpcp_what_export',
				'type'     => 'radio',
				'class'    => 'wpcp_what_export',
				'title'    => __( 'Choose What To Export', 'wp-carousel-free' ),
				'multiple' => false,
				'options'  => array(
					'all_shortcodes'      => __( 'All Carousels (Shortcodes)', 'wp-carousel-free' ),
					'selected_shortcodes' => __( 'Selected Carousels (Shortcodes)', 'wp-carousel-free' ),
				),
				'default'  => 'all_shortcodes',
			),
			array(
				'id'          => 'lcp_post',
				'class'       => 'wpcp_post_ids',
				'type'        => 'select',
				'title'       => ' ',
				'options'     => 'sp_wp_carousel',
				'chosen'      => true,
				'sortable'    => false,
				'multiple'    => true,
				'placeholder' => __( 'Choose carousel(s)', 'wp-carousel-free' ),
				'query_args'  => array(
					'posts_per_page' => -1,
				),
				'dependency'  => array( 'wpcp_what_export', '==', 'selected_shortcodes', true ),

			),
			array(
				'id'       => 'export',
				'class'    => 'wpcp_export',
				'type'     => 'button_set',
				'sanitize' => 'sanitize_text_field',
				'title'    => ' ',
				'options'  => array(
					'' => 'Export',
				),
			),
		),
	)
);
SP_WPCF::createSection(
	$prefix,
	array(
		'title'  => __( 'Import', 'wp-carousel-free' ),
		'fields' => array(
			array(
				'class' => 'wpcp_import',
				'type'  => 'custom_import',
				'title' => __( 'Import JSON File To Upload', 'wp-carousel-free' ),
			),
		),
	)
);
