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
        form.css({'opacity' : 1});
        $('.btn-checkout').show();

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
                url: 'index.php?route=checkout/checkout/processOrder',//"<?php echo $this->url->link('checkout/checkout/processOrder', '', 'SSL'); ?>",
                data: data,
                dataType: 'json',     
                success: function(jsondata){
                    form.css({'opacity' : 1});
                    $('.btn-checkout').show();

                    if (jsondata.success) {
                        var token = jsondata.token;

                        window.location = 'index.php?route=checkout/checkout/onSuccess';//"<?php echo $this->url->link('checkout/checkout/onSuccess', '', 'SSL');?>"
                    } else {
                        showCheckoutGeneralError(jsondata.errorMsg);
                    }
                }
            });
        });
    }
}

$('.btn-checkout').on('click', function(e) {
    e.preventDefault();
    $(this).hide();

    var form = $('#payment-form');
    form.css({'opacity' : 0.5 });

    removeErrors(form);
    var hasError = showFormErrors(form);

    if (!hasError) {
        Stripe.card.createToken(form, stripeResponseHandler);
    } else {
        form.css({'opacity' : 1});
        $(this).show();
    }
});