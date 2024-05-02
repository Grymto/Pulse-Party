<?php

require_once("Vite.php");

function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

// Theme Setup
function theme_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('custom-header');
    add_theme_support('custom-background');
    // Add more theme support as needed
}
add_action('after_setup_theme', 'theme_setup');



// Enqueue Stylesheets and Scripts
function theme_scripts() {
    // Stylesheets
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    // Scripts
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/js/theme-script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_scripts');



// Custom Navigation Menus
function register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'text-domain'),
        'footer-menu' => __('Footer Menu', 'text-domain'),
    ));
}
add_action('init', 'register_menus');


// Custom Image Sizes
function add_custom_image_sizes() {
    add_image_size('custom-thumbnail', 300, 200, true); // width, height, crop
}
add_action('after_setup_theme', 'add_custom_image_sizes');

//Custom Footer Menu
function register_my_menus() {
    register_nav_menus(
        array(
            'footer-party-pulse' => __('Footer Party Pulse'),
            'footer-information' => __('Footer Information'), 
            'footer-kundservice' => __('Footer Kundservice'),
            'footer-sociala-medier' => __('Footer Sociala Medier'),
            'footer-här-finns-vi' => __('Footer Här Finns Vi')
        )
    );
}
add_action('init', 'register_my_menus');
