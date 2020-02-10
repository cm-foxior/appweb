'use strict';

menuActive('providers');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de proveedores
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

    /* Obtener proveedor para editar
    /* ------------------------------------------------------------------------ */
    var idProvider;

    $(document).on('click', '[data-action="getProviderToEdit"]', function()
    {
        idProvider = $(this).data('id');

        $.ajax({
            url: '/providers/getProviderToEdit/' + idProvider,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);

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

                    $('[data-modal="providers"] header > h6').html('Editar proveedor');
                    $('[data-modal="providers"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="providers"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar proveedor
    /* ------------------------------------------------------------------------ */
    var frmProviders = $('form[name="providers"]');

    modal('providers', function(modal)
    {
        modal.find('header > h6').html('Nuevo proveedor');
        modal.find('form').attr('data-submit-action', 'new');
        $('#fiscalName').html('Razón Social');
        $('#fiscalCode').html('RFC');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmProviders.submit();
    });

    frmProviders.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idProvider,
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

    /* Activar selección de proveedores
    /* ------------------------------------------------------------------------ */
    var btnActivateProviders = $('[data-action="activateProviders"]');
    var urlActivateProviders = '/providers/changeStatusProviders/activate';

    multipleSelect(btnActivateProviders, urlActivateProviders, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección de proveedores
    /* ------------------------------------------------------------------------ */
    var btnDeactivateProviders = $('[data-action="deactivateProviders"]');
    var urlDeactivateProviders = '/providers/changeStatusProviders/deactivate';

    multipleSelect(btnDeactivateProviders, urlDeactivateProviders, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección de proveedores
    /* ------------------------------------------------------------------------ */
    var btnDeleteProviders = $('[data-action="deleteProviders"]');
    var urlDeleteProviders = '/providers/deleteProviders';

    multipleSelect(btnDeleteProviders, urlDeleteProviders, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
