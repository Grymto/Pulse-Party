(function ($) {
    'use strict';

    $(document).ready(function(){
        function wpcf_load_script(){
            var wpcfAjaxInterval = setInterval(function(){
                // Check if the WP Carousel is not initialized or preloader is not removed.
                if( $(document).find('.wpcp-carousel-wrapper:not(.wpcp-loaded)').length > 0 ) {
                    if (typeof wpcf_vars !== "undefined") {
                        $.getScript(wpcf_vars.script_path + '/preloader.js');
                        $.getScript(wpcf_vars.script_path + '/wp-carousel-free-public.js');
                    }
                    clearInterval(wpcfAjaxInterval);
                }
            }, 1000);
            // Clear interval after 10s.
            setTimeout(function(){
                    clearInterval(wpcfAjaxInterval);
            }, 10000);
        }
        
        // Load script for ajax loaded theme.
        $(window).on('popstate', function(event) {
            wpcf_load_script();
        });
        $(document).on('click','a',function(){
            wpcf_load_script();
        });
    });
})(jQuery);
