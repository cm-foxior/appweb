'use strict';

$( document ).ready(function ()
{
    var MyDocument = $(this);

    /* Sign in
	--------------------------------------------------------------------------- */
    var btnSignIn = $('[data-action="signin"]');
    var frmSignIn = $('form[name="signin"]');

    btnSignIn.on('click', function()
    {
        frmSignIn.submit();
    });

    frmSignIn.on('submit', function(e)
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

                });
            }
        });
    });
});
