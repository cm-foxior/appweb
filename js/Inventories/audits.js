'use strict';

var modal;
var product;
var control;

$(document).ready(function()
{
    $('[data-search="inventories_audits"]').focus();

    $('[data-search="inventories_audits"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories_audits"]').find(' > tbody > tr'));
    });

    $('[data-search="inventories_existences"]').focus();

    $('[data-search="inventories_existences"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="inventories_existences"]').find(' > tbody > tr'));
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

    var upload_physical = 'upload_physical';
    var create_inventory_audit = 'create_inventory_audit';
    var update_inventory_audit = 'update_inventory_audit';
    var delete_action = 'delete_inventory_audit';

    $(document).on('click', '[data-action="' + upload_physical + '"]', function()
    {
        action = upload_physical;
        id = null;
        modal = $('[data-modal="' + upload_physical + '"]');
        product = [];
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
            modal.find('[name="quantity"]').parent().find('span').html(((product.length == 0) ? 'Unidad' : ((product.unity_system == true) ? product.unity_name.es : product.unity_name)));
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
                modal.find('[name="weight"]').parent().find('span').html(((product.unity_system == true) ? product.unity_name.es : product.unity_name));

            modal.find('[name="weight"]').focus();
        }
    });

    $('[name="product_token"]').parents('.compound.st-6').find('[data-preview] > [data-cancel]').on('click', function()
    {
        clean_product_form(modal, 'cancel');
    });

    $('[name="product_token"]').parents('.compound.st-6').find('[data-preview] > [data-success]').on('click', function()
    {
        var form = modal.find('form');
        var data = new FormData(form[0]);

        data.append('action', 'add_product_to_physical_table');

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

                    form.find('[data-table="physical"]').find(' > tbody').html(response.data.table);

                    load_physical_table_actions();

                    form.find('[name="product_token"]').focus();
                });
            }
        });
    });

    load_physical_table_actions();

    $('[data-modal="' + upload_physical + '"]').find('form > fieldset > div.button > button[type="submit"]').on('click', function()
    {
        if (modal.find('[name="product_token"]').val().length > 0)
            control = 'scan';
        else
            control = 'unscan';
    });

    $('[data-modal="' + upload_physical + '"]').find('form').on('submit', function(event)
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
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-preview] > input').val(modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').data('value'));
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-preview] > div').html(modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parent().find('div').html());
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-preview]').addClass('open');
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-search] > input').val('');
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-search]').addClass('close');
                        modal.find('[name="product_token"]').parents('.compound.st-6').find('[data-success][data-value="' + response.data.product.id + '"]').parents('.compound.st-6').find('[data-list]').addClass('hidden');

                        load_product_form(response.data);
                    }
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
        else if (control == 'unscan')
        {
            $.ajax({
                type: 'POST',
                data: 'action=add_products_to_inventory_audit',
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                        open_notification_modal('success');
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
    });

    $(document).on('click', '[data-action="' + create_inventory_audit + '"]', function()
    {
        action = create_inventory_audit;
        id = null;
        modal = $('[data-modal="' + create_inventory_audit + '"]');

        open_form_modal('create', modal);
    });

    $('[data-modal="' + create_inventory_audit + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event, true);
    });

    $(document).on('click', '[data-action="' + update_inventory_audit + '"]', function()
    {
        action = update_inventory_audit;
        id = null;
        modal = $('[data-modal="' + update_inventory_audit + '"]');

        open_form_modal('create', modal);
    });

    $('[data-modal="' + update_inventory_audit + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('update', $(this), event, true);
    });

    var self;

    $('[data-action="add_success_to_inventory_audit"]').on('click', function()
    {
        self = $(this);

        $.ajax({
            type: 'POST',
            data: 'action=add_success_to_inventory_audit&id=' + self.data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    self.addClass('success');
                else
                    self.removeClass('success');

                self.parent().parent().find('[data-action="add_error_to_inventory_audit"]').removeClass('alert');
            }
        });
    });

    $('[data-action="add_error_to_inventory_audit"]').on('click', function()
    {
        self = $(this);

        $.ajax({
            type: 'POST',
            data: 'action=add_error_to_inventory_audit&id=' + self.data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'error')
                    self.addClass('alert');
                else
                    self.removeClass('alert');

                self.parent().parent().find('[data-action="add_success_to_inventory_audit"]').removeClass('success');
            }
        });
    });

    $(document).on('click', '[data-action="add_comment_to_inventory_audit"], [data-action="read_comment_to_inventory_audit"]', function()
    {
        self = $(this);
        id = self.data('id');

        $.ajax({
            type: 'POST',
            data: 'action=read_comment_to_inventory_audit&id=' + id,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success' || response.status == '')
                {
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="success"]').prop('checked', true);
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="success"]').parent().find('span').addClass('checked');
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="error"]').prop('checked', false);
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="error"]').parent().find('span').removeClass('checked');
                }
                else if (response.status == 'error')
                {
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="success"]').prop('checked', false);
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="success"]').parent().find('span').removeClass('checked');
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="error"]').prop('checked', true);
                    $('[data-modal="add_comment_to_inventory_audit"]').find('[name="status"][value="error"]').parent().find('span').addClass('checked');
                }

                $('[data-modal="add_comment_to_inventory_audit"]').find('[name="comment"]').val(response.comment);
                $('[data-modal="add_comment_to_inventory_audit"]').addClass('view');
            }
        });
    });

    $('[data-modal="add_comment_to_inventory_audit"]').find('form').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        data.append('action', 'add_comment_to_inventory_audit');
        data.append('id', id);

        $.ajax({
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    self.parent().parent().find('[data-action="add_success_to_inventory_audit"]').addClass('success');
                    self.parent().parent().find('[data-action="add_error_to_inventory_audit"]').removeClass('alert');
                }
                else if (response.status == 'error')
                {
                    self.parent().parent().find('[data-action="add_success_to_inventory_audit"]').removeClass('success');
                    self.parent().parent().find('[data-action="add_error_to_inventory_audit"]').addClass('alert');
                }
                else
                {
                    self.parent().parent().find('[data-action="add_success_to_inventory_audit"]').removeClass('success');
                    self.parent().parent().find('[data-action="add_error_to_inventory_audit"]').removeClass('alert');
                }

                if (response.comment == '')
                    self.removeClass('success');
                else if (response.comment != '')
                    self.addClass('success');

                $('[data-modal="add_comment_to_inventory_audit"]').removeClass('view');
            }
        });
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

function load_product_form(data)
{
    product = data.product;

    clean_product_form(modal, 'add');

    modal.find('[name="saved"][value="weight"]').attr('disabled', false);
    modal.find('[name="quantity"]').parent().find('span').html(((product.unity_system == true) ? product.unity_name.es : product.unity_name));
    modal.find('[name="content"]').attr('disabled', false);

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
    }

    if (option == 'cancel' || option == 'success')
        product = [];
}

function load_physical_table_actions()
{
    $('[data-action="remove_product_to_physical_table"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=remove_product_to_physical_table&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[data-table="physical"]').find(' > tbody').html(response.data.table);

                    load_physical_table_actions();

                    modal.find('[name="product_token"]').focus();
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
}
