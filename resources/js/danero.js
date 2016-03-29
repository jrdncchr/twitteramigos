$(function() {
    /* Sticky Footer */
    setColumnSize();
    $(window).resize(function () { setColumnSize(); });

    /* Activate Tooltips */
    activateTooltips();
});

function activateTooltips() {
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'top'
    });
}

function setColumnSize() {
    $('.wrapper').css('min-height', $( window ).height() - 189);
}

var inputErrors = {
    required : [], email : []
};
function validateForm(form) {
    inputErrors = {
        required : [], email : []
    };
    form.find('input, select, textarea').filter(':visible').each(function() {
        var e = $(this);
        if(!e.val()) {
            if($(this).hasClass('required')) {
                inputErrors.required.push(e);
            }
        } else {
            if($(this).hasClass('email')) {
                if(!validateEmail($(this).val())) {
                    inputErrors.email.push(e);
                }
            }
        }
        displayInputError(e, false);
    });

    var result = checkForAlertError(form);
    return result.success;
}

function checkForAlertError(form) {
    var result = {
        success : true
    };
    var i = 0;
    if(inputErrors.required.length > 0) {
        result.success = false;
        result.message = "A required field cannot be empty.";
        for(i = 0; i < inputErrors.required.length; i++) {
            displayInputError(inputErrors.required[i], true);
        }
    } else if(inputErrors.email.length > 0) {
        result.success = false;
        result.message = "Invalid email format.";
        for(i = 0; i < inputErrors.email.length; i++) {
            displayInputError(inputErrors.email[i], true);
        }
    }
    displayAlertError(form, !result.success, result.message)
    return result;
}

function enableFormValidationOnBlur(form) {
    form.find('input, select, textarea').filter(':visible').each(function() {
        var e = $(this);
        e.blur(function() {
            displayInputError(e, false);
            if(!e.val()) {
                if($(this).hasClass('required')) {
                    inputErrors.required.push(e);
                    displayInputError(e, true);
                }
            } else {
                removInputError(e, 'required');
                if($(this).hasClass('email')) {
                    if(!validateEmail($(this).val())) {
                        displayInputError(e, true);
                        inputErrors.email.push(e);
                    } else {
                        removInputError(e, 'email')
                    }
                }
            }
            checkForAlertError(form);
        });
    });
}

function displayInputError(input, show) {
    if(show) {
        input.parents('.form-group').addClass('has-error');
    } else {
        input.parents('.form-group').removeClass('has-error');
    }
}

function displayAlertError(form, show, message) {
    if(show) {
        form.find('.alert-danger').addClass('alert').html("<i class='fa fa-exclamation-circle'></i> " + message);
    } else {
        form.find('.alert-danger').removeClass('alert').html("");
    }
}

function removInputError(e, type) {
    $.each(inputErrors[type], function(i) {
        if(inputErrors[type][i] === e || inputErrors[type][i].attr('id') == e.attr('id')) {
            inputErrors[type].splice(i, 1);
            return false;
        }
    });
}

function clearFormInputs(form) {
    form.find('input, select, textarea').filter(':visible').each(function() {
        $(this).val("");
    });
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
