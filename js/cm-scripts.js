'use strict';

/**
* @package valkyrie.js
*
* @summary Stock de funciones opcionales.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

/**
* @summary Ejecuta el Data Loader al ejecutarse una acción Ajax.
*/
$(window).on('beforeunload ajaxStart', function()
{
    $('body').prepend('<div data-ajax-loader><div class="loader"></div></div>');
});

/**
* @summary Detiene el Data Loader al terminar de ejecutarse una acción Ajax.
*/
$(window).on('ajaxStop', function()
{
    $('body').find('[data-ajax-loader]').remove();
});

$(document).ready(function()
{
    /**
    * @summary Ejecuta la función uploader de tipo low.
    */
    $('[data-low-uploader]').each(function()
    {
        uploader('low', $(this));
    });

    /**
    * @summary Ejecuta la función uploader de tipo fast.
    */
    $('[data-fast-uploader]').each(function()
    {
        uploader('fast', $(this));
    });

    /**
    * @summary Agrega una imagen como background.
    */
    $('[data-image-src]').each(function()
    {
        $(this).css('background-image', 'url("' + $(this).data('image-src') + '")');
    });
});

/**
* @summary Busca una cadena de texto en una tabla.
*
* @var string data: Cadena de texto que se va a buscar.
* @var <HTML Tag> target: Etiqueta HTML en la que se va a realizar la búsqueda.
* @var boolean hidden: Establece si al terminar la búsqueda se esconderán las etiquetas HTML del target.
*/
function search_in_table(data, target, hidden)
{
    hidden = (hidden == undefined) ? false : true;

    $.each(target, function(key, value)
    {
        if (data.length > 0)
        {
            var string_1 = data.toLowerCase();
            var string_2 = value.innerHTML.toLowerCase();
            var result = string_2.indexOf(string_1);

            if (result > 0)
                value.className = '';
            else if (result <= 0)
                value.className = 'hidden';
        }
        else if (data.length <= 0 && hidden == true)
            value.className = 'hidden';
    });

    // Que los checboxes seleccionados no desaparescan.
    // Que las palabras iguales pero con acentos aparescan aunque se busque sin acento y viceversa.
    // Buscar strings separados en los target.
}

/**
* @summary Valida los valores de una cadena de texto en una etiqueta HTML <input>.
*
* @param string option: (uppercase, lowercase, int, float) Tipo de cadena de texto permitida.
* @param string data: Cadena de texto a validar.
* @param <input> target: Etiqueta HTML donde retornará la validación.
*/
function validate_string(option, data, target)
{
    var filter = '';
    var uppercase = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    var lowercase = 'abcdefghijklmnñopqrstuvwxyz';
    var numbet_int = '0123456789';
    var number_float = '.';

    if (Array.isArray(option))
    {
        $.each(option, function(key, value)
        {
            if (value == 'uppercase')
                filter = filter + uppercase;
            else if (value == 'lowercase')
                filter = filter + lowercase;
            else if (value == 'int')
                filter = filter + numbet_int;
            else if (value == 'float')
                filter = filter + number_float + numbet_int;
        });
    }
    else if (option == 'uppercase')
        filter = uppercase;
    else if (option == 'lowercase')
        filter = lowercase;
    else if (option == 'int')
        filter = numbet_int;
    else if (option == 'float')
        filter = number_float + numbet_int;

    var out = '';

    for (var i = 0; i < data.length; i++)
    {
        if (filter.indexOf(data.charAt(i)) != -1)
            out += data.charAt(i);
    }

    target.val(out);
}

/**
* @summary Genera una cadena de texto random.
*
* @param string option: (uppercase, lowercase, int, float) Tipo de cadena de texto que se va a generar.
* @param string length: Tamaño de la cadena de texto que se va a generar.
* @param <input> target: Etiqueta HTML donde retornará la cadena de texto generada.
*/
function generate_string(option, length, target)
{
    var filter = '';
    var uppercase = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    var lowercase = 'abcdefghijklmnñopqrstuvwxyz';
    var numbet_int = '0123456789';
    var number_float = '.';

    if (Array.isArray(option))
    {
        $.each(option, function(key, value)
        {
            if (value == 'uppercase')
                filter = filter + uppercase;
            else if (value == 'lowercase')
                filter = filter + lowercase;
            else if (value == 'int')
                filter = filter + numbet_int;
            else if (value == 'float')
                filter = filter + number_float + numbet_int;
        });
    }
    else if (option == 'uppercase')
        filter = uppercase;
    else if (option == 'lowercase')
        filter = lowercase;
    else if (option == 'int')
        filter = numbet_int;
    else if (option == 'float')
        filter = number_float + numbet_int;

    var out  = '';

    for (var x = 0; x < length; x++)
    {
        var math = Math.floor(Math.random() * filter.length);
        out += filter.substr(math, 1);
    }

    target.val(out);
}

/**
* @summary Variables para trabajar en el CRUD.
*
* @var string action: Almacena la acción que ejecutará el CRUD.
* @var int id: Almacena el id del registro en la base de datos con el que se trabajará en el CRUD.
*/
var action = null;
var id = null;

