(function ($) {
    'use strict';

    jQuery('body').find('.wpcp-carousel-section.wpcp-standard').each(function () {

        var carousel_id = $(this).attr('id');
        var _this = $(this);
        var wpcpSwiperData = $('#' + carousel_id).data('swiper');
        var wpcpSwiper = new Swiper('#' + carousel_id + ':not(.swiper-initialized, .swiper-container-initialized)', {
            // Optional parameters
            autoplay: wpcpSwiperData.autoplay ? ({ 
                delay: wpcpSwiperData.autoplaySpeed, disableOnInteraction: false, }) : false,
            speed: wpcpSwiperData.speed,
            // slidesPerGroup: 1,
            slidesPerView: wpcpSwiperData.slidesToShow.mobile,
            simulateTouch: wpcpSwiperData.draggable,
            loop: wpcpSwiperData.infinite,
            allowTouchMove: wpcpSwiperData.swipe,
			spaceBetween: wpcpSwiperData.spaceBetween,
            freeMode: wpcpSwiperData.freeMode,
            grabCursor: true,
			preloadImages: ('false' !== wpcpSwiperData.lazyLoad) ? true : false,
			lazy: {
				loadPrevNext: ('false' !== wpcpSwiperData.lazyLoad) ? true : false,
				loadPrevNextAmount: 1
			},

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 480px
                [wpcpSwiperData.responsive.mobile]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.tablet,
                },
                // when window width is >= 736px
                [wpcpSwiperData.responsive.tablet]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.laptop,
                },
                // when window width is >= 980px
                [wpcpSwiperData.responsive.laptop]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.desktop,
                },
                [wpcpSwiperData.responsive.desktop]: {
                    slidesPerView: wpcpSwiperData.slidesToShow.lg_desktop,
                }
            },
          
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            a11y: wpcpSwiperData.accessibility ? ({
                prevSlideMessage: 'Previous slide',
                nextSlideMessage: 'Next slide',
            }) : false,
          
            // Navigation arrows
            navigation: {
              nextEl: '#' + carousel_id + ' .swiper-button-next',
              prevEl: '#' + carousel_id + ' .swiper-button-prev',
            },
        });
        
        // On hover stop.
        if (wpcpSwiperData.pauseOnHover && wpcpSwiperData.autoplay) {
            $('#' + carousel_id).on({
                mouseenter: function () {
                    wpcpSwiper.autoplay.stop();
                },
                mouseleave: function () {
                    wpcpSwiper.autoplay.start();
                }
            });
        }
    });

    jQuery(document).find('.wpcp-carousel-wrapper').addClass('wpcp-loaded');
})(jQuery);