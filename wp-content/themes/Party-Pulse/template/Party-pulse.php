<?php
/* Template Name: V-Custom */

get_header();

if (have_posts()) :
   while (have_posts()) :
      the_post();
      // Your template code here for single page
      the_content(); // Example: Display the post/page content
   endwhile;
endif;

get_footer();