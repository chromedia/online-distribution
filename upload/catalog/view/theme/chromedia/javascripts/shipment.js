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

                    //$('.shipping-selection').children().not('.items-header').remove();

                    $('.shipping-selection').find('select.select-rates').remove();

                    if (jsondata.rates_count > 0) {
                        $('.shipping-selection').append('<select class="select-rates"></select>');

                        $.each(providers, function(index, provider) {
                            $('.select-rates').append('<optgroup label="'+provider+'"></optgroup>');

                            var ratesOfProvider = rates[provider];
                            var group = $('.select-rates').find('optgroup:last');

                            $.each(ratesOfProvider, function(index, rate) {
                                var service = rate.service;
                                var alias = service.split(' ').join('-');
                                var option = '<option class="shipping-option id="'+alias+'" amount="'+rate.total+'" value="'+service+'" days="'+rate.days+'">'+service+'<em>(average of '+rate.days+' day/s - <b>'+rate.total+'</b>)</em></option>';

                                group.append(option);
                            });
                        });

                        setShipmentData();

                        $('.display-on-rates-checked').show();
                        $('.select-rates').find('option:first').prop('selected', 'selected');
                        $('.select-rates').trigger('change');
                    } else {
                        $('.display-on-rates-checked:first').show();

                        if ($('input[name="enable-signature-confirmation"]').is(':checked')) {
                            $('<div data-alert class="alert-box alert radius">Rates could not be retrieved. Please verify your shipping address and <a href="#" class="shipment-retrieval">try again</a> without signature on delivery.</div>').insertAfter($('.shipping-selection').children('h3'));
                            $('input[name="enable-signature-confirmation"]').prop('checked', false);
                        } else {
                            $('<div data-alert class="alert-box alert radius">Sorry, shipment service is unavailable in the specified location.</div>').insertAfter($('.shipping-selection').children('h3'));
                        }

                        updateShipment(0);
                    }

                } else {
                    var message = jsondata.errorMsg ? jsondata.errorMsg : 'An error occured. Please make sure data are correct.';
                    showCheckoutGeneralError(message);

                    updateShipment(0);
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

    if ($('.shipping-selection').find('.select-rates').length > 0) {
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

    shipmentData = {
        name : $('#shipping-name').val(),
        email : $('#shipping-email').val(),
        address : address.join(', ')
    }

    setShipmentCarrier();
}

var setShipmentCarrier = function() {
    var shipmentValue = $('.select-rates').val();
    var shipmentOption = $('.select-rates').find('option[value="'+shipmentValue+'"]');

    shipmentData['shipment'] = shipmentValue+' (average of '+shipmentOption.attr('days')+' days for '+shipmentOption.attr('amount')+')';
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

$('.shipping-selection').on('change', '.select-rates', function() {
    var selected = $(this).find(':selected');
    var shippingAmount = parseFloat(selected.attr('amount'));

    updateShipment(shippingAmount);
    setShipmentCarrier();
});

$('#shipment-form').off('submit').on('submit', function(e) {
    e.preventDefault();

    retrieveShipmentRates($(this));
});

$('#step-shipping').on('click', '.shipment-retrieval', function(e) {
    e.preventDefault();

    $('input[name="enable-signature-confirmation"]').prop('checked', false);
    retrieveShipmentRates($('#shipment-form'));

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

    $('.state-container').show();
    $('select[name="state"]').hide();
    $('select[name="state"]').attr('disabled', 'disabled');
    $('select[name="state"]').removeAttr('required');

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
        $('.state-container').hide();
        // $('#shipping-province').show();
        // $('#shipping-province').removeAttr('disabled');
        // $('#shipping-province').attr('required', 'required');
    }

    storeShipmentDataInSession(data);
});

// init state field
if($('select[name="state"] > option:selected').length == 0){
    $('select[name="state"]').children('option:first-child').attr('selected', 'selected');
}

// var currentShipmentCost = parseFloat($('#shipment-cost').val());

// if (currentShipmentCost > 0) {
//     updateShipment(currentShipmentCost);
// }


