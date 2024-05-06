<?php
/*
Template Name: Custom Homepage
*/


get_header(); ?>

<div class="main-container">
<div class="homepage-banner">
<img src="<?php echo esc_url(home_url('/wp-content/uploads/2024/05/Eurovision-veckan.webp')); ?>" alt="Main-banner">
</div>

<h1>Kampanjer!</h1>
<?php echo do_shortcode('[sp_wpcarousel id="213"]'); ?>
<h1>Nyheter</h1>
<?php echo do_shortcode('[sp_wpcarousel id="214"]'); ?>
<h1>Utförsälning</h1>
<?php echo do_shortcode('[sp_wpcarousel id="215"]'); ?>
</div>

<?php get_footer(); ?>