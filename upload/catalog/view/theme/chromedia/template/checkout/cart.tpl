<?php echo $header; ?>

<!-- CONTENT STARTS HERE -->
<div class="bar">
  <div class="row">
    <ul class="steps-bar">
      <li class="active step1">Shipping information</li>
      <li><i class="icon-arrow-right"></i></li>
      <li class="step2">Credit Card Information</li>  
    </ul>  
  </div>
</div>

<div class="notif-msg" style="display:none;">
    <div class="notif error"></div>  
</div>

<div class="mtb40"> 
    <div class="row">
        <div class="large-6 columns step-content">
            <span id="step-shipping">
                <?php echo $shippingForm; ?>
            </span>
            <span id="step-payment" style="display:none;">
                <?php echo $paymentForm; //include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_shipment.tpl'); ?>
            </span>
        </div>

        <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_items.tpl'); ?>
    </div>
</div>

<!-- TODO: transfer this to other file for modularity -->
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/cart.js"></script>
<script type="text/javascript">
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
</script>

<script type="text/javascript">
    var shipmentData;
    var shippingTotal = 0;
    var subTotal = <?php echo $subTotal; ?>

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
        $('#step-shipping').show();
        $('#step-payment').hide();
        removeErrors($('#step-shipping').find('form'));

        $('.steps-bar').find('.step1')
            .addClass('active')
            .find('i').remove();

        $('.steps-bar').find('.step2').removeClass('active');
        focusElement($('.steps-bar'));
    }

    var activateStep2 = function() {
        removeErrors($('#step-payment').find('form'))
        $('#step-shipping').hide();
        $('#step-payment').show();

        $('.steps-bar').find('.step1')
            .removeClass('active')
            .prepend('<i class="icon-green-check"></i>');
        $('.steps-bar').find('.step2').addClass('active');

        focusElement($('.steps-bar'));
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
                            $('<div data-alert class="alert-box alert radius">Sorry, no shipping is available for the provided address.</div>').insertAfter($('.shipping-selection').children('h3'));
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

    var removeProduct = function(element) {
        var removeProductConfirm = confirm("Are you sure you want to remove this product?");

        if (removeProductConfirm == true) {
            var closestContainer = element.closest('.group');

            $.ajax({
                type: "POST",
                url: "<?php echo $this->url->link('checkout/cart/removeProductInCart', '', 'SSL'); ?>",
                data: {key : element.attr('key')},
                dataType: 'json',
                beforeSend: function() {
                    closestContainer.css({'opacity' : 0.5});
                    closestContainer.find('input').attr('readonly', true);
                },
                success: function(jsondata) {
                    if (jsondata.success) {
                        closestContainer.remove();

                        updateSubTotal(jsondata.total);
                        updateProductsCount(jsondata.productsCount);

                        refreshShipmentData();
                    } else {
                        closestContainer.css({'opacity' : 1});
                        closestContainer.find('input').attr('readonly', false);

                        alert(jsondata.msg);
                    }
                },
                error: function(error) {
                    closestContainer.css({'opacity' : 1});
                    closestContainer.find('input').attr('readonly', false);

                    alert(error);
                }
            });
        }
    }

    $('#step2-trigger-btn').off('click').on('click', function() {
        // populateCCInfoBasedOnShipmentInfo();
        // populateShipmentReviewInfoInPaymentStep();

        activateStep2();
    });

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

    
</script>


<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var publishableKey = "<?php echo STRIPE_PUBLIC_KEY; ?>";
    Stripe.setPublishableKey(publishableKey);
</script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/payment.js"></script>

<?php echo $footer;?>