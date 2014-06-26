var retrieveShipmentRates = function(form, event) {
    removeErrors(form);
    var hasError = showFormErrors(form);

    shipmentFormStatus(form, false);
    $('.display-on-rates-checked').hide();
    
    if (!hasError) {
        var data = form.serialize();    

        // Send POST data to server
        $.ajax({
            type: "POST",
            url: "<?php echo $this->url->link('checkout/checkout/checkShippingInfo', '', 'SSL'); ?>",
            data: data,
            dataType: 'json',
            success: function(jsondata) {
                $('.shipping-selection').find('.alert-box').remove();
                $('.shipping-selection').remove('label');
                
                shipmentFormStatus(form, true);

                if (jsondata.success && jsondata.rates) {
                    var rates = jsondata.rates;

                    $('.shipping-selection').children().not('h3').remove();

                    if (jsondata.rates_count > 0) {
                        $.each(rates, function(index, rate) {
                            var service = rate.service;
                            var alias = service.split(' ').join('-');

                            $('.shipping-selection').append('<label for="'+alias+'"><input class="shipping-option" type="radio" id="'+alias+'" name="shipping-option" amount="'+rate.total+'" value="'+service+'" days="'+rate.days+'"> '+service+'  <em>(average of '+rate.days+' days - <b>'+rate.total+'</b>)</em></label>');
                        });

                        $('.display-on-rates-checked').show();
                        $('.shipping-selection').find('.shipping-option:first').prop('checked', true).trigger('click');

                        setShipmentData();
                    } else {
                        $('.display-on-rates-checked:first').show();
                        $('<div data-alert class="alert-box alert radius">Sorry, no shipping is available for the provided address or the amount of products.</div>').insertAfter($('.shipping-selection').children('h3'));
                    }

                } else {
                    var message = jsondata.errorMsg ? jsondata.errorMsg : 'An error occured. Please make sure data are correct.';
                    showCheckoutGeneralError(message);
                }
            },
            error: function(error) {
                shipmentFormStatus(form, true);
                alert('error');
            }
        }); 
    } else {
        shipmentFormStatus(form, true);
    }
}

 var refreshShipmentData = function() {
    $('.display-on-rates-checked').hide();
    
    if ($('.shipping-selection').find('.shipping-option').length > 0) {
        retrieveShipmentRates($('#shipment-form'));
    }
}

var shipmentFormStatus = function(form, is_active) {
    if (is_active) {
        form.children().css({'opacity' : '1'});
        form.removeLoader();
        form.find('input[type="submit"]').show();
    } else {
        form.children(':not(.loader)').css({'opacity' : '0.3'});
        form.showLoader({'size' : 'small'});
        form.find('input[type="submit"]').show();
    }
}

 var setShipmentData = function() {
        var address = [
            $('#shipping-street-address').val(),
            $('#shipping-city').val(),
            getState(),
            $('#field-shipping-postcode').val()
        ]

        var shipmentOption = $('input[name="shipping-option"]:checked');

        shipmentData = {
            name : $('#shipping-name').val(),
            email : $('#shipping-email').val(),
            address : address.join(', '),
            shipment: shipmentOption.val()+' (average of '+shipmentOption.attr('days')+' days for '+shipmentOption.attr('amount')+')'
        }
    }

    var getShipmentData = function() {
        return shipmentData;
    }

    var getState = function() {
        var stateField = $('label[for="state"]').children('select:visible');

        if (stateField.length > 0) {
            return stateField.find(':selected').text();
        } else {
            return $('label[for="state"]').children('input:visible');
        }
    }

$('.shipping-selection').on('click', '.shipping-option', function() {
    var shippingAmount = parseFloat($(this).attr('amount'));
    
    updateShipment(shippingAmount);
});

$('#shipment-form').off('submit').on('submit', function(e) {
    e.preventDefault();

    retrieveShipmentRates($(this));
});

$('.edit-shipping').off('click').on('click', function() {
    activateStep1();
});

$('#shipping-country').on('change', function() {
    var value = $(this).val();
    $('#shipping-province, select[name="state"]').hide();
    $('#shipping-province, select[name="state"]').attr('disabled', 'disabled');
    $('#shipping-province, select[name="state"]').removeAttr('required');

    if (value == 'US') {
        $('#shipping-us-states').show();
        $('#shipping-us-states').removeAttr('disabled');
        $('#shipping-us-states').attr('required', 'required');
    } else  if (value == "CA") {
        $('#shipping-canada-regions').show();
        $('#shipping-canada-regions').removeAttr('disabled');
        $('#shipping-canada-regions').attr('required', 'required');
    } else {
        $('#shipping-province').show();
        $('#shipping-province').removeAttr('disabled');
        $('#shipping-province').attr('required', 'required');
    }
});