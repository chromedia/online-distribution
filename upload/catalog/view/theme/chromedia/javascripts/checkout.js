var shipmentData;
var shippingTotal = 0;
var subTotal = $('.subtotal-value').val();

var formatNumberWithCommas = function(number) {
    var n = number.toString().split(".");
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return n.join(".");
}

var updateOverallTotal = function() {
    var overallTotal = (parseFloat(subTotal) + parseFloat(shippingTotal)).toFixed(2);

    $('.total-payment').find('strong').html('$ '+formatNumberWithCommas(overallTotal));
}

var updateShipment = function(shipmentCost) {
    shippingTotal = shipmentCost;
    $('.shipping-cost').find('strong').html('$ '+shipmentCost);

    updateOverallTotal();
}

var updateSubTotal = function(total) {
    subTotal = total.replace(/[$,]+/g, "");
    $('.sub-total-value').find('strong').html('$ '+total);

    updateOverallTotal();
}

var updateProductsCount = function(productsCount) {
    var itemsText = productsCount > 1 ? 'items' : 'item'; 
    $('.items-header').empty().html('You have <strong class="products-count">'+productsCount+'</strong> '+itemsText+' in your shopping cart');
    $('.items-in-cart').html(productsCount);
}

var activateStep1 = function() {
    $('.remove').show();
    $('.qty-in-cart').show();
    $('.qty-in-cart').next('span').hide();

    $('#step-shipping').fadeIn('slow');
    $('#step-payment').hide();
    removeErrors($('#step-shipping').find('form'));

    $('.steps-bar').find('.step1')
        .addClass('active')
        .find('i').remove();

    $('.steps-bar').find('.step2').removeClass('active');
    focusElement($('.steps-bar'));
}

var activateStep2 = function() {
    $('.remove').hide();
    // $('.qty-in-cart').prop('disabled', true);
    $('.qty-in-cart').hide();
    $('.qty-in-cart').next('span').show();
    
    removeErrors($('#step-payment').find('form'))
    $('#step-shipping').hide();
    $('#step-payment').fadeIn('slow');

    $('.steps-bar').find('.step1')
        .removeClass('active')
        .prepend('<i class="icon-green-check"></i>');
    $('.steps-bar').find('.step2').addClass('active');

    focusElement($('.steps-bar'));
}

var activateStep3 = function() {
    $('.steps-bar').find('.step2')
        .removeClass('active')
        .prepend('<i class="icon-green-check"></i>');
    $('#step-payment').hide();

    $('.items-in-cart').hide().html('');

    $('#checkout-successful').fadeIn('slow');
}

var doUpdateData = function(orderId) {
    $.ajax({
        type: "POST",
        url: "index.php?route=checkout/success/updateData",
        data: {order_id : orderId},
        error: function(error) {
            console.log(error);
        }
    });
}

var populateCCInfoBasedOnShipmentInfo = function() {
    var shipmentData = getShipmentData();

    if ($('#use-cc-info-checkbox').is(':checked')) {
        $('#payment-name').val(shipmentData.name);
        $('#payment-email').val(shipmentData.email);
    } else {
        $('#payment-name').val('');
        $('#payment-email').val('');
    }
}

var populateShipmentReviewInfoInPaymentStep = function() {
    var summaryContainer = $('.shipping-summary');
    var shipment = getShipmentData();

    summaryContainer.find('.shipping-info-name').text(shipment.name);
    summaryContainer.find('.shipping-info-email').text(shipment.email);
    summaryContainer.find('.shipping-info-address').text(shipment.address);
    summaryContainer.find('.shipping-info-speed').text(shipment.shipment);
}




$('#step2-trigger-btn').off('click').on('click', function() {
    //populateCCInfoBasedOnShipmentInfo();
    populateShipmentReviewInfoInPaymentStep();

    activateStep2();
});