<?php
/*
Template Name: Custom Homepage
*/ ?>

<?php get_header(); ?>

<div class="main-container">
<div class="homepage-banner">
<img src="<?php echo esc_url(home_url('/wp-content/uploads/2024/05/Eurovision-veckan.webp')); ?>" alt="Main-banner">
</div>


<?php echo do_shortcode('[sp_wpcarousel id="214"]'); ?>

<?php echo do_shortcode('[sp_wpcarousel id="213"]'); ?>

<?php echo do_shortcode('[sp_wpcarousel id="215"]'); ?>
</div>

<?php get_footer(); ?>