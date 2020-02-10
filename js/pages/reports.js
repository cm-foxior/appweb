'use strict';

menuActive('reports');

$( document ).ready(function ()
{
    var MyDocument = $(this);

    $('[name="report"]').on('change', function()
    {
        window.location.href = '/reports/inventories/' + $(this).val()
    });

    var tblHistorical = MyDocument.find("#historical").DataTable({
        dom: "Bfrtip",
        buttons: [
            "pdf"
        ],
        "columns": [
            {
                "title": "Fecha",
                "width": "170px"
            },
            {
                "title": "Producto"
            },
            {
                "title": "Cantidad",
                "width": "100px"
            },
            {
                "title": "Proveedor"
            },
            {
                "title": "Tipo",
                "width": "120px"
            },
            {
                "title": "Movimiento",
                "width": "100px"
            },
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
            [0,'desc']
        ],
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    $('form[name="historical"]').find('select[name="type"]').on('change', function()
    {
        if ($(this).val() == 'historical')
            $('form[name="historical"]').find('select[name="movement"]').html('<option value="">Todo</option>');
        else if ($(this).val() == 'inputs')
        {
            $('form[name="historical"]').find('select[name="movement"]').html(
                '<option value="">Todo</option>' +
                '<option value="1">Compra</option>' +
                '<option value="2">Transferencia</option>' +
                '<option value="3">Devolución de venta</option>' +
                '<option value="4">Devolución de préstamo</option>'
            );
        }
        else if ($(this).val() == 'outputs')
        {
            $('form[name="historical"]').find('select[name="movement"]').html(
                '<option value="">Todo</option>' +
                '<option value="1">Transferencia</option>' +
                '<option value="2">Merma / Pérdida</option>' +
                '<option value="3">Devolución a proveedor</option>' +
                '<option value="4">Venta</option>' +
                '<option value="6">Préstamo</option>'
            );
        }
    });

    $('form[name="historical"]').on('submit', function(e)
    {
        e.preventDefault();

        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize() + '&action=report',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                tblHistorical.clear().draw();

                if (response.data.length > 0)
                {
                    $.each(response.data, function (key, value)
                    {
                        tblHistorical.row.add([
                            value.date,
                            value.product,
                            value.quantify,
                            value.provider,
                            value.type,
                            value.movement,
                        ]).draw();
                    });
                }
            }
        });
    });

    var tblExistence = MyDocument.find("#existence").DataTable({
        dom: "Bfrtip",
        buttons: [
            "pdf"
        ],
        "columns": [
            {
                "title": "Producto"
            },
            {
                "title": "Entrada",
                "width": "100px"
            },
            {
                "title": "Salida real",
                "width": "100px"
            },
            {
                "title": "Salida ligada",
                "width": "100px"
            },
            {
                "title": "Existencia",
                "width": "100px"
            },
            {
                "title": "Mínimo",
                "width": "100px"
            },
            {
                "title": "Máximo",
                "width": "100px"
            },
            {
                "title": "Estado",
                "width": "100px"
            },
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
            [0,'asc']
        ],
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    $('form[name="existence"]').find('select[name="search"]').on('change', function()
    {
        if ($(this).val() == 'dates_range')
            $('form[name="existence"]').find('input[name="date_start"]').removeAttr('disabled');
        else if ($(this).val() == 'total')
            $('form[name="existence"]').find('input[name="date_start"]').attr('disabled', true);
    });

    $('form[name="existence"]').on('submit', function(e)
    {
        e.preventDefault();

        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize(),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                tblExistence.clear().draw();

                if (response.data.length > 0)
                {
                    $.each(response.data, function (key, value)
                    {
                        tblExistence.row.add([
                            value.product,
                            value.inputs,
                            value.outputs,
                            value.flirts,
                            value.existence,
                            value.min,
                            value.max,
                            value.status,
                        ]).draw();
                    });
                }
            }
        });
    });

    var tblSales = MyDocument.find("#sales").DataTable({
        dom: "Bfrtip",
        buttons: [
            "pdf"
        ],
        "columns": [
            {
                "title": "folio",
                "width": "100px"
            },
            {
                "title": "total",
                "width": "100px"
            },
            {
                "title": "Pago",
                "width": "100px"
            },
            {
                "title": "Fecha",
                "width": "200px"
            },
            {
                "title": "Vendedor",
                "width": "200px"
            },
            {
                "title": "Ventas"
            },
            {
                "title": "Estado",
                "width": "100px"
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
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    $('#sda1').on('change', function()
    {
        $('form[name="sales"]').submit();
    });

    $('#sda2').on('change', function()
    {
        $('form[name="sales"]').submit();
    });

    $('#sbra').on('change', function()
    {
        $.ajax({
            url: '',
            type: 'POST',
            data: 'branch=' + $(this).val() + '&action=sellers',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                tblSales.clear().draw();

                $('#ssel').empty().trigger('chosen:updated');
                $('#ssel').append('<option value="all">Todos</option>').trigger('chosen:updated');

                if (response.data.length > 0)
                {
                    $.each(response.data, function (key, value)
                    {
                        $('#ssel').append('<option value="' + value.id_user + '">' + value.name + '</option>').trigger('chosen:updated');
                    });

                    $('form[name="sales"]').submit();
                }
            }
        });
    });

    $('#ssel').on('change', function()
    {
        $('form[name="sales"]').submit();
    });

    $('form[name="sales"]').on('submit', function(e)
    {
        e.preventDefault();

        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize() + '&action=report',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                tblSales.clear().draw();

                if (response.data.length > 0)
                {
                    $.each(response.data[0], function (key, value)
                    {
                        tblSales.row.add([
                            value.folio,
                            value.total,
                            value.payment,
                            value.date,
                            value.seller,
                            value.sales,
                            value.status,
                        ]).draw();
                    });

                    tblSales.row.add([
                        '',
                        '<strong>$ ' + response.data[1] + ' MXN</strong>',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]).draw();
                }
            }
        });
    });
});
