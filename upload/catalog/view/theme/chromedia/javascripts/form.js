var FormManager = (function($) {
    return {
        disableFormFields : function(form) {
            form.find('input, select, textarea')
                .attr('readonly', true)
                .css({'opacity' : 0.3});
        },

        enableFormFields : function(form) {
            form.find('input, select, textarea')
                .attr('readonly', false)
                .css({'opacity' : 1});
        }
    }
})(jQuery);


// TODO: Make this like that of above
var addFieldError = function(field) {
    if (!field.hasClass('has-error')) {
        field.closest('label').addClass('has-error');
        field.addClass('has-error');
    }
}

var showFormErrors = function(form) {
    var hasError = false;

    form.find('input[required="required"], select[required="required"]').each(function() {
        var value = $(this).val();

        if (value.length == 0) {
            addFieldError($(this));

            hasError = true;
        }
    });

    form.find('input[data-type="email"]').each(function() {
        var value = $(this).val();
        var emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;

        if (value.search(emailRegex) == -1) {
            var label = $(this).parent('label');

            if (!label.hasClass('has-error')) {
                addFieldError($(this));
            }

            hasError = true;
        }
    });

    return hasError;
}

var focusElement = function(element) {
    var offset = element.offset();

    $('html, body').animate({
      scrollTop: offset.top - 10
    }, 1000);
}

var removeErrors = function(form) {
    form.find('.has-error').each(function() {
        $(this).removeClass('has-error');
    });

    $('.notif-msg')
        .hide()
        .find('.notif').empty();
}

var showCheckoutGeneralError = function(message) {
    $('.notif-msg')
        .show()
        .find('.notif').html(message)
        .focus();

    focusElement($('.notif-msg'));

}
                                                                                                                                                                                                                             