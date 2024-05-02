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
    <img class="main-icon" src="/wp-content/uploads/2024/04/Loga-100x100.png" alt="Icon"> <!-- Use PHP to generate the correct path -->
   
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
        <div class="search-bar">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img src="/wp-content/uploads/loupe.png" alt="Icon" class="icon-4">
            <input type="search" class="search-field" placeholder="Sök bland 25.000+ produkter..." value="<?php echo get_search_query(); ?>" name="s" />
            <i class="icon-search"></i>
        </div>
        </form>
    </div>

    <div class="Icon-div">
    <img src="/wp-content\uploads\customer.png" alt="Icon">
    <img src="/wp-content\uploads\heart.png" alt="Icon">
    <a href="https://pulse-party.test/cart/">
    <img src="/wp-content/uploads/Shopping basket.png" alt="Icon">
    </a>
    </div>

</header>

</body>
</html>
