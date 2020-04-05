'use strict';

$(document).ready(function()
{
    $('[data-search="products"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="products"]').find(' > tbody > tr'));
    });

    $('[data-action="generate_random_token"]').on('click', function()
    {
        generate_string(['uppercase','lowercase','int'], 8, $('[name="token"]'));
    });

    $('[name="token"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="price"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="gain_margin_amount"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="weight_full"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="weight_empty"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[data-search="categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="categories"]').find(' > label'), 'hidden');
    });

    $('[data-search="supplies"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="supplies"]').find(' > label'), 'hidden');
    });

    $('[data-search="recipes"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="recipes"]').find(' > label'), 'hidden');
    });

    var create_action = 'create_product';
    var read_action = 'read_product';
    var update_action = 'update_product';
    var block_action = 'block_product';
    var unblock_action = 'unblock_product';
    var delete_action = 'delete_product';

    $(document).on('click', '[data-action="' + create_action + '"]', function()
    {
        action = create_action;
        id = null;

        transform_form_modal('create', $('[data-modal="' + create_action + '"]'));
        open_form_modal('create', $('[data-modal="' + create_action + '"]'));
    });

    $(document).on('click', '[data-action="' + update_action + '"]', function()
    {
        action = read_action;
        id = $(this).data('id');

        transform_form_modal('update', $('[data-modal="' + create_action + '"]'));
        open_form_modal('update', $('[data-modal="' + create_action + '"]'), function(data)
        {
            action = update_action;

            if (data.type == 'sale_menu')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="avatar"]').parents('.uploader').find('img').attr('src', ((validate_string('empty', data.avatar) == false) ? '../uploads/' + data.avatar : '../images/empty.png'));

            $('[data-modal="' + create_action + '"]').find('form').find('[name="name"]').val(data.name);

            if (data.type == 'sale_menu' || data.type == 'supply' || data.type == 'work_material')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="inventory"]').prop('checked', ((data.inventory == true) ? true : false));

            if (data.type == 'sale_menu' || data.type == 'supply' || data.type == 'work_material')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="token"]').val(data.token);

            if (data.type == 'sale_menu' || data.type == 'supply' || data.type == 'work_material')
            {
                $('[data-modal="' + create_action + '"]').find('form').find('[name="input_unity"]').val(data.input_unity);
                $('[data-modal="' + create_action + '"]').find('form').find('[name="storage_unity"]').val(data.storage_unity);
            }

            if (data.type == 'sale_menu')
            {
                $('[data-modal="' + create_action + '"]').find('form').find('[name="price"]').val(data.price);
                $('[data-modal="' + create_action + '"]').find('form').find('[name="gain_margin_amount"]').val(data.gain_margin.amount);
                $('[data-modal="' + create_action + '"]').find('form').find('[name="gain_margin_type"]').val(data.gain_margin.type);
            }

            if (data.type == 'sale_menu' || data.type == 'supply')
            {
                $('[data-modal="' + create_action + '"]').find('form').find('[name="weight_full"]').val(data.weight.full);
                $('[data-modal="' + create_action + '"]').find('form').find('[name="weight_empty"]').val(data.weight.empty);
            }

            $.each(data.categories, function (key, value)
            {
                $('[data-modal="' + create_action + '"]').find('form').find('[name="categories[]"][value="' + value + '"]').prop('checked', true);
            });

            if (data.type == 'sale_menu' || data.type == 'recipe')
            {
                $.each(data.supplies, function (key, value)
                {
                    $('[data-modal="' + create_action + '"]').find('form').find('[name="supplies[]"][value="' + value + '"]').prop('checked', true);
                });
            }

            if (data.type == 'sale_menu')
            {
                $.each(data.recipes, function (key, value)
                {
                    $('[data-modal="' + create_action + '"]').find('form').find('[name="recipes[]"][value="' + value + '"]').prop('checked', true);
                });
            }
        });
    });

    $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });

    $(document).on('click', '[data-action="' + block_action + '"]', function()
    {
        action = block_action;
        id = $(this).data('id');

        send_form_modal('block');
    });

    $(document).on('click', '[data-action="' + unblock_action + '"]', function()
    {
        action = unblock_action;
        id = $(this).data('id');

        send_form_modal('unblock');
    });

    $(document).on('click', '[data-action="' + delete_action + '"]', function()
    {
        action = delete_action;
        id = $(this).data('id');

        open_form_modal('delete', $('[data-modal="' + delete_action + '"]'));
    });

    $('[data-modal="' + delete_action + '"]').modal().onSuccess(function()
    {
        send_form_modal('delete');
    });
});
