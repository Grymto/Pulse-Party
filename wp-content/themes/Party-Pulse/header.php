<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?> <!-- Important WordPress hook for including styles, scripts, and other head elements -->
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file -->
</head>

<body <?php body_class(); ?>>

<header class="site-header">
    <img src="/wp-content/uploads/2024/04/Loga-100x100.png" alt="Icon"> <!-- Use PHP to generate the correct path -->
    <div class="search-bar">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label>
                <span class="screen-reader-text"><?php _e( 'Search for:', 'textdomain' ); ?></span>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'textdomain' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <button type="submit" class="search-submit"><?php echo esc_attr_x( 'Search', 'submit button', 'textdomain' ); ?></button>
        </form>
    </div>
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

</body>
</html>
