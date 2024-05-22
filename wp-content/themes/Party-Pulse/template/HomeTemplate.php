<?php
/*
Template Name: Custom Homepage
*/ ?>

<?php get_header(); ?>

<?php the_content(); ?>
<div class="main-container">

<?php echo do_shortcode('[sp_wpcarousel id="214"]'); ?>


<?php echo do_shortcode('[sp_wpcarousel id="213"]'); ?>


<?php echo do_shortcode('[sp_wpcarousel id="215"]'); ?>

</div>

<?php get_footer(); ?>