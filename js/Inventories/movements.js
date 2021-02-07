'use strict';

var modal;
var product;
var movement;
var control;

$(document).ready(function()
{
    $('[data-search="inventories_movements"]').focus();

    $('[data-search="inventories_movements"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories_movements"]').find(' > tbody > tr'));
    });

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

    $('[data-action="switch_inventory_period"]').on('change', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=switch_inventory_period&id=' + $(this).val(),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    location.reload();
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    var create_inventory_input = 'create_inventory_input';
    var create_inventory_output = 'create_inventory_output';
    var create_inventory_transfer = 'create_inventory_transfer';
    var read_action = 'read_inventory_movement';
    var delete_action = 'delete_inventory_movement';

    $(document).on('click', '[data-action="' + create_inventory_input + '"]', function()
    {
        action = create_inventory_input;
        id = null;
        modal = $('[data-modal="' + create_inventory_input + '"]');
        product = [];
        movement = 'input';
        control = '';

        clean_product_form(modal, 'create');
        open_form_modal('create', modal);
    });

    $(document).on('click', '[data-action="' + create_inventory_output + '"]', function()
    {
        action = create_inventory_output;
        id = null;
        modal = $('[data-modal="' + create_inventory_output + '"]');
        product = [];
        movement = 'output';
        control = '';

        clean_product_form(modal, 'create');
        open_form_modal('create', modal);
    });

    $(document).on('click', '[data-action="' + create_inventory_transfer + '"]', function()
    {
        action = create_inventory_transfer;
        id = null;
        modal = $('[data-modal="' + create_inventory_transfer + '"]');
        product = [];
        movement = 'transfer';
        control = '';

        clean_product_form(modal, 'create');
        open_form_modal('create', modal);
    });

    $('[name="product_token"]').parents('.compound.st-6').find('[data-list] > [data-success]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=read_product&id=' + $(this).data('value'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    load_product_form(response.data);
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    $('[name="saved"]').on('change', function()
    {
        modal.find('[name="quantity"]').val('');
        modal.find('[name="weight"]').val('');
        modal.find('[name="content"]').val('');

        if ($(this).val() == 'quantity')
        {
            modal.find('[name="quantity"]').parent().find('span').html(((product.length == 0) ? 'Unidad' : (((movement == 'output' || movement == 'transfer') && product.inventory == false) ? 'Unidad' : ((product.unity_system == true) ? product.unity_name.es : product.unity_name))));
            modal.find('[name="quantity"]').removeClass('hidden');
            modal.find('[name="weight"]').addClass('hidden');
            modal.find('[name="content"]').find('[value=""]').removeAttr('hidden');
            modal.find('[name="content"]').find('[data-no-weight]').removeAttr('hidden');
            modal.find('[name="content"]').find('[data-unity]').attr('hidden', true);

            modal.find('[name="quantity"]').focus();
        }
        else if ($(this).val() == 'weight')
        {
            modal.find('[name="quantity"]').parent().find('span').html('Unidad');
            modal.find('[name="quantity"]').addClass('hidden');
            modal.find('[name="weight"]').removeClass('hidden');
            modal.find('[name="content"]').find('[value=""]').attr('hidden', true);
            modal.find('[name="content"]').find('[data-no-weight]').attr('hidden', true);
            modal.find('[name="content"]').find('[data-unity]').removeAttr('hidden');

            modal.find('[name="weight"]').focus();
        }
    });

    $('[name="quantity"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="weight"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="content"]').on('change', function()
    {
        if (modal.find('[name="saved"]:checked').val() == 'quantity')
            modal.find('[name="quantity"]').focus();
        else if (modal.find('[name="saved"]:checked').val() == 'weight')
        {
            var split = $(this).val().split('_');

            if (split[0] == 'cnt')
                modal.find('[name="weight"]').parent().find('span').html(((product.contents[split[1]].weight.unity_system == true) ? product.contents[split[1]].weight.unity_name.es : product.contents[split[1]].weight.unity_name));
            else if (split[0] == 'unt')
                modal.find('[name="weight"]').parent().find('span').html((((movement == 'output' || movement == 'transfer') && product.inventory == false) ? 'Unidad' : ((product.unity_system == true) ? product.unity_name.es : product.unity_name)));

            modal.find('[name="weight"]').focus();
        }
    });

    $('[name="cost"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[data-search="categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), modal.find('[data-table="categories"]').find(' > label'), 'hidden');
    });

    $('[name="product_token"]').parents('.compound.st-6').find('[data-preview] > [data-cancel]').on('click', function()
    {
        clean_product_form(modal, 'cancel');
    });

    $('[name="product_token"]').parents('.compound.st-6').find('[data-preview] > [data-success]').on('click', function()
    {
        var form = modal.find('form');
        var data = new FormData(form[0]);

        if (movement == 'input')
            data.append('action', 'add_product_to_inputs_table');
        else if (movement == 'output')
            data.append('action', 'add_product_to_outputs_table');
        else if (movement == 'transfer')
            data.append('action', 'add_product_to_transfers_table');

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
                    clean_product_form(form, 'success');

                    if (movement == 'input')
                    {
                        form.find('[data-table="inputs"]').find(' > tbody').html(response.data.table);

                        load_inputs_table_actions();
                    }
                    else if (movement == 'output')
                    {
                        form.find('[data-table="outputs"]').find(' > tbody').html(response.data.table);

                        load_outputs_table_actions();
                    }
                    else if (movement == 'transfer')
                    {
                        form.find('[data-table="transfers"]').find(' > tbody').html(response.data.table);

                        load_transfers_table_actions();
                    }

                    form.find('[name="product_token"]').focus();
                });
            }
        });
    });

    load_inputs_table_actions();
    load_outputs_table_actions();
    load_transfers_table_actions();

    $('[name="bill_type"]').on('change', function()
    {
        modal.find('[name="bill_token"]').val('');
        modal.find('[name="bill_payment_way"]').val('cash');
        modal.find('[name="bill_iva"]').val('');
        modal.find('[name="bill_discount_type"]').val('$');
        modal.find('[name="bill_discount_amount"]').val('');

        if ($(this).val() == 'bill' || $(this).val() == 'ticket')
        {
            modal.find('[name="bill_token"]').attr('disabled', false);
            modal.find('[name="bill_token"]').focus();
            modal.find('[name="bill_payment_way"]').attr('disabled', false);
            modal.find('[name="bill_iva"]').attr('disabled', false);
            modal.find('[name="bill_discount_type"]').attr('disabled', false);
            modal.find('[name="bill_discount_amount"]').attr('disabled', false);
        }
        else
        {
            modal.find('[name="bill_token"]').attr('disabled', true);
            modal.find('[name="bill_payment_way"]').attr('disabled', true);
            modal.find('[name="bill_iva"]').attr('disabled', true);
            modal.find('[name="bill_discount_type"]').attr('disabled', true);
            modal.find('[name="bill_discount_amount"]').attr('disabled', true);
        }
    });

    $('[data-action="generate_random_bill_token"]').on('click', function()
    {
        generate_string(['uppercase','lowercase','int'], 8, $('[name="bill_token"]'));
    });

    $('[name="bill_token"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="bill_payment_way"]').on('change', function()
    {
        modal.find('[name="bill_token"]').focus();
    });

    $('[name="bill_iva"]').on('keyup', function()
    {
        validate_string(['float'], $(this).val(), $(this));
    });

    $('[name="bill_discount_amount"]').on('keyup', function()
    {
        validate_string(['float'], $(this).val(), $(this));
    });

    $('[data-modal="' + create_inventory_input + '"]').find('form > fieldset > div.button > button[type="submit"]').on('click', function()
    {
        if (modal.find('[name="product_token"]').val().length > 0)
            control = 'scan';
        else
            control = 'unscan';
    });

    $('[data-modal="' + create_inventory_input + '"]').find('form').on('submit', function(event)
    {
        event.preventDefault();

        if (control == 'scan')
        {
            $.ajax({
                type: 'POST',
                data: 'action=read_product&token=' + modal.find('[name="product_token"]').val(),
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                    {
                        var target = modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]');

                        load_product_view(target);
                        load_product_form(response.data);
                    }
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
        else if (control == 'unscan')
            send_form_modal('create', $(this), event);
    });

    $('[data-modal="' + create_inventory_output + '"]').find('form > fieldset > div.button > button[type="submit"]').on('click', function()
    {
        if (modal.find('[name="product_token"]').val().length > 0)
            control = 'scan';
        else
            control = 'unscan';
    });

    $('[data-modal="' + create_inventory_output + '"]').find('form').on('submit', function(event)
    {
        event.preventDefault();

        if (control == 'scan')
        {
            $.ajax({
                type: 'POST',
                data: 'action=read_product&token=' + modal.find('[name="product_token"]').val(),
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                    {
                        var target = modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]');

                        load_product_view(target);
                        load_product_form(response.data);
                    }
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
        else if (control == 'unscan')
            send_form_modal('create', $(this), event);
    });

    $('[data-modal="' + create_inventory_transfer + '"]').find('form > fieldset > div.button > button[type="submit"]').on('click', function()
    {
        if (modal.find('[name="product_token"]').val().length > 0)
            control = 'scan';
        else
            control = 'unscan';
    });

    $('[data-modal="' + create_inventory_transfer + '"]').find('form').on('submit', function(event)
    {
        event.preventDefault();

        if (control == 'scan')
        {
            $.ajax({
                type: 'POST',
                data: 'action=read_product&token=' + modal.find('[name="product_token"]').val(),
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                    {
                        var target = modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]');

                        load_product_view(target);
                        load_product_form(response.data);
                    }
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
        else if (control == 'unscan')
            send_form_modal('create', $(this), event);
    });

    // $(document).on('click', '[data-action="' + read_action + '"]', function()
    // {
    //     action = read_action;
    //     id = $(this).data('id');
    //
    //     open_form_modal('read', $('[data-modal="' + read_action + '"]'), function(data)
    //     {
    //         $('[data-modal="' + read_action + '"]').find('main > div').html(data.html);
    //     });
    // });

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

function load_product_form(data)
{
    product = data.product;

    clean_product_form(modal, 'add');

    modal.find('[name="saved"][value="weight"]').attr('disabled', (((movement == 'output' || movement == 'transfer') && product.inventory == false) ? true : false));
    modal.find('[name="quantity"]').parent().find('span').html(((movement == 'output' || movement == 'transfer') && product.inventory == false) ? 'Unidad' : ((product.unity_system == true) ? product.unity_name.es : product.unity_name));
    modal.find('[name="content"]').attr('disabled', (((movement == 'output' || movement == 'transfer') && product.inventory == false) ? true : false));

    if (product.inventory == true)
    {
        if (product.contents != null)
        {
            $.each(product.contents, function(key, value)
            {
                modal.find('[name="content"]').append('<option value="cnt_' + key + '" ' + ((value.weight.length == 0) ? 'data-no-weight' : '') + '>' + value.content.amount + ' ' + ((value.content.unity_system == true) ? value.content.unity_name.es : value.content.unity_name) + '</option>');
            });
        }

        if (product.contents == null || (Array.isArray(product.contents) && product.contents.length <= 0))
        {
            $.each(data.products_unities, function(key, value)
            {
                modal.find('[name="content"]').append('<option value="unt_' + value.id + '" data-unity hidden>' + ((value.system == true) ? value.name.es : value.name) + '</option>');
            });
        }
    }

    modal.find('[name="quantity"]').focus();
}

function load_product_view(target)
{
    target.parents('.compound.st-6').find('[data-preview] > input').val(target.data('value'));
    target.parents('.compound.st-6').find('[data-preview] > div').html(target.parent().find('div').html());
    target.parents('.compound.st-6').find('[data-preview]').addClass('open');
    target.parents('.compound.st-6').find('[data-search] > input').val('');
    target.parents('.compound.st-6').find('[data-search]').addClass('close');
    target.parents('.compound.st-6').find('[data-list]').addClass('hidden');
}

function clean_product_form(target, option)
{
    if (option == 'create' || option == 'cancel' || option == 'success')
    {
        target.find('[name="product_id"]').parents('.compound.st-6').find('[data-preview] > div').html('');
        target.find('[name="product_id"]').parents('.compound.st-6').find('[data-preview]').removeClass('open');
        target.find('[name="product_token"]').parents('.compound.st-6').find('[data-search]').removeClass('close');
        target.find('[name="product_token"]').parents('.compound.st-6').find('[data-list]').addClass('hidden');
    }

    target.find('[name="saved"][value="quantity"]').parent().find('span').addClass('checked');
    target.find('[name="saved"][value="weight"]').parent().find('span').removeClass('checked');
    target.find('[name="saved"][value="weight"]').attr('disabled', false);
    target.find('[name="quantity"]').parent().find('span').html('Unidad');
    target.find('[name="quantity"]').removeClass('hidden');
    target.find('[name="weight"]').addClass('hidden');
    target.find('[name="content"]').html('<option value="">Contenido no establecido</option>');
    target.find('[name="content"]').attr('disabled', false);

    if (movement == 'input')
        target.find('[data-table="categories"]').find('label').addClass('hidden');

    if (option == 'cancel' || option == 'success')
    {
        target.find('[name="product_id"]').val('');
        target.find('[name="product_token"]').val('');
    }

    if (option == 'add' || option == 'cancel' || option == 'success')
    {
        target.find('[name="saved"][value="quantity"]').prop('checked', true);
        target.find('[name="saved"][value="weight"]').prop('checked', false);
        target.find('[name="quantity"]').val('');
        target.find('[name="weight"]').val('');
        target.find('[name="content"]').val('');

        if (movement == 'input')
        {
            target.find('[name="cost"]').val('');
            target.find('[name="location"]').val('');
            target.find('[data-search="categories"]').val('');
            target.find('[name="categories[]"]').prop('checked', false);
        }
    }

    if (option == 'create' && movement == 'input')
    {
        target.find('[name="bill_token"]').attr('disabled', true);
        target.find('[name="bill_payment_way"]').attr('disabled', true);
        target.find('[name="bill_iva"]').attr('disabled', true);
        target.find('[name="bill_discount_type"]').attr('disabled', true);
        target.find('[name="bill_discount_amount"]').attr('disabled', true);
    }

    if (option == 'transfer')
        target.find('[name="branch"]').val('');

    if (option == 'cancel' || option == 'success')
        product = [];
}

function load_inputs_table_actions()
{
    $('[data-action="remove_product_to_inputs_table"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=remove_product_to_inputs_table&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[data-table="inputs"]').find(' > tbody').html(response.data.table);

                    load_inputs_table_actions();

                    modal.find('[name="product_token"]').focus();
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
}

function load_outputs_table_actions()
{
    $('[data-action="remove_product_to_outputs_table"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=remove_product_to_outputs_table&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[data-table="outputs"]').find(' > tbody').html(response.data.table);

                    load_outputs_table_actions();

                    modal.find('[name="product_token"]').focus();
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
}

function load_transfers_table_actions()
{
    $('[data-action="remove_product_to_transfers_table"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=remove_product_to_transfers_table&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[data-table="transfers"]').find(' > tbody').html(response.data.table);

                    load_transfers_table_actions();

                    modal.find('[name="product_token"]').focus();
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
}
