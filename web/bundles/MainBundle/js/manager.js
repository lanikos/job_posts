$(document).ready(function() {
    form_bind_submit('form', "POST");
    
});


function form_bind_submit(form, method) {
    $('#submitJob').on('click', function(event) {
        event.preventDefault();

        if (validate()) {
            $.ajax({
                type: method,
                url: $(form).attr('action'),
                data: $(form).serialize()
            }) .done(function(response, textStatus, jqXHR) {
                    $('.container-fluid').html(response.content);
            }) .fail(function() {
                alert( "Error!" );
            });
        }

    });
}

function validate() {
    valid = true;

    var mail_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/ ;
    if (!mail_regex.test($('#inputMail').val())) {
        $('#fg-mail').addClass('has-error');
        $('#helpBlock-mail').html('Invalid mail adress');
        valid = false;
    } else {
        $('#fg-mail').removeClass('has-error');
        $('#helpBlock-mail').html('');
    }

    return valid;
}
