'use strict';

$( document ).ready(function ()
{
    var MyDocument = $( this );

    MyDocument.find('.owl-carousel').owlCarousel({
        items: 1,
        animateOut: 'fadeOut',
        autoplay: true,
        loop: true,
        mouseDrag: false,
        touchDrag: false,
    });

    MyDocument.find('input').change(function ()
    {
        var input = $(this);

        checkInputsEmpty(input);
    });

    MyDocument.find('form[name="login"]').submit(function ( event )
    {
        event.preventDefault();

        var self = $(this);
        var data = self.serialize();
        var checkEmpty;

        checkEmpty = checkInputsEmpty($('input[name="username"]'));
        checkEmpty = checkInputsEmpty($('input[name="password"]'));

        if ( checkEmpty !== true )
        {
            $.ajax({
                url: '',
                type: "POST",
                data: data,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function ( response )
                {
                    var a = self.find('[name]').parents('.input-group');
                        a.find('label').removeClass('error');
                        a.find('> p.pre-error').removeClass('error').hide();
                        a.find('> p.error').remove();

                    if ( response.status == 'success' )
                    {
                        window.location = '/';
                    }
                    else
                    {
                        if ( response.labels && response.labels.length > 0 )
                        {
                            $.each(response.labels, function (i, label)
                            {
                                var b = self.find('[name="'+ label[0] +'"]').parents('.input-group');
                                    b.append('<p class="error">'+ label[1] +'</p>');
                                    b.find('label').addClass('error');
                            });
                        }
                    }
                }
            });
        }
    });
});