/**
* @summary Abre el modal para trabajar en el CRUD.
*
* @var string option: (create, update, delete) Tipo de modal que se abrirá.
* @var <[data-modal]> target: Modal que se abrirá.
* @var function callback: Acciones que se ejecutarán al terminar de abrí el modal.
*/
function open_form_modal(option, target, callback)
{
    if (option == 'create' || option == 'update')
    {
        reset_form(target.find('form'));

        if (option == 'update')
        {
            $.ajax({
                type: 'POST',
                data: 'action=' + action + '&id=' + id,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                        callback(response.data);
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
    }

    target.addClass('view');
}

/**
* @summary Envía el modal con el que se está trabajando en el CRUD al controlador.
*
* @var string option: (create, update, block, unblock, delete) Tipo de envío.
* @var <form> target: Formulario que se enviará.
* @var Event event: Evento de formulario.
*/
function send_form_modal(option, target, event)
{
    if (option == 'create' || option == 'update')
    {
        event.preventDefault();

        var data = new FormData(target[0]);

        data.append('action', action);
        data.append('id', id);

        $.ajax({
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                check_form_errors(target, response, function()
                {
                    open_notification_modal('success', response.message);
                });
            }
        });
    }
    else if (option == 'block' || option == 'unblock')
    {
        $.ajax({
            type: 'POST',
            data: 'action=' + action + '&id=' + id,
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
    }
    else if (option == 'delete')
    {
        $.ajax({
            type: 'POST',
            data: 'action=' + action + '&id=' + id,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    open_notification_modal('success', response.message);
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    }
}

/**
* @summary Restablece los datos de un formulario.
*
* @param <form> target: Formulario a restablecer.
*/
function reset_form(target)
{
    target[0].reset();
    target.find('.uploader').find('img').attr('src', '../images/empty.png');
    target.find('p.error').remove();
    target.find('.error').removeClass('error');
}

/**
* @summary Abre el modal de notificación.
*
* @var string option: (success, alert) Tipo de notificación.
* @var string message: Mensaje que mostrará el modal
* @var string path: Ruta de recarga o redirección.
* @var string timeout: Tiempo en que se ejecura la recarga o redirección.
*/
function open_notification_modal(option, message, path, timeout)
{
    message = (message == undefined) ? '' : message;
    path = (path == undefined) ? ((option == 'success') ? 'reload' : false) : path;
    timeout = (timeout == undefined) ? '1000' : timeout;

    if (option == 'success')
    {
        $('[data-modal="success"]').addClass('view');
        $('[data-modal="success"]').find('main > p').html(message);
    }
    else if (option == 'alert')
    {
        $('[data-modal="alert"]').addClass('view');
        $('[data-modal="alert"]').find('main > p').html(message);
    }

    if (path != false)
    {
        setTimeout(function()
        {
            if (path == 'reload')
                location.reload();
            else
                window.location.href = path;
        }, timeout);
    }
}

/**
* @summary Revisa los errores que retornó el controlador y los aplica visualmente.
*
* @param <form> target: Formulario a revisar.
* @param Ajax response response: Respuesta del controlador.
* @param Function callback: Acciones que se ejecutarán en caso que no haya errores.
*/
function check_form_errors(target, response, callback)
{
    target.find('[name]').parents('.error').find('p.error').remove();
    target.find('[name]').parents('.error').removeClass('error');

    if (response.status == 'success')
        callback();
    else if (response.status == 'error')
    {
        if (Array.isArray(response.errors))
        {
            $.each(response.errors, function (key, value)
            {
                target.find('[name="' + value[0] + '"]').parent().addClass('error');
                target.find('[name="' + value[0] + '"]').parent().append('<p class="error">'+ value[1] +'</p>');
            });

            target.find('input[name="'+ response.errors[0][0] +'"]').focus();
        }
        else
            open_notification_modal('alert', response.errors);
    }
}

/**
* @summary Envia archivos al controlador para que se suban al almacenamiento.
*
* @param string option: (low, fast) Tipo de subida.
* @param <[data-uploader]> target: Etiqueta HTML del uploader.
*/
function uploader(option, target)
{
    target.find('a[data-select]').on('click', function()
    {
        target.find('input[data-select]').click();
    });

    target.find('input[data-select]').on('change', function()
    {
        if ($(this)[0].files[0].type.match($(this).attr('accept')))
        {
            if (option == 'low')
            {
                var reader = new FileReader();

                reader.onload = function(e)
                {
                    target.find('[data-preview] > img').attr('src', e.target.result);
                }

                reader.readAsDataURL($(this)[0].files[0]);
            }
            else if (option == 'fast')
            {
                var data = new FormData();

                data.append('action', action);
                data.append('file', $(this)[0].files[0]);

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
                            open_notification_modal('success', response.message, true);
                        else if (response.status == 'error')
                            open_notification_modal('alert', response.message);
                    }
                });
            }
        }
        else
            open_notification_modal('alert', 'ERROR');
    });
}

/**
* @summary Agrega un clase de css a una etiqueta al detectar un scroll down.
*
* @param string target: Etiqueta a la que se agregará la clase.
* @param string style: Clase de css que se agregará.
* @param string height: Medida en la cual se agregará la clase a la etiqueta dentro del scroll down.
*
* @return object
*/
function nav_scroll_down(target, style, height, lt_target, lt_img_1, lt_img_2)
{
    var nav = {
        initialize: function()
        {
            $(document).each(function()
            {
                nav.scroller()
            });

            $(document).on('scroll', function()
            {
                nav.scroller()
            });
        },
        scroller: function()
        {
            if ($(document).scrollTop() > height)
            {
                if (lt_target || lt_img_2)
                    $(lt_target).attr('src', lt_img_2);

                $(target).addClass(style);
            }
            else
            {
                if (lt_target || lt_img_1)
                    $(lt_target).attr('src', lt_img_1);

                $(target).removeClass(style);
            }
        }
    }

    nav.initialize();
}
