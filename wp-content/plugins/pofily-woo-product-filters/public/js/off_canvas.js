jQuery(document).ready(function () {
    /* global  viwcpf_localize_args */
    if (jQuery('.viwcpf-off_canvas-wrap:not(.viwcpf-off_canvas-wrap-init)').length) {
        jQuery('.viwcpf-off_canvas-wrap:not(.viwcpf-off_canvas-wrap-init)').addClass('viwcpf-off_canvas-wrap-init');
        viwcpf_off_canvas_init();
        if( Cookies.get('viwcpf_off_canvas') == 'show' && (typeof viwcpf_localize_args.auto_open_modal !== 'undefined') ){
            viwcpf_off_canvas_toggle('show');
        }else{
            Cookies.set('viwcpf_off_canvas', 'hide');
        }
    }
});

jQuery(window).on('load', function () {

    if (jQuery('.viwcpf-off_canvas-wrap:not(.viwcpf-off_canvas-wrap-init)').length) {
        jQuery('.viwcpf-off_canvas-wrap:not(.viwcpf-off_canvas-wrap-init)').addClass('viwcpf-off_canvas-wrap-init');
        viwcpf_off_canvas_init();

    }
});

function viwcpf_off_canvas_init() {
    jQuery(document).on('mouseenter', '.viwcpf-off_canvas-icon-wrap', function () {
        if (jQuery(this).hasClass('viwcpf-off_canvas-icon-wrap-click')) {
            jQuery(this).removeClass('viwcpf-off_canvas-icon-wrap-mouseleave').addClass('viwcpf-off_canvas-icon-wrap-mouseenter');
        } else {
            viwcpf_off_canvas_toggle('show');
        }
    }).on('mouseleave', '.viwcpf-off_canvas-icon-wrap', function () {
        if (jQuery(this).hasClass('viwcpf-off_canvas-icon-wrap-mouseenter')) {
            jQuery(this).removeClass('viwcpf-off_canvas-icon-wrap-mouseenter').addClass('viwcpf-off_canvas-icon-wrap-mouseleave');
        }
    }).on('click', '.viwcpf-off_canvas-icon-wrap', function () {
        if (jQuery(this).hasClass('viwcpf-off_canvas-icon-wrap-click')) {
            viwcpf_off_canvas_toggle('show');
            Cookies.set('viwcpf_off_canvas', 'show');
        }
    });
    jQuery(document).on('click', '.viwcpf-off_canvas-overlay, .viwcpf-off_canvas-close-wrap', function () {
        if (!jQuery(this).hasClass('viwcpf-not-hidden')) {
            viwcpf_off_canvas_toggle('hide');
            Cookies.set('viwcpf_off_canvas', 'hide');
        }
    });

}

function viwcpf_off_canvas_icon_toggle(show = false) {
    if (show) {
        jQuery('.viwcpf-off_canvas-icon-wrap').removeClass('viwcpf-disabled viwcpf-off_canvas-icon-wrap-close viwcpf-off_canvas-icon-wrap-mouseenter viwcpf-off_canvas-icon-wrap-mouseleave');
        jQuery('.viwcpf-off_canvas-icon-wrap').addClass('viwcpf-off_canvas-icon-wrap-open');
    } else {
        jQuery('.viwcpf-off_canvas-icon-wrap').addClass('viwcpf-off_canvas-icon-wrap-close');
        jQuery('.viwcpf-off_canvas-icon-wrap').removeClass('viwcpf-off_canvas-icon-wrap-open viwcpf-off_canvas-icon-wrap-mouseenter viwcpf-off_canvas-icon-wrap-mouseleave');
    }
}


