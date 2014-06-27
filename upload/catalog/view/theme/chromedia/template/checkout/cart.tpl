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
        $('.remove').show();
        $('.qty-in-cart').show();
        $('.qty-in-cart').next('span').hide();

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
        $('.remove').hide();
        // $('.qty-in-cart').prop('disabled', true);
        $('.qty-in-cart').hide();
        $('.qty-in-cart').next('span').show();
        
        removeErrors($('#step-payment').find('form'))
        $('#step-shipping').hide();
        $('#step-payment').show();

        $('.steps-bar').find('.step1')
            .removeClass('active')
            .prepend('<i class="icon-green-check"></i>');
        $('.steps-bar').find('.step2').addClass('active');

        focusElement($('.steps-bar'));
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
        populateCCInfoBasedOnShipmentInfo();
        populateShipmentReviewInfoInPaymentStep();

        activateStep2();
    });
</script>

<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/shipment.js"></script>


<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var publishableKey = "<?php echo STRIPE_PUBLIC_KEY; ?>";
    Stripe.setPublishableKey(publishableKey);
</script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/payment.js"></script>

<?php echo $footer;?>