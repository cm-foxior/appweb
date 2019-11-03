'use strict';

menuActive('reports');

$( document ).ready(function ()
{
    var MyDocument = $(this);

    $('#etyp').on('change', function()
    {
        window.location.href = '/reports/inventories/' + $('select[name="type"]').val()
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
                "title": "Entradas",
                "width": "100px"
            },
            {
                "title": "Salidas",
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

        ],
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    $('#einv').on('change', function()
    {
        $('form[name="existence"]').submit();
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

    var tblHistorical = MyDocument.find("#historical").DataTable({
        dom: "Bfrtip",
        buttons: [
            "pdf"
        ],
        "columns": [
            {
                "title": "Producto"
            },
            {
                "title": "Cantidad",
                "width": "100px"
            },
            {
                "title": "Fecha"
            },
            {
                "title": "Proveedor"
            },
            {
                "title": "Tipo"
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

        ],
        "searching": true,
        "info": false,
        "paging": true,
        "language": {

        }
    });

    $('#hda1').on('change', function()
    {
        $('form[name="historical"]').submit();
    });

    $('#hda2').on('change', function()
    {
        $('form[name="historical"]').submit();
    });

    $('#hbra').on('change', function()
    {
        $.ajax({
            url: '',
            type: 'POST',
            data: 'branch=' + $(this).val() + '&action=inventories',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                tblHistorical.clear().draw();

                $('#hinv').empty().trigger('chosen:updated');

                if (response.data.length > 0)
                {
                    $.each(response.data, function (key, value)
                    {
                        $('#hinv').append('<option value="' + value.id_inventory + '">' + value.name + ' (' + value.type + ') Suc. ' + value.branch + '</option>').trigger('chosen:updated');
                    });

                    $('form[name="historical"]').submit();
                }
            }
        });
    });

    $('#hinv').on('change', function()
    {
        $('form[name="historical"]').submit();
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
                            value.product,
                            value.quantify,
                            value.date,
                            value.provider,
                            value.type,
                            value.movement,
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
