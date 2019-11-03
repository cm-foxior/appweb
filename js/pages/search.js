'use strict';

$(document).ready(function ()
{
    $(document).on('change', 'select[name="search"]', function()
    {
        $.ajax({
            url: '',
            type: 'POST',
            data: 'idProduct=' + $(this).val() + '&action=search',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('#filter').html('');
                    $('#filter').html(response.html);
                }
            }
        });
    });

    var foli;
    var action;

    $(document).on('click', '[data-action="tosell"]', function()
    {
        foli = $(this).data('id');
        action = $(this).data('action');
        $(this).parent().submit();
    });

    $(document).on('submit', 'form', function(e)
    {
        e.preventDefault();

        var form = $(this);

        $.ajax({
            url: '',
            type: 'POST',
            data: form.serialize() + '&foli=' + foli + '&action=' + action,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'error')
                {
                    $.each(response.errors, function (key, value)
                    {
                        form.find('[name="' + value[0] + '"]').addClass('error');
                    });

                    setTimeout(function() { form.find('.error').removeClass('error'); }, 4000);
                }
                else if (response.status == 'success')
                    window.location.href = '/pointsale/add';
            }
        });
    });
});
