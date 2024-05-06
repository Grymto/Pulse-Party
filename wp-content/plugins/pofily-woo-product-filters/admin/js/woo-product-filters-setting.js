/*
Author: DubleyNguyen
Author URI: http://villatheme.com
Copyright 2021 villatheme.com. All rights reserved.
*/


jQuery(document).ready(function ($) {
    "use strict";
    $('.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });

    $(document).on('change', 'input[type="radio"],input[type="checkbox"], select', function (e) {
        _check_condition_input($(this));
    }).trigger('change');
    $('.vi-ui.dropdown').dropdown();
    $('.vi-ui.dropdown.symbol').dropdown({
        onChange: function (value, text, $selectedItem) {
            let $this = $(this),
                this_symbol = value,
                wrap = $this.closest('.setting_modal_icon'),
                this_number = wrap.find('.modal_number_value').val();
            wrap.find('.modal_hidden_value').val(this_number + this_symbol);
            $(this).closest('.setting_modal_icon').find('.modal_number_value').attr('data-symbol', value);
        }
    });
    $(document).on('change', '.modal_number_value', function () {
        let $this = $(this),
            this_symbol = $this.attr('data-symbol'),
            this_value = $this.val(),
            wrap = $this.closest('.setting_modal_icon');
        wrap.find('.modal_hidden_value').val(this_value + this_symbol);
    });
    $(document).on('change', '.label_number_value', function () {
        let $this = $(this),
            this_symbol = $this.attr('data-symbol'),
            this_value = $this.val(),
            wrap = $this.closest('.setting_label_styles');
        wrap.find('.label_hidden_value').val(this_value + this_symbol);
    });
    /*
    Function check condition and update field. Use class name of field to check condition instead name on old version
    @param selector: $(this) of field
    @use : attribute add to field hide :    data-select="" -> class name of field condition
                                            data-typeshow="" -> value of field condition want display
    version:1.0.1 =))))
    */
    function _check_condition_input(selectors) {
        let $value = selectors.val(),
            $name = selectors.attr('name'),
            $selectors = selectors.attr('id'),
            $type = '';

        if (selectors.is("select")) {
            $type = 'select';
        } else {
            $type = selectors.attr('type');
        }
        let option_select = $('.option_select');

        switch ($type) {
            case 'checkbox':
                $value = selectors.is(':checked').toString();

                if ($value === 'true') {

                    option_select.each(function () {
                        let $this = $(this),
                            this_select = $this.attr('data-select'),
                            this_type_show = $this.attr('data-type_show'),
                            this_array_type_show = this_type_show.split(",");

                        if (
                            (selectors.hasClass(this_select)) &&
                            (jQuery.inArray($value, this_array_type_show) !== -1)
                        ) {
                            $this.removeClass('hidden');
                        }
                    });

                } else {
                    option_select.each(function () {
                        let $this = $(this),
                            this_select = $this.attr('data-select');
                        if (selectors.hasClass(this_select)) {
                            $this.addClass('hidden');
                        }
                    });
                }
                break;
            case 'radio':

                option_select.each(function () {
                    let $this = $(this),
                        this_select = $this.attr('data-select'),
                        this_type_show = $this.attr('data-type_show'),
                        this_array_type_show = this_type_show.split(",");
                    if (selectors.hasClass(this_select)) {

                        $this.addClass('hidden');
                    }
                    if (
                        (selectors.hasClass(this_select)) &&
                        (jQuery.inArray($value, this_array_type_show) !== -1)
                    ) {
                        $this.removeClass('hidden');
                    }
                });

                break;
            case 'select':

                option_select.each(function () {
                    let $this = $(this),
                        this_select = $this.attr('data-select'),
                        this_type_show = $this.attr('data-type_show'),
                        this_array_type_show = this_type_show.split(",");

                    if (selectors.closest('.dropdown').hasClass(this_select)) {
                        $this.addClass('hidden');
                    }
                    if (
                        selectors.closest('.dropdown').hasClass(this_select) &&
                        (jQuery.inArray($value, this_array_type_show) !== -1)
                    ) {
                        $this.removeClass('hidden');
                    }
                });
                break;
            default:
                break;

        }

    }

    /*Color Picker*/
    function _iris(selector) {
        selector.iris({
            change: function (event, ui) {
                $(this).css({'backgroundColor': ui.color.toString()});
                $(this).closest('.input').find('.color-text').val(ui.color.toString());
            },
            hide: true,
            border: true
        }).click(function () {
            $('.iris-picker').hide();
            $(this).closest('.input').find('.iris-picker').show();

        });
        $('body').click(function () {
            $('.iris-picker').hide();
        });
        //Prevent event body click from acting on the ".color-picker" button
        selector.click(function (e) {
            e.stopPropagation();
        });
        $(document).on('change', '.color-text', function () {
            let $this = $(this),
                color_val = $this.val();

            if (color_val.length >= 3) {
                $this.parent().find('.color-picker').css({'backgroundColor': color_val.toString()}).iris('color', color_val);
            }
        });
        return selector;
    }

    function _iris_alpha(selector) {
        selector.wpColorPicker({
            palettes: false,
            width: 200,
            mode: 'hsl',
            clear: function () {
                var input = $(this);
                input.val(input.data('default-color'));
                input.change();
            }
        });
    }

    _iris($('.color-picker'));
    _iris_alpha($('.color-picker-alpha'));

    /*Reset color to default*/
    $(document).on('click', '.reset_color', function () {
        let $this = $(this),
            $this_wrap_field = $this.closest('.setting_color_field'),
            $this_color_text = $this_wrap_field.find('.color-text'),
            $color_default = $this_color_text.attr('data-default-color');

        $this_color_text.val($color_default).trigger('change');
        return false;
    });

    $('#viwcpf_display_metakey').select2({
        placeholder: 'Choose meta key',
        closeOnSelect: false,
        scrollAfterSelect: false,
    });

    $('.vi-ui.accordion').vi_accordion();

    $(document).on('click', '.reset-default', function () {
        if (confirm("Important Note: By pressing OK all your settings will be reset to their default states")) {
            console.log("You pressed OK!");
        } else {
            console.log("You pressed Cancel!");
            return false;
        }
    });

});
//load data by Filter For
jQuery(window).load(function () {
    setTimeout(function () {
        // let option_select = jQuery('.option_select');
        // option_select.each(function () {
        //     let $this = jQuery(this),
        //         this_select = $this.attr('data-select');
        //     console.log(this_select)
        //     jQuery('input.' + this_select + ' , select.' + this_select).trigger('change');
        // });

        jQuery('.viwcpf_modal').trigger('change');
        jQuery('input[name="viwcpf_setting[modal][style]"]:checked').trigger('change');
        // console.log(jQuery('input[name="viwcpf_setting[modal][style]"]:checked').val())
        jQuery('.off_canvas_position select').trigger('change');
        jQuery('.show_active_labels').trigger('change');
        jQuery('.color-text').trigger('change');
    }, 100);
});
