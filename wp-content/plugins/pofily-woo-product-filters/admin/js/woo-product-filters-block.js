/*
Author: DubleyNguyen
Author URI: http://villatheme.com
Copyright 2021 villatheme.com. All rights reserved.
*/


jQuery(document).ready(function ($) {
    "use strict";
    /*
    global  viwcpf_i18n
    */
    /*
    global  viwcpf_ajax
    */
    /*
    global  viwcpf_default_color
    */
    const terms_list_table = $('#terms_list').DataTable({
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ]
    });



    /* Trigger submit form Detail Filter Block*/
    $(document).on('submit', 'form#post', function () {
        // terms_list_table.page.len(-1).draw();

        let $block_filter_display_type = ($('#viwcpf_display-type').val() !== '') ? $('#viwcpf_display-type').val() : 'vertical',
            $block_filter_name = ($('#filter_block_name').val() != '') ? $('#filter_block_name').val() : '',
            $show_clear_button = ($('#viwcpf-show_clear').prop('checked')) ? 1 : 0,
            $show_view_more = ($('#viwcpf-show_view_more').prop('checked')) ? 1 : 0,
            $show_as_toggle = ($('#viwcpf-show_as_toggle').prop('checked')) ? 1 : 0,
            $toggle_style = 'toggle_style-opened',
            $view_more_limit = '10',
            $filter_data = {},
            $viwcpf_filter_block = {},
            $viwcpf_filter_for = ($('#viwcpf_filter_for').val() !== '') ? $('#viwcpf_filter_for').val() : '',
            $post_ID = $('#post_ID').val(),
            $_viwcpf_filter_block_nonce = $('#_viwcpf_filter_block_nonce').val();
        if ($show_as_toggle) {
            $toggle_style = ($('#viwcpf_toggle_style').val() != '') ? $('#viwcpf_toggle_style').val() : 'toggle_style-opened';
        }
        if ($show_view_more) {
            $view_more_limit = ($('#viwcpf-label_limit').val() != '') ? $('#viwcpf-label_limit').val() : '10';
        }

        switch ($viwcpf_filter_for) {
            case 'filter_by_taxonomy':
                $filter_data = __viwcpf_get_data_filter($viwcpf_filter_for);
                $viwcpf_filter_block = {
                    'name': $block_filter_name,
                    'filter_for': $viwcpf_filter_for,
                    'filter_data': $filter_data,
                    'settings':
                        {
                            'display_type': $block_filter_display_type,
                            'show_clear': $show_clear_button,
                            'show_as_toggle': $show_as_toggle,
                            'toggle_style': $toggle_style,
                            'show_view_more': $show_view_more,
                            'view_more_limit': $view_more_limit
                        }
                };
                let data_send = {
                    action: 'viwcpf_ajax_update_filterBlock',
                    post_ID: $post_ID,
                    _viwcpf_filter_block_nonce: $_viwcpf_filter_block_nonce,
                    viwcpf_filter_block: $viwcpf_filter_block
                };
                $.ajax({
                    url: viwcpf_ajax.ajax,
                    type: 'post',
                    data: data_send,
                    beforeSend: function () {
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            return true;
                        }
                    },
                    complete: function () {
                        return true;
                    },

                });
                break;
            case 'filter_by_price':
                let check_data_price = __validate_data_detail_block( $viwcpf_filter_for );
                if( !check_data_price ){
                    alert('all min and max price fields are required');
                    $('.spinner').removeClass('is-active');
                    $('#publish').removeClass('disabled');
                    return false;
                }
                break;
            case 'filter_by_metabox':
                let check_data_meta_number = __validate_data_detail_block( $viwcpf_filter_for );
                if( !check_data_meta_number ){
                    alert('all range min and max fields are required');
                    $('.spinner').removeClass('is-active');
                    $('#publish').removeClass('disabled');
                    return false;
                }
                break;
        }

    });

    function __validate_data_detail_block( $viwcpf_filter_for ) {
        let result = true;
        switch ($viwcpf_filter_for) {
            case 'filter_by_price':
                let last_range_limitless = $('#viwcpf_price_last_range_limitless').is(':checked');
                $('#range_price_list tbody').find('tr').each(function () {
                    let $this = $(this),
                        $minVal = $this.find('.min_input input').val(),
                        $maxVal = $this.find('.max_input input').val();

                    if( last_range_limitless ){
                        if (!$this.is(':last-child')) {
                            if( ($minVal === '') || ($maxVal === '') ){
                                result = false
                            }
                        } else {
                            if( ($minVal === '')  ){
                                result = false
                            }
                        }
                    }else{
                        if( ($minVal === '') || ($maxVal === '') ){
                            result = false
                        }
                    }

                });
                break;
           default:
                result = true;
            break

        }

        return result;
    }

    function __viwcpf_get_data_filter(filter_for) {
        let result;
        if (filter_for === '') {
            return result = {};
        }

        let $customize_terms_data = {},
            $tax_name = ($('#viwcpf_filter_tax').val() !== '') ? $('#viwcpf_filter_tax').val() : 'product_cat',
            $list_terms = ($('#viwcpf_input_search_term').val() !== '') ? $('#viwcpf_input_search_term').val() : [],
            $tax_type_show = ($('#tax-type_show').val() !== '') ? $('#tax-type_show').val() : 'button',
            $multi_relation = ($('input[name=tax-multi_relation]:checked').val() !== '') ? $('input[name=tax-multi_relation]:checked').val() : 'AND',
            $order_by = ($('#tax-order_by').val() !== '') ? $('#tax-order_by').val() : 'name',
            $order_type = ($('#tax-order_type').val() !== '') ? $('#tax-order_type').val() : 'asc',
            $multiselect = ($('#tax-multi_select').prop('checked')) ? 1 : 0,
            $tax_show_count_item = ($('#tax-show_count_items').prop('checked')) ? 1 : 0,
            $btn_style = '',
            $tax_color_separator = '1',
            $tax_show_search_field = 'false';
        if ($tax_type_show === 'select') {
            $tax_show_search_field = $('#tax-show_search_field').prop('checked') ? 1 : 0;
        }
        if (
            ($tax_type_show === 'color_swatches')
        ) {
            let $btn_width = ($('#tax-btn_width').val() !== '') ? $('#tax-btn_width').val() : '22';
            let $btn_height = ($('#tax-btn_height').val() !== '') ? $('#tax-btn_height').val() : '22';
            let $btn_border_radius = ($('#tax-btn_border_radius').val() !== '') ? $('#tax-btn_border_radius').val() : '50%';

            $btn_style = {
                'btn_width': $btn_width,
                'btn_height': $btn_height,
                'btn_border_radius': $btn_border_radius,
                'btn_color_separator': $tax_color_separator,
            };

        }
        if (
            (Array.isArray($list_terms)) &&
            ($list_terms.length > 0)
        ) {

            for (let $term_item_id of $list_terms) {
                let $term_old_label = (terms_list_table.$('#viwcpf_term' + $term_item_id + '_old_label').val() !== '') ? terms_list_table.$('#viwcpf_term' + $term_item_id + '_old_label').val() : '',
                    $term_new_label = (terms_list_table.$('#viwcpf_term' + $term_item_id + '_new_label').val() !== '') ? terms_list_table.$('#viwcpf_term' + $term_item_id + '_new_label').val() : '',
                    $term_tooltip = (terms_list_table.$('#viwcpf_term' + $term_item_id + '_tooltip').val()) !== '' ? terms_list_table.$('#viwcpf_term' + $term_item_id + '_tooltip').val() : '';

                $customize_terms_data[$term_item_id] = {
                    'old_label': $term_old_label,
                    'new_label': $term_new_label,
                    'tooltip': $term_tooltip,
                }; //save data of term
                if (
                    ($tax_type_show === 'color_swatches')
                ) {
                    if ($tax_type_show === 'color_swatches') {
                        let $list_color_term = terms_list_table.$('input[name="viwcpf_term_color[' + $term_item_id + '][]"]').map(function () {
                            return $(this).val();
                        }).get();
                        /*remove value "", 0, NaN, null, undefined, and false in array*/
                        $customize_terms_data[$term_item_id]["color"] = $list_color_term.filter(Boolean);
                    }
                }
            }
        }
        result = {
            'tax_name': $tax_name,
            'list_terms': $list_terms,
            'customize_value': $customize_terms_data,
            'btn_style': $btn_style,
            'type_show': $tax_type_show,
            'multiselect': $multiselect,
            'multi_relation': $multi_relation,
            'order_by': $order_by,
            'order_type': $order_type,
            'show_count_item': $tax_show_count_item,
            'show_search_field': $tax_show_search_field,
        };


        return result;
    }

    $('.vi-ui.dropdown select.viwcpf_filter_block_select').dropdown();
    $('.vi-ui.dropdown').dropdown();
    $('.vi-ui.checkbox').unbind().checkbox();

    //Choose Filter for
    $(document).on('change', '#viwcpf_filter_for', function () {
        let $this = $(this),
            this_value = $this.val(),
            filter_by_item = $('.filter_by_item');

        filter_by_item.removeClass('active');
        filter_by_item.each(function () {
            let data_type = $(this).attr('data-type');
            if (this_value === data_type) {
                $(this).addClass('active');
            }
        });
        switch (this_value) {
            case 'filter_by_price':
                $('#price-type_filter').trigger('change');
                break;
            case 'filter_by_metabox':
                $('#viwcpf_meta_numberic-show_type').trigger('change');
                break;
        }
    });
    $(document).on('change', '#viwcpf_filter_tax', function () {
        $('.viwcpf_input_search_term').find('option').remove().trigger('change');
    });
    //Search term
    $('#viwcpf_input_search_term').select2({
        // theme: "classic",
        minimumInputLength: 2,
        placeholder: viwcpf_i18n.i18n_term_name,
        closeOnSelect: false,
        ajax: {
            type: 'post',
            url: viwcpf_ajax.ajax,
            data: function (params) {
                let query = {
                    keysearch: params.term,
                    tax_search: $('#viwcpf_filter_tax').val(),
                    type: 'public',
                    action: 'viwcpf_search_term'
                };
                return query;
            },
            processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'

                return {
                    results: data
                };

                let newOption = new Option(data.text, data.id, false, false);

                $('#viwcpf_input_search_term').append(newOption);
            }
        }
    });


    //Add all term
    $(document).on('click', '#tax-select_all', function () {
        let viwcpf_filter = $('#viwcpf_filter_tax'),
            viwcpf_search_term = $('#viwcpf_input_search_term'),
            json_term = JSON.parse(viwcpf_filter.attr('data-counts')),
            choose_term = viwcpf_filter.val(),
            $this = $(this);
        //Get total term by taxonomy
        let term_count = json_term[choose_term]['total'];
        //Show alert
        if (confirm('Are you sure you want to add ' + term_count + ' terms ?')) {
            let old_data_viwcpf_search_term = viwcpf_search_term.select2("data");

            /*
            * Ajax get all term by Taxonomy
            * Param keysearch = -1 - Get all term
            * Param tax_seacrh = get value field #viwcpf_filter_tax
            * Return Object [ { id:id_term, text:name_term, ... } ]
            * */
            $this.addClass('loading');
            $('#loading_table_term').addClass('active');
            viwcpf_search_term.val(null).html("").trigger('change');
            $.ajax({
                url: viwcpf_ajax.ajax,
                method: "POST",
                data: {
                    keysearch: -1,
                    tax_search: choose_term,
                    action: 'viwcpf_search_term'
                },
                dataType: "json"
            }).done(function (respon) {
                //remove all option of select

                //add all option from object to select
                respon.forEach(function (item, index) {
                    let addNewOption = new Option(item.text, item.id, false, false);
                    viwcpf_search_term.append(addNewOption);
                });
                //Select all option
                viwcpf_search_term.find('option').prop('selected', true).trigger('change');
                let taxonomyList = [],
                    ObjNewDataTerm = viwcpf_search_term.select2("data");
                /*Take the terms that have not been added, keep the existing ones */
                let ObjNewDataTermNotAdd = ObjNewDataTerm.filter(({id: id1}) => !old_data_viwcpf_search_term.some(({id: id2}) => id2 === id1));

                for (let item of ObjNewDataTermNotAdd) {
                    taxonomyList.push({
                        name: item.text,
                        id: item.id,
                    })
                }

                /*edit 20-07-2021 */
                /*Remove all table when change tax_name */
                if (old_data_viwcpf_search_term.length === 0) {
                    terms_list_table.clear().draw();
                }

                /*Update row to table*/
                for (let term of taxonomyList) {
                    let term_default_color ='#fe2740';
                    let key_default_color = term.name.trim().toLowerCase();
                    key_default_color = key_default_color.split(" ");
                    key_default_color = key_default_color.join('-');

                    if (typeof viwcpf_default_color[key_default_color] !== 'undefined') {
                        term_default_color = viwcpf_default_color[key_default_color];
                    }

                    let new_row = $(`
						<tr data-term_id="${term.id}" data-term_name="${term.name}">
							<td>
								<div class="wrap_td">
									<label class="label-setting">${term.name}</label>
									<input type="hidden"  name="viwcpf_term${term.id}_old_label" id="viwcpf_term${term.id}_old_label" value="${term.name}">
								</div>
							</td>
							<td>
								<div class="wrap_td">
									<div class="vi-ui input">
										<input type="text"  name="viwcpf_term${term.id}_new_label" id="viwcpf_term${term.id}_new_label" placeholder="${viwcpf_i18n.i18n_new_label}">
									</div>
								</div>
							</td>
							<td>
								<div class="vi-ui input ">
									<input type="text" name="viwcpf_term${term.id}_tooltip" id="viwcpf_term${term.id}_tooltip" placeholder="${viwcpf_i18n.i18n_enter_tooltip}">
								</div>
							</td>
							<td class="choose_color option_select hidden" data-select="viwcpf_tax-type_show" data-type_show="color_swatches">
								<div class="field">
									<div class="vi-ui input">
										<span class="color-picker"></span>
										<input type="text" class="color-text" name="viwcpf_term_color[${term.id}][]" id="viwcpf_term${term.id}_color1" placeholder="${viwcpf_i18n.i18n_choose_color}" value="${term_default_color}">
									</div>
								</div>
							</td>
						</tr>
					`);
                    terms_list_table.row.add(new_row).draw();
                    _iris(new_row.find('.color-picker'));
                    new_row.find('.color-text').trigger('change');
                }

                $('#tax-type_show').trigger('change');
                $this.removeClass('loading');
                $('#loading_table_term').removeClass('active');
            });

        }
        return false;
    });
    // Remove all term
    $(document).on('click', '#tax-remove_all', function () {
        if (confirm('Do you want to delete all terms ?')) {
            $('#viwcpf_input_search_term').val(null).html("").trigger('change');
            terms_list_table.clear().draw();
        }

        return false;
    });


    $(document).on('change', 'input[type="radio"],input[type="checkbox"], select', function () {
        _check_condition_input($(this));
    });

    /*
   Function check condition and update field.
   @param selector: $(this) of field
   @use : attribute add to field hide :    data-select="" -> Name of field condition
                                           data-typeshow="" -> value of field condition want display
   version:1.0.0
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
                            (this_select === $name) &&
                            (jQuery.inArray($value, this_array_type_show) !== -1)
                        ) {
                            $this.removeClass('hidden');
                        }
                    });

                } else {
                    option_select.each(function () {
                        let $this = $(this),
                            this_select = $this.attr('data-select');
                        if (this_select === $name) {
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
                    if (this_select === $name) {
                        $this.addClass('hidden');
                    }
                    if (
                        (this_select === $name) &&
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
                    if (this_select === $name) {
                        $this.addClass('hidden');
                    }
                    if (
                        (this_select === $name) &&
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
                color_val = $this.val(),
                color_default = $this.data('color_default');

            if (color_val.length >= 3) {
                $this.parent().find('.color-picker').css({'backgroundColor': color_val.toString()}).iris('color', color_val);
            } else if (color_val.length <= 0) {
                // $this.val(color_default).trigger('change');
                $this.val(color_default);
            }
        });
        return selector;
    }

    _iris($('.color-picker'));

    /*Un Selected event of #viwcpf_input_search_term, remove row of table list term*/
    $(document).on("select2:unselect", '#viwcpf_input_search_term', function () {
        let arr_term = $(this).val();
        terms_list_table.rows().every(function () {
            // ... do something with data(), or this.node(), etc
            let node = this.nodes()[0];
            let term_id = $(node).attr("data-term_id");

            if ($.inArray(term_id, arr_term) === -1) {
                terms_list_table.row($(this)).remove().draw();
                $('#tax-type_show').trigger('change');
                setTimeout(function () {
                    _iris($('.color-picker'));
                    $('.color-text').trigger('change');
                }, 100);
            }
        });

    });
    /*Selected event of #viwcpf_input_search_term, update row for table list term*/
    $(document).on("select2:select", '#viwcpf_input_search_term', function () {
        /*get data of #viwcpf_input_search_term */
        let taxonomyList = [],
            ObjDataTerm = $(this).select2("data");
        for (let item of ObjDataTerm) {
            taxonomyList.push({
                name: item.text,
                id: item.id
            })
        }
        /*Get all current data of table list*/
        let arr_list_term = [];
        terms_list_table.rows().every(function () {
            // ... do something with data(), or this.node(), etc
            let node = this.nodes()[0];
            let term_id = $(node).attr("data-term_id");
            let term_name = $(node).attr("data-term_name");
            arr_list_term.push({
                name: term_name,
                id: term_id
            })

        });
        /*Compare current data and existing data and get difference value*/
        let arr_term_not_add = taxonomyList.filter(({id: id1}) => !arr_list_term.some(({id: id2}) => id2 === id1));

        if (arr_term_not_add.length >= 0) {
            /*Update the table with another value obtained*/
            for (let term of arr_term_not_add) {
                let new_row = $(`
					<tr data-term_id="${term.id}" data-term_name="${term.name}">
						<td>
							<div class="wrap_td">
								<label class="label-setting">${term.name}</label>
								<input type="hidden"  name="viwcpf_term${term.id}_old_label" id="viwcpf_term${term.id}_old_label" value="${term.name}">
							</div>
						</td>
						<td>
							<div class="wrap_td">
								<div class="vi-ui input">
									<input type="text"  name="viwcpf_term${term.id}_new_label" id="viwcpf_term${term.id}_new_label" placeholder="${viwcpf_i18n.i18n_new_label}">
								</div>
							</div>
						</td>
						<td>
							<div class="vi-ui input ">
								<input type="text" name="viwcpf_term${term.id}_tooltip" id="viwcpf_term${term.id}_tooltip" placeholder="${viwcpf_i18n.i18n_enter_tooltip}">
							</div>
						</td>
						<td class="choose_color option_select hidden" data-select="viwcpf_tax-type_show" data-type_show="color_swatches">
							<div class="field">
								<div class="vi-ui input">
									<span class="color-picker"></span>
									<input type="text" class="color-text" name="viwcpf_term_color[${term.id}][]" id="viwcpf_term${term.id}_color1" placeholder="${viwcpf_i18n.i18n_choose_color}">
								</div>
							</div>
						</td>
					</tr>
				`);
                _iris(new_row.find('.color-picker'));
                terms_list_table.row.add(new_row).draw();

                $('#tax-type_show').trigger('change');
            }
        }

    });


    /*Trigger event when click pagination table */
    $(document).on('page.dt', '#terms_list', function () {
        setTimeout(function () {
            $('#tax-type_show').trigger('change');
        }, 100);
        setTimeout(function () {
            _iris($('.color-picker'));
            jQuery('.color-text').trigger('change');
        }, 100);
    });
    $(document).on('length.dt', '#terms_list', function () {
        setTimeout(function () {
            $('#tax-type_show').trigger('change');
        }, 100);
        setTimeout(function () {
            _iris($('.color-picker'));
            jQuery('.color-text').trigger('change');
        }, 100);
    });


    function load_name_color_duplicate(seletor) {
        let _this_wrap_field = seletor.closest('.choose_color'),
            _this_wrap_field_data = seletor.closest('.field'),
            _this_clone_input = _this_wrap_field_data.find('input.color-text'),
            _this_clone_id = _this_clone_input.attr('id').toString().slice(0, -1);

        let i = 1;
        _this_wrap_field.find('.color-text').each(function () {
            $(this).attr('id', _this_clone_id + i);
            // $(this).attr('name',_this_clone_id+i)
            i++;
        });
    }

    /*<-----------------Price--------------->*/

    /*Validate range price value*/
    $(document.body)
        .on('viwcpf_add_error_tip', function (e, element, error_type) {
            let offset = element.position();

            if (element.parent().find('.viwcpf_error_tip').length === 0) {
                element.after('<div class="viwcpf_error_tip ' + error_type + '">' + viwcpf_i18n[error_type] + '</div>');
                element.parent().find('.viwcpf_error_tip')
                    .css('left', offset.left + element.width() - (element.width() / 2) - ($('.viwcpf_error_tip').width() / 2))
                    .css('top', offset.top + 10 + element.height() )
                    .fadeIn('100');
            }
        })
        .on('viwcpf_remove_error_tip', function (e, element, error_type) {
            element.parent().find('.viwcpf_error_tip.' + error_type).fadeOut('100', function () {
                $(this).remove();
            });
        })
        .on('click', function () {
            $('.viwcpf_error_tip').fadeOut('100', function () {
                $(this).remove();
            });
        })
        .on('change', '.min_input input', function () {
            let $this = $(this),
                $this_val = $this.val(),
                wrap,
                viwcpf_filter_for = $('#viwcpf_filter_for').val();

            if( viwcpf_filter_for === 'filter_by_price' ){
                wrap = $this.closest('.range_price_item');
            }else{
                wrap = $this.closest('.range_meta_item');
            }

            let max_val = wrap.find('.max_input input').val(),
                error = 'i18n_min_range_more_than_max_range_error';

            if (max_val !== '') {
                if (parseInt($this_val) > parseInt(max_val)) {
                    $this.val('');
                }
            }
        })
        .on('keyup', '.min_input input', function () {
            let $this = $(this),
                $this_val = $this.val(),
                wrap,
                viwcpf_filter_for = $('#viwcpf_filter_for').val();

            if( viwcpf_filter_for === 'filter_by_price' ){
                wrap = $this.closest('.range_price_item');
            }else{
                wrap = $this.closest('.range_meta_item');
            }
            let max_val = wrap.find('.max_input input').val(),
                error = 'i18n_min_range_more_than_max_range_error';

            if (parseInt($this_val) > parseInt(max_val)) {
                $(document.body).triggerHandler('viwcpf_add_error_tip', [$this, error]);
            } else {
                $(document.body).triggerHandler('viwcpf_remove_error_tip', [$this, error]);
            }

        })
        .on('change', '.max_input input', function () {
            let $this = $(this),
                $this_val = $this.val(),
                wrap,
                viwcpf_filter_for = $('#viwcpf_filter_for').val();

            if( viwcpf_filter_for === 'filter_by_price' ){
                wrap = $this.closest('.range_price_item');
            }else{
                wrap = $this.closest('.range_meta_item');
            }
            let min_val = wrap.find('.min_input input').val(),
                error = 'i18n_max_range_less_than_min_range_error';
            if (min_val !== '') {
                if (parseInt($this_val) < parseInt(min_val)) {
                    $this.val('');
                }
            }
        })
        .on('keyup', '.max_input input', function () {
            let $this = $(this),
                $this_val = $this.val(),
                wrap,
                viwcpf_filter_for = $('#viwcpf_filter_for').val();

            if( viwcpf_filter_for === 'filter_by_price' ){
                wrap = $this.closest('.range_price_item');
            }else{
                wrap = $this.closest('.range_meta_item');
            }
            let min_val = wrap.find('.min_input input').val(),
                error = 'i18n_max_range_less_than_min_range_error';

            if (min_val !== '') {
                if (parseInt($this_val) < parseInt(min_val)) {
                    $(document.body).triggerHandler('viwcpf_add_error_tip', [$this, error]);
                } else {
                    $(document.body).triggerHandler('viwcpf_remove_error_tip', [$this, error]);
                }
            }
        });

    /*Conditrional setting block*/
    function _conditional_setting() {
        $(document.body).on('change', '#viwcpf_filter_for', function () {
            let this_val = $(this).val();
            if (this_val !== 'filter_by_name_product') {
                $('.global_setting_conditional').show();
            } else {
                $('.global_setting_conditional').hide();
            }
        });
        $(document.body).on('change', '#price-type_filter', function () {
            let this_val = $(this).val();
            if (this_val == 'range_slide') {

                $('.global_setting_conditional').hide();
            }
        }).trigger('change');
        $(document.body).on('change', '#tax-type_show, #price-type_show, #review-type_show, #onsale-instock_type_show, #viwcpf_meta_string-show_type, #viwcpf_meta_numberic-show_type', function () {
            let $this = $(this),
                $this_val = $this.val();
            if (
                ($this_val == 'button') ||
                ($this_val == 'color_swatches') ||
                ($this_val == 'range')
            ) {
                $('.global_setting_conditional').show();
            } else {
                $('.global_setting_conditional').hide();
            }

        });
    }

    _conditional_setting();
});
//load data by Filter For
jQuery(window).load(function () {
    setTimeout(function () {
        jQuery('#viwcpf_filter_for').trigger('change');
        /*jQuery('#tax-type_show').trigger('change');
        jQuery('#price-type_filter').trigger('change');
        jQuery('#review-type_show').trigger('change');
        jQuery('#viwcpf_meta_type').trigger('change');
        jQuery('#viwcpf_meta_numberic-show_type').trigger('change');*/
        jQuery('.color-text').trigger('change');
        let option_select = jQuery('.option_select');
        option_select.each(function () {
            let $this = jQuery(this),
                this_select = $this.attr('data-select');
            jQuery('input[name=' + this_select + '] , select[name=' + this_select + ']').trigger('change');
        });
    }, 100);
    setTimeout(function () {

        let viwcpf_filter_for = jQuery('#viwcpf_filter_for').val();
        switch (viwcpf_filter_for) {
            case 'filter_by_taxonomy':
                jQuery('#tax-type_show').trigger('change');
                break;
            case 'filter_by_price':
                let price_type_filter = jQuery('#price-type_filter');
                let price_type_filter_value = price_type_filter.val();
                if (price_type_filter_value === 'range') {
                    jQuery(' #price-type_show').trigger('change');
                }else{
                    price_type_filter.trigger('change');
                }

                break;
            case 'filter_by_review':
                jQuery('#review-type_show').trigger('change');
                break;
            case 'filter_by_sale_or_stock':
                jQuery('#onsale-instock_type_show').trigger('change');
                break;
            default:
                break;
        }

    }, 100);

});
