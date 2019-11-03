'use strict';

menuActive('catalogs');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de prospectos
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

    /* Obtener prospecto para editar
    /* ------------------------------------------------------------------------ */
    var idProspect;
    var btnGetProspectToEdit = $('[data-action="getProspectToEdit"]');

    btnGetProspectToEdit.on('click', function()
    {
        idProspect = $(this).data('id');

        $.ajax({
            url: '/prospects/getProspectToEdit/' + idProspect,
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

                    $('[data-modal="prospects"] header > h6').html('Editar prospecto');
                    $('[data-modal="prospects"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="prospects"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar prospecto
    /* ------------------------------------------------------------------------ */
    var frmProspects = $('form[name="prospects"]');

    modal('prospects', function(modal)
    {
        modal.find('header > h6').html('Nuevo prospecto');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmProspects.submit();
    });

    frmProspects.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idProspect,
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

    /* Importar prospectos desde Excel
    /* ------------------------------------------------------------------------ */
    var frmImportFromExcel = $('form[name="importFromExcel"]');

    modal('importFromExcel', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmImportFromExcel.submit();
    });

    frmImportFromExcel.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = new FormData(this);

        $.ajax({
            url: '/prospects/importFromExcel',
            type: 'POST',
            data: data,
            contentType: false,
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

    /* Agregar selección de prospectos a clientes
    /* ------------------------------------------------------------------------ */
    var btnAddToClients = $('[data-action="addToClients"]');
    var urlAddToClients = '/prospects/addToClients';

    multipleSelect(btnAddToClients, urlAddToClients, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Enviar correo masivo a clientes
    /* ------------------------------------------------------------------------ */
    var frmSendMassEmail = $('form[name="sendMassEmail"]');
    var selected = [];

    $('[data-check]').change(function ()
    {
        var self = $(this);

        if ( !self.is(':checked') )
        {
            $("[data-check-all]").prop('checked', false);

            var removeItem = self.val();
            selected = jQuery.grep(selected, function ( value )
            {
                return value != removeItem;
            });
        }
        else
        {
            var value = $(this).val();

            if( isNaN(value) == false )
                selected.push(value);
        }
    });

    modal('sendMassEmail', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmSendMassEmail.submit();
    });

    frmSendMassEmail.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = new FormData(this);

        if (selected.length > 0)
        {
            var jsonSelected = JSON.stringify(selected);
            data.append('selected', jsonSelected);
        }

        $.ajax({
            url: '/prospects/sendMassEmail',
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    alert('Correo masivo enviado correctamente');
                    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    location.reload();
                });
            }
        });
    });

    /* Eliminar selección de prospectos
    /* ------------------------------------------------------------------------ */
    var btnDeleteProspects = $('[data-action="deleteProspects"]');
    var urlDeleteProspects = '/prospects/deleteProspects';

    multipleSelect(btnDeleteProspects, urlDeleteProspects, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
