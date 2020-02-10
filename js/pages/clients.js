'use strict';

menuActive('clients');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de clientes
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

    /* Asignar la misma dirección a la dirección fiscal
    /* ------------------------------------------------------------------------ */
    var cbxAssignSameAddress = $('[data-action="assignSameAddress"]');

    cbxAssignSameAddress.on('change', function()
    {
        if (cbxAssignSameAddress.is(':checked') == true)
        {
            var address = $('input[name="address"]').val();
            $('input[name="fiscalAddress"]').val(address);
        }
        else
            $('input[name="fiscalAddress"]').val('');
    });

    /* Obtener cliente para editar
    /* ------------------------------------------------------------------------ */
    var idClient;

    $(document).on('click', '[data-action="getClientToEdit"]', function()
    {
        idClient = $(this).data('id');

        $.ajax({
            url: '/clients/getClientToEdit/' + idClient,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('select[name="type"]').val(response.data.type);

                    if (response.data.email != null)
                        $('input[name="email"]').val(response.data.email);

                    if (response.data.phone_number != null)
                    {
                        var phoneNumber = eval('(' + response.data.phone_number + ')');
                        $('select[name="phoneCountryCode"]').val(phoneNumber.country_code);
                        $('input[name="phoneNumber"]').val(phoneNumber.number);
                        $('select[name="phoneType"]').val(phoneNumber.type);
                    }

                    if (response.data.address != null)
                        $('input[name="address"]').val(response.data.address);

                    if (response.data.fiscal_country != null)
                        $('select[name="fiscalCountry"]').val(response.data.fiscal_country);

                    if (response.data.fiscal_name != null)
                        $('input[name="fiscalName"]').val(response.data.fiscal_name);

                    if (response.data.fiscal_code != null)
                        $('input[name="fiscalCode"]').val(response.data.fiscal_code);

                    if (response.data.fiscal_address != null)
                        $('input[name="fiscalAddress"]').val(response.data.fiscal_address);

                    $('[data-modal="clients"] header > h6').html('Editar cliente');
                    $('[data-modal="clients"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="clients"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar cliente
    /* ------------------------------------------------------------------------ */
    var frmClients = $('form[name="clients"]');

    modal('clients', function(modal)
    {
        modal.find('header > h6').html('Nuevo cliente');
        modal.find('form').attr('data-submit-action', 'new');
        $('#fiscalName').html('Razón Social');
        $('#fiscalCode').html('RFC');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmClients.submit();
    });

    frmClients.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idClient,
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

    /* Activar selección de clientes
    /* ------------------------------------------------------------------------ */
    var btnActivateClients = $('[data-action="activateClients"]');
    var urlActivateClients = '/clients/changeStatusClients/activate';

    multipleSelect(btnActivateClients, urlActivateClients, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección de clientes
    /* ------------------------------------------------------------------------ */
    var btnDeactivateClients = $('[data-action="deactivateClients"]');
    var urlDeactivateClients = '/clients/changeStatusClients/deactivate';

    multipleSelect(btnDeactivateClients, urlDeactivateClients, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección de clientes
    /* ------------------------------------------------------------------------ */
    var btnDeleteClients = $('[data-action="deleteClients"]');
    var urlDeleteClients = '/clients/deleteClients';

    multipleSelect(btnDeleteClients, urlDeleteClients, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
