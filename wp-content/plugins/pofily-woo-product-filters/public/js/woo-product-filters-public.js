/*
Author: DubleyNguyen
Author URI: http://villatheme.com
Copyright 2021 villatheme.com. All rights reserved.
*/
jQuery(document).ready(function ($) {
    "use strict";
    /* global  jQuery, viwcpf_localize_args, accounting */

    if( $('body.archive').hasClass('post-type-archive-product') ){
        $('body.archive').append(`<div class="viwcpf_filter_loading"><div class="viwcpf_filter_loading_effect"></div></div>`);
    }

    _submit_filter_with_btn();
    _viwcpf_tooltip($('.viwcpf_filter-items'));
    _displayToggle($('.viwcpf_filter-title.collapsable'));

    /*Redirect when click on input checkbox filter */
    $('.viwcpf_filter-items .viwcpf_filter-item.viwcpf_checkbox .viwcpf_checkbox_checkmark').on('click', function (e) {
        let $this = $(this),
            $thisClosest = $this.closest('.viwcpf_filter-item.viwcpf_checkbox'),
            $thisWrap = $this.closest('.viwcpf_wrap_checkbox'),
            alinkFilter = $thisClosest.find('a.viwcpf_link_checkbox').attr('href'),
            aFilter = $thisClosest.find('a.viwcpf_link_checkbox'),
            formFilter = $thisClosest.closest('.viwcpf_form_filter');

        if (formFilter.hasClass('has-submit-btn')) {
            aFilter.click();
            e.preventDefault();

        } else {
            _viwcpf_effect_loading('show');

            window.location.replace(alinkFilter);
        }

    });

    $('.viwcpf_wrap_filter-content').each(function () {
        let $this = $(this),
            $thisDropdown = $this.find('.viwcpf_wrap_dropdown'),
            $thisRangeSlide = $this.find('.viwcpf_filter.viwcpf_range_slider'),
            $thisSearchField = $this.find('.viwcpf_filter.viwcpf_search_field');

        if ($thisDropdown.length > 0) {
            _viwcpf_dropdown($thisDropdown);
        }
        if ($thisRangeSlide.length > 0) {
            _filterRangeSlider($thisRangeSlide);
        }
        if ($thisSearchField.length > 0) {
            _filterTextSearch($thisSearchField);
        }
        _displayViewmore($this.find('.viwcpf_filter-items.has_view_more'));
    });


    $(document).on('click', '.viwcpf-trigger-off_canvas-open , .viwcpf-off_canvas-icon', function () {
        if( (typeof viwcpf_localize_args.modal_style !== 'undefined') ){
            let sidebar_top = $('#viwcpf-side-filters-top'),
                is_visible = sidebar_top.is(':visible');
            if( viwcpf_localize_args.modal_style == 'top_product_loop'){
                sidebar_top.slideToggle(200, function () {
                    is_visible ? sidebar_top.removeClass('fade-in') : sidebar_top.addClass('fade-in');
                });
            }else{
                viwcpf_off_canvas_toggle('show');
                Cookies.set('viwcpf_off_canvas', 'show');
            }
        }
        return false;
    });
    // $(document).on('click', '.viwcpf-modal-close-wrap .viwcpf-icon-filter-close', function () {
    //     if( (typeof viwcpf_localize_args.modal_style !== 'undefined') ){
    //         let sidebar_top = $('#viwcpf-side-filters-top'),
    //             is_visible = sidebar_top.is(':visible');
    //         if( viwcpf_localize_args.modal_style == 'top_product_loop'){
    //             sidebar_top.slideToggle(200, function () {
    //                 is_visible ? sidebar_top.removeClass('fade-in') : sidebar_top.addClass('fade-in');
    //             });
    //         }
    //     }
    //     return false;
    // });


    function _submit_filter_with_btn() {
        $('.viwcpf_form_filter').each(function () {
            let $thisForm = $(this);
            if ($thisForm.hasClass('has-submit-btn')) {
                $thisForm.find('.viwcpf_wrap_filter-content').each(function () {
                    let $this = $(this);
                    $(this).find('.input_filter_hidden').each(function () {
                        let th = $(this),
                            th_value = th.val();
                        if (th_value != '') {
                            if (th.hasClass('query_type')) {
                                let th_query_type_closest = th.closest('.viwcpf_wrap_filter-content'),
                                    th_query_type_tax = th_query_type_closest.find('.filter_by_taxonomy'),
                                    th_query_type_tax_val = th_query_type_tax.val();
                                if (th_query_type_tax_val !== '') {
                                    th.prop('disabled', false);
                                } else {
                                    th.prop('disabled', true);
                                }
                            } else if (th.hasClass('hidden_meta_type')) {
                            } else {
                                th.prop('disabled', false);
                            }
                        } else {
                            th.prop('disabled', true);
                        }
                    });
                });

                $thisForm.find('.input_filter_hidden').on('change', function () {
                    let th = $(this),
                        $this_closest = th.closest('.viwcpf_wrap_filter-content'),
                        hiden_query_type = $this_closest.find('.input_filter_hidden.query_type'),
                        hiden_meta_type = $this_closest.find('.input_filter_hidden.hidden_meta_type'),
                        th_value = th.val();

                    if (th_value !== '') {

                        hiden_query_type.prop('disabled', false);
                        hiden_meta_type.prop('disabled', false);
                        th.prop('disabled', false);

                    } else {

                        hiden_query_type.prop('disabled', true);
                        hiden_meta_type.prop('disabled', true);
                        th.prop('disabled', true);

                    }
                });

                $thisForm.find('.viwcpf_filter-items a').on('click', function () {
                    let th = $(this),
                        $this_checkbox = th.closest('.viwcpf_filter-item.viwcpf_checkbox').find('input.viwcpf_checkbox'),
                        $this_closest = th.closest('.viwcpf_wrap_filter-content'),
                        $this_filter_multiple = $this_closest.data('filter_multiple'),
                        $this_filter_by = $this_closest.data('filter_by'),
                        $this_filter_type = $this_closest.data('filter_type'),
                        $this_filter_relation = $this_closest.data('filter_relation'),
                        th_value = th.data('value'),
                        input_hidden = $this_closest.find('.input_filter_hidden.' + $this_filter_by),
                        input_hidden_val = input_hidden.val(),
                        new_arr_val = [];

                    if ($this_filter_multiple === 'yes') {
                        th.toggleClass('viwcpf_chosen');
                    } else {
                        $this_closest.find('.viwcpf_filter-items a').not(this).removeClass('viwcpf_chosen');
                        th.toggleClass('viwcpf_chosen');
                    }

                    switch ($this_filter_by) {
                        case 'filter_by_taxonomy':
                            $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                let viwcpf_chosen = $(this),
                                    chosen_val = viwcpf_chosen.data('value');
                                new_arr_val.push(chosen_val);
                            });
                            if (
                                ($this_filter_type === 'product_cat') ||
                                ($this_filter_type === 'product_tag')
                            ) {
                                if ($this_filter_relation === 'AND') {
                                    input_hidden.val(new_arr_val.join("+")).trigger('change');
                                } else {
                                    input_hidden.val(new_arr_val.join(",")).trigger('change');
                                }
                            } else {
                                input_hidden.val(new_arr_val.join(",")).trigger('change');
                            }

                            break;
                        case 'filter_by_price':
                            let chosen_val = '';
                            $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                let viwcpf_chosen = $(this);
                                chosen_val = viwcpf_chosen.data('value');
                            });
                            new_arr_val = chosen_val.split("-");
                            $this_closest.find('.input_filter_hidden.min_price').val(new_arr_val[0]).trigger('change');
                            $this_closest.find('.input_filter_hidden.max_price').val(new_arr_val[1] == viwcpf_localize_args.php_int_max ? '' : new_arr_val[1]).trigger('change');
                            break;
                        case 'filter_by_review':
                            $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                let viwcpf_chosen = $(this),
                                    chosen_val = viwcpf_chosen.data('value');
                                new_arr_val.push(chosen_val);
                            });
                            input_hidden.val(new_arr_val.join(",")).trigger('change');
                            break;
                        case 'filter_by_sale_or_stock':
                            let arr_chosen = [];
                            $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                let viwcpf_chosen = $(this),
                                    chosen_val = viwcpf_chosen.data('value');
                                new_arr_val[chosen_val] = '1';
                            });
                            $this_closest.find('.input_filter_hidden').val('').trigger('change');
                            for (let item_chosen in new_arr_val) {
                                $this_closest.find('.input_filter_hidden.' + item_chosen).val(new_arr_val[item_chosen]).trigger('change');
                            }
                            break;
                        case 'filter_by_metabox':
                            if ($this_closest.find('.input_filter_hidden.hidden_meta_type').val() === 'string') {
                                $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                    let viwcpf_chosen = $(this),
                                        chosen_val = viwcpf_chosen.data('value');
                                    new_arr_val.push(chosen_val);
                                });
                                input_hidden.val(new_arr_val.join(",")).trigger('change');
                            } else if ($this_closest.find('.input_filter_hidden.hidden_meta_type').val() === 'numberic') {
                                let chosen_val = '';
                                $this_closest.find('.viwcpf_filter-items .viwcpf_chosen').each(function () {
                                    let viwcpf_chosen = $(this);
                                    chosen_val = viwcpf_chosen.data('value');
                                });
                                input_hidden.val(chosen_val).trigger('change');

                            }
                            break;
                        default:
                            break;
                    }

                    if (th.hasClass('viwcpf_chosen')) {
                        $this_checkbox.prop('checked', true).trigger('change');
                    } else {
                        $this_checkbox.prop('checked', false).trigger('change');
                    }

                    return false;
                });

                $thisForm.find('.viwcpf-apply-filters').on('click',function () {
                    _viwcpf_effect_loading('show');
                });
            } else {
                $thisForm.find('.viwcpf_filter-items a').on('click', function () {
                    _viwcpf_effect_loading('show');
                });
                $thisForm.submit(function () {
                    return false;
                });
            }
        });
        $('a.active_filter_label, a.viwcpf_clear_block_filter_btn').on('click', function () {
            _viwcpf_effect_loading('show');
        });
    }

    function _viwcpf_tooltip($filter) {
        let position = '';
        $filter.find('[data-tooltip]').each(function () {
            const t = $(this);
            if (t.hasClass('viwcpf_tooltip-added') || !t.data('tooltip')) {
                return;
            }

            t.on('mouseenter', function () {
                let th = $(this),
                    tooltip = null,
                    wrapperWidth = th.outerWidth(),
                    left = 0,
                    width = 0;

                const container = th.closest('.viwcpf_filter-item');
                position = container.is('.viwcpf_label, .viwcpf_images, .viwcpf_color_swatches') ? 'top' : 'right';

                tooltip = $('<span>', {
                    class: 'viwcpf-tooltip',
                    html: th.data('tooltip'),
                });
                th.append(tooltip);
                width = tooltip.outerWidth() + 6;
                tooltip.outerWidth(width);

                if ('top' === position) {
                    left = (wrapperWidth - width) / 2;
                } else {
                    left = wrapperWidth + 15;
                }
                tooltip.css({
                    left: left.toFixed(0) + 'px'
                }).fadeIn(200);
                th.addClass('with-tooltip');
            }).on('mouseleave', function () {
                const th = $(this);
                th.find('.viwcpf-tooltip').fadeOut(200, function () {
                    th.removeClass('with-tooltip').find('.viwcpf-tooltip').remove();
                });
            });
            t.addClass('viwcpf_tooltip-added');
        });
    }

    function _viwcpf_dropdown(selector) {

        let show_search = selector.find('select').data('show_search'),
            multiple = selector.find('select').data('filter_multiple'),
            arr_val = [],
            is_show_search_field,
            wrap_selector = selector.closest('.viwcpf_filter-wrap-items');

        if( show_search ){
            is_show_search_field = true;
        }else{
            is_show_search_field = false;
        }

        selector.vi_dropdown({
            searchable: is_show_search_field,
            extendProps:['filter_url','count'],
            choice: function (e) {

                let current_choice = this.$select.val();
                let choice_target = $(e.target);
                if(selector.closest('.viwcpf_form_filter').hasClass('has-submit-btn')) {
                    arr_val = [];
                    let ObjVal;
                    if( Array.isArray(current_choice) ){
                        ObjVal = current_choice;
                    }else{
                        ObjVal = [current_choice];
                    }

                    for (let item of ObjVal) {
                        arr_val.push(item);
                    }
                    _update_hide_input_dropdown(selector, arr_val);

                } else {
                    _viwcpf_effect_loading('show');

                    if (current_choice.length === 0) {
                        // let linkFilter = this.$select.find('option:selected').data('filter_url');
                        let paramsUrl = new URLSearchParams(window.location.search),
                            urlOrigin = window.location.origin,
                            urlPathName = window.location.pathname,
                            name_param = wrap_selector.find('input.viwcpf_filter_value').attr('name'),
                            query_type = wrap_selector.find('input.query_type').attr('name');

                        paramsUrl.delete(name_param);
                        paramsUrl.delete(query_type);

                        let arrParamUrl = [];
                        for (const [key, value] of paramsUrl) {
                            arrParamUrl[key] = value;
                        }
                        let objectParamUrl = Object.assign({}, arrParamUrl); //Convert array to object
                        let convertParamUrl = jQuery.param(objectParamUrl);

                        if(convertParamUrl !== ''){
                            window.location.replace(urlOrigin + urlPathName + '?' + convertParamUrl);

                        }else{
                            window.location.replace(urlOrigin + urlPathName);
                        }
                    }else{
                        if(choice_target.hasClass('del')){
                            let paramsUrl = new URLSearchParams(window.location.search),
                                urlOrigin = window.location.origin,
                                urlPathName = window.location.pathname,
                                name_param = wrap_selector.find('input.viwcpf_filter_value').attr('name'),
                                query_type = wrap_selector.find('input.query_type').attr('name');

                            paramsUrl.delete(name_param);
                            paramsUrl.delete(query_type);

                            let arrParamUrl = [];
                            for (const [key, value] of paramsUrl) {
                                arrParamUrl[key] = value;
                            }
                            let objectParamUrl = Object.assign({}, arrParamUrl); //Convert array to object
                            let convertParamUrl = jQuery.param(objectParamUrl);

                            if(convertParamUrl !== ''){
                                window.location.replace(urlOrigin + urlPathName + '?' + convertParamUrl);

                            }else{
                                window.location.replace(urlOrigin + urlPathName);
                            }
                        }else{

                            let linkFilter = choice_target.data('filter_url');
                            window.location.replace(linkFilter);
                        }

                    }
                }
            }
        });


        return selector;
    }

    function _update_hide_input_dropdown(selector, arr_val) {
        let th = selector,
            $this_closest = th.closest('.viwcpf_wrap_filter-content'),
            $this_filter_multiple = th.data('filter_multiple'),
            $this_filter_by = $this_closest.data('filter_by'),
            $this_filter_type = $this_closest.data('filter_type'),
            $this_filter_relation = $this_closest.data('filter_relation'),
            input_hidden = $this_closest.find('.input_filter_hidden.' + $this_filter_by),
            input_hidden_val = input_hidden.val(),
            new_arr_val = [];
        console.log(arr_val);
        switch ($this_filter_by) {
            case 'filter_by_taxonomy':

                if (
                    ($this_filter_type === 'product_cat') ||
                    ($this_filter_type === 'product_tag')
                ) {
                    if ($this_filter_relation === 'AND') {
                        input_hidden.val(arr_val.join("+")).trigger('change');
                    } else {
                        input_hidden.val(arr_val.join(",")).trigger('change');
                    }
                } else {
                    input_hidden.val(arr_val.join(",")).trigger('change');
                }

                break;
            case 'filter_by_price':

                new_arr_val = arr_val[0].split("-");
                $this_closest.find('.input_filter_hidden.min_price').val(new_arr_val[0]).trigger('change');
                $this_closest.find('.input_filter_hidden.max_price').val(new_arr_val[1] == viwcpf_localize_args.php_int_max ? '' : new_arr_val[1]).trigger('change');
                break;
            case 'filter_by_review':

                input_hidden.val(arr_val.join(",")).trigger('change');
                break;

            case 'filter_by_metabox':
                if ($this_closest.find('.input_filter_hidden.hidden_meta_type').val() === 'string') {

                    input_hidden.val(arr_val.join(",")).trigger('change');
                } else if ($this_closest.find('.input_filter_hidden.hidden_meta_type').val() === 'numberic') {

                    input_hidden.val(arr_val[0]).trigger('change');

                }
                break;
            default:
                break;
        }
    }

    function _dropdown_check_multiple(multiple) {
        return multiple === 'yes';
    }

    function _dropdown_placeholder(multiple) {
        if (multiple === 'yes') {
            return 'Choose / Search...';
        } else {
            return {
                id: '-1', // the value of the option
                text: 'All'
            };
        }
    }

    function _filterRangeSlider(selector) {
        if (!selector.hasClass('viwcpf_range_slider')) {
            return;
        }

        let $container = selector,
            $wrapContainer = selector.closest('.viwcpf_wrap_filter-content'),
            filter_type = String($wrapContainer.data('filter_type')),
            $minInput = selector.find('.range-slider-min'),
            $maxInput = selector.find('.range-slider-max'),
            $minHiddenInput = $wrapContainer.find('input[name=min_price]'),
            $maxHiddenInput = $wrapContainer.find('input[name=max_price]'),
            $metaHiddenInput = $wrapContainer.find('.hidden_meta_value'),
            min = parseFloat($container.data('min')),
            max = parseFloat($container.data('max')),
            currentMin = parseFloat($minInput.val()),
            currentMax = parseFloat($maxInput.val()),
            step = parseFloat($container.data('step')),
            filter_for = String($container.data('filter_for')),
            number_symbol = '';

        if (filter_for === 'filter_by_price') {
            number_symbol = String('price');
        }

        selector.find('.viwcpf-range-slider-ui').ionRangeSlider({
            skin: 'round',
            type: 'double',
            min,
            max,
            step,
            from: currentMin,
            to: currentMax,
            min_interval: step,
            values_separator: ' - ',
            prettify: (v) => __viwcpfFormatNumber(v, number_symbol),
            onChange: (data) => {
                $minInput.val(data.from);
                $maxInput.val(data.to);
            },
            onFinish: (data) => {
                if ($container.closest('.viwcpf_form_filter').hasClass('has-submit-btn')) {
                    if (
                        (data.from === min) &&
                        (data.to === max)
                    ) {

                        if (filter_for === 'filter_by_price') {
                            $minHiddenInput.val('').trigger('change');
                            $maxHiddenInput.val('').trigger('change');
                        } else {
                            $metaHiddenInput.val('').trigger('change');

                        }
                    } else {
                        if (filter_for === 'filter_by_price') {
                            $minHiddenInput.val(data.from).trigger('change');
                            $maxHiddenInput.val(data.to).trigger('change');
                        } else {
                            $metaHiddenInput.val(data.from + '-' + data.to).trigger('change');
                        }
                    }

                } else {
                    _viwcpf_effect_loading('show');

                    if ($(this).sliderTimeout) {
                        clearTimeout($(this).sliderTimeout);
                    }

                    $(this).sliderTimeout = setTimeout(function () {
                        if (
                            (data.from === min) &&
                            (data.to === max)
                        ) {
                            let paramsUrl = new URLSearchParams(window.location.search),
                                urlOrigin = window.location.origin,
                                urlPathName = window.location.pathname;
                            if (filter_for === 'filter_by_price') {
                                paramsUrl.delete("min_price");
                                paramsUrl.delete("max_price");
                            } else {
                                let arr_filter_type = filter_type.split(",");

                                for (let paramUrlDel of arr_filter_type) {
                                    paramsUrl.delete(paramUrlDel);
                                }
                            }

                            let arrParamUrl = [];
                            for (const [key, value] of paramsUrl) {
                                arrParamUrl[key] = value;
                            }
                            let objectParamUrl = Object.assign({}, arrParamUrl); //Convert array to object
                            let convertParamUrl = jQuery.param(objectParamUrl);

                            window.location.replace(urlOrigin + urlPathName + '?' + convertParamUrl);
                        } else {
                            let paramsUrl = new URLSearchParams(window.location.search),
                                urlOrigin = window.location.origin,
                                urlPathName = window.location.pathname;
                            if (filter_for === 'filter_by_price') {
                                paramsUrl.set("min_price", data.from);
                                paramsUrl.set("max_price", data.to);
                            } else {
                                let arr_filter_type = filter_type.split(",");

                                paramsUrl.set(arr_filter_type[0], data.from + '-' + data.to);
                                paramsUrl.set(arr_filter_type[1], 'numberic');
                            }

                            let arrParamUrl = [];
                            for (const [key, value] of paramsUrl) {
                                arrParamUrl[key] = value;
                            }
                            let objectParamUrl = Object.assign({}, arrParamUrl); //Convert array to object
                            let convertParamUrl = jQuery.param(objectParamUrl);

                            window.location.replace(urlOrigin + urlPathName + '?' + convertParamUrl);
                        }

                    }, 200);
                }

            },
        });
    }

    function _filterTextSearch(selector) {
        if (!selector.hasClass('viwcpf_search_field')) {
            return;
        }
        let $container = selector,
            $wrapContainer = selector.closest('.viwcpf_wrap_filter-content'),
            filter_type = String($wrapContainer.data('filter_type')),
            $searchField = selector.find('input.viwcpf_text_search'),
            $searchFieldValue = $searchField.val(),
            $resetField = selector.find('.viwcpf_search_reset'),
            filter_for = String($container.data('filter_for'));

        if ($searchFieldValue != '') {
            $resetField.show();
        }

        $($searchField).on('change', function (e) {
            let $searchVal = $(e.target).val(),
                paramsUrl = new URLSearchParams(window.location.search),
                urlOrigin = window.location.origin,
                urlPathName = window.location.pathname;
            if ($searchVal !== '') {
                paramsUrl.set("s", $searchVal);
                $resetField.show();
            } else {
                paramsUrl.delete("s");
                $resetField.hide();
            }

            if ($container.closest('.viwcpf_form_filter').hasClass('has-submit-btn')) {
                $('.input_filter_hidden.filter_by_name_product').val($searchVal).trigger('change');
            } else {
                _viwcpf_effect_loading('show');

                let arrParamUrl = [];
                for (const [key, value] of paramsUrl) {
                    arrParamUrl[key] = value;
                }
                let objectParamUrl = Object.assign({}, arrParamUrl); //Convert array to object
                let convertParamUrl = jQuery.param(objectParamUrl);

                window.location.replace(urlOrigin + urlPathName + '?' + convertParamUrl);
            }


        });
        $resetField.click(function (e) {
            let $thisWrap = $(e.target).closest('.viwcpf_filter'),
                $thisField = $thisWrap.find('.viwcpf_text_search');
            $thisField.val('').trigger('change');
            return false;
        });
    }

    /*
    * var number - (int)numberic (Maybe is Price or Number)
    * var num_symbol - price
    */
    function __viwcpfFormatNumber(num, number_symbol) {
        if ('undefined' !== typeof accounting) {
            if (number_symbol === 'price') {
                num = accounting.formatMoney(num, {
                    symbol: viwcpf_localize_args.currency_format.symbol,
                    decimal: viwcpf_localize_args.currency_format.decimal,
                    thousand: viwcpf_localize_args.currency_format.thousand,
                    precision: 0,
                    format: viwcpf_localize_args.currency_format.format,
                });
            } else {
                num = accounting.formatMoney(num, {
                    symbol: '',
                    decimal: viwcpf_localize_args.currency_format.decimal,
                    thousand: viwcpf_localize_args.currency_format.thousand,
                    precision: 0,
                    format: viwcpf_localize_args.currency_format.format,
                });
            }
        }

        return num;
    }

    function _displayToggle(selector) {
        selector.off("click").on("click", function (event) {
            event.stopPropagation();
            let t = $(this);
            let wrap_items = t.closest('.viwcpf_wrap_filter-content').find('.viwcpf_filter-wrap-items');
            if (t.hasClass("closed")) {
                wrap_items.hide();
            }
            t.toggleClass("opened").toggleClass("closed");
            wrap_items.slideToggle().toggleClass('closed');
        });
    }

    function _displayViewmore(selector) {
        let wrap = selector.closest('.viwcpf_wrap_filter-content'),
            number_limit = selector.data('view_more'),
            size_li = selector.find('li.viwcpf_filter-item').length;
        selector.find('li.viwcpf_filter-item:lt(' + number_limit + ')').show();

        if (size_li <= number_limit) {
            wrap.find('.viwcpf_view_more_btn').hide();
        }
        $('.viwcpf_view_more_btn').click(function () {
            let wrap = $(this).closest('.viwcpf_wrap_filter-content');
            wrap.find('li.viwcpf_filter-item').show();
            $(this).hide();
            return false;
        });
    }

    /*Function delete duplicate elements in array */
    function _viwcpf_unique_arr(arr) {
        let newArr = [];
        if( Array.isArray(arr) ){
            for (let i = 0; i < arr.length; i++) {
                if (newArr.indexOf(arr[i]) === -1) {
                    newArr.push(arr[i])
                }
            }
        }else{
            newArr = arr;
        }

        return newArr
    }

    /*Function show/hide filter loading*/
    function _viwcpf_effect_loading( type ='' ) {
        if (type.length <= 0 ){ return '';}
        if( type.toString() === 'show' ){
            $('.viwcpf_filter_loading').addClass('loading');
        }else if( type.toString() === 'hide'){
            $('.viwcpf_filter_loading').removeClass('loading');
        }
    }
});
