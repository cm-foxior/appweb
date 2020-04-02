'use strict';

$(document).ready(function()
{
    $('[data-action="switch_branch"]').on('change', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=switch_branch&id=' + $(this).val(),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    window.location.href = response.path;
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    $('[data-search="inventories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories"]').find(' > tbody > tr'));
    });

    $('[name="saved"]').on('change', function()
    {
        if ($(this).val() == 'free')
            $('[name="bill_token"]').parents('fieldset').addClass('hidden');
        else if ($(this).val() == 'bill')
            $('[name="bill_token"]').parents('fieldset').removeClass('hidden');
    });

    $('[name="bill_token"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="product"]').parents('.st-6').find('[data-list-value]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=read_product_to_add_to_input_table&id=' + $(this).data('list-value'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[name="input_quantity"]').parent().find('span').html(((validate_string('empty', response.data.input_unity) == false) ? response.data.input_unity : 'No aplica'));
                    $('[name="input_quantity"]').attr('disabled', ((validate_string('empty', response.data.input_unity) == false) ? false : true));
                    $('[name="storage_quantity"]').parent().find('span').html(response.data.storage_unity);
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    $('[name="input_quantity"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="storage_quantity"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="price"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[data-search="categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="categories"]').find(' > label'));
    });

    $('[data-action="add_product_to_input_table"]').on('click', function()
    {
        var form = $(this).parents('form');
        var data = new FormData(form[0]);

        data.append('action', 'add_product_to_input_table');

        $.ajax({
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                check_form_errors(form, response, function()
                {
                    $('[name="product"]').parents('.st-6').find('[data-preview-value]').val('');
                    $('[name="product"]').val('');
                    $('[name="input_quantity"]').val('');
                    $('[name="input_quantity"]').parent().find('span').html('No aplica');
                    $('[name="storage_quantity"]').val('');
                    $('[name="storage_quantity"]').parent().find('span').html('Unidad');
                    $('[name="price"]').val('');
                    $('[name="location"]').val('');
                    $('[name="categories[]"]').prop('checked', false);
                    $('[data-table="inputs"]').find(' > tbody').html(response.data.table);
                });
            }
        });
    });

    $('[data-action="remove_product_to_input_table"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=remove_product_to_input_table&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    $('[data-table="inputs"]').find(' > tbody').html(response.data.table);
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    var create_inventory_input = 'create_inventory_input';

    $(document).on('click', '[data-action="' + create_inventory_input + '"]', function()
    {
        action = create_inventory_input;
        id = null;

        open_form_modal('create', $('[data-modal="' + create_inventory_input + '"]'));
    });

    $('[data-modal="' + create_inventory_input + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });
});
