'use strict';

/**
* @package valkyrie.js
*
* @summary Funciones del dashboard.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

$(document).ready(function()
{
    /**
    * @summary Ejecuta el Nav scroll down a la barra superior y a la barra del módulo.
    */
    nav_scroll_down('header.topbar', 'down', 0);
    nav_scroll_down('header.modbar', 'down', 0);

    /**
    * @summary Abre y cierra el menú derecho del dashboard.
    */
    $('[data-action="open_rightbar"]').on('click', function(e)
    {
        e.stopPropagation();

        $('header.rightbar').toggleClass('open');
    });

    /**
    * @summary Solicita cambiar de cuenta en linea en la sesión del usuario logueado.
    */
    $('[data-action="switch_account"]').on('click', function()
    {
        $.ajax({
            url: '/dashboard',
            type: 'POST',
            data: 'action=switch_account&id=' + $(this).data('id'),
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    location.reload();
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    /**
    * @summary Solicita destruir la sesión del usuario logueado.
    */
    $('[data-action="logout"]').on('click', function()
    {
        $.ajax({
            url: '/dashboard',
            type: 'POST',
            data: 'action=logout',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                location.reload();
            }
        });
    });
});

/**
* @summary Transforma el modal para trabaja en el CRUD.
*
* @var string option: (create, update) Tipo de modal que se abrirá.
* @var HTML Object target: Modal donde se aplicará la transformación.
*/
function transform_form_modal(option, target)
{
    if (option == 'create')
    {
        target.find('form').find('button[type="submit"]').html('<i class="fas fa-plus"></i>');
        target.find('form').find('button[type="submit"]').removeClass('warning');
        target.find('form').find('button[type="submit"]').addClass('success');
    }
    else if (option == 'update')
    {
        target.find('form').find('button[type="submit"]').html('<i class="fas fa-pen"></i>');
        target.find('form').find('button[type="submit"]').removeClass('success');
        target.find('form').find('button[type="submit"]').addClass('warning');
    }
}
