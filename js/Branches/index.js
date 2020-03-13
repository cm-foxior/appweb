'use strict';

$(document).ready(function()
{
    $('[data-search="branches"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="branches"]'), 'tbl-st-3');
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        check_type_input('number', $(this).val(), $(this));
    });

    $('[name="fiscal_id"]').on('keyup', function()
    {
        check_type_input('uppercase_number', $(this).val().toUpperCase(), $(this));
    });

    var create_action = 'create_branch';
    var read_action = 'read_branch';
    var update_action = 'update_branch';
    var block_action = 'block_branch';
    var unblock_action = 'unblock_branch';
    var delete_action = 'delete_branch';

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

            $('[data-modal="' + create_action + '"]').find('form').find('[name="avatar"]').parents('.uploader').find('img').attr('src', ((data.avatar != null) ? '../uploads/' + data.avatar : '../images/branch.png'));
            $('[data-modal="' + create_action + '"]').find('form').find('[name="name"]').val(data.name);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="token"]').val(data.token);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="email"]').val(data.email);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="phone_country"]').val(data.phone.country);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="phone_number"]').val(data.phone.number);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="country"]').val(data.country);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="address"]').val(data.address);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="fiscal_id"]').val(data.fiscal.id);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="fiscal_name"]').val(data.fiscal.name);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="fiscal_country"]').val(data.fiscal.country);
            $('[data-modal="' + create_action + '"]').find('form').find('[name="fiscal_address"]').val(data.fiscal.address);
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
