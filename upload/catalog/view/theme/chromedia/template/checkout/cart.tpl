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

    var activateStep2 = function() {
        $('.steps-bar').find('.step1')
            .removeClass('active')
            .prepend('<i class="icon-green-check"></i>');
        $('.steps-bar').find('.step2').addClass('active');
    }

    var setShipmentData = function() {
        shipmentData = {
            name : $('#shipment-form').find('#shipping-name').val(),
            email : $('#shipment-form').find('#shipping-email').val()
        }
    }

    var getShipmentData = function() {
        return shipmentData;
    }

    var populateCCInfoBasedOnShipmentInfo = function() {
        var shipmentData = getShipmentData();

        $('#payment-name').val(shipmentData.name);
        $('#payment-email').val(shipmentData.email);
    }

    var retrieveShipmentRates = function(form, event) {
        removeErrors(form);
        var hasError = showFormErrors(form);

        shipmentFormStatus(form, false);
        
        if (!hasError) {
            var data = form.serialize();    

            setShipmentData(data);

            // Send POST data to server
            $.ajax({
                type: "POST",
                url: "<?php echo $this->url->link('checkout/checkout/checkShippingInfo', '', 'SSL'); ?>",
                data: data,
                dataType: 'json',
                success: function(jsondata) {
                    $('.shipping-selection').remove('label');
                    shipmentFormStatus(form, true);

                    if (jsondata.success && jsondata.rates) {
                        var rates = jsondata.rates;

                        $('.shipping-selection').children().not('h3').remove();

                        if (jsondata.rates_count > 0) {
                            $.each(rates, function(index, rate) {
                                var service = rate.service;
                                var alias = service.split(' ').join('-');

                                $('.shipping-selection').append('<label for="'+alias+'"><input class="shipping-option" type="radio" id="'+alias+'" name="shipping-option" amount="'+rate.total+'" value="'+service+'"> '+service+'  <em>(average of '+rate.days+' days - <b>'+rate.total+'</b>)</em></label>');
                            });

                            $('#display-on-rates-checked').show();
                            $('.shipping-selection').find('.shipping-option:first').prop('checked', true).trigger('click');
                        } else {
                            showCheckoutGeneralError('Shippo could not retrieve shipment rates. Please update provided address and products.');
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
        $('#display-on-rates-checked').hide();
        
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
        $('#step-shipping').hide();
        $('#step-payment').show();

        if ($('#use-cc-info-checkbox').is(':checked')) {
            populateCCInfoBasedOnShipmentInfo();
        }

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

    $('.quantity-changed').off('click').on('click', function() {
        var btn = $(this);
        var qtyInput = btn.parent('li').find('.qty-input');
        var quantity = qtyInput.val();

        if (quantity) {
            var data = {
                quantity : quantity,
                key : $(this).attr('key')
            };

            $.ajax({
                type: "POST",
                url: "<?php echo $this->url->link('checkout/cart/updateCartProductQuantity', '', 'SSL'); ?>",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    qtyInput.css({'opacity' : 0.5});
                    qtyInput.attr('readonly', true);
                },
                success: function(jsondata) {
                    qtyInput.css({'opacity' : 1});
                    qtyInput.prop('readonly', false);

                    updateSubTotal(jsondata.total);
                    updateProductsCount(jsondata.productsCount);

                    refreshShipmentData();
                },
                error: function(error) {
                    qtyInput.css({'opacity' : 1});
                    qtyInput.prop('readonly', false);

                    alert('error');
                }
            });
        }
    });

    $('.remove').off('click').on('click', function() {
        removeProduct($(this));
    });
</script>


<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<!-- payment -->
<script type="text/javascript">
    var publishableKey = "<?php echo STRIPE_PUBLIC_KEY; ?>";
    Stripe.setPublishableKey(publishableKey);

    var showCreditCardError = function(response) {
        var form = $('#payment-form');
        var errorCode = response.error.code;

        if (errorCode == "incorrect_number" || errorCode == "incorrect_number" || errorCode == "invalid_number") {
            addFieldError(form.find('#cc-number'));
            // $form.find('#error-card-number').text("Please enter a valid card number");
        } else if (errorCode == "invalid_expiry_month") {
            addFieldError(form.find('#cc-expirationMonth'));
            // $form.find('#error-expiry-month').text("Please enter a valid expiry month");
        } else if (errorCode == "invalid_expiry_year") {
            addFieldError(form.find('#cc-expirationYear'));
            // $form.find('#error-expiry-year').text("Please enter a valid expiry year");
        } else if (errorCode == "invalid_cvc") {
            addFieldError(form.find('#cc-securityCode'));
            // $form.find('#error-security-code').text("Please enter a valid security code");
        }   else {
            showCheckoutGeneralError(response.error.message);
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
                    url: "<?php echo $this->url->link('checkout/checkout/processOrder', '', 'SSL'); ?>",
                    data: data,
                    dataType: 'json',     
                    success: function(jsondata){
                        form.css({'opacity' : 1});
                        $('.btn-checkout').show();

                        if (jsondata.success) {
                            var token = jsondata.token;

                            window.location = "<?php echo $this->url->link('checkout/checkout/onSuccess', '', 'SSL');?>"
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
</script>

<?php echo $footer;?>