<html>

<head>
    <?php wp_head() ?>
</head>

<body>
    <?php wp_body_open(); ?>

    <header class="header">
        <div class="headerleft">
        
        <div class="logo">
            <?php
            $menu = array(
                'theme_location' => 'headermenu-home',
                'menu_id' => 'headermenu-home',
                'container' => 'nav',
                'container_class' => 'menu'
            );
            
            wp_nav_menu($menu);
            ?>
            
          </div>
        
          <button class="kategorier" id="openModalBtn">
          <a href="">kategorier</a>  
          </button>
            
            
            <?php
            $menu = array(
                'theme_location' => 'headermenu-left',
                'menu_id' => 'headermenu-left',
                'container' => 'nav',
                'container_class' => 'menu'
            );
            
            wp_nav_menu($menu);
            ?>
        </div>

<div class="headerRight">
<?php
            $menu = array(
                'theme_location' => 'headermenu-right',
                'menu_id' => 'headermenu-right',
                'container' => 'nav',
                'container_class' => 'menu'
            );

            wp_nav_menu($menu);
            ?>


</div>





<div id="modal" class="modal">
    <div class="modal-content">
      
    <div class="header"> <h2>Kategorier</h2>
      <span class="close">X</span></div>
   
    <?php echo do_shortcode('[product_categories]'); ?>

   

    </div>
    <div class="skämt"><p id="skämt">Vad sa ölet till champagnen?</p>
    <p id="skämt">Du är så bubblig idag!</p></div>
    
    <?php include('footerModul.php') ?>
</div>

    </header>

    </body>

<script>

var modal = document.getElementById("modal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function(event) {
  event.preventDefault(); 
  modal.style.left = "0";
}

span.onclick = function() {
  modal.style.left = "-100%";
}

window.onclick = function(event) {
  if (event.target === modal) {
    modal.style.left = "-100%";
  }
}


</script>

</html>

