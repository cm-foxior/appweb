'use strict';

$(document).ready(function()
{
    $('[data-search="branches"]').focus();

    $('[data-search="branches"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="branches"]').find(' > div'));
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

        switch_states('');

        transform_form_modal('create', $('[data-modal="' + create_action + '"]'));
        open_form_modal('create', $('[data-modal="' + create_action + '"]'));
    });

    $('[name="phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="phone_number"]').val('');
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="fiscal_country"]').on('change', function()
    {
        switch_states($(this).val());
    });

    $('[name="fiscal_id"]').on('keyup', function()
    {
        validate_string(['uppercase','int'], $(this).val().toUpperCase(), $(this));
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

            $('[name="avatar"]').parents('.uploader').find('img').attr('src', ((validate_string('empty', data.avatar) == false) ? '../uploads/' + data.avatar : '../images/branch.png'));
            $('[name="name"]').val(data.name);
            $('[name="email"]').val(data.email);
            $('[name="phone_country"]').val(data.phone.country);
            $('[name="phone_number"]').val(data.phone.number);
            $('[name="fiscal_country"]').val(data.fiscal.country);

            switch_states(data.fiscal.country, data.fiscal.state);

            $('[name="fiscal_address"]').val(data.fiscal.address);
            $('[name="fiscal_name"]').val(data.fiscal.name);
            $('[name="fiscal_id"]').val(data.fiscal.id);
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

function switch_states(country, state)
{
    $.ajax({
        type: 'POST',
        data: 'action=switch_states&country=' + country,
        processData: false,
        cache: false,
        dataType: 'json',
        success: function(response)
        {
            if (response.status == 'success')
            {
                state = (state == undefined || state == null || state == '') ? '' : state;

                $('[name="fiscal_state"]').html(response.html);
                $('[name="fiscal_state"]').val(state);

                if (country == 'MEX')
                    $('[name="fiscal_state"]').attr('disabled', false);
                else
                    $('[name="fiscal_state"]').attr('disabled', true);
            }
            else if (response.status == 'error')
                open_notification_modal('alert', response.message);
        }
    });
}
