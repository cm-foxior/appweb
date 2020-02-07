'use strict';

menuActive('inventories');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de inventarios
    /* ------------------------------------------------------------------------ */
    var tableOrder = myDocument.find('#inventoriesTable').data('table-order');

    var inventoriesTable = myDocument.find('#inventoriesTable').DataTable({
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
            [tableOrder,'desc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Obtener inventario para editar
    /* ------------------------------------------------------------------------ */
    var idInventory;

    $(document).on('click', '[data-action="getInventoryToEdit"]', function()
    {
        idInventory = $(this).data('id');

        $.ajax({
            url: '/inventories/getInventoryToEdit/' + idInventory,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('select[name="type"]').val(response.data.type);
                    $('select[name="branchOffice"]').val(response.data.id_branch_office).trigger('chosen:updated');

                    $('[data-modal="inventories"] header > h6').html('Editar inventario');
                    $('[data-modal="inventories"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="inventories"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar inventario
    /* ------------------------------------------------------------------------ */
    var frmInventories = $('form[name="inventories"]');

    modal('inventories', function(modal)
    {
        modal.find('header > h6').html('Nuevo inventario');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmInventories.submit();
    });

    frmInventories.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idInventory,
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

    /* Activar selección de inventarios
    /* ------------------------------------------------------------------------ */
    var btnActivateInventories = $('[data-action="activateInventories"]');
    var urlActivateInventories = '/inventories/changeStatusInventories/activate';

    multipleSelect(btnActivateInventories, urlActivateInventories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección de inventarios
    /* ------------------------------------------------------------------------ */
    var btnDeactivateInventories = $('[data-action="deactivateInventories"]');
    var urlDeactivateInventories = '/inventories/changeStatusInventories/deactivate';

    multipleSelect(btnDeactivateInventories, urlDeactivateInventories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección de inventarios
    /* ------------------------------------------------------------------------ */
    var btnDeleteInventories = $('[data-action="deleteInventories"]');
    var urlDeleteInventories = '/inventories/deleteInventories';

    multipleSelect(btnDeleteInventories, urlDeleteInventories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Tabla de entradas al inventario
    /* ------------------------------------------------------------------------ */
    var inputsTable = myDocument.find('#inputsTable').DataTable({
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
            [3,'desc']
        ],
        "language": {

        }
    });

    /* Obtener entrada al inventario para editar
    /* ------------------------------------------------------------------------ */
    var idInput;

    $(document).on('click', '[data-action="getInputToEdit"]', function()
    {
        idInput = $(this).data('id');

        $.ajax({
            url: '/inventories/getInputToEdit/' + idInput,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('select[name="product"]').val(response.data.id_product).trigger('chosen:updated');
                    $('input[name="quantify"]').val(response.data.quantify);
                    $('select[name="type"]').val(response.data.type);
                    $('input[name="price"]').val(response.data.price);
                    $('input[name="date"]').val(response.data.input_date_time[0]);
                    $('input[name="hour"]').val(response.data.input_date_time[1]);
                    $('select[name="provider"]').val(response.data.id_provider).trigger('chosen:updated');

                    $('[data-modal="inputs"] header > h6').html('Editar entrada');
                    $('[data-modal="inputs"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="inputs"]').toggleClass('view');
                }
            }
        });
    });

    /* Obtener información de transferencia de entrada
    /* ------------------------------------------------------------------------ */
    $(document).on('click', '[data-action="getInputTransferInfo"]', function()
    {
        idInput = $(this).data('id');

        $.ajax({
            url: '/inventories/getInputTransferInfo/' + idInput,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('#transferInfoInventory').val(response.data.inventory);
                    $('#transferInfoBranchOffice').val(response.data.branchOffice);

                    $('[data-modal="transferInfo"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar entrada al inventario
    /* ------------------------------------------------------------------------ */
    var frmInputs = $('form[name="inputs"]');

    modal('inputs', function(modal)
    {
        modal.find('header > h6').html('Nuevo entrada');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmInputs.submit();
    });

    frmInputs.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idInput,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    alert('Listo');
                    // $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    // location.reload();
                });
            }
        });
    });

    /* Tabla de salidas del inventario
    /* ------------------------------------------------------------------------ */
    var outputsTable = myDocument.find('#outputsTable').DataTable({
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
            [2,'desc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Obtener salida del inventario para editar
    /* ------------------------------------------------------------------------ */
    var idOutput;

    $(document).on('click', '[data-action="getOutputToEdit"]', function()
    {
        idOutput = $(this).data('id');

        $.ajax({
            url: '/inventories/getOutputToEdit/' + idOutput,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('select[name="product"]').val(response.data.id_product).trigger('chosen:updated');
                    $('input[name="quantity"]').val(response.data.quantity);
                    $('select[name="type"]').val(response.data.type);

                    $('[data-modal="outputs"] header > h6').html('Editar salida');
                    $('[data-modal="outputs"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="outputs"]').toggleClass('view');
                }
            }
        });
    });

    /* Obtener información de transferencia de salida
    /* ------------------------------------------------------------------------ */
    $(document).on('click', '[data-action="getOutputTransferInfo"]', function()
    {
        idOutput = $(this).data('id');

        $.ajax({
            url: '/inventories/getOutputTransferInfo/' + idOutput,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('#transferInfoInventory').val(response.data.inventory);
                    $('#transferInfoBranchOffice').val(response.data.branchOffice);

                    $('[data-modal="transferInfo"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar salida del inventario
    /* ------------------------------------------------------------------------ */
    var frmOutputs = $('form[name="outputs"]');

    modal('outputs', function(modal)
    {
        modal.find('header > h6').html('Nueva salida');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmOutputs.submit();
    });

    frmOutputs.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idOutput,
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

    /* seleccionar tipo de Transferencia
    /* ------------------------------------------------------------------------ */
    var filter;
    var idBranchOffice;

    var sltTypeTransfer     = $('select[name="typeTransfer"]');
    var sltFilterTransfer   = $('select[name="filterTransfer"]');
    var sltFilterInventory  = $('select[name="filterInventory"]');

    sltTypeTransfer.on('change', function()
    {
        filter      = $(this).val();
        idInventory = $(this).data('id');

        if (filter == '1' || filter == '2')
        {
            $.ajax({
                url: '/inventories/getFilterTransfer',
                type: 'POST',
                data: 'filter=' + filter + '&id=' + idInventory,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function (response)
                {
                    if (response.status == 'success')
                    {
                        var options = '<option value="">Seleccione una opción</option>';

                        $.each(response.data, function (i, ob)
                        {
                            if (filter == '1')
                                var obId = ob.id_inventory;
                            else if (filter == '2')
                                var obId = ob.id_branch_office;

                            options += '<option value="'+ obId +'">'+ ob.name +'</option>';
                        });

                        sltFilterTransfer.html('');
                        sltFilterTransfer.append(options).trigger('chosen:updated');

                        if (filter == '1')
                            sltFilterTransfer.parent().find('span').html('Inventario destino');
                        else if (filter == '2')
                            sltFilterTransfer.parent().find('span').html('Sucursal destino');

                        sltFilterTransfer.parent().parent().removeClass('hidden');

                        sltFilterInventory.html('');
                        sltFilterInventory.parent().parent().addClass('hidden');

                        $('select[name="product"]').val('').trigger('chosen:updated');
                        $('select[name="product"]').parent().parent().addClass('hidden');

                        $('input[name="quantity"]').val('');
                        $('input[name="quantity"]').parent().parent().addClass('hidden');
                    }
                }
            });
        }
        else
        {
            sltFilterTransfer.html('');
            sltFilterTransfer.parent().find('span').html('');
            sltFilterTransfer.parent().parent().addClass('hidden');

            sltFilterInventory.html('');
            sltFilterInventory.parent().parent().addClass('hidden');

            $('select[name="product"]').val('').trigger('chosen:updated');
            $('select[name="product"]').parent().parent().addClass('hidden');

            $('input[name="quantity"]').val('');
            $('input[name="quantity"]').parent().parent().addClass('hidden');
        }
    });

    sltFilterTransfer.on('change', function()
    {
        idBranchOffice = $(this).val();

        if (idBranchOffice != '')
        {
            if (filter == '1')
            {
                sltFilterInventory.html('');
                sltFilterInventory.parent().parent().addClass('hidden');

                $('select[name="product"]').val('').trigger('chosen:updated');
                $('select[name="product"]').parent().parent().removeClass('hidden');

                $('input[name="quantity"]').val('');
                $('input[name="quantity"]').parent().parent().removeClass('hidden');
            }
            else if (filter == '2')
            {
                $.ajax({
                    url: '/inventories/getFilterInventories',
                    type: 'POST',
                    data: 'idBranchOffice=' + idBranchOffice + '&idInventory=' + idInventory,
                    processData: false,
                    cache: false,
                    dataType: 'json',
                    success: function (response)
                    {
                        if (response.status == 'success')
                        {
                            var options = '<option value="">Seleccione una opción</option>';

                            $.each(response.data, function (i, ob)
                            {
                                options += '<option value="'+ ob.id_inventory +'">'+ ob.name +'</option>';
                            });

                            sltFilterInventory.html('');
                            sltFilterInventory.append(options).trigger('chosen:updated');
                            sltFilterInventory.parent().parent().removeClass('hidden');

                            $('select[name="product"]').val('').trigger('chosen:updated');
                            $('select[name="product"]').parent().parent().addClass('hidden');

                            $('input[name="quantity"]').val('');
                            $('input[name="quantity"]').parent().parent().addClass('hidden');
                        }
                    }
                });
            }
        }
        else
        {
            sltFilterInventory.html('');
            sltFilterInventory.parent().parent().addClass('hidden');

            $('select[name="product"]').val('').trigger('chosen:updated');
            $('select[name="product"]').parent().parent().addClass('hidden');

            $('input[name="quantity"]').val('');
            $('input[name="quantity"]').parent().parent().addClass('hidden');
        }
    });

    sltFilterInventory.on('change', function()
    {
        var val = $(this).val();

        if (val != '')
        {
            $('select[name="product"]').val('').trigger('chosen:updated');
            $('select[name="product"]').parent().parent().removeClass('hidden');

            $('input[name="quantity"]').val('');
            $('input[name="quantity"]').parent().parent().removeClass('hidden');
        }
        else
        {
            $('select[name="product"]').val('').trigger('chosen:updated');
            $('select[name="product"]').parent().parent().addClass('hidden');

            $('input[name="quantity"]').val('');
            $('input[name="quantity"]').parent().parent().addClass('hidden');
        }
    });

    /* Nueva transferencia de producto
    /* ------------------------------------------------------------------------ */
    var frmTransferProduct = $('form[name="transferProduct"]');

    modal('transferProduct', function(modal)
    {
        sltTypeTransfer.val('');

        sltFilterTransfer.html('');
        sltFilterTransfer.parent().find('span').html('');
        sltFilterTransfer.parent().parent().addClass('hidden');

        sltFilterInventory.html('');
        sltFilterInventory.parent().parent().addClass('hidden');

        $('select[name="product"]').val('').trigger('chosen:updated');
        $('select[name="product"]').parent().parent().addClass('hidden');

        $('input[name="quantity"]').val('');
        $('input[name="quantity"]').parent().parent().addClass('hidden');

        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmTransferProduct.submit();
    });

    frmTransferProduct.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var data    = self.serialize();
        idInventory = self.data('id');

        $.ajax({
            url: '/inventories/transferProduct',
            type: 'POST',
            data: data + '&idInventory=' + idInventory,
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

    /* Tabla de stocks del inventario
    /* ------------------------------------------------------------------------ */
    var stocksTable = myDocument.find("#stocksTable").DataTable({
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
            [1, 'asc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Obtener stock para editar
    /* ------------------------------------------------------------------------ */
    var idStock;

    $(document).on('click', '[data-action="getStockToEdit"]', function()
    {
        idStock = $(this).data('id');

        $.ajax({
            url: '/inventories/getStockToEdit/' + idStock,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="min"]').val(response.data.min);
                    $('input[name="max"]').val(response.data.max);
                    $('select[name="product"]').val(response.data.id_product).trigger('chosen:updated');

                    $('[data-modal="stocks"] header > h6').html('Editar stock');
                    $('[data-modal="stocks"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="stocks"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar stock
    /* ------------------------------------------------------------------------ */
    var frmStocks = $('form[name="stocks"]');

    modal('stocks', function(modal)
    {
        modal.find('header > h6').html('Nuevo stock');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmStocks.submit();
    });

    frmStocks.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idStock,
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

    /* Eliminar selección de stocks
    /* ------------------------------------------------------------------------ */
    var btnDeleteStocks = $('[data-action="deleteStocks"]');
    var urlDeleteStocks = '/inventories/deleteStocks';

    multipleSelect(btnDeleteStocks, urlDeleteStocks, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /*
    /* ------------------------------------------------------------------------ */
    var tblLoans = myDocument.find('#tblLoans').DataTable({
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
            [2,'desc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /*
    /* ------------------------------------------------------------------------ */
    $('input[name="setDateTime"]').on('change', function()
    {
        if ($('input[name="setDateTime"]').is(':checked') == true)
        {
            $('input[name="date"]').attr('disabled', false);
            $('input[name="time"]').attr('disabled', false);
        }
        else
        {
            var today = new Date();

            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yy = today.getFullYear();
            var hh = today.getHours();
            var mn = today.getMinutes();
            var ss = today.getSeconds();

            if (dd < 10)
                dd = '0' + dd;

            if (mm < 10)
                mm = '0' + mm;

            if (hh < 10)
                hh = '0' + hh;

            if (mn < 10)
                mn = '0' + mn;

            if (ss < 10)
                ss = '0' + ss;

            var date = yy + '-' + mm + '-' + dd;
            var time = hh + ':' + mn + ':' + ss;

            setTimeout(time, 1000);

            $('input[name="date"]').val(date);
            $('input[name="date"]').attr('disabled', true);

            $('input[name="time"]').val(time);
            $('input[name="time"]').attr('disabled', true);
        }
    });

    /*
    /* ------------------------------------------------------------------------ */
    var frmLoans = $('form[name="loans"]');

    modal('loans', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmLoans.submit();
    });

    frmLoans.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=open',
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

    /*
    /* ------------------------------------------------------------------------ */
    var idLoan;

    $('[data-button-modal="closeLoan"]').on('click', function()
    {
        idLoan = $(this).data('id');
    });

    $('[data-action="closeLoan"]').on('click', function()
    {
        $.ajax({
            url: '',
            type: 'POST',
            data: 'id=' + idLoan + '&action=close',
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
            }
        });
    });
});
