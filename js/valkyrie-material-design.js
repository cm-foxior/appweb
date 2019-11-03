'use strict';

var clickEventType = ((!document.ontouchstart) ? 'click' : 'touchend');

$(document).ready(function ()
{
    $('html,body').on(clickEventType, function ()
    {
        $('*.open').removeClass('open');
    });

    $('[href=""],.btn[disabled]').on(clickEventType, function ()
    {
        return false;
    });

    $('.md--group-form').each(function ()
    {
        var self = $(this);

        if (self.hasClass('md--select')) /* Type Select */
        {
            var button = self.find('> p,> label');
            var output = self.find('> p:not(p.text)');
            var option = self.find('div.dropdown > span');
            var input  = self.find('> input[type="hidden"]');

            button.on(clickEventType, function ( event )
            {
                event.stopPropagation();
                $('.md--group-form.md--select.open').removeClass('open');
                self.toggleClass('open');
            });

            option.on(clickEventType, function ()
            {
                var value = $(this).data('value');
                var text  = $(this).text();

                output.html(text);
                input.val(value).trigger('change');

                self.removeClass('error');
                self.addClass('valid');
            });
        }
        else /* Type input or textarea */
        {
            var label = self.find('> label');
            var input = self.find('> input,> textarea,> select');

            label.on(clickEventType, function ()
            {
                input.focus();
            });

            input.change(function ()
            {
                if (input.is(':invalid'))
                    self.addClass('error');
                else
                    self.removeClass('error');
            });
        }
    });

    $('.toggles').each(function ()
    {
        var self = $(this);
        var clicked = self.find('.toggle > h3');

        self.find('> .toggle.view').find('> .box').show();
        /* Icons */
        self.find('> .toggle.view').find('> h3 > .icon').html('remove');
        /* End Icons */

        clicked.on(clickEventType, function ()
        {
            var $this = $(this).parent();

            if (self.hasClass('accordion'))
            {
                /* Icons */
                self.find('.toggle > h3 > .icon').html('add');
                $this.find('> h3 > .icon').html('remove');
                /* End Icons */

                if ($this.hasClass('view'))
                    return false;

                self.find('.view').removeClass('view');
                $this.addClass('view');
                self.find('.toggle > .box').slideUp(300);
                $this.find('> .box').slideDown(300);
            }
            else
            {
                /* Icons */
                if (!$this.hasClass('view'))
                    $this.find('> h3 > .icon').html('remove');
                else
                    $this.find('> h3 > .icon').html('add');
                /* End Icons */

                $this.toggleClass('view');
                $this.find('> .box').slideToggle(300);
            }
        });
    });

    // $('[data-modal]').each(function ()
    // {
    //     var self = $(this);
    //     var target = self.data('modal');
    //     var modal = $('body').find('[data-modal-target-destination="'+ target +'"]');
    //     var buttonSend = modal.find('[data-action-modal="send"]');
    //     var buttonCancel = modal.find('[data-action-modal="cancel"]');
    //
    //     self.on(clickEventType, function ()
    //     {
    //         modal.addClass('view');
    //         $('body').addClass('noscroll');
    //     });
    //
    //     $( document ).on(clickEventType, buttonCancel, function ()
    //     {
    //         setTimeout(function ()
    //         {
    //             modal.removeClass('view');
    //             $('body').removeClass('noscroll');
    //         }, 300);
    //     });
    // });

    $( document ).on(clickEventType, '.tabs > ul.tab-buttons > li[data-tab]', function ()
    {
        var $this = $(this);
        var target = $this.data('tab');
        var content = $this.parents('.tabs');
        var tabContent = content.find('.tab-content[data-tab-target-destination="'+ target +'"]');
        var tabButton = content.find('> ul.tab-buttons > li[data-tab]');

        tabButton.removeClass('view');
        $this.addClass('view');

        content.find('.tab-content.view').removeClass('view');
        tabContent.addClass('view');
    });

    $('[data-position]').each(function ()
    {
        var self        = $(this);
        var position    = self.data('position');
        var level       = self.data('z-index');

        if (position)
            self.css('position', position);

        if (level)
            self.css('z-index', level);
    });

    $("[data-smooth-scroll]").click(function ()
    {
        if (location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "")
            && location.hostname === this.hostname)
        {
            var a = $(this.hash);
            a = a.length ? a : $("[name=" + this.hash.slice(1) + "]");

            if (a.length)
            {
                $("html,body").animate({scrollTop: a.offset().top}, 1000);
                return false;
            }
        }
    });

    $('[data-image-src]').each(function () {
        var self    = $(this);
        var image   = self.data('image-src');

        if (image)
            self.css('background-image','url("' + image + '")');
    });
});

