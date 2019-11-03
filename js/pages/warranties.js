'use strict';

menuActive('settings');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de garantías
    /* ------------------------------------------------------------------------ */
    var table = myDocument.find("table").DataTable({
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

    /* Obtener garantía para editar
    /* ------------------------------------------------------------------------ */
    var idWarranty;
    var btnGetWarrantyToEdit = $('[data-action="getWarrantyToEdit"]');

    btnGetWarrantyToEdit.on('click', function()
    {
        idWarranty = $(this).data('id');

        $.ajax({
            url: '/warranties/getWarrantyToEdit/' + idWarranty,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="quantity"]').val(response.data.quantity);
                    $('select[name="timeFrame"]').val(response.data.time_frame);

                    $('[data-modal="warranties"] header > h6').html('Editar garantía');
                    $('[data-modal="warranties"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="warranties"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar garantías
    /* ------------------------------------------------------------------------ */
    var frmWarranties = $('form[name="warranties"]');

    modal('warranties', function(modal)
    {
        modal.find('header > h6').html('Nueva garantía');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmWarranties.submit();
    });

    frmWarranties.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idWarranty,
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

    /* Eliminar selección de garantías
    /* ------------------------------------------------------------------------ */
    var btnDeleteWarranties    = $('[data-action="deleteWarranties"]');
    var urlDeleteWarranties    = '/warranties/deleteWarranties';

    multipleSelect(btnDeleteWarranties, urlDeleteWarranties, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
