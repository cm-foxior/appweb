'use strict';

menuActive('users');

$(document).ready(function ()
{
    var myDocument = $(this);

    /* Tabla de usuarios
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
            [4,'asc']
        ],
        "searching": true,
        "info": true,
        "paging": true,
        "language": {

        }
    });

    /* Generar contraseña aleatoria
    /* ------------------------------------------------------------------------ */
    var cbxRandomPassword = $('[data-action="randomPassword"]');
    var cbxRandomPasswordRestored = $('[data-action="randomPasswordRestored"]');

    cbxRandomPassword.on('change', function()
    {
        if (cbxRandomPassword.is(':checked') == true)
        {
            var random  = '';
            var chars   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&¿?.*';

            for (var x = 0; x < 8; x++)
            {
                var math = Math.floor(Math.random() * chars.length);
                random += chars.substr(math, 1);
            }

            $('input[name="password"]').val(random);
        }
        else
            $('input[name="password"]').val('');
    });

    cbxRandomPasswordRestored.on('change', function()
    {
        if (cbxRandomPasswordRestored.is(':checked') == true)
        {
            var random  = '';
            var chars   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&¿?.*';

            for (var x = 0; x < 8; x++)
            {
                var math = Math.floor(Math.random() * chars.length);
                random += chars.substr(math, 1);
            }

            $('input[name="newPassword"]').val(random);
        }
        else
            $('input[name="newPassword"]').val('');
    });

    /* Mostrar contraseña
    /* ------------------------------------------------------------------------ */
    var cbxShowPassword = $('[data-action="showPassword"]');
    var cbxShowPasswordRestored = $('[data-action="showPasswordRestored"]');

    cbxShowPassword.on('change', function()
    {
        if (cbxShowPassword.is(':checked') == true)
            $('input[name="password"]').attr('type', 'text');
        else
            $('input[name="password"]').attr('type', 'password');
    });

    cbxShowPasswordRestored.on('change', function()
    {
        if (cbxShowPasswordRestored.is(':checked') == true)
            $('input[name="newPassword"]').attr('type', 'text');
        else
            $('input[name="newPassword"]').attr('type', 'password');
    });

    /*
    /* ------------------------------------------------------------------------ */
    var sltUserLevel = $('select[name="level"]');

    sltUserLevel.on('change', function()
    {
        if (sltUserLevel.val() == '9' || sltUserLevel.val() == '8' || sltUserLevel.val() == '7')
        {
            $('select[name="branchOffice"]').parent().parent().removeClass('hidden');
        }
        else
        {
            $('select[name="branchOffice"]').val('').trigger('chosen:updated');
            $('select[name="branchOffice"]').parent().parent().addClass('hidden');
        }
    });

    /* Obtener usuario para editar
    /* ------------------------------------------------------------------------ */
    var idUser;

    $(document).on('click', '[data-action="getUserToEdit"]', function()
    {
        idUser = $(this).data('id');

        $.ajax({
            url: '/users/getUserToEdit/' + idUser,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('input[name="name"]').val(response.data.name);
                    $('input[name="email"]').val(response.data.email);

                    if (response.data.phone_number != null)
                    {
                        var phoneNumber = eval('(' + response.data.phone_number + ')');

                        $('select[name="phoneCountryCode"]').val(phoneNumber.country_code);
                        $('input[name="phoneNumber"]').val(phoneNumber.number);
                        $('select[name="phoneType"]').val(phoneNumber.type);
                    }

                    $('input[name="username"]').val(response.data.username);
                    $('select[name="level"]').val(response.data.level);

                    if (response.data.avatar != null)
                        $('div[image-preview="image-preview"]').attr('style', 'background-image: url(/images/users/' + response.data.avatar + ')');

                    if (response.data.level == '9' || response.data.level == '8' || response.data.level == '7')
                    {
                        $('select[name="branchOffice"]').parent().parent().removeClass('hidden');
                        $('select[name="branchOffice"]').val(response.data.id_branch_office).trigger('chosen:updated');
                    }

                    $('input[name="password"]').parent().parent().addClass('hidden');

                    $('[data-modal="users"] header > h6').html('Editar usuario');
                    $('[data-modal="users"] form').attr('data-submit-action', 'edit');
                    $('[data-modal="users"]').toggleClass('view').animate({scrollTop: 0}, 0);
                }
            }
        });
    });

    /* Obtener usuario para restablecer su contraseña
    /* ------------------------------------------------------------------------ */
    $(document).on('click', '[data-action="getUserToRestorePassword"]', function()
    {
        idUser = $(this).data('id');
    });

    /* Crear y editar usuario
    /* ------------------------------------------------------------------------ */
    var frmUsers = $('form[name="users"]');

    $('[data-button-modal="users"]').on('click', function()
    {
        frmUsers[0].reset();
    });

    modal('users', function(modal)
    {
        modal.find('header > h6').html('Nuevo usuario');
        modal.find('input[name="password"]').parent().parent().removeClass('hidden');
        modal.find('select[name="branchOffice"]').parent().parent().addClass('hidden');
        modal.find('form').attr('data-submit-action', 'new');
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmUsers.submit();
    });

    frmUsers.on('submit', function(e)
    {
        e.preventDefault();

        var self    = $(this);
        var action  = self.data('submit-action');
        var data    = new FormData(this);

        data.append('action', action);
        data.append('id', idUser);

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

    /* Restablecer contraseña de usuario
    /* ------------------------------------------------------------------------ */
    var frmRestoreUserPassword = $('form[name="restoreUserPassword"]');

    modal('restoreUserPassword', function(modal)
    {
        modal.find('form')[0].reset();

    }, function(modal)
    {
        frmRestoreUserPassword.submit();
    });

    frmRestoreUserPassword.on('submit', function(e)
    {
        e.preventDefault();

        var self = $(this);
        var data = self.serialize();

        $.ajax({
            url: '/users/restoreUserPassword',
            type: 'POST',
            data: data + '&id=' + idUser,
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

    /* Activar selección de usuarios
    /* ------------------------------------------------------------------------ */
    var btnActivateUsers = $('[data-action="activateUsers"]');
    var urlActivateUsers = '/users/changeStatusUsers/activate';

    multipleSelect(btnActivateUsers, urlActivateUsers, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Desactivar selección de usuarios
    /* ------------------------------------------------------------------------ */
    var btnDeactivateUsers = $('[data-action="deactivateUsers"]');
    var urlDeactivateUsers = '/users/changeStatusUsers/deactivate';

    multipleSelect(btnDeactivateUsers, urlDeactivateUsers, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });

    /* Eliminar selección de usuarios
    /* ------------------------------------------------------------------------ */
    var btnDeleteUsers = $('[data-action="deleteUsers"]');
    var urlDeleteUsers = '/users/deleteUsers';

    multipleSelect(btnDeleteUsers, urlDeleteUsers, function(response)
    {
        if(response.status == 'success')
        {
            $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
            location.reload();
        }
    });
});
