'use strict';

$(document).ready(function()
{
    $('[data-action="switch_branch"]').on('change', function()
    {
        action = 'switch_branch';
        id = $(this).val();

        send_ajax('normal', null, null, function(response)
        {
            window.location.href = response.path;
        });
    });

    $('[data-search="inventories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories"]').find(' > tbody > tr'));
    });

    $('[name="saved"]').on('change', function()
    {
        if ($(this).val() == 'free')
            $('[name="bill_folio"]').parents('fieldset').addClass('hidden');
        else if ($(this).val() == 'bill')
            $('[name="bill_folio"]').parents('fieldset').removeClass('hidden');
    });

    $('[name="bill_folio"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="product"]').parents('fieldset').find('[data-list-value]').on('click', function()
    {
        action = 'read_product';
        id = $(this).data('list-value');

        send_ajax('normal', null, null, function(response)
        {
            $('[name="quantity"]').parents('fieldset').find('span').html(response.data.unity);
        });
    });

    $('[name="quantity"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="price"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[data-search="inventories_categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories_categories"]').find(' > label'));
    });

    $('[data-action="add_product_to_input_table"]').on('click', function()
    {
        action = 'add_product_to_input_table';

        var form = $(this).parents('form');

        send_ajax('form', null, form, function(response)
        {
            $('[name="product"]').parents('fieldset').find('[data-preview-value]').val('');
            $('[name="product"]').val('');
            $('[name="location"]').val('');
            $('[name="quantity"]').val('');
            $('[name="quantity"]').parents('fieldset').find('span').html('Unidad');
            $('[name="price"]').val('');
            $('[name="categories[]"]').prop('checked', false);
            $('[data-table="inputs"]').find(' > tbody').html(response.data.table);
        });
    });

    $('[data-action="remove_product_to_input_table"]').on('click', function()
    {
        action = 'remove_product_to_input_table';
        id = $(this).data('id');

        send_ajax('normal', null, null, function(response)
        {
            $('[data-table="inputs"]').find(' > tbody').html(response.data.table);
        });
    });

    // var create_inventory_input = 'create_inventory_input';
    //
    // $(document).on('click', '[data-action="' + create_inventory_input + '"]', function()
    // {
    //     action = create_inventory_input;
    //     id = null;
    //
    //     open_form_modal('create', $('[data-modal="' + create_inventory_input + '"]'));
    // });
    //
    // $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    // {
    //     send_form_modal('create', $(this), event);
    // });
});
