'use strict';

menuActive('catalogs');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de sucursales
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

    /* Obtener sucursal para editar
    /* ------------------------------------------------------------------------ */
    var idBranchOffice;

    $(document).on('click', '[data-action="getBranchOfficeToEdit"]', function()
    {
        idBranchOffice = $(this).data('id');

        $.ajax({
            url: '/branchoffices/getBranchOfficeToEdit/' + idBranchOffice,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    var phoneNumber = eval('(' + response.data.phone_number + ')');

                    $('input[name="name"]').val(response.data.name);
                    $('input[name="email"]').val(response.data.email);
                    $('select[name="phoneCountryCode"]').val(phoneNumber.country_code);
                    $('input[name="phoneNumber"]').val(phoneNumber.number);
                    $('select[name="phoneType"]').val(phoneNumber.type);
                    $('input[name="address"]').val(response.data.address);
                    $('select[name="fiscalCountry"]').val(response.data.fiscal_country);
                    $('input[name="fiscalName"]').val(response.data.fiscal_name);
                    $('input[name="fiscalCode"]').val(response.data.fiscal_code);
                    $('input[name="fiscalRegime"]').val(response.data.fiscal_regime);
                    $('input[name="fiscalAddress"]').val(response.data.fiscal_address);

                    $('[data-modal="branchOffices"] header > h6').html('Editar sucursal');
                    $('[data-modal="branchOffices"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="branchOffices"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar sucursal
    /* ------------------------------------------------------------------------ */
    var frmBranchOffices = $('form[name="branchOffices"]');

    modal('branchOffices', function(modal)
    {
        modal.find('header > h6').html('Nueva sucursal');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmBranchOffices.submit();
    });

    frmBranchOffices.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idBranchOffice,
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

    /* Activar selección de sucursales
    /* ------------------------------------------------------------------------ */
    var btnActivateBranchOffices = $('[data-action="activateBranchOffices"]');
    var urlActivateBranchOffices = '/branchoffices/changeStatusBranchOffices/activate';

    multipleSelect(btnActivateBranchOffices, urlActivateBranchOffices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección de sucursales
    /* ------------------------------------------------------------------------ */
    var btnDeactivateBranchOffices = $('[data-action="deactivateBranchOffices"]');
    var urlDeactivateBranchOffices = '/branchoffices/changeStatusBranchOffices/deactivate';

    multipleSelect(btnDeactivateBranchOffices, urlDeactivateBranchOffices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección de sucursales
    /* ------------------------------------------------------------------------ */
    var btnDeleteBranchOffices = $('[data-action="deleteBranchOffices"]');
    var urlDeleteBranchOffices = '/branchoffices/deleteBranchOffices';

    multipleSelect(btnDeleteBranchOffices, urlDeleteBranchOffices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
