'use strict';

$(document).ready(function()
{
    $('[data-search="products_categories"]').focus();

    $('[data-search="products_categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="products_categories"]').find(' > tbody > tr'));
    });

    var create_action = 'create_product_category';
    var read_action = 'read_product_category';
    var update_action = 'update_product_category';
    var block_action = 'block_product_category';
    var unblock_action = 'unblock_product_category';
    var delete_action = 'delete_product_category';

    $(document).on('click', '[data-action="' + create_action + '"]', function()
    {
        action = create_action;
        id = null;

        transform_form_modal('create', $('[data-modal="' + create_action + '"]'));
        open_form_modal('create', $('[data-modal="' + create_action + '"]'));
    });

    $('[name="level"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });

    $(document).on('click', '[data-action="' + update_action + '"]', function()
    {
        action = read_action;
        id = $(this).data('id');

        transform_form_modal('update', $('[data-modal="' + create_action + '"]'));
        open_form_modal('update', $('[data-modal="' + create_action + '"]'), function(data)
        {
            action = update_action;

            $('[name="name"]').val(data.name);
            $('[name="level"]').val(data.level);
            $('[name="sale_menu"]').prop('checked', ((data.sale_menu == true) ? true : false));
            $('[name="supply"]').prop('checked', ((data.supply == true) ? true : false));
            $('[name="recipe"]').prop('checked', ((data.recipe == true) ? true : false));
            $('[name="work_material"]').prop('checked', ((data.work_material == true) ? true : false));
        });
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
