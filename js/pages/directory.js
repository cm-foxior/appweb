'use strict';

menuActive('catalogs');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de contactos
    /* ------------------------------------------------------------------------ */
    var tblContacts = myDocument.find("#tblContacts").DataTable({
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
            [5,'asc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Obtener contacto para editar
    /* ------------------------------------------------------------------------ */
    var idContact;
    var btnGetContactToEdit = $('[data-action="getContactToEdit"]');

    btnGetContactToEdit.on('click', function()
    {
        idContact = $(this).data('id');

        $.ajax({
            url: '/directory/getContactToEdit/' + idContact,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('input[name="email"]').val(response.data.email);

                    if (response.data.phone_number)
                    {
                        var phoneNumber = eval('(' + response.data.phone_number + ')');

                        $('select[name="phoneCountryCode"]').val(phoneNumber.country_code);
                        $('input[name="phoneNumber"]').val(phoneNumber.number);
                        $('select[name="phoneType"]').val(phoneNumber.type);
                    }

                    $('select[name="client"]').val(response.data.id_client);
                    $('select[name="category"]').val(response.data.id_contact_category);

                    $('[data-modal="contacts"] header > h6').html('Editar contacto');
                    $('[data-modal="contacts"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="contacts"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Crear y editar contacto
    /* ------------------------------------------------------------------------ */
    var frmContacts = $('form[name="contacts"]');

    modal('contacts', function(modal)
    {
        modal.find('header > h6').html('Nuevo contacto');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmContacts.submit();
    });

    frmContacts.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var action = self.data('submit-action');
        var data = self.serialize();

        $.ajax({
            url: '',
            type: 'POST',
            data: data + '&action=' + action + '&id=' + idContact,
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

    /* Eliminar selección de contactos
    /* ------------------------------------------------------------------------ */
    var btnDeleteContacts = $('[data-action="deleteContacts"]');
    var urlDeleteContacts = '/directory/deleteContacts';

    multipleSelect(btnDeleteContacts, urlDeleteContacts, function(response)
    {
        if (response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
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
    var btnGetCategoryToEdit = $('[data-action="getCategoryToEdit"]');

    btnGetCategoryToEdit.on('click', function()
    {
        idCategory = $(this).data('id');

        $.ajax({
            url: '/directory/getCategoryToEdit/' + idCategory,
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
    var urlDeleteCategories    = '/directory/deleteCategories';

    multipleSelect(btnDeleteCategories, urlDeleteCategories, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
