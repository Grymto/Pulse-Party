<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?> 
    <link rel="stylesheet" href="style.css">
</head>

<body <?php body_class(); ?>>

<footer>
    <div class="container">
    <img src="<?php echo esc_url(home_url('/wp-content/uploads/2024/05/footer-banner.png')); ?>" alt="footer-banner">

        <div class="column">
            <div class="category-title">Party Pulse</div>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-party-pulse',
                'menu_id' => 'footer-party-pulse',
                'container' => 'nav',
                'container_class' => 'menu'
            )); ?>
        </div>

        <div class="column">
            <div class="category-title">Information</div>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-information',
                'menu_id' => 'footer-information',
                'container' => 'nav',
                'container_class' => 'menu'
            )); ?>
        </div>

        <div class="column">
            <div class="category-title">Kundservice</div>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-kundservice',
                'menu_id' => 'footer-kundservice',
                'container' => 'nav',
                'container_class' => 'menu'
            )); ?>
        </div>
        <div class="column">
            <div class="category-title">Här finns vi </div>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-här-finns-vi',
                'menu_id' => 'footer-här-finns-vi',
                'container' => 'nav',
                'container_class' => 'menu'
            )); ?>
        </div>
        <div class="column">
            <div class="category-title">Sociala Medier</div>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-sociala-medier',
                'menu_id' => 'footer-sociala-medier',
                'container' => 'nav',
                'container_class' => 'menu'
            )); ?>
        </div>

        <div class="rights">
            <p>&copy; <?php echo date('Y'); ?> Party Pulse AB. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?> 
</body>
</html>
