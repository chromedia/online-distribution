var shipment_checking_xhr = null;

var retrieveShipmentRates = function(form, event) {
    removeErrors(form);
    var hasError = showFormErrors(form);

    shipmentFormStatus(form, false);
    $('.display-on-rates-checked').hide();
    
    if (!hasError) {
        var data = form.serialize();

        if(shipment_checking_xhr && shipment_checking_xhr.readyState != 4 && shipment_checking_xhr.readyState != 0){
            shipment_checking_xhr.abort();
        }

        // Send POST data to server
        shipment_checking_xhr = $.ajax({
            type: "POST",
            url: "index.php?route=checkout/checkout/checkShippingInfo",
            data: data,
            dataType: 'json',
            success: function(jsondata) {
                $('.shipping-selection').find('.alert-box').remove();
                $('.shipping-selection').remove('label');
                
                shipmentFormStatus(form, true);

                if (jsondata.success && jsondata.rates) {
                    var rates = jsondata.rates;
                    var providers = jsondata.providers;

                    $('.shipping-selection').children().not('.items-header').remove();

                    if (jsondata.rates_count > 0) {
                        $.each(providers, function(index, provider) {
                            var ratesOfProvider = rates[provider];
                            var labelStyle = "margin-top: 10px;"

                            $('.shipping-selection').append('<span style="padding:10px;">'+provider+'</span>');

                            $.each(ratesOfProvider, function(index, rate) {
                                var service = rate.service;
                                var alias = service.split(' ').join('-');

                                $('.shipping-selection').append('<label for="'+alias+'" style="'+labelStyle+'"><input class="shipping-option" type="radio" id="'+alias+'" name="shipping-option" amount="'+rate.total+'" value="'+service+'" days="'+rate.days+'"> '+service+'  <em>(average of '+rate.days+' day/s - <b>'+rate.total+'</b>)</em></label>');
                                labelStyle = '';
                            });
                        });


                        // $.each(rates, function(index, rate) {
                        //     var service = rate.service;
                        //     var alias = service.split(' ').join('-');

                        //     $('.shipping-selection').append('<label for="'+alias+'"><input class="shipping-option" type="radio" id="'+alias+'" name="shipping-option" amount="'+rate.total+'" value="'+service+'" days="'+rate.days+'"> '+service+'  <em>(average of '+rate.days+' day/s - <b>'+rate.total+'</b>)</em></label>');
                        // });

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
        FormManager.enableFormFields(form);
        form.children().not('input[type="submit"]').css({'opacity' : '1'});

        form.removeLoader();
        form.find('input[type="submit"]').css({'opacity' : '0.9'});
        form.find('input[type="submit"]').show();

        $('.remove').show();
        $('.qty-in-cart').show();
        $('.qty-in-cart').next('span').hide();
    } else {
        form.removeLoader();
        FormManager.disableFormFields(form);
        form.children(':not(.loader)').css({'opacity' : '0.3'});
        form.showLoader({'size' : 'small'});
        form.find('input[type="submit"]').hide();

        $('.remove').hide();
        $('.qty-in-cart').hide();
        $('.qty-in-cart').next('span').show();
    }
}

var getState = function() {
    var stateField = $('.state-container').find('select:visible');

    if (stateField.length > 0) {
        return stateField.find(':selected').text();
    } else {
        return $('.state-container').find('input:visible').val();
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

var shipment_storing_xhr;

var storeShipmentDataInSession = function(key_values) {

    // if(shipment_storing_xhr && shipment_storing_xhr.readyState != 4 && shipment_storing_xhr.readyState != 0){
    //     shipment_storing_xhr.abort();
    // }

    shipment_storing_xhr = $.ajax({
        type: "POST",
        url: "index.php?route=checkout/checkout/storeShippingInformation",
        data: { data : key_values },
        dataType: 'json',

    }); 
}

$('.shipping-selection').on('click', '.shipping-option', function() {
    var shippingAmount = parseFloat($(this).attr('amount'));
    
    updateShipment(shippingAmount);

    var data = {
        cost : shippingAmount,
        method : $(this).val()
    }

    //storeShipmentDataInSession(data);
});

$('#shipment-form').off('submit').on('submit', function(e) {
    e.preventDefault();

    retrieveShipmentRates($(this));
});


$('#shipment-form').find('input').on('change', function(e) {
    e.preventDefault(); 

    var data = {};
    data[$(this).attr('name')] = $(this).val();

    storeShipmentDataInSession(data);
});

$('select:not("#shipping-country")').on('change', function() {
    var data = {};
    data[$(this).attr('name')] = $(this).val();
    
    storeShipmentDataInSession(data);
});

$('.edit-shipping').off('click').on('click', function() {
    activateStep1();
});

$('#shipping-country').on('change', function() {
    var value = $(this).val();

    $('#shipping-province, select[name="state"]').hide();
    $('#shipping-province, select[name="state"]').attr('disabled', 'disabled');
    $('#shipping-province, select[name="state"]').removeAttr('required');

    var data = {};
    data[$(this).attr('name')] = value;

    if (value == 'US') {
        $('#shipping-us-states').show();
        $('#shipping-us-states').removeAttr('disabled');
        $('#shipping-us-states').attr('required', 'required');

        data['state'] = $('#shipping-us-states').val();
    } else  if (value == "CA") {
        $('#shipping-canada-regions').show();
        $('#shipping-canada-regions').removeAttr('disabled');
        $('#shipping-canada-regions').attr('required', 'required');

        data['state'] = $('#shipping-canada-regions').val();
    } else {
        $('#shipping-province').show();
        $('#shipping-province').removeAttr('disabled');
        $('#shipping-province').attr('required', 'required');
    }

    storeShipmentDataInSession(data);
});

// init state field
if($('select[name="state"] > option:selected').length == 0){
    $('select[name="state"]').children('option:first-child').attr('selected', 'selected');
}

var currentShipmentCost = parseFloat($('#shipment-cost').val());

if (currentShipmentCost > 0) {
    updateShipment(currentShipmentCost);
}

setShipmentData();