<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?> <!-- Important WordPress hook for including styles, scripts, and other head elements -->

</head>

<body <?php body_class(); ?>>

    <header class="site-header">
        <nav class="main-navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'Header',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                )
            );
            ?>
        </nav>

    </header>
