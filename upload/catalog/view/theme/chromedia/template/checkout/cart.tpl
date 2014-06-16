<?php echo $header; ?>

<!-- CONTENT STARTS HERE -->
<div class="bar">
  <div class="row">
    <ul class="steps-bar">
      <li class="active">Shipping information</li>
      <li><i class="icon-arrow-right"></i></li>
      <li>Credit Card Information</li>  
    </ul>  
  </div>
</div>

<div class="mtb40"> 
    <div class="row">
        <div class="large-6 columns step-content">
            <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_shipment.tpl'); ?>
        </div>

        <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_items.tpl'); ?>
    </div>
</div>

<script type="text/javascript">
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

    // var getCurrentTotal = function() {
    //     var total = $('#total').find('td:last').html();

    //     if (total) {
    //         total = total.replace(/[$,]+/g, "");
    //     } else {
    //         total = 0;
    //     }

    //     return parseFloat(total);
    // }

    // var currentTotal = subTotal;

    $('.shipping-selection').on('click', '.shipping-option', function() {
        var shippingAmount = parseFloat($(this).attr('amount'));
        var newTotal = shippingAmount + subTotal;

        if ($('#total').find('.shipping-total-amount').length == 0) {
            var shipmentTotalDisplay = '<tr class="shipping-total-amount"><td class="right"><b>Shipment</b></td><td class="right shipmentValue"></td><tr>';

            $(shipmentTotalDisplay).insertBefore($('#total').find('tr:last'));
        }

        $('.shipmentValue').html('$ '+shippingAmount);
        $('#total').find('td:last').html('$ '+newTotal);
    });

    $('#shipment-form').on('submit', function(e) {
        e.preventDefault();
    
        var data = $(this).serialize();    
        var self = $(this);
        var shipmentFormStatus = function($is_active) {
            if ($is_active) {
                self.children().css({'opacity' : '1'});
                self.removeLoader();
                self.find('input[type="submit"]').show();
            } else {
                self.children(':not(.loader)').css({'opacity' : '0.3'});
                self.showLoader({'size' : 'small'});
                self.find('input[type="submit"]').show();
            }
        }

        // Send POST data to server
        $.ajax({
            type: "POST",
            url: "<?php echo $this->url->link('checkout/shipping/checkShippingInfo', '', 'SSL'); ?>",
            data: data,
            dataType: 'json',
            beforeSend: function() {
                shipmentFormStatus(false);
            },
            success: function(jsondata) {
                $('.shipping-selection').remove('label');
                shipmentFormStatus(true);

                if (jsondata.rates) {
                    var rates = jsondata.rates;

                    $.each(rates, function(index, rate) {
                        var alias = rate.service.join('-');

                        $('.shipping-selection').append('<label for="'+alias+'"><input class="shipping-option" type="radio" id="'+alias+'" name="'+alias+'"> '+rate.service+'  <em>('+rate.total+')</em></label>');
                    });

                    $('.shipping-selection').find('.shipping-option:first').prop('checked', true).trigger('click');
                }
            },
            error: function(error) {
                shipmentFormStatus(true);
                alert('error');
            }
        }); 
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
                },
                error: function(error) {
                    qtyInput.css({'opacity' : 1});
                    qtyInput.prop('readonly', false);

                    alert('error');
                }
            });
        }
    });
</script>

<?php echo $footer;?>