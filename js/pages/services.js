'use strict';

menuActive('catalogs');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de servicios
    /* ------------------------------------------------------------------------ */
    var tableOrder = myDocument.find("#tblServices").data('table-order');

    var tblServices = myDocument.find("#tblServices").DataTable({
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
            [tableOrder,'asc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Generar folio de servicio aleatorio
    /* ------------------------------------------------------------------------ */
    var cbxRandomFolio = $('[data-action="randomFolio"]');

    cbxRandomFolio.on('change', function()
    {
        if (cbxRandomFolio.is(':checked') == true)
        {
            var random  = '';
            var chars   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

            for (var x = 0; x < 6; x++)
            {
                var math = Math.floor(Math.random() * chars.length);
                random += chars.substr(math, 1);
            }

            $('input[name="folio"]').val(random);
        }
        else
            $('input[name="folio"]').val('');
    });

    /* Seleccionar tipo de descuento
    /* ------------------------------------------------------------------------ */
    var sltDiscountType = $('select[name="discountType"]');

    sltDiscountType.on('change', function()
    {
        if (sltDiscountType.val() == '1' || sltDiscountType.val() == '2')
        {
            $('input[name="discountQuantity"]').val('');
            document.getElementById('discountQuantity').disabled = false;
        }
        else
        {
            $('input[name="discountQuantity"]').val('');
            document.getElementById('discountQuantity').disabled = true;
        }
    });

    /* Obtener servicio para editar
    /* ------------------------------------------------------------------------ */
    var idService;

    $(document).on('click', '[data-action="getServiceToEdit"]', function()
    {
        idService = $(this).data('id');

        $.ajax({
            url: '/services/getServiceToEdit/' + idService,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('input[name="folio"]').val(response.data.folio);

                    if (response.data.components == null)
                        $('select[name="type"]').val('1');
                    else
                        $('select[name="type"]').val('2');

                    $('input[name="price"]').val(response.data.price);

                    if (response.data.discount != null)
                    {
                        var discount = eval('(' + response.data.discount + ')');

                        $('input[name="discountQuantity"]').val(discount.quantity);
                        $('select[name="discountType"]').val(discount.type);
                    }

                    $('select[name="coin"]').val(response.data.coin);
                    $('select[name="warranty"]').val(response.data.id_warranty).trigger('chosen:updated');
                    $('select[name="category"]').val(response.data.id_product_category).trigger('chosen:updated');
                    $('textarea[name="observations"]').val(response.data.observations);

                    $('[data-modal="services"] header > h6').html('Editar servicio');
                    $('[data-modal="services"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="services"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar servicio
    /* ------------------------------------------------------------------------ */
    var frmServices = $('form[name="services"]');

    modal('services', function(modal)
    {
        modal.find('header > h6').html('Nuevo servicio');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmServices.submit();
    });

    frmServices.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idService,
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

    /* Activar selección multiple de servicios
    /* ------------------------------------------------------------------------ */
    var btnActivateServices = $('[data-action="activateServices"]');
    var urlActivateServices = '/services/changeStatusServices/activate';

    multipleSelect(btnActivateServices, urlActivateServices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección multiple de servicios
    /* ------------------------------------------------------------------------ */
    var btnDeactivateServices = $('[data-action="deactivateServices"]');
    var urlDeactivateServices = '/services/changeStatusServices/deactivate';

    multipleSelect(btnDeactivateServices, urlDeactivateServices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección multiple de servicios
    /* ------------------------------------------------------------------------ */
    var btnDeleteServices = $('[data-action="deleteServices"]');
    var urlDeleteServices = '/services/deleteServices';

    multipleSelect(btnDeleteServices, urlDeleteServices, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Tabla de componentes
    /* ------------------------------------------------------------------------ */
    var tblComponents = myDocument.find("#tblComponents").DataTable({
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
            [1,'desc']
        ],
        "searching": false,
        "info": false,
        "paging": false,
        "language": {

        }
    });

    /* Nuevo componente
    /* ------------------------------------------------------------------------ */
    var frmNewComponent = $('form[name="newComponent"]');

    modal('newComponent', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmNewComponent.submit();
    });

    frmNewComponent.on('submit', function(e)
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

    /* Eliminar componente
    /* ------------------------------------------------------------------------ */
    var btnSendIdComponentToDelete = $('[data-action="sendIdComponentToDelete"]');
    var btnDeleteComponent = $('[data-action="deleteComponent"]');

    var idComponent;

    btnSendIdComponentToDelete.on('click', function()
    {
        idComponent = $(this).data('id');
    });

    btnDeleteComponent.on('click', function()
    {
        idService = $(this).data('id');

        $.ajax({
            url: '/services/deleteComponent',
            type: 'POST',
            data: 'idService=' + idService + '&idComponent=' + idComponent,
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

    /* Tabla de categorías
    /* ------------------------------------------------------------------------ */
    var tblCategories = myDocument.find("#tblCategories").DataTable({
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

    /* Obtener categoría para editar
    /* ------------------------------------------------------------------------ */
    var idCategory;

    $(document).on('click', '[data-action="getCategoryToEdit"]', function()
    {
        idCategory = $(this).data('id');

        $.ajax({
            url: '/services/getCategoryToEdit/' + idCategory,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);

                    $('[data-modal="categories"] header > h6').html('Editar categoría');
                    $('[data-modal="categories"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="categories"]').toggleClass('view');
                }
            }
        });
    });

    /* Nueva y editar categoría
    /* ------------------------------------------------------------------------ */
    var frmCategories = $('form[name="categories"]');

    modal('categories', function(modal)
    {
        modal.find('header > h6').html('Nueva categoría');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmCategories.submit();
    });

    frmCategories.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idCategory,
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

    /* Eliminar selección multiple de categorías
    /* ------------------------------------------------------------------------ */
    var btnDeleteCategories    = $('[data-action="deleteCategories"]');
    var urlDeleteCategories    = '/services/deleteCategories';

    multipleSelect(btnDeleteCategories, urlDeleteCategories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