/* Ripple */
$(function ()
{
    var _ripple = '[data-ripple]';

    $('body').on(clickEventType +' tap', _ripple, function ()
    {
        var self = $(this);
        var ripple = self.find('> .ripple');

        if ( !ripple.length )
            self.append('<div class="ripple"></div>');
    });

    $('body').on(clickEventType +' tap', _ripple, function ( event )
    {
        var self        = $(this);
        var ripple      = self.find('> .ripple');
        var offset      = self.offset();
        var relativeX   = ((event.pageX - offset.left) - 20);
        var relativeY   = ((event.pageY - offset.top) - 20);
        var speed       = (self.data('ripple').length > 0) ? self.data('ripple') : 'medium';
        var type        = (self.data('ripple-type')) ? self.data('ripple-type') : '';
        var color       = (self.data('ripple-color')) ? self.data('ripple-color') : 'rgba(0,0,0,0.3)';

        if ( type == 'center' )
        {
            ripple.css({
                "top": "0",
                "left": "0",
                "right": "0",
                "bottom": "0",
                "margin": "auto",
                "width": "100%",
                "height": "100%",
                "background-color": color
            });
        }
        else if ( type == 'tap' )
        {
            ripple.css({
                "top": "0",
                "left": "0",
                "right": "0",
                "bottom": "0",
                "margin": "auto",
                "background-color": color
            });
        }
        else
        {
            ripple.css({
                "top": relativeY,
                "left": relativeX,
                "background-color": color
            });
        }

        ripple.addClass('ripple-effect '+ speed);

        setTimeout(function ()
        {
            ripple.remove();
        }, 500);
    });
});

/* Parallax */
$(function ()
{
    $('[data-parallax]').each(function ()
    {
        var config = {};

        config.parallax         = $(this);
        config.container        = config.parallax.parents('.parallax-content');
        config.pixelsTop        = config.container.offset().top;
        config.startParallax    = config.pixelsTop - $( window ).height();
        config.speed            = config.parallax.data('parallax-speed');
        config.direction        = config.parallax.data('parallax-direction');

        config.speed = ( config.speed ) ? config.speed : 5;
        config.direction = (config.direction == 'up') ? '-' : '';

        var touchSupported = (('ontouchstart' in window) ||
                            window.DocumentTouch && document instanceof DocumentTouch);

        $( document )
            .each(function () { scroller(); })
            .on("scroll", function () { scroller(); });

        if ( touchSupported )
            $( document ).on('touchmove', function () { scroller(); });

        function scroller()
        {
            if ($( document ).scrollTop() >= config.startParallax)
            {
                var scroll = (($( document ).scrollTop() - config.startParallax) / config.speed);

                config.parallax.css({
                    '-webkit-transform': 'translate(0px, '+ config.direction + scroll +'px)',
                    'transform': 'translate(0px, '+ config.direction + scroll +'px)'
                });
            }
            else
            {
                config.parallax.css({
                    '-webkit-transform': 'translate(0px, 0px)',
                    'transform': 'translate(0px, 0px)'
                });
            }
        }
    });
});

/* Back to top */
function BackToTop(selector)
{
    $(selector).hide();

    $(window).scroll(function ()
    {
        if ($(this).scrollTop() > 100)
            $(selector).fadeIn();
        else
            $(selector).fadeOut();
    });

    $(selector).on(clickEventType, function ()
    {
        $('body,html').animate(
        {
            scrollTop: 0
        }, 800);

        return false;
    });
}

/* ToggleAttr */
$.fn.toggleAttr = function (attr, value)
{
    return this.each(function ()
    {
        var self = $(this);
        if (self.attr(attr) == value)
            self.attr(attr, '');
        else
            self.attr(attr, value);
    });
};

/* On Scroll Down */
function scrollDown (a, b, c, d)
{
    var config = {};

    config.a = a;
    config.b = b;
    config.c = c;
    config.d = d;

    if (!config.d)
        config.d = $( document );
    else
        config.d = $(''+ config.d +'');

    config.d
        .each(function () { scroller() })
        .on("scroll", function () { scroller() });

    function scroller ()
    {
        if (config.d.scrollTop() > config.c)
            $(config.a).addClass(config.b);
        else
            $(config.a).removeClass(config.b);
    }
}
