'use strict';

$( window ).on('beforeunload ajaxStart', function ()
{
    $('body').prepend('<div data-loader-ajax><div class="loader-01"></div></div>');
});

$( window ).on('ajaxStop', function ()
{
    $('body').find('[data-loader-ajax]').remove();
});

$( document ).ready(function ()
{
    var MyDocument = $( this );

    MyDocument.on(clickEventType, '[disabled]', function ()
    {
        return false;
    });

    /* dropdown menu
    --------------------------------------------------------------------------- */
    MyDocument.on('click', '.dropdown-menu > button', function ( event )
    {
        event.stopPropagation();

        var self = $(this);
        var parent = self.parent();
        var dropdown = parent.find('> .dropdown');

        dropdown.addClass('open');
        dropdown.find('a:not([href])').on('click', function ( event ) { event.stopPropagation(); });
    });

    /* tab buttons
    --------------------------------------------------------------------------- */
    var btnTab = $('[button-tab]');

    btnTab.on('click', function()
    {
        var self = $(this);

        btnTab.removeClass('view');
        self.addClass('view');
    });

    /* upload image
    --------------------------------------------------------------------------- */
    $('div.upload-image').each(function ()
    {
        var self = $(this);
        var button = self.find('[select-image]');
        var clearButton = self.find('[clear-image]');
        var input = self.find('input[image-preview]');

        button.on('click', function ()
        {
            input.click();
        });

        clearButton.on('click', function()
        {
            input.val('');
            self.find('[image-preview]').removeAttr('style');
        });

        input.change(function ()
        {
            var id = $(this).attr('id');
            var target = $(this).attr('image-preview');
            var input  = document.getElementById(id);

            if ( window.FileReader )
            {
                var file    = input.files[0];
                var reader  = new FileReader();

                if ( file && file.type.match('image.*') )
                    reader.readAsDataURL( file );

                reader.onloadend = function ()
                {
                    self.find('div.image-preview[image-preview="'+ target +'"]').attr('style', 'background-image: url('+ reader.result +')');
                }
            }
        });
    });

    /* modal
    --------------------------------------------------------------------------- */
    MyDocument.on('click', '[data-button-modal]', function ()
    {
        var self = $(this);
        var target = self.data('button-modal');
        var modal = MyDocument.find('[data-modal="'+ target +'"]');

        $('body').addClass('noscroll');
        modal.addClass('view').animate({ scrollTop: 0 }, 300);
    });

    $('[data-modal]').each(function ()
    {
        var self = $(this);
        var content = self.find('> div.content');
        var closeOnOverlay = self.data('close-on-overlay');
        var buttonClose = self.find('[button-close]');
        var buttonCancel = self.find('[button-cancel]');

        content.on('click', function ( event ) { event.stopPropagation(); });

        if ( closeOnOverlay === true )
            self.on('click', function () { close(); });

        buttonClose.on('click', function () { close(); });

        buttonCancel.on('click', function ()
        {
            self.find('label.error').removeClass('error');
            self.find('p.pre-error').hide().removeClass('error');
            self.find('p.error:not(p.pre-error)').remove();
            self.find('div.image-preview').removeAttr('style');
        });

        function close ()
        {
            $('body').removeClass('noscroll');
            self.removeClass('view');
        }
    });
});

function checkInputsEmpty( input )
{
    var fieldset = input.parents('fieldset.input-group');
    var labelImportant = fieldset.find('> label[data-important]');

    fieldset.find('p.error:not(p.pre-error)').remove();

    if ( labelImportant.length > 0 )
    {
        if ( input.val() === '' )
        {
            labelImportant.addClass('error');
            fieldset.find('.pre-error').addClass('error').show();

            return true;
        }
        else
        {
            labelImportant.removeClass('error');
            fieldset.find('.pre-error').hide().removeClass('error');

            return false;
        }
    }
}

function checkValidateForm( form )
{
    var checkEmpty;

    form.find('[name]').each(function ()
    {
        var self = $(this);
        var response = checkInputsEmpty(self);

        if ( response === true )
            checkEmpty = response;
    });

    if ( checkEmpty === true )
    {
        var elementsErrors = form.find('label.error');
            elementsErrors = elementsErrors[0];

            elementsErrors.focus();

        return false;
    }
    else
        return true;
}

function checkValidateFormAjax( form, response, callback )
{
    var a = form.find('[name]').parents('.input-group');
        a.find('label').removeClass('error');
        a.find('> p.pre-error').removeClass('error').hide();
        a.find('> p.error').remove();

    if ( response.status == 'success' )
        callback();
    else
    {
        if ( response.labels && response.labels.length > 0 )
        {
            $.each(response.labels, function (i, label)
            {
                var b = form.find('[name="'+ label[0] +'"]').parents('.input-group');
                    b.append('<p class="error">'+ label[1] +'</p>');
                    b.find('label').addClass('error');
            });

            form.find('input[name="'+ response.labels[0][0] +'"]').focus();
        }
    }
}

function menuActive( index )
{
    $('body > aside.leftbar').find('li[data-target="'+ index +'"]').addClass('active');
}

function modal( modal, callback_cancel, callback_success )
{
    var modal = $( document ).find('[data-modal="'+ modal +'"]');
    var buttonCancel = modal.find('[button-cancel]');
    var buttonSuccess = modal.find('[button-success]');

    buttonCancel.on('click', function ()
    {
        if ( callback_cancel != null )
            callback_cancel( modal );

        $('body').removeClass('noscroll');
        modal.removeClass('view');
    });

    buttonSuccess.on('click', function ()
    {
        if ( callback_success != null )
            callback_success( modal );
    });
}

function multipleSelect( button, url, callback )
{
    var selected = [];

    $("[data-check-all]").change(function ()
    {
        var self = $(this);
        var check = $("input[data-check]:checkbox");

        if( !check.is(':checked') )
        {
            check.prop('checked', true);
            self.prop('checked', true);

            $("[data-check]").each(function ()
            {
                var value = $(this).val();
                if( isNaN(value) == false )
                    selected.push(value);
            });
        }
        else
        {
            check.prop('checked', false);
            self.prop('checked', false);

            $("[data-check]").each(function ()
            {
                var removeItem = $(this).val();
                selected = jQuery.grep(selected, function ( value )
                {
                    return value != removeItem;
                });
            });
        }
    });

    $('[data-check]').change(function ()
    {
        var self = $(this);

        if ( !self.is(':checked') )
        {
            $("[data-check-all]").prop('checked', false);

            var removeItem = self.val();
            selected = jQuery.grep(selected, function ( value )
            {
                return value != removeItem;
            });
        }
        else
        {
            var value = $(this).val();
            // if( isNaN(value) == false )
                selected.push(value);
        }
    });

    button.on('click', function ()
    {
        var jsonString = JSON.stringify(selected);

        $.ajax({
            url: url,
            type: "POST",
            data: 'data=' + jsonString,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function( response )
            {
                if ( callback != null )
                    callback(response);
            }
        });
    });
}

$(function() {
    $(".chosen-select").chosen();
});
