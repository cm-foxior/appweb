'use strict';

$(document).ready(function()
{
    $('[data-search="providers"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="providers"]').find(' > div'));
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="fiscal_id"]').on('keyup', function()
    {
        validate_string(['uppercase','int'], $(this).val().toUpperCase(), $(this));
    });

    var create_action = 'create_provider';
    var read_action = 'read_provider';
    var update_action = 'update_provider';
    var block_action = 'block_provider';
    var unblock_action = 'unblock_provider';
    var delete_action = 'delete_provider';

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

            $('[data-modal="' + create_action + '"]').find('form').find('[name="avatar"]').parents('.uploader').find('img').attr('src', ((data.avatar != null) ? '../uploads/' + data.avatar : '../images/provider.png'));
            $('[data-modal="' + create_action + '"]').find('form').find('[name="name"]').val(data.name);
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
