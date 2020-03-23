'use strict';

$(document).ready(function()
{
    $('[data-search="inventories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories"]').find(' > tbody > tr'));
    });

    $('[data-action="switch_branch"]').on('change', function()
    {
        action = 'switch_branch';

        var vars = {
            'token': $(this).val()
        };

        send_ajax('normal', vars, null, function(response)
        {
            window.location.href = response.path;
        });
    });

    $('[name="bill"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="remission"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="quantity"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="price"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="saved"]').on('change', function()
    {
        if ($(this).val() == 'normal')
        {
            $('[name="bill"]').parents('fieldset').addClass('hidden');
            $('[name="remission"]').parents('fieldset').addClass('hidden');
        }
        else if ($(this).val() == 'bill')
        {
            $('[name="bill"]').parents('fieldset').removeClass('hidden');
            $('[name="remission"]').parents('fieldset').addClass('hidden');
        }
        else if ($(this).val() == 'remission')
        {
            $('[name="bill"]').parents('fieldset').addClass('hidden');
            $('[name="remission"]').parents('fieldset').removeClass('hidden');
        }
    });

    $('[data-list-value]').on('click', function()
    {
        action = 'read_product';
        id = $(this).data('list-value');

        send_ajax('normal', null, null, function(response)
        {
            $('[name="quantity"]').parent().find('span').html(response.data.unity);
        });
    });

    $('[data-action="add_product_to_table"]').on('click', function()
    {
        action = 'add_product_to_table';

        var form = $(this).parents('form');

        send_ajax('form', null, form, function(response)
        {
            $('[name="bill"]').parent().find('span').html(response.data.total);
            $('[name="remission"]').parent().find('span').html(response.data.total);
            $('[name="product"]').parent().find('[data-preview-value]').val('');
            $('[name="product"]').val('');
            $('[name="quantity"]').val('');
            $('[name="price"]').val('');
            $('[data-table="products"]').find(' > div').html('');
            $('[data-table="products"]').find(' > div').html(response.data.html);
        });
    });

    $('[data-action="remove_product_to_table"]').on('click', function()
    {
        action = 'remove_product_to_table';
        id = $(this).data('id');

        send_ajax('normal', null, null, function(response)
        {
            $('[name="bill"]').parent().find('span').html(response.data.total);
            $('[name="remission"]').parent().find('span').html(response.data.total);
            $('[data-table="products"]').find(' > div').html(response.data.html);
        });
    });

    var create_inventory_input = 'create_inventory_input';

    $(document).on('click', '[data-action="' + create_inventory_input + '"]', function()
    {
        action = create_inventory_input;
        id = null;

        open_form_modal('create', $('[data-modal="' + create_inventory_input + '"]'));
    });

    $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });
});
