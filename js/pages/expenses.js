'use strict';

menuActive('expenses');

$(document).ready(function ()
{
    var myDocument = $(this);

    var tbl = myDocument.find("table").DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "columnDefs": [
            {
                "orderable": true,
                "targets": '_all'
            },
            {
                "className": 'text-left',
                "targets": '_all'
            }
        ],
        "order": [
            [1,'asc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    var id;

    $(document).on('click', '[data-action="get"]', function()
    {
        id = $(this).data('id');

        $.ajax({
            type: 'POST',
            data: 'id=' + id + '&action=get',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);

                    if (response.data.bill != null)
                        $('input[name="bill"]').val(response.data.bill);

                    if (response.data.cost != null)
                        $('input[name="cost"]').val(response.data.cost);

                    if (response.data.payment != null)
                        $('select[name="payment"]').val(response.data.payment);

                    $('input[name="date"]').val(response.data.datetime[0]);
                    $('input[name="hour"]').val(response.data.datetime[1]);
                    $('[data-modal="expenses"] header > h6').html('Editar gasto');
                    $('[data-modal="expenses"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="expenses"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    var frm = $('form[name="expenses"]');

    modal('expenses', function(modal)
    {
        modal.find('header > h6').html('Nuevo gasto');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frm.submit();
    });

    frm.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            type: 'POST',
            data: data + '&action=' + action + '&id=' + id,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    location.reload();
                });
            }
        });
    });

    multipleSelect($('[data-action="delete"]'), '/expenses/delete', function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
