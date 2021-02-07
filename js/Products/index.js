'use strict';

$(document).ready(function()
{
    $('[data-search="products"]').focus();

    $('[data-search="products"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="products"]').find(' > tbody > tr'));
    });

    // var filter_action = 'filter_products';
    var create_action = 'create_product';
    var read_action = 'read_product';
    var update_action = 'update_product';
    var block_action = 'block_product';
    var unblock_action = 'unblock_product';
    var delete_action = 'delete_product';
    var modal;

    // $(document).on('click', '[data-action="' + filter_action + '"]', function()
    // {
    //     action = filter_action;
    //     id = null;
    //     modal = $('[data-modal="' + filter_action + '"]');
    //
    //     modal.find('[data-search="categories"]').val('');
    //     modal.find('[name="categories[]"]').parent().removeClass('hidden');
    //
    //     open_form_modal('filter', modal);
    // });

    // $('[data-modal="' + filter_action + '"]').find('form').on('submit', function(event)
    // {
    //     send_form_modal('filter', $(this), event);
    // });

    $(document).on('click', '[data-action="' + create_action + '"]', function()
    {
        action = create_action;
        id = null;
        modal = $('[data-modal="' + create_action + '"]');

        modal.find('[name="unity"]').attr('disabled', false);
        modal.find('[name="portion"]').attr('disabled', false);
        modal.find('[name="portion"]').parent().find('span').html('Unidad');
        modal.find('[name="formula_code"]').attr('disabled', true);
        modal.find('[name="formula_parent"]').attr('disabled', true);
        modal.find('[name="formula_quantity"]').attr('disabled', true);
        modal.find('[name="formula_quantity"]').parent().find('span').html('Unidad');
        modal.find('[data-search="contents"]').attr('disabled', false);
        modal.find('[name="contents[]"]').parent().parent().addClass('hidden');
        modal.find('[name="supplies[]"]').parent().parent().addClass('hidden');
        modal.find('[name="categories[]"]').parent().addClass('hidden');

        transform_form_modal('create', modal);
        open_form_modal('create', modal);
    });

    $('[data-action="generate_random_token"]').on('click', function()
    {
        generate_string(['uppercase','lowercase','int'], 8, modal.find('[name="token"]'));
    });

    $('[name="token"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="inventory"]').on('change', function()
    {
        modal.find('[name="unity"]').val('');
        modal.find('[name="unity"]').attr('disabled', (($(this).val() == 'yes') ? false : true));
        modal.find('[name="portion"]').val('');
        modal.find('[name="portion"]').attr('disabled', (($(this).val() == 'yes') ? false : true));
        modal.find('[name="portion"]').parent().find('span').html('Unidad');
        modal.find('[name="formula_code"]').val('');
        modal.find('[name="formula_code"]').attr('disabled', (($(this).val() == 'yes') ? true : false));
        modal.find('[name="formula_parent"]').val('');
        modal.find('[name="formula_parent"]').attr('disabled', true);
        modal.find('[name="formula_quantity"]').val('');
        modal.find('[name="formula_quantity"]').attr('disabled', true);
        modal.find('[name="formula_quantity"]').parent().find('span').html('Unidad');
        modal.find('[data-search="contents"]').val('');
        modal.find('[data-search="contents"]').attr('disabled', (($(this).val() == 'yes') ? false : true));
        modal.find('[name="contents[]"]').prop('checked', false);
        modal.find('[name="contents[]"]').parent().parent().addClass('hidden');
    });

    $('[name="unity"]').on('change', function()
    {
        modal.find('[name="portion"]').parent().find('span').html($(this).find('[value="' + $(this).val() + '"]').html());
    });

    $('[name="price"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="portion"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));
    });

    $('[name="formula_code"]').on('change', function()
    {
        modal.find('[name="formula_parent"]').val('');
        modal.find('[name="formula_quantity"]').val('');
        modal.find('[name="formula_quantity"]').parent().find('span').html('Unidad');

        if ($(this).val().length > 0)
        {
            modal.find('[name="formula_parent"]').attr('disabled', false);

            if ($(this).val() == 'SHG78K9H')
                modal.find('[name="formula_quantity"]').attr('disabled', false);
            else
                modal.find('[name="formula_quantity"]').attr('disabled', true);
        }
        else
        {
            modal.find('[name="formula_parent"]').attr('disabled', true);
            modal.find('[name="formula_quantity"]').attr('disabled', true);
        }
    });

    $('[name="formula_parent"]').on('change', function()
    {
        if (modal.find('[name="formula_code"]').val() == 'SHG78K9H')
        {
            $.ajax({
                type: 'POST',
                data: 'action=read_product&id=' + $(this).val(),
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                    {
                        modal.find('[name="formula_quantity"]').parent().find('span').html(((response.data.unity_system == true) ? response.data.unity_name.es : response.data.unity_name));
                        modal.find('[name="formula_quantity"]').focus();
                    }
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
    });

    $('[data-search="contents"]').on('keyup', function()
    {
        search_in_table($(this).val(), modal.find('[data-table="contents"]').find(' > div'), 'hidden');
    });

    $('[data-search="supplies"]').on('keyup', function()
    {
        search_in_table($(this).val(), modal.find('[data-table="supplies"]').find(' > div'), 'hidden');
    });

    $('[data-search="supplies"]').on('change', function()
    {
        var target = $(this);

        if (target.prop('checked'))
        {
            $.ajax({
                type: 'POST',
                data: 'action=read_product&id=' + target.val(),
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                        target.parent().parent().find('select').val(response.data.unity_id);
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
        else
            target.parent().parent().find('select').val('');
    });

    $('[data-search="categories"]').on('keyup', function()
    {
        search_in_table($(this).val(), modal.find('[data-table="categories"]').find(' > label'), 'hidden');
    });

    $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });

    $(document).on('click', '[data-action="' + update_action + '"]', function()
    {
        action = read_action;
        id = $(this).data('id');
        modal = $('[data-modal="' + create_action + '"]');

        transform_form_modal('update', modal);
        open_form_modal('update', modal, function(data)
        {
            action = update_action;

            if (data.type == 'sale_menu')
                modal.find('[name="avatar"]').parents('.uploader').find('img').attr('src', ((validate_string('empty', data.avatar) == false) ? '../uploads/' + data.avatar : '../images/product.png'));

            modal.find('[name="name"]').val(data.name);
            modal.find('[name="token"]').val(data.token);
            modal.find('[name="inventory"]').val(((data.inventory == true) ? 'yes' : 'not'));
            modal.find('[name="unity"]').val(((data.inventory == true) ? data.unity_id : ''));
            modal.find('[name="unity"]').attr('disabled', ((data.inventory == true) ? false : true));
            modal.find('[name="cost"]').val(data.cost);

            if (data.type == 'sale_menu')
                modal.find('[name="price"]').val(data.price);

            if (data.type == 'recipe')
            {
                modal.find('[name="portion"]').val(((data.inventory == true) ? data.portion : ''));
                modal.find('[name="portion"]').parent().find('span').html(((data.inventory == true) ? ((data.unity_system == true) ? data.unity_name.es : data.unity_name) : 'Unidad'));
                modal.find('[name="portion"]').attr('disabled', ((data.inventory == true) ? false : true));
            }

            if (data.type == 'sale_menu')
            {
                modal.find('[name="formula_code"]').val(((data.inventory == false) ? data.formula.code : ''));
                modal.find('[name="formula_code"]').attr('disabled', ((data.inventory == false) ? false : true));
                modal.find('[name="formula_parent"]').val(((data.inventory == false && data.formula.code.length > 0) ? data.formula.parent.id : ''));
                modal.find('[name="formula_parent"]').attr('disabled', ((data.inventory == false && data.formula.code.length > 0) ? false : true));
                modal.find('[name="formula_quantity"]').val(((data.inventory == false && data.formula.code == 'SHG78K9H') ? data.formula.quantity : ''));
                modal.find('[name="formula_quantity"]').attr('disabled', ((data.inventory == false && data.formula.code == 'SHG78K9H') ? false : true));
                modal.find('[name="formula_quantity"]').parent().find('span').html(((data.inventory == false && data.formula.code == 'SHG78K9H') ? ((data.formula.parent.unity_system == true) ? data.formula.parent.unity_name.es : data.formula.parent.unity_name) : 'Unidad'));
            }

            if (data.type == 'sale_menu' || data.type == 'supply' || data.type == 'work_material')
            {
                modal.find('[data-search="contents"]').attr('disabled', ((data.inventory == true) ? false : true));
                modal.find('[name="contents[]"]').parent().parent().addClass('hidden');

                if (data.inventory == true)
                {
                    $.each(data.contents, function (key, value)
                    {
                        modal.find('[name="contents[]"][value="' + key + '"]').parent().parent().removeClass('hidden');
                        modal.find('[name="contents[]"][value="' + key + '"]').prop('checked', true);
                        modal.find('input[name="' + key + '[]"]').val(value.weight);
                        modal.find('select[name="' + key + '[]"]').val(value.unity);
                    });
                }
            }

            if (data.type == 'sale_menu' || data.type == 'recipe')
            {
                modal.find('[name="supplies[]"]').parent().parent().addClass('hidden');

                $.each(data.supplies, function (key, value)
                {
                    modal.find('[name="supplies[]"][value="' + key + '"]').parent().parent().removeClass('hidden');
                    modal.find('[name="supplies[]"][value="' + key + '"]').prop('checked', true);
                    modal.find('input[name="' + key + '[]"]').val(value.quantity);
                    modal.find('select[name="' + key + '[]"]').val(value.unity);
                });
            }

            modal.find('[name="categories[]"]').parent().addClass('hidden');

            $.each(data.categories, function (key, value)
            {
                modal.find('[name="categories[]"][value="' + value + '"]').parent().removeClass('hidden');
                modal.find('[name="categories[]"][value="' + value + '"]').prop('checked', true);
            });
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
