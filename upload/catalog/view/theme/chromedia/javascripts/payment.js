var showCreditCardError = function(response) {
    var form = $('#payment-form');
    var errorCode = response.error.code;

    if (errorCode == "incorrect_number" || errorCode == "incorrect_number" || errorCode == "invalid_number") {
        addFieldError(form.find('#cc-number'));
        showCheckoutGeneralError("Please enter a valid card number.");
    } else if (errorCode == "invalid_expiry_month") {
        addFieldError(form.find('#cc-expirationMonth'));
        showCheckoutGeneralError("Please enter a valid expiry month.");
    } else if (errorCode == "invalid_expiry_year") {
        addFieldError(form.find('#cc-expirationYear'));
        showCheckoutGeneralError("Please enter a valid expiry year.");
    } else if (errorCode == "invalid_cvc") {
        addFieldError(form.find('#cc-securityCode'));
        showCheckoutGeneralError("Please enter a valid security code.");
    }   else {
        showCheckoutGeneralError("There was an error processing your form. Please try again.");
    }
}

var stripeResponseHandler = function(status, response) {
    var form = $('#payment-form');

    if (response.error) {
        payAjaxLoad(true);

        showCreditCardError(response);
    } else {
        var token = response.id;

        var data = {
            service_name : $('.shipping-option:checked').val(),
            customer_email : $('#shipping-email').val(),
            token : token,
            customer_name : $('#payment-name').val()
        }

        // Send form data to server with POST method, then retrieve json data
        $(function() {
            $.ajax({
                type: "POST",
                url: 'index.php?route=checkout/checkout/processOrder',
                data: data,
                dataType: 'json',     
                success: function(jsondata){
                    if (jsondata.success) {
                        window.location = 'index.php?route=checkout/success';
                    } else {
                        payAjaxLoad(true);
                        showCheckoutGeneralError(jsondata.errorMsg);
                    }
                }
            });
        });
    }
}

var payAjaxLoad = function(isDone) {
    var form = $('#payment-form');

    if (isDone) {
        form.children('div.row:last-child').children('div:first-child').addClass('mt20');
        FormManager.enableFormFields(form);
        form.find('.loader-container').removeLoader();
        form.children(':not(.loader-container)').css({'opacity' : '1'});

        $('.pay-via-paypal').show();
        $('.btn-checkout').show();
    } else {
        form.children('div.row:last-child').children('.mt20').removeClass('mt20');
        FormManager.disableFormFields(form);
        form.find('.loader-container').showLoader({'size' : 'small'});
        form.children(':not(.loader-container)').css({'opacity' : '0.3'});

        $('.pay-via-paypal').hide();
        $('.btn-checkout').hide();
    }
}

$('.btn-checkout').on('click', function(e) {
    e.preventDefault();
    payAjaxLoad(false);
    var form = $('#payment-form');

    removeErrors(form);
    var hasError = showFormErrors(form);

    if (!hasError) {
        Stripe.card.createToken(form, stripeResponseHandler);
    } else {
        payAjaxLoad(true);
    }
});

// PAYPAL //
$('.pay-via-paypal').off('click').on('click', function() {
    var data = {
        service_name : $('.shipping-option:checked').val()
    }

    payAjaxLoad(false);

    $.ajax({
        type: "POST",
        url: 'index.php?route=checkout/checkout/payViaPaypal',
        data: data,
        dataType: 'json',     
        success: function(jsondata){
            if (jsondata.success) {
                window.location = jsondata.url;
            } else {
                payAjaxLoad(true);
                showCheckoutGeneralError(jsondata.errorMsg);
            }
        },
        error: function() {
            payAjaxLoad(true);
        }
    });
});