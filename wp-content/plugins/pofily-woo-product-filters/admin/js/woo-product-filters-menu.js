/*
Author: DubleyNguyen
Author URI: http://villatheme.com
Copyright 2021 villatheme.com. All rights reserved.
*/


jQuery(document).ready(function ($) {
    "use strict";
    /*Copy shortcode when click*/
    $('.vi-ui.dropdown').dropdown();
    $(document).on('click', '.viwcpf_shortcode_show', function () {
        let $this = $(this),
            this_copied_text = $this.parent().find('.viwcpf_copied_tooltip');
        /*Copy event*/
        $this.select();
        document.execCommand('copy');
        /*Show tooltip and auto hide it */
        this_copied_text.css('visibility', 'visible');
        setTimeout(function () {
            this_copied_text.css('visibility', 'hidden');
        }, 1000);
    });

    let width_content_sortable = $('#sortable_block_selected').width() - 10;
    $("#sortable_block_selected").sortable({
        revert: true,
        placeholder: "sortable-placeholder",
        stop: function (event, ui) {
            if (!ui.item.data('tag') && !ui.item.data('handle')) {

                ui.item.data('tag', true);
                if (!ui.item.hasClass('block_selected')) {
                    ui.item.find('.wrapp_btn').append('<a href="#" class="vi-ui del_block icon red button mini compact"> <i class="minus icon"></i></a>');
                    ui.item.find('.add_block').remove();
                    ui.item.addClass('block_selected');
                }
            }
        },
        sort: function (event, ui) {
            ui.item.css('width', width_content_sortable);
        },
        update: function (event, ui) {
            let new_arr_blocks_selected = [],
                $this = ui.item,
                this_block_id = $this.attr('data-block_id'),
                val_blocks_selected = $('#viwcpf_blocks_selected').val();

            if (val_blocks_selected === '') {
                $('#viwcpf_blocks_selected').val(this_block_id);
            } else {

                /*Update value to array*/

                $("#sortable_block_selected .item_block").each(function (i, el) {
                    var p = $(el).attr('data-block_id').toLowerCase();
                    new_arr_blocks_selected.push(p);
                });
                /**/
                let new_val_blocks_selected = new_arr_blocks_selected.toString();

                /*Update new value to input*/
                $('#viwcpf_blocks_selected').val(new_val_blocks_selected);

            }
        }
    });

    // $("#dragable_block_select .item_block").draggable({
    //     connectToSortable: '#sortable_block_selected',
    //     cursor: "crosshair",
    //     /*helper: 'clone',*/
    //     revert: 'invalid'
    // });

    $(".item_block").disableSelection();

    $(document).on('click', '.del_block', function () {
        let $this = $(this),
            this_item_block = $this.closest('.item_block'),
            this_item_name = this_item_block.attr('data-block_name'),
            this_item_id = this_item_block.attr('data-block_id'),
            this_item_url = this_item_block.attr('data-block_url');

        $("#dragable_block_select").append(`
            <div class="vi-ui segment item_block add_block" data-block_id="${this_item_id}" data-block_name="${this_item_name}" data-block_url="${this_item_url}">
                <h4>${this_item_name}</h4>
                <div class="wrapp_btn">
                    <a href="${this_item_url}" class="vi-ui edit_block icon blue button mini compact"> <i class="edit icon"></i></a>
                </div>
            </div>`);
        // $("#dragable_block_select .item_block").each(function () {
        //     if ($(this).attr('data-block_id') === this_item_id) {
        //         $(this).draggable({connectToSortable: '#sortable_block_selected', disabled: false});
        //     }
        // });
        /*Update value when item remove*/
        let val_blocks_selected = $('#viwcpf_blocks_selected').val(),
            arr_blocks_selected = val_blocks_selected.split(',');
        if (val_blocks_selected !== '') {
            /*delete duplicate elements in array*/
            arr_blocks_selected = _viwcpf_unique_arr(arr_blocks_selected);
            /*Update value to array*/
            if (jQuery.inArray(this_item_id, arr_blocks_selected) !== -1) {

                let index_remove = jQuery.inArray(this_item_id, arr_blocks_selected);

                arr_blocks_selected.splice(index_remove, 1);

                let new_val_blocks_selected = arr_blocks_selected.toString();
                /*Update new value to input*/
                $('#viwcpf_blocks_selected').val(new_val_blocks_selected);
            }

        }
        this_item_block.remove();
        return false;
    });

    $(document).on('click', '.add_block', function (e) {

        if (e.target !== e.currentTarget) return;


        let $this = $(this),
            this_item_block = $this,
            this_item_name = this_item_block.attr('data-block_name'),
            this_item_id = this_item_block.attr('data-block_id'),
            this_item_url = this_item_block.attr('data-block_url');

        $("#sortable_block_selected").append(`
            <div class="vi-ui segment item_block block_selected" data-block_id="${this_item_id}" data-block_name="${this_item_name}" data-block_url="${this_item_url}">
                <h4>${this_item_name}</h4>
                <div class="wrapp_btn">
                    <a href="${this_item_url}" class="vi-ui edit_block icon blue button mini compact"> <i class="edit icon"></i></a>
                    <a href="#" class="vi-ui del_block icon red button mini compact"> <i class="minus icon"></i></a>
                </div>
            </div>`);
        $("#sortable_block_selected").sortable('refresh');
        /*Update value when item remove*/
        let val_blocks_selected = $('#viwcpf_blocks_selected').val();
        let arr_blocks_selected = [];
        if (val_blocks_selected !== '') {
            arr_blocks_selected = val_blocks_selected.split(',');
        }
        /*delete duplicate elements in array*/
        arr_blocks_selected = _viwcpf_unique_arr(arr_blocks_selected);

        /*Update value to array*/
        if (jQuery.inArray(this_item_id, arr_blocks_selected) == -1) {

            arr_blocks_selected.push(this_item_id);

            let new_val_blocks_selected = arr_blocks_selected.toString();
            /*Update new value to input*/
            $('#viwcpf_blocks_selected').val(new_val_blocks_selected);
        }
        this_item_block.remove();
        return false;
    });


    $(document).on('change', 'input[type="radio"],input[type="checkbox"], select', function () {
        _check_condition_input($(this));
    });

    $(document).on('click', '.refresh_blocks_filter', function () {
        let $this = $(this),
            exclude_str_id = $('#viwcpf_blocks_selected').val();
        $this.addClass('loading');
        $('#dragable_block_select').addClass('disabled');
        $.ajax({
            url: viwcpf_ajax.ajax,
            method: "POST",
            data: {
                exclude_str_id: exclude_str_id,
                action: 'viwcpf_refresh_block_filter'
            },
            dataType: "json"
        }).done(function (respon) {
            $('#dragable_block_select').html(respon).removeClass('disabled');

            $this.removeClass('loading');
        });

        return false;
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

    /*Function delete duplicate elements in array */
    function _viwcpf_unique_arr(arr) {
        let newArr = [];
        for (let i = 0; i < arr.length; i++) {
            if (newArr.indexOf(arr[i]) === -1) {
                newArr.push(arr[i])
            }
        }
        return newArr
    }

});
//load data by Filter For
jQuery(window).load(function () {

    setTimeout(function () {

        let option_select = jQuery('.option_select');
        option_select.each(function () {
            let $this = jQuery(this),
                this_select = $this.attr('data-select');
            jQuery('input[name=' + this_select + '] , select[name=' + this_select + ']').trigger('change');
        });

    }, 100);

});