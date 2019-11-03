'use strict';

$( document ).ready(function ()
{
    var MyDocument = $(this);

    /* efectos visuales
    --------------------------------------------------------------------------- */
    navScrollDown('header.main-menu', 'down',  0);

    $('[data-open-r-menu]').on('click', function()
    {
        $('header.main-menu nav.menu').toggleClass('open');
    });

    /* slider del home
    /* ------------------------------------------------------------------------ */
    MyDocument.find('.owl-carousel').owlCarousel({
        items: 1,
        animateOut: 'fadeOut',
        autoplay: true,
        loop: true,
        mouseDrag: false,
        touchDrag: false
    });

    /* know us controles del video
    --------------------------------------------------------------------------- */
    var know_us_video                  = document.getElementById('know_us_video');
    var btn_play_know_us_video         = $('[data-action="play_know_us_video"]');
    var btn_pause_know_us_video        = $('[data-action="pause_know_us_video"]');
    var btn_stop_know_us_video         = $('[data-action="stop_know_us_video"]');

    var know_us_video_played = true;

    // $('#know_us_video').NodeAction(
    // function()
    // {
    //     if (know_us_video_played == true)
    //     {
    //         know_us_video.play();
    //
    //         btn_play_know_us_video.removeClass('view');
    //         btn_pause_know_us_video.addClass('view');
    //         btn_stop_know_us_video.addClass('view');
    //
    //         btn_play_know_us_video.removeClass('not-disappear');
    //         btn_pause_know_us_video.removeClass('not-disappear');
    //         btn_stop_know_us_video.removeClass('not-disappear');
    //     }
    //
    // },
    // function()
    // {
    //     if (know_us_video_played == true)
    //     {
    //         know_us_video.pause();
    //
    //         btn_play_know_us_video.addClass('view');
    //         btn_pause_know_us_video.removeClass('view');
    //         btn_stop_know_us_video.addClass('view');
    //
    //         btn_play_know_us_video.addClass('not-disappear');
    //         btn_pause_know_us_video.removeClass('not-disappear');
    //         btn_stop_know_us_video.addClass('not-disappear');
    //     }
    // });

    btn_play_know_us_video.on('click', function()
    {
        know_us_video.play();

        btn_play_know_us_video.removeClass('view');
        btn_pause_know_us_video.addClass('view');
        btn_stop_know_us_video.addClass('view');

        btn_play_know_us_video.removeClass('not-disappear');
        btn_pause_know_us_video.removeClass('not-disappear');
        btn_stop_know_us_video.removeClass('not-disappear');

        know_us_video_played = true;
    });

    btn_pause_know_us_video.on('click', function()
    {
        know_us_video.pause();

        btn_play_know_us_video.addClass('view');
        btn_pause_know_us_video.removeClass('view');
        btn_stop_know_us_video.addClass('view');

        btn_play_know_us_video.addClass('not-disappear');
        btn_pause_know_us_video.removeClass('not-disappear');
        btn_stop_know_us_video.addClass('not-disappear');

        know_us_video_played = false;
    });

    btn_stop_know_us_video.on('click', function()
    {
        know_us_video.pause();
        know_us_video.currentTime = 0;

        btn_play_know_us_video.addClass('view');
        btn_pause_know_us_video.removeClass('view');
        btn_stop_know_us_video.removeClass('view');

        btn_play_know_us_video.addClass('not-disappear');
        btn_pause_know_us_video.removeClass('not-disappear');
        btn_stop_know_us_video.removeClass('not-disappear');

        know_us_video_played = false;
    });

    /* envío de correo electrónico de contacto
    --------------------------------------------------------------------------- */
    var btnSendContactEmail = $('[data-action="sendContactEmail"]');
    var frmContact = $('form[name="contact"]');

    btnSendContactEmail.on('click', function()
    {
        frmContact.submit();
    });

    frmContact.on('submit', function(e)
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
                    $('[data-modal="emailHasBeenSent"]').addClass('view');
                    $('[data-modal="emailHasBeenSent"] main').html('<p><strong>' + response.data.name + '</strong> gracias por comunicarte con nosotros. En breve uno de nuestros asesores de ventas se comunicaran contigo a través de tu teléfono <strong>' + response.data.phone + '</strong> o tu correo electrónico <strong>' + response.data.email + '</strong> ¡Estamos para servirte!</p>');
                });
            }
        });
    });

    modal('emailHasBeenSent', function(modal)
    {
        $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
        location.reload();
    });
});

function navScrollDown($target, $class, $height)
{
    var nav = {
        initialize: function ()
        {
            $( document ).each(function () { nav.scroller() });
            $( document ).on("scroll", function () { nav.scroller() });
        },
        scroller: function ()
        {
            if ($(document).scrollTop() > $height)
                $($target).addClass($class);
            else
                $($target).removeClass($class);
        }
    }

    nav.initialize();
}

/**
* @name NodeAction
* @description Manda a llamar una funcion cuando entre en el nodo html y ejecuta otra cuando salga del nodo.
*
* @return null;
*/
$.fn.NodeAction = function ( callback_on, callback_off )
{
    var self = $(this);
    var config = {};

    config.pixelsTop = self.offset().top;
    config.node = config.pixelsTop - $( window ).height();

    config.selfHeight = self.height();

    var checkHeightNode = null;

    $( document )
        .each(function () { scroller() })
        .on("scroll", function () { scroller() });

    function scroller ()
    {
        if ( $( document ).scrollTop() >= (config.node + 400) )
        {
            if ( checkHeightNode == null )
                checkHeightNode = $( document ).scrollTop();

            if ( $( document ).scrollTop() >= (checkHeightNode + config.selfHeight) )
                callback_off();
            else
                callback_on();

        }
        else
            callback_off();
    }
}
/** END **/