function viwcpf_off_canvas_toggle(action = '', new_effect = '') {
    let wrap = jQuery('.viwcpf-off_canvas-content-wrap'),
        position = jQuery('.viwcpf-off_canvas').data('position'),
        effect = jQuery('.viwcpf-off_canvas').data('effect'),
        content_show = 'products';

    if (action === 'show' && wrap.hasClass('viwcpf-off_canvas-content-open')) {
        wrap.find('.viwcpf-off_canvas-content-wrap1, .viwcpf-off_canvas-footer').addClass('viwcpf-disabled');
        wrap.find('.viwcpf-off_canvas-content-wrap1.viwcpf-off_canvas-' + content_show + '-wrap').removeClass('viwcpf-disabled');
        wrap.find('.viwcpf-off_canvas-footer.viwcpf-off_canvas-footer-' + content_show).removeClass('viwcpf-disabled');
        return false;
    }
    if (action === 'hide' && wrap.hasClass('viwcpf-off_canvas-content-close')) {
        return false;
    }
    let type = (position === 'top_left' || position === 'bottom_left') ? 'left' : 'right';
    if (action === 'start' && new_effect) {
        if (wrap.hasClass('viwcpf-off_canvas-content-close')) {
            wrap.removeClass('viwcpf-off_canvas-content-open viwcpf-off_canvas-content-open-' + effect + '-' + type);
            wrap.addClass('viwcpf-off_canvas-content-close viwcpf-off_canvas-content-close-' + new_effect + '-' + type);
        } else {
            wrap.addClass('viwcpf-off_canvas-content-open viwcpf-off_canvas-content-open-' + new_effect + '-' + type);
            wrap.removeClass('viwcpf-off_canvas-content-close viwcpf-off_canvas-content-close-' + effect + '-' + type);
        }
        jQuery('.viwcpf-off_canvas').data('effect', new_effect);
        return false;
    }
    new_effect = new_effect ? new_effect : effect;
    let old_position = jQuery('.viwcpf-off_canvas').data('old_position') || '';
    let old_type = old_position ? ((old_position === 'top_left' || old_position === 'bottom_left') ? 'left' : 'right') : type;
    let class_open = 'viwcpf-off_canvas-content-open viwcpf-off_canvas-content-open-' + new_effect + '-' + type,
        class_close = 'viwcpf-off_canvas-content-close viwcpf-off_canvas-content-close-' + new_effect + '-' + type,
        class_open_old = 'viwcpf-off_canvas-content-open viwcpf-off_canvas-content-open-' + effect + '-' + old_type,
        class_close_old = 'viwcpf-off_canvas-content-close viwcpf-off_canvas-content-close-' + effect + '-' + old_type + ' viwcpf-off_canvas-content-close-' + effect + '-' + type;
    if (wrap.hasClass('viwcpf-off_canvas-content-close')) {
        wrap.addClass(class_open).removeClass(class_close).removeClass(class_close_old);
        // wrap.find('.viwcpf-off_canvas-content-wrap1, .viwcpf-off_canvas-footer').addClass('viwcpf-disabled');
        wrap.find('.viwcpf-off_canvas-content-wrap1.viwcpf-off_canvas-' + content_show + '-wrap').removeClass('viwcpf-disabled');
        wrap.find('.viwcpf-off_canvas-footer.viwcpf-off_canvas-footer-' + content_show).removeClass('viwcpf-disabled');
        jQuery('.viwcpf-off_canvas-icon-wrap').addClass('viwcpf-off_canvas-icon-wrap-close');
        jQuery('.viwcpf-off_canvas-icon-wrap').removeClass('viwcpf-off_canvas-icon-wrap-open viwcpf-off_canvas-icon-wrap-mouseenter viwcpf-off_canvas-icon-wrap-mouseleave');
        jQuery('.viwcpf-off_canvas-overlay').removeClass('viwcpf-disabled');
        jQuery('html').addClass('viwcpf-html-non-scroll');
    } else {
        wrap.addClass(class_close).removeClass(class_open).removeClass(class_open_old);
        jQuery('.viwcpf-off_canvas-icon-wrap').removeClass('viwcpf-off_canvas-icon-wrap-close viwcpf-off_canvas-icon-wrap-mouseenter viwcpf-off_canvas-icon-wrap-mouseleave');
        jQuery('.viwcpf-off_canvas-icon-wrap').addClass('viwcpf-off_canvas-icon-wrap-open');
        jQuery('.viwcpf-off_canvas-overlay').addClass('viwcpf-disabled');
        jQuery('html').removeClass('viwcpf-html-non-scroll');
    }
    jQuery('.viwcpf-off_canvas').data('effect', new_effect);
}


