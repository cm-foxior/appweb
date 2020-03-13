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

/**
* @summary Variables para trabajar en el CRUD.
*
* @var string action: Almacena la acción que ejecutará el CRUD.
* @var int id: Almacena el id del registro en la base de datos con el que se trabajará en el CRUD.
*/
var action = null;
var id = null;

$(document).ready(function()
{
    /**
    * @summary Ejecuta la función uploader de tipo low.
    */
    $('[data-low-uploader]').each(function()
    {
        uploader($(this), 'low');
    });

    /**
    * @summary Ejecuta la función uploader de tipo fast.
    */
    $('[data-fast-uploader]').each(function()
    {
        uploader($(this), 'fast', $(this).data('fast-uploader'));
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
* @summary Abre el modal para trabajar en el CRUD.
*
* @var string type: Tipo de modal que se abrirá.
* @var HTML Object modal: Modal que se abrirá.
* @var function callback: Acciones que se ejecutarán al terminar de abrí el modal.
*/
function open_form_modal(type, modal, callback)
{
    if (type == 'create' || type == 'update')
    {
        reset_form_modal(modal);

        if (type == 'update')
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
    else if (type == 'delete')
    {

    }

    modal.addClass('view');
}

/**
* @summary Envía el modal con el que se está trabajando en el CRUD al controlador.
*
* @var string type: Tipo de envío.
* @var HTML Object form: Formulario que se enviará.
*/
function send_form_modal(type, form, event)
{
    if (type == 'create' || type == 'update')
    {
        event.preventDefault();

        var data = new FormData(form[0]);

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
                check_form_errors(form, response, function()
                {
                    open_notification_modal('success', response.message);
                });
            }
        });
    }
    else if (type == 'block' || type == 'unblock')
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
    else if (type == 'delete')
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
* @param string target: Formulario a revisar.
*/
function reset_form_modal(modal)
{
    modal.find('form')[0].reset();
    modal.find('form').find('.uploader').find('img').attr('src', '../images/empty.png');
    modal.find('form').find('p.error').remove();
    modal.find('form').find('.error').removeClass('error');
}

/**
* @summary Abre el modal de notificación.
*
* @var string type: Tipo de notificación.
* @var string message: Mensaje que mostrará el modal
* @var string path: Ruta de recarga o redirección.
* @var string timeout: Tiempo en que se ejecura la recarga o redirección.
*/
function open_notification_modal(type, message, path, timeout)
{
    message = (message == undefined) ? '' : message;
    path = (path == undefined) ? ((type == 'success') ? 'reload' : false) : path;
    timeout = (timeout == undefined) ? '1500' : timeout;

    if (type == 'success')
    {
        $('[data-modal="success"]').addClass('view');
        $('[data-modal="success"]').find('main > p').html(message);
    }
    else if (type == 'alert')
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
* @param string target: Formulario a revisar.
* @param string response: Respuesta del controlador.
* @param string callback: Acciones que se ejecutarán en caso que no haya errores.
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
* @summary Revisa los valores de una cadena de texto.
*
* @param string type: Tipo de cadena de texto permitida.
* @param string string: Cadena de texto.
* @param string input: Input a revisar.
*/
function check_type_input(type, string, input)
{
    if (type == 'letter')
        var filter = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz';
    else if (type == 'uppercase')
        var filter = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    else if (type == 'lowercase')
        var filter = 'abcdefghijklmnñopqrstuvwxyz';
    else if (type == 'number')
        var filter = '0123456789';
    else if (type == 'decimal')
        var filter = '.0123456789';
    else if (type == 'letter_number')
        var filter = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz0123456789';
    else if (type == 'uppercase_number')
        var filter = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789';
    else if (type == 'lowercase_number')
        var filter = 'abcdefghijklmnñopqrstuvwxyz0123456789';

    var out = '';

    for (var i = 0; i < string.length; i++)
    {
        if (filter.indexOf(string.charAt(i)) != -1)
            out += string.charAt(i);
    }

    input.val(out);
}

/**
* @summary Genera una cadena de texto random.
*
* @param string type: Tipo de cadena de texto a generar.
* @param string length: Tamaño de la cadena de texto a generar.
* @param string input: Input a generar.
*/
function generate_random_token(type, length, input)
{
    if (type == 'letter')
        var string = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz';
    else if (type == 'uppercase')
        var string = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    else if (type == 'lowercase')
        var string = 'abcdefghijklmnñopqrstuvwxyz';
    else if (type == 'number')
        var string = '0123456789';
    else if (type == 'letter_number')
        var string = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz0123456789';
    else if (type == 'uppercase_number')
        var string = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789';
    else if (type == 'lowercase_number')
        var string = 'abcdefghijklmnñopqrstuvwxyz0123456789';

    var out  = '';

    for (var x = 0; x < length; x++)
    {
        var math = Math.floor(Math.random() * string.length);
        out += string.substr(math, 1);
    }

    input.val(out);
}

/**
* @summary Envia archivos al controlador para que se suban al almacenamiento.
*
* @param string target: Uploader.
* @param string type: (low, fast) Tipo de subida.
* @param boolean multiple: Define si se va a subir un solo archivo o muchos a la vez.
* @param string action: Tipo de acción que se enviará al controlador.
*/
function uploader(target, type, multiple, action)
{
    target.find('a[data-select]').on('click', function()
    {
        target.find('input[data-select]').click();
    });

    target.find('input[data-select]').on('change', function()
    {
        if ($(this)[0].files[0].type.match($(this).attr('accept')))
        {
            if (type == 'low')
            {
                var reader = new FileReader();

                reader.onload = function(e)
                {
                    target.find('[data-preview] > img').attr('src', e.target.result);
                }

                reader.readAsDataURL($(this)[0].files[0]);
            }
            else if (type == 'fast')
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
