<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package YourThemeName
 */
?>

 <footer id="footer">
 <div class="container">
     <div class="row">
         <div class="col-md-6">
             <!-- Copyright Information -->
             <p>&copy; <?php echo date('Y'); ?> Your Website Name. All rights reserved.</p>
         </div>
         <div class="col-md-6">
             <!-- Navigation Links -->
             <nav id="footer-navigation">
                 <ul>
                     <li><a href="<?php echo home_url(); ?>">Home</a></li>
                     <li><a href="<?php echo site_url('/about-us'); ?>">About Us</a></li>
                     <li><a href="<?php echo site_url('/contact'); ?>">Contact</a></li>
                     <!-- Add more navigation links as needed -->
                 </ul>
             </nav>
         </div>
     </div>
 </div>
</footer>

<?php