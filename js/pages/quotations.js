'use strict';

menuActive('sales');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Lista de ventas
    /* ------------------------------------------------------------------------ */
    var salesTable = myDocument.find("#salesTable").DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "columnDefs": [
            {
                "orderable": true,
                "targets": "_all"
            },
            {
                "className": "text-left",
                "targets": "_all"
            }
        ],
        "order": [
            [4,'desc']
        ],
        "searching": true,
        "info":     true,
        "paging":   true,
        "language": {

        }
    });

    /* Lista de items de nueva venta
    /* ------------------------------------------------------------------------ */
    var addItemsSaleTable = myDocument.find("#addItemsSaleTable").DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "columns": [
            {
                "title": "Cant."
            },
            {
                "title": "Folio"
            },
            {
                "title": "Descripción"
            },
            {
                "title": "Precio"
            },
            {
                "title": "Descuento"
            },
            {
                "title": "Total"
            },
            {
                "title": ""
            }
        ],
        "columnDefs": [
            {
                "orderable": false,
                "targets": "_all"
            },
            {
                "className": "text-left",
                "targets": "_all"
            }
        ],
        "order": [

        ],
        "searching": false,
        "info":     false,
        "paging":   false,
        "language": {

        }
    });

    /* Obtener todas las configuraciones iniciales
    /* ------------------------------------------------------------------------ */
    var mainCoin = '';
    var ivaRate = 0;
    var usdRate = 0;

    $.ajax({
        url: '/pointsale/getAllSalesSettings',
        type: 'POST',
        processData: false,
        cache: false,
        dataType: 'json',
        success: function(response)
        {
            mainCoin = response.data.main_coin;
            ivaRate = response.data.iva_rate;
            usdRate = response.data.usd_rate;
        }
    });

    /* Buscar producto o servicio para vender
    /* ------------------------------------------------------------------------ */
    var btnSearchToSell = $('[data-action="searchToSell"]');
    var frmSearchToSell = $('form[name="searchToSell"]');

    var total = 0;
    var mxnTotal = 0;
    var usdTotal = 0;

    var sales = [];
    var salesDataJason = [];

    btnSearchToSell.on('click', function()
    {
        frmSearchToSell.submit();
    });

    frmSearchToSell.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '/pointsale/searchToSell',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    var additionalDiscount = response.additionalDiscount;

                    var quantity = response.data.quantity;
                    var folio = response.data.object.folio;
                    var description = response.data.object.name;

                    if (response.data.object.coin == '1')
                        var coin = 'MXN';
                    else if (response.data.object.coin == '2')
                        var coin = 'USD';

                    var price = response.data.totals.price;

                    if (response.data.totals.discount)
                        var discount = response.data.totals.discount;
                    else
                        var discount = '$ 0 ' + coin;

                    var discountQuantity = response.data.totals.discountQuantity;
                    var discountType = response.data.totals.discountType;

                    var totalPrice = response.data.totals.total;

                    if (additionalDiscount == true)
                    {
                        $('form[name="searchToSell"]').addClass('hidden');
                        $('form[name="applyAdditionalDiscount"]').removeClass('hidden');

                        $('input[name="dQuantity"]').val(quantity);
                        $('input[name="dDescription"]').val('[' + folio + '] ' + description);
                        $('input[name="dPrice"]').val('$ ' + price + ' ' + coin);
                        $('input[name="dTotal"]').val('$ ' + totalPrice + ' ' + coin);
                        $('input[name="additionalDiscountQuantity"]').val(discountQuantity);
                        $('select[name="additionalDiscountType"]').val(discountType);

                        salesDataJason = JSON.stringify(response.data);
                    }
                    else
                    {
                        var folioRepeat = false;

                        $('#addItemsSaleTable tr').each(function()
                        {
                            if ($(this).find('td').eq(1).text() == folio)
                                folioRepeat = true;
                        });

                        if (folioRepeat == false)
                        {
                            addItemsSaleTable.row.add([
                                quantity,
                                folio,
                                description,
                                '$ ' + price + ' ' + coin,
                                discount,
                                '$ <span class="' + coin + '">' + totalPrice + '</span> ' + coin,
                                '<a data-delete="' + folio + '"><i class="material-icons">delete</i></a>'
                            ]).draw();

                            sales.push(response.data);
                        }

                        mxnTotal = 0;
                        usdTotal = 0;

                        $('.MXN').each(function()
                        {
                            mxnTotal += parseFloat($(this).html()||0,10);
                        });

                        $('.USD').each(function()
                        {
                            usdTotal += parseFloat($(this).html()||0,10);
                        });

                        $('input[name="mxnTotal"]').val('$ ' + mxnTotal + ' MXN');
                        $('input[name="usdTotal"]').val('$ ' + usdTotal + ' USD');

                        if (mainCoin == 'MXN')
                        {
                            total = (usdTotal * usdRate) + mxnTotal;
                            $('input[name="total"]').val('$ ' + total + ' MXN');
                        }
                        else if (mainCoin == 'USD')
                        {
                            total = (mxnTotal / usdRate) + usdTotal;
                            $('input[name="total"]').val('$ ' + Math.ceil(total) + ' USD');
                        }

                        $('input[name="quantity"]').val('1');
                        $('input[name="folio"]').val('');
                        $('input[name="folio"]').focus();
                    }
                });
            }
        });
    });

    /* Aplicar descuento extra
    /* ------------------------------------------------------------------------ */
    var btnApplyAdditionalDiscount = $('[data-action="applyAdditionalDiscount"]');
    var frmApplyAdditionalDiscount = $('form[name="applyAdditionalDiscount"]');

    btnApplyAdditionalDiscount.on('click', function()
    {
        frmApplyAdditionalDiscount.submit();
    });

    frmApplyAdditionalDiscount.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '/pointsale/applyAdditionalDiscount',
            type: 'POST',
            data: data + '&data=' + salesDataJason,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    var quantity = response.data.quantity;
                    var folio = response.data.object.folio;
                    var description = response.data.object.name;

                    if (response.data.object.coin == '1')
                        var coin = 'MXN';
                    else if (response.data.object.coin == '2')
                        var coin = 'USD';

                    var price = response.data.totals.price;

                    if (response.data.totals.discount)
                        var discount = response.data.totals.discount;
                    else
                        var discount = '$ 0 ' + coin;

                    var totalPrice = response.data.totals.total;
                    var folioRepeat = false;

                    $('#addItemsSaleTable tr').each(function()
                    {
                        if ($(this).find('td').eq(1).text() == folio)
                            folioRepeat = true;
                    });

                    if (folioRepeat == false)
                    {
                        addItemsSaleTable.row.add([
                            quantity,
                            folio,
                            description,
                            '$ ' + price + ' ' + coin,
                            discount,
                            '$ <span class="' + coin + '">' + totalPrice + '</span> ' + coin,
                            '<a data-delete="' + folio + '"><i class="material-icons">delete</i></a>'
                        ]).draw();

                        sales.push(response.data);
                    }

                    mxnTotal = 0;
                    usdTotal = 0;

                    $('.MXN').each(function()
                    {
                        mxnTotal += parseFloat($(this).html()||0,10);
                    });

                    $('.USD').each(function()
                    {
                        usdTotal += parseFloat($(this).html()||0,10);
                    });

                    $('input[name="mxnTotal"]').val('$ ' + mxnTotal + ' MXN');
                    $('input[name="usdTotal"]').val('$ ' + usdTotal + ' USD');

                    if (mainCoin == 'MXN')
                    {
                        total = (usdTotal * usdRate) + mxnTotal;
                        $('input[name="total"]').val('$ ' + total + ' MXN');
                    }
                    else if (mainCoin == 'USD')
                    {
                        total = (mxnTotal / usdRate) + usdTotal;
                        $('input[name="total"]').val('$ ' + Math.ceil(total) + ' USD');
                    }

                    $('form[name="searchToSell"]').removeClass('hidden');
                    $('form[name="searchToSell"]')[0].reset();

                    $('form[name="applyAdditionalDiscount"]').addClass('hidden');
                    $('form[name="applyAdditionalDiscount"]')[0].reset();
                });
            }
        });
    });

    /* Cancelar descuento extra
    /* ------------------------------------------------------------------------ */
    var btnCancelApplyAdditionalDiscount = $('[data-action="cancelApplyAdditionalDiscount"]');

    btnCancelApplyAdditionalDiscount.on('click', function(e)
    {
        $('form[name="searchToSell"]').removeClass('hidden');
        $('input[name="quantity"]').parent().parent().removeClass('hidden');
        $('input[name="folio"]').parent().parent().attr('class', 'input-group span10');
        document.getElementById('additionalDiscount').checked = false;

        $('form[name="applyAdditionalDiscount"]').addClass('hidden');
        $('form[name="applyAdditionalDiscount"]')[0].reset();

    });

    /* Eliminar servicio o producto de la lista de ventas
    /* ------------------------------------------------------------------------ */
    $(document).on('click', '[data-delete]', function ()
    {
        var self = $(this);
        var folio = self.data('delete');
        var row_table = self.parents('tr');

        if (confirm("¿Esta seguro que desea eliminar este elemento?") == true)
        {
            addItemsSaleTable.$('tr.selected').removeClass('selected');
            row_table.addClass('selected');
            addItemsSaleTable.row('.selected').remove().draw(false);

            sales = jQuery.grep(sales, function (data, key)
            {
                if (data['object']['folio'] != folio)
                    return data;
            });

            mxnTotal = 0;
            usdTotal = 0;

            $('.MXN').each(function()
            {
                mxnTotal += parseFloat($(this).html()||0,10);
            });

            $('.USD').each(function()
            {
                usdTotal += parseFloat($(this).html()||0,10);
            });

            $('input[name="mxnTotal"]').val('$ ' + mxnTotal + ' MXN');
            $('input[name="usdTotal"]').val('$ ' + usdTotal + ' USD');

            if (mainCoin == 'MXN')
            {
                total = (usdTotal * usdRate) + mxnTotal;
                $('input[name="total"]').val('$ ' + total + ' MXN');
            }
            else if (mainCoin == 'USD')
            {
                total = (mxnTotal / usdRate) + usdTotal;
                $('input[name="total"]').val('$ ' + Math.ceil(total) + ' USD');
            }

            $('input[name="quantity"]').val('1');
            $('input[name="folio"]').val('');
            $('input[name="folio"]').focus();
        }
    });

    /* Seleccionar tipo de pago
    /* ------------------------------------------------------------------------ */
    var sltPayment = $('select[name="payment"]');

    sltPayment.on('change', function()
    {
        if (sltPayment.val() == 'other')
        {
            $('input[name="num_deferred_payments"]').val('2');
            $('input[name="num_deferred_payments"]').parent().parent().addClass('hidden');

            $('#deferred_payments').html('');
            $('#deferred_payments').removeClass('hidden');
        }
        else if (sltPayment.val() == 'deferred')
        {
            if ($('input[name="num_deferred_payments"]').val() < 2)
                var num_deferred_payments = 2;
            else
                var num_deferred_payments = $('input[name="num_deferred_payments"]').val();

            $('input[name="num_deferred_payments"]').val(num_deferred_payments);
            $('input[name="num_deferred_payments"]').parent().parent().removeClass('hidden');

            for (var i = 1; i <= num_deferred_payments; i++)
            {
                $('#deferred_payments').append('<fieldset class="input-group">'
                    + '<label data-important>'
                    + '<span>Pago ' + i + '</span>'
                    + '<input type="number" name="deferred_payment_' + i + '" value="' + (total / num_deferred_payments) + '" />'
                    + '</label>'
                    + '</fieldset>');
            }

            $('#deferred_payments').removeClass('hidden');
        }
    });

    $('input[name="num_deferred_payments"]').on('change', function()
    {
        if ($('input[name="num_deferred_payments"]').val() >= 2)
        {
            var num_deferred_payments = $('input[name="num_deferred_payments"]').val();

            $('#deferred_payments').html('');

            for (var i = 1; i <= num_deferred_payments; i++)
            {
                $('#deferred_payments').append('<fieldset class="input-group">'
                    + '<label data-important>'
                    + '<span>Pago ' + i + '</span>'
                    + '<input type="number" name="deferred_payment_' + i + '" value="' + (total / num_deferred_payments) + '" />'
                    + '</label>'
                    + '</fieldset>');
            }
        }
        else
        {
            alert('No pueden haber pagos diferidos menor a 2');
            $('input[name="num_deferred_payments"]').val('2')
        }
    });

    /* Hacer el pago de la venta
    /* ------------------------------------------------------------------------ */
    var btnMakePayment = $('[data-action="makePayment"]');
    var frmMakePayment = $('form[name="makePayment"]');

    btnMakePayment.on('click', function()
    {
        frmMakePayment.submit();
    });

    frmMakePayment.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        var salesJson = JSON.stringify(sales);

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&total=' + total + '&mxnTotal=' + mxnTotal + '&usdTotal=' + usdTotal + '&sales=' + salesJson,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    alert('El correo se envió correctamente');
                    location.reload();
                });
            }
        });
    });

    /* Lista de productos y servicios
    /* ------------------------------------------------------------------------ */
    var tblProductsAndServices = myDocument.find("#tblProductsAndServices").DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "columnDefs": [
            {
                "orderable": true,
                "targets": "_all"
            },
            {
                "className": "text-left",
                "targets": "_all"
            }
        ],
        "order": [
            [1, 'asc']
        ],
        "searching": true,
        "info":     false,
        "paging":   true,
        "language": {

        }
    });

    /* Cargar folio para búsqueda
    /* ------------------------------------------------------------------------ */
    var btnLoadFolioToSearch = $('[data-action="loadFolioToSearch"]');

    btnLoadFolioToSearch.on('click', function()
    {
        var folio = $(this).data('folio');
        $('input[name="folio"]').val(folio);
        $('[data-modal="productsAndServices"]').removeClass('view');
        $('body').removeClass('noscroll');
    });


    /* Lista de productos y servicios
    /* ------------------------------------------------------------------------ */
    modal('productsAndServices', function(modal)
    {

    }, function(modal)
    {

    });

    /* Lista de items de venta
    /* ------------------------------------------------------------------------ */
    var itemsSaleTable = myDocument.find("#itemsSaleTable").DataTable({
        dom: 'Bfrtip',
        buttons: [

        ],
        "columnDefs": [
            {
                "orderable": false,
                "targets": "_all"
            },
            {
                "className": "text-left",
                "targets": "_all"
            }
        ],
        "order": [
            [1, 'desc']
        ],
        "searching": false,
        "info":     false,
        "paging":   false,
        "language": {

        }
    });

    $('[data-action="resend_email"]').on('click', function()
    {
        $.ajax({
            url: '',
            type: 'POST',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
                    alert('El correo se envió correctamente');
                    location.reload();
                }
            }
        });
    });
});
