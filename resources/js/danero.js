$(function() {
    /* Sticky Footer */
    setColumnSize();
    $(window).resize(function () { setColumnSize(); });

    /* Activate Tooltips */
    activateTooltips();

    /* toastr */
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": true
    };

    /* Modal Global Events  */
    $(".modal")
        .on("shown.bs.modal", function() {
            $(this).find('input, textarea, select')
                .not('input[type=hidden],input[type=button],input[type=submit],input[type=reset],input[type=image],button')
                .filter(':enabled:visible:first')
                .focus();
        })

        .on("hidden.bs.modal", function() {
            var modal = $(this);
            validator.displayAlertError(modal, false);
            modal.find('input, textarea, select')
                .not('input[type=hidden],input[type=button],input[type=submit],input[type=reset],input[type=image],button')
                .filter(':enabled')
                .each(function() {
                    validator.displayInputError($(this), false);
                    $(this).val("");
                });
        });
});

function activateTooltips() {
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'top'
    });
}

function setColumnSize() {
    $('.wrapper').css('min-height', $( window ).height() - 189);
}

