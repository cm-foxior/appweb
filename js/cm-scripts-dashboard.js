'use strict';

/**
* @package valkyrie.js
*
* @summary Dashboard general.
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
* @summary Busca una cadena de texto en una tabla.
*
* @var string data: Cadena de texto que se desea buscar.
* @var HTML Object table: Tabla en la que se va a realizar la búsqueda.
* @var string type: Estilo de la tabla
*/
function search_in_table(data, table, type)
{
    if (type == 'tbl-st-1')
        var values = table.find(' > tbody > tr');
    else if (type == 'tbl-st-3')
        var values = table.find(' > div');
    else if (type == 'cbx')
        var values = table.find(' > label');

    $.each(values, function(key, value)
    {
        var string_1 = data.toLowerCase();
        var string_2 = value.innerHTML.toLowerCase();
        var indexof = string_2.indexOf(string_1);

        if (indexof >= 0)
            value.className = '';
        else
            value.className = 'hidden';
    });
}

/**
* @summary Filtra una tabla de acuerdo a los paramentros enviados.
*
* @var HTML Object form: Formulario con los paramentros a filtrar.
* @var HTML Object table: Tabla en la que se va a realizar el filtrado.
* @var string type: Estilo de la tabla
*/
function filter_in_table(form, table, type)
{
    var data = new FormData(form[0]);

    data.append('action', action)

    $.ajax({
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        cache: false,
        dataType: 'json',
        success: function(response)
        {
            if (response.status == 'success')
            {
                if (type == 'tbl-st-1')
                {
                    table.find('tbody').html('');
                    table.find('tbody').html(response.html);
                }
            }
        }
    });
}

/**
* @summary Transforma el modal para trabaja en el CRUD.
*
* @var string type: Tipo de modal que se abrirá.
* @var HTML Object modal:
* @var function callback:
*/
function transform_form_modal(type, modal)
{
    if (type == 'create')
    {
        modal.find('form').find('button[type="submit"]').html('<i class="fas fa-plus"></i>');
        modal.find('form').find('button[type="submit"]').removeClass('warning');
        modal.find('form').find('button[type="submit"]').addClass('success');
    }
    else if (type == 'update')
    {
        modal.find('form').find('button[type="submit"]').html('<i class="fas fa-pen"></i>');
        modal.find('form').find('button[type="submit"]').removeClass('success');
        modal.find('form').find('button[type="submit"]').addClass('warning');
    }
    else if (type == 'delete')
    {

    }
}
