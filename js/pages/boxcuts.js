'use strict';

menuActive('sales');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de cortes de caja
    /* ------------------------------------------------------------------------ */
    var tblBoxCuts = myDocument.find("#tblBoxCuts").DataTable({
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
            [1, 'DESC']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Buscar totales para nuevo corte de caja
    /* ------------------------------------------------------------------------ */
    var btnSearchTotals = $('[data-action="searchTotals"]');
    var frmSearchTotals = $('form[name="searchTotals"]');

    btnSearchTotals.on('click', function()
    {
        frmSearchTotals.submit();
    });

    frmSearchTotals.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '/boxcuts/searchTotals',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                $('[data-modal="searchTotals"]').removeClass('view');

                checkValidateFormAjax(self, response, function()
                {

                });
            }
        });
    });

    /* Tabla de gastos adicionales
    /* ------------------------------------------------------------------------ */
    var tblExpenses = myDocument.find("#tblExpenses").DataTable({
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
            [0, 'desc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Obtener gasto para editar
    /* ------------------------------------------------------------------------ */
    var idExpense;
    var btnGetExpenseToEdit = $('[data-action="getExpenseToEdit"]');

    btnGetExpenseToEdit.on('click', function()
    {
        idExpense = $(this).data('id');

        $.ajax({
            url: '/boxcuts/getExpenseToEdit/' + idExpense,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="dateTime"]').val(response.data.date_time);
                    $('input[name="total"]').val(response.data.total);
                    $('textarea[name="description"]').val(response.data.description);
                    $('select[name="branchOffice"]').val(response.data.id_branch_office);

                    $('[data-modal="expenses"] header > h6').html('Editar gasto');
                    $('[data-modal="expenses"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="expenses"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar gastos adicionales
    /* ------------------------------------------------------------------------ */
    var frmExpenses = $('form[name="expenses"]');

    modal('expenses', function(modal)
    {
        modal.find('header > h6').html('Nuevo gasto');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmExpenses.submit();
    });

    frmExpenses.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&idExpense=' + idExpense,
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
});
