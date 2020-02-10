'use strict';

menuActive('products');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de productos
    /* ------------------------------------------------------------------------ */
    var tblProducts = myDocument.find("#tblProducts").DataTable({
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
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Generar folio de producto aleatorio
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

    /* Seleccionar tipo de producto
    /* ------------------------------------------------------------------------ */
    var sltType = $('select[name="type"]');

    sltType.on('change', function()
    {
        if (sltType.val() == '1')
        {
            $('input[name="basePrice"]').parent().parent().removeClass('hidden');
            $('input[name="prefPrice"]').parent().parent().removeClass('hidden');
            $('input[name="publicPrice"]').parent().parent().removeClass('hidden');
            $('select[name="discountType"]').parent().parent().removeClass('hidden');
            $('select[name="coin"]').parent().parent().removeClass('hidden');
        }
        else
        {
            $('input[name="basePrice"]').val('');
            $('input[name="prefPrice"]').val('');
            $('input[name="publicPrice"]').val('');
            $('input[name="discountQuantity"]').val('');
            $('select[name="discountType"]').val('');
            $('select[name="coin"]').val('1');
            $('input[name="basePrice"]').parent().parent().addClass('hidden');
            $('input[name="prefPrice"]').parent().parent().addClass('hidden');
            $('input[name="publicPrice"]').parent().parent().addClass('hidden');
            $('select[name="discountType"]').parent().parent().addClass('hidden');
            $('select[name="coin"]').parent().parent().addClass('hidden');
        }
    });

    /* Seleccionar tipo de descuento
    /* ------------------------------------------------------------------------ */
    var sltDiscountType = $('select[name="discountType"]');

    sltDiscountType.on('change', function()
    {
        if (sltDiscountType.val() == '1' || sltDiscountType.val() == '4')
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

    /* Obtener producto para editar
    /* ------------------------------------------------------------------------ */
    var idProduct;

    $(document).on('click', '[data-action="getProductToEdit"]', function()
    {
        idProduct = $(this).data('id');

        $.ajax({
            url: '/products/getProductToEdit/' + idProduct,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('input[name="folio"]').val(response.data.folio);
                    $('select[name="type"]').val(response.data.type);

                    if (response.data.type == '1')
                    {
                        var price = eval('(' + response.data.price + ')');
                        $('input[name="basePrice"]').val(price.base_price);
                        $('input[name="prefPrice"]').val(price.pref_price);
                        $('input[name="publicPrice"]').val(price.public_price);

                        if (response.data.discount != null)
                        {
                            var discount = eval('(' + response.data.discount + ')');
                            $('input[name="discountQuantity"]').val(discount.quantity);
                            $('select[name="discountType"]').val(discount.type);
                        }

                        $('select[name="coin"]').val(response.data.coin);
                        $('input[name="basePrice"]').parent().parent().removeClass('hidden');
                        $('input[name="prefPrice"]').parent().parent().removeClass('hidden');
                        $('input[name="publicPrice"]').parent().parent().removeClass('hidden');
                        $('input[name="discountQuantity"]').parent().parent().removeClass('hidden');
                        $('select[name="discountType"]').parent().parent().removeClass('hidden');
                        $('select[name="coin"]').parent().parent().removeClass('hidden');
                    }

                    $('select[name="unity"]').val(response.data.unity);

                    if (response.data.avatar != null)
                        $('div[image-preview="image-preview"]').attr('style', 'background-image: url(/images/products/' + response.data.avatar + ')');

                    if (response.data.id_product_category_one != null)
                        $('select[name="category_one"]').val(response.data.id_product_category_one).trigger('chosen:updated');

                    if (response.data.id_product_category_two != null)
                        $('select[name="category_two"]').val(response.data.id_product_category_two).trigger('chosen:updated');

                    if (response.data.id_product_category_tree != null)
                        $('select[name="category_tree"]').val(response.data.id_product_category_tree).trigger('chosen:updated');

                    if (response.data.id_product_category_four != null)
                        $('select[name="category_four"]').val(response.data.id_product_category_four).trigger('chosen:updated');

                    if (response.data.observations != null)
                        $('textarea[name="observations"]').val(response.data.observations);

                    $('[data-modal="products"] header > h6').html('Editar producto');
                    $('[data-modal="products"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="products"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar producto
    /* ------------------------------------------------------------------------ */
    var frmProducts = $('form[name="products"]');

    modal('products', function(modal)
    {
        modal.find('header > h6').html('Nuevo producto');
        modal.find('input[name="basePrice"]').parent().parent().removeClass('hidden');
        modal.find('input[name="prefPrice"]').parent().parent().removeClass('hidden');
        modal.find('input[name="publicPrice"]').parent().parent().removeClass('hidden');
        modal.find('select[name="discountType"]').parent().parent().removeClass('hidden');
        modal.find('select[name="coin"]').parent().parent().removeClass('hidden');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmProducts.submit();
    });

    frmProducts.on('submit', function(e)
    {
        e.preventDefault();
        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = new FormData(this);
        data.append('action', action);
        data.append('id', idProduct);

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
            url: '/products/importFromExcel',
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

    /* Activar selección multiple de productos
    /* ------------------------------------------------------------------------ */
    var btnActivateProducts = $('[data-action="activateProducts"]');
    var urlActivateProducts = '/products/changeStatusProducts/activate';

    multipleSelect(btnActivateProducts, urlActivateProducts, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección multiple de productos
    /* ------------------------------------------------------------------------ */
    var btnDeactivateProducts = $('[data-action="deactivateProducts"]');
    var urlDeactivateProducts = '/products/changeStatusProducts/deactivate';

    multipleSelect(btnDeactivateProducts, urlDeactivateProducts, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección multiple de productos
    /* ------------------------------------------------------------------------ */
    var btnDeleteProducts = $('[data-action="deleteProducts"]');
    var urlDeleteProducts = '/products/deleteProducts';

    multipleSelect(btnDeleteProducts, urlDeleteProducts, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Lista de etiquetas para imprimir
    /* ------------------------------------------------------------------------ */
    var tblFreeList = myDocument.find("#tblFreeList").DataTable({
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
            [0, 'asc']
        ],
        "searching": false,
        "info": false,
        "paging": false,
        "language": {

        }
    });

    /* Crear / eliminar lista libre de etiquetas
    /* ------------------------------------------------------------------------ */
    var btnCreateFreeList = $('[data-action="createFreeList"]');
    var btnDeleteFreeList = $('[data-action="deleteFreeList"]');
    var btnAddToFreeList = $('[data-action="addToFreeList"]');
    var frmCreateFreeList = $('form[name="createFreeList"]');

    var createFreeList = false;
    var freeList = [];

    btnCreateFreeList.on('click', function()
    {
        createFreeList = true;

        $('#tags').html('<div class="tags"></div>');

        frmCreateFreeList.removeClass('hidden');

        $('input[name="searchDate"]').parent().parent().addClass('hidden');
        $('input[name="searchDate"]').prop('disabled', true);
        $('input[name="searchDate"]').val('');

        cbxEstablishSearchDate.prop('checked', false);
        cbxEstablishSearchDate.prop('disabled', true);

        btnCreateFreeList.addClass('hidden');
        btnDeleteFreeList.removeClass('hidden');
    });

    btnDeleteFreeList.on('click', function()
    {
        location.reload();
    });

    btnAddToFreeList.on('click', function()
    {
        frmCreateFreeList.submit();
    });

    frmCreateFreeList.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '/products/createFreeList',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                checkValidateFormAjax(self, response, function()
                {
                    tblFreeList.row.add([
                        response.data.folio,
                        response.data.exists,
                        '<a data-delete="' + response.data.folio + '"><i class="material-icons">delete</i><span>Eliminar</span></a>'
                    ]).draw();

                    freeList.push(response.data);

                    $('input[name="folio"]').val('');
                    $('input[name="itemsNumber"]').val('');
                });
            }
        });
    });

    $(document).on('click', '[data-delete]', function ()
    {
        var self = $(this);
        var folio = self.data('delete');
        var row_table = self.parents('tr');

        if (confirm("¿Esta seguro que desea eliminar este producto?") == true)
        {
            tblFreeList.$('tr.selected').removeClass('selected');
            row_table.addClass('selected');
            tblFreeList.row('.selected').remove().draw(false);

            freeList = jQuery.grep(freeList, function (data, key)
            {
                if (data['folio'] != folio)
                    return data;
            });
        }
    });

    /* Establecer fecha de búsqueda para impresión de etiquetas
    /* ------------------------------------------------------------------------ */
    var cbxEstablishSearchDate = $('input[name="establishSearchDate"]');

    cbxEstablishSearchDate.on('change', function()
    {
        if (cbxEstablishSearchDate.is(':checked') == true)
        {
            $('#tags').html('<div class="tags"></div>');
            $('input[name="searchDate"]').prop('disabled', false);
        }
        else
            location.reload();
    });

    /* Actualizar opciones de impresion de etiquetas
    /* ------------------------------------------------------------------------ */
    var btnPrintTags = $('[data-action="printTags"]');
    var btnUpdatePrintOptions = $('[data-action="updatePrintOptions"]');
    var frmUpdatePrintOptions = $('form[name="updatePrintOptions"]');

    var printOption;

    btnPrintTags.on('click', function()
    {
        printOption = 'print';
        frmUpdatePrintOptions.submit();
    });

    btnUpdatePrintOptions.on('click', function()
    {
        printOption = 'update';
        frmUpdatePrintOptions.submit();
    });

    frmUpdatePrintOptions.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        if (createFreeList == true)
        {
            var freeListJson = JSON.stringify(freeList);
            data = data + '&freeList=' + freeListJson + '&createFreeList=' + createFreeList;
        }

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
                    if (printOption == 'print')
                    {
                        var printWindow = window.open('', 'Imprimir / Guardar', 'width=800,height=600');
                        printWindow.document.write(response.html);
                        printWindow.document.close();
                        printWindow.print();
                        printWindow.close();
                    }
                    else if (printOption == 'update')
                        $('#tags').html(response.html);
                });
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

    var number;

    $('[data-button-modal="categories"]').on('click', function()
    {
        number = $(this).data('number');
    });

    /* Obtener categoría para editar
    /* ------------------------------------------------------------------------ */
    var idCategory;

    $(document).on('click', '[data-action="getCategoryToEdit"]', function()
    {
        idCategory = $(this).data('id');
        number = $(this).data('number');

        $.ajax({
            url: '/products/getCategoryToEdit/' + idCategory,
            type: 'POST',
            data: 'number=' + number,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);

                    if (number == 'one')
                    {
                        if (response.data.avatar != null)
                            $('div[image-preview="image-preview"]').attr('style', 'background-image: url(/images/products/categories/' + response.data.avatar + ')');
                    }

                    $('[data-modal="categories"] header > h6').html('Editar categoría');
                    $('[data-modal="categories"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="categories"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar categoría
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
        var data    = new FormData(this);

        data.append('action', action);
        data.append('id', idCategory);
        data.append('number', number);

        $.ajax({
            url: '/products/categories_one',
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

    /* Eliminar selección multiple de categorías
    /* ------------------------------------------------------------------------ */
    var btnDeleteCategories    = $('[data-action="deleteCategories"]');
    var urlDeleteCategories    = '/products/deleteCategories';

    multipleSelect(btnDeleteCategories, urlDeleteCategories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Tabla de productos ligados
    /* ------------------------------------------------------------------------ */
    var tblFlirts = myDocument.find("#tblFlirts").DataTable({
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

    /* Obtener producto ligado para editar
    /* ------------------------------------------------------------------------ */
    var idFlirt;

    $(document).on('click', '[data-action="getFlirtToEdit"]', function()
    {
        idFlirt = $(this).data('id');

        $.ajax({
            url: '/products/getFlirtToEdit/' + idFlirt,
            type: 'POST',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('select[name="product_1"]').val(response.data.id_product_1).trigger('chosen:updated');
                    $('select[name="product_2"]').val(response.data.id_product_2).trigger('chosen:updated');
                    $('input[name="stock_base"]').val(response.data.stock_base);
                    $('[data-modal="flirts"] header > h6').html('Editar producto ligado');
                    $('[data-modal="flirts"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="flirts"]').toggleClass('view');
                }
            }
        });
    });

    /* Crear y editar producto ligado
    /* ------------------------------------------------------------------------ */
    var frmFlirts = $('form[name="flirts"]');

    modal('flirts', function(modal)
    {
        modal.find('header > h6').html('Nuevo producto ligado');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmFlirts.submit();
    });

    frmFlirts.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = new FormData(this);

        data.append('action', action);
        data.append('id', idFlirt);

        $.ajax({
            url: '/products/flirts',
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

    /* Eliminar selección multiple de productos ligados
    /* ------------------------------------------------------------------------ */
    var btnDeleteFlirts    = $('[data-action="deleteFlirts"]');
    var urlDeleteFlirts    = '/products/deleteFlirts';

    multipleSelect(btnDeleteFlirts, urlDeleteFlirts, function(response)
    {
        if (response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
