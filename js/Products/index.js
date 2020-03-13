'use strict';

$(document).ready(function()
{
    $('[data-search="products"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="products"]'), 'tbl-st-1');
    });

    $('[data-random="token"]').on('click', function()
    {
        generate_random_token('uppercase_number', 8, $('[name="token"]'));
    });

    $('[name="token"]').on('keyup', function()
    {
        check_type_input('letter_number', $(this).val(), $(this));
    });

    $('[name="price"]').on('keyup', function()
    {
        check_type_input('decimal', $(this).val(), $(this));
    });

    $('[name="weight_empty"]').on('keyup', function()
    {
        check_type_input('decimal', $(this).val(), $(this));
    });

    $('[name="weight_full"]').on('keyup', function()
    {
        check_type_input('decimal', $(this).val(), $(this));
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

            if (data.type == 'sale')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="avatar"]').parents('.uploader').find('img').attr('src', ((data.avatar != null) ? '../uploads/' + data.avatar : '../images/empty.png'));

            $('[data-modal="' + create_action + '"]').find('form').find('[name="name"]').val(data.name);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="token"]').val(data.token);

            if (data.type == 'sale')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="price"]').val(data.price);

            if (data.type == 'sale' || data.type == 'supply' || data.type == 'workmaterial')
                $('[data-modal="' + create_action + '"]').find('form').find('[name="unity"]').val(data.unity);

            if (data.type == 'sale' || data.type == 'supply')
            {
                $('[data-modal="' + create_action + '"]').find('form').find('[name="weight_empty"]').val(data.weight.empty);
                $('[data-modal="' + create_action + '"]').find('form').find('[name="weight_full"]').val(data.weight.full);
            }

            if (data.type == 'sale')
            {
                $.each(data.recipes, function (key, value)
                {
                    $('[data-modal="' + create_action + '"]').find('[name="recipes[]"][value="' + value + '"]').prop('checked', true);
                });
            }

            if (data.type == 'recipe')
            {
                $.each(data.supplies, function (key, value)
                {
                    $('[data-modal="' + create_action + '"]').find('[name="supplies[]"][value="' + value + '"]').prop('checked', true);
                });
            }

            $.each(data.categories, function (key, value)
            {
                $('[data-modal="' + create_action + '"]').find('[name="categories[]"][value="' + value + '"]').prop('checked', true);
            });
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
