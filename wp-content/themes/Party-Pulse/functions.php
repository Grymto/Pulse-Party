<?php

require_once("Vite.php");
require_once(get_template_directory() . "/init.php");

function mytheme_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

function arphabet_widgets_init() {
    register_sidebar(array(
        'name'          => 'Home right sidebar',
        'id'            => 'home_right_1',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'arphabet_widgets_init');

if (is_active_sidebar('home_right_1')) : ?>
    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <?php dynamic_sidebar('home_right_1'); ?>
    </div><!-- #primary-sidebar -->
<?php endif;

function theme_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('custom-header');
    add_theme_support('custom-background');
}
add_action('after_setup_theme', 'theme_setup');

function theme_scripts() {
    wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    wp_enqueue_style('additional-styles', get_template_directory_uri() . '/build/src/app.css', array('theme-style'), '1.0');
    wp_enqueue_script('theme-script', get_template_directory_uri() . '/js/theme-script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_scripts');

function register_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'text-domain'),
        'footer-menu' => __('Footer Menu', 'text-domain'),
        'footer-party-pulse' => __('Footer Party Pulse'),
        'footer-information' => __('Footer Information'),
        'footer-kundservice' => __('Footer Kundservice'),
        'footer-sociala-medier' => __('Footer Sociala Medier'),
        'footer-här-finns-vi' => __('Footer Här Finns Vi'),
        'headermenu-left' => __('headermenu-left'),
        'headermenu-right' => __('headermenu-right'),
        'headermenu-home' => __('headermenu-home')
    ));
}
add_action('init', 'register_menus');

function add_custom_image_sizes() {
    add_image_size('custom-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'add_custom_image_sizes');

function enqueue_fireworks_script() {
    if (is_page('home')) {
        wp_enqueue_script('canvas-confetti', 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js', array(), null, true);
        wp_enqueue_script('custom-fireworks', get_template_directory_uri() . '/resources/js/fireworks.js', array('canvas-confetti'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_fireworks_script');

function add_preload_links() {
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style">';
    $additional_css = get_template_directory_uri() . '/build/src/app.css';
    echo '<link rel="preload" href="' . esc_url($additional_css) . '" as="style">';
}
add_action('wp_head', 'add_preload_links');


// Create a shortcode for WooCommerce breadcrumbs
function woocommerce_breadcrumb_shortcode() {
    if ( function_exists( 'woocommerce_breadcrumb' ) ) {
        ob_start();
        woocommerce_breadcrumb();
        return ob_get_clean();
    }
}
add_shortcode( 'woocommerce_breadcrumb', 'woocommerce_breadcrumb_shortcode' );

//Remove Woo messages.
function custom_enqueue_woocommerce_ajax_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        function removeWooCommerceNotices() {
            setTimeout(function() {
                $('.woocommerce-message, .woocommerce-error').fadeOut();
            }, 3000);
        }

        $(document).ajaxComplete(function(event, xhr, settings) {
            if ($('.woocommerce-message').length || $('.woocommerce-error').length) {
                removeWooCommerceNotices();
            }
        });

        removeWooCommerceNotices();
    });
    </script>
    <?php
}
add_action('wp_footer', 'custom_enqueue_woocommerce_ajax_script');