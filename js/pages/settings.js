'use strict';

menuActive('settings');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Obtener configuraciones del negocio para editar
    /* ------------------------------------------------------------------------ */
    var btnGetBusinessSettingsToEdit = $('[data-action="getBusinessSettingsToEdit"]');

    btnGetBusinessSettingsToEdit.on('click', function()
    {
        $.ajax({
            url: '/settings/getBusinessSettingsToEdit',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);

                    if (response.data.website != null)
                        $('input[name="website"]').val(response.data.website);

                    if (response.data.logotype != null)
                        $('div[image-preview="image-preview"]').attr('style', 'background-image: url(/images/logotypes/' + response.data.logotype + ')');

                    $('[data-modal="editBusinessSettings"]').toggleClass('view');
                }
            }
        });
    });

    /* Editar configuraciones del negocio
    /* ------------------------------------------------------------------------ */
    var frmEditBusinessSettings = $('form[name="editBusinessSettings"]');

    modal('editBusinessSettings', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmEditBusinessSettings.submit();
    });

    frmEditBusinessSettings.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = new FormData(this);

        $.ajax({
            url: '',
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

    /* Obtener configuraciones de ventas para editar
    /* ------------------------------------------------------------------------ */
    var btnGetSalesSettingsToEdit = $('[data-action="getSalesSettingsToEdit"]');

    btnGetSalesSettingsToEdit.on('click', function()
    {
        $.ajax({
            url: '/settings/getSalesSettingsToEdit',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('select[name="mainCoin"]').val(response.data.main_coin);
                    $('select[name="saleTicketPrint"]').val(response.data.sale_ticket_print);
                    $('select[name="saleTicketTotalsBreakdown"]').val(response.data.sale_ticket_totals_breakdown);
                    $('select[name="applyDiscounds"]').val(response.data.apply_discounds);
                    $('select[name="deferred_payments"]').val(response.data.deferred_payments);
                    $('select[name="sync_point_sale_with_inventories"]').val(response.data.sync_point_sale_with_inventories);
                    $('select[name="sync_quotations_with_inventories"]').val(response.data.sync_quotations_with_inventories);
                    $('input[name="ivaRate"]').val(response.data.iva_rate);
                    $('input[name="usdRate"]').val(response.data.usd_rate);
                    $('textarea[name="saleTicketLegend"]').val(response.data.sale_ticket_legend);

                    $('[data-modal="editSalesSettings"]').toggleClass('view');
                }
            }
        });
    });

    /* Editar configuraciones de ventas
    /* ------------------------------------------------------------------------ */
    var frmEditSalesSettings = $('form[name="editSalesSettings"]');

    modal('editSalesSettings', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmEditSalesSettings.submit();
    });

    frmEditSalesSettings.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data,
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

    /* Tabla de configuraciones PDIS
    /* ------------------------------------------------------------------------ */
    var tblPdisSettings = myDocument.find("#tblPdisSettings").DataTable({
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
            [0,'asc']
        ],
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    /* Actualizar configuraciones pdis
    /* ------------------------------------------------------------------------ */
    var btnUpdatePdisSettings = $('[data-action="updatePdisSettings"]');

    modal('updatePdisSettings', function(modal)
    {
        modal.find('main').addClass('hidden');
        modal.find('main > p').html('');
        modal.find('footer > a[button-cancel]').html('Cancelar');
        modal.find('footer > a[data-action="updatePdisSettings"]').removeClass('hidden');

    }, function(modal)
    {

    });

    btnUpdatePdisSettings.on('click', function()
    {
        $.ajax({
            url: '/settings/updatePdisSettings',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    location.reload();
                }
                else if (response.status == 'success_same')
                {
                    $('[data-modal="updatePdisSettings"] main').removeClass('hidden');
                    $('[data-modal="updatePdisSettings"] main > p').html('No hay actualizaciones disponibles');
                    $('[data-modal="updatePdisSettings"] footer > a[button-cancel]').html('Aceptar');
                    $('[data-modal="updatePdisSettings"] footer > a[data-action="updatePdisSettings"]').addClass('hidden');
                }
            }
        });
    });
});
