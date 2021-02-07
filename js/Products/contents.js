'use strict';

$(document).ready(function()
{
    $('[data-search="products_contents"]').focus();

    $('[data-search="products_contents"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="products_contents"]').find(' > tbody > tr'));
    });

    var create_action = 'create_product_content';
    var read_action = 'read_product_content';
    var update_action = 'update_product_content';
    var block_action = 'block_product_content';
    var unblock_action = 'unblock_product_content';
    var delete_action = 'delete_product_content';

    $(document).on('click', '[data-action="' + create_action + '"]', function()
    {
        action = create_action;
        id = null;

        transform_form_modal('create', $('[data-modal="' + create_action + '"]'));
        open_form_modal('create', $('[data-modal="' + create_action + '"]'));
    });

    $('[name="amount"]').on('keyup', function()
    {
        validate_string(['int'], $(this).val(), $(this));
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

            $('[name="amount"]').val(data.amount);
            $('[name="unity"]').val(data.unity);
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
