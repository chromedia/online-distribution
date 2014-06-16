<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

    <div class="content-right">
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="cart-info">
          <table>
            <thead>
              <tr>
                <td class="image"><?php echo $column_image; ?></td>
                <td class="name"><?php echo $column_name; ?></td>
                <td class="model"><?php echo $column_model; ?></td>
                <td class="quantity"><?php echo $column_quantity; ?></td>
                <td class="price"><?php echo $column_price; ?></td>
                <td class="total"><?php echo $column_total; ?></td>
                <td class="action">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr>
                <td class="image"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                  <?php } ?></td>

                <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
                <td class="model"><?php echo $product['model']; ?></td>
                <td class="quantity"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" /></td>
                <td class="price"><?php echo $product['price']; ?></td>
                <td class="total"><?php echo $product['total']; ?></td>
                <td class="action">  <input type="image" src="catalog/view/theme/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />
                  &nbsp;<a href="<?php echo $product['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
              </tr>
             <?php endforeach;?>

            </tbody>
          </table>
        </div>
      </form>

      <div class="cart-total">
        <table id="total">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="right"><b><?php echo $total['title']; ?>:</b></td>
            <td class="right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
  </div>

  <div style="padding:30px;">

    <!-- Shipping -->
    <form action="" method="POST" id="shipping-form-new">
      <input type="hidden" name="shipping_address" value="1"/>
        <!-- Name Column -->
        <div id="column-shipping-name">
          <div><?php echo "Name"; ?></div>
          <div><input id="field-shipping-name" type="text" value="Laura Behrens Wu" name="name" size="25" /></div>
        </div>  

        <!-- Street Address Column -->
        <div id="column-shipping-street-address">
          <?php echo "Street Address"; ?>
          <input id="field-shipping-street-address" type="text" value="3002 New Yort St" name="address" size="25" />
        </div>  

        <!-- City -->
        <div id="column-shipping-city">
          <div><?php echo 'City'; ?></div>
          <div><input id="field-shipping-city" type="text" value="Denver" name="city" size="25" /></div>
        </div>

        <!-- State/Province -->
        <div id="column-shipping-region">
          <div><?php echo "State/Province"; ?></div>
          <input id="field-shipping-region" type="text" value="CO" name="state" maxlength="2"/>
        </div>    

        <!-- Postal Code -->
        <div id="column-shipping-postcode">
          <div><?php echo "Postal Code"; ?></div>
          <div><input id="field-shipping-postcode" type="text" value="80205" name="postcode" size="10" /></div>
        </div>
        

        <!-- Country and Postal Code -->
        <div id="column-shipping-country">
          <div><?php echo "Country"; ?></div>
          <div><input id="field-shipping-country" type="text" value="US"  name="country" size="25" /></div>
        </div>

        <!-- Email Column -->
        <div id="column-shipping-email">
          <div><?php echo "Email"; ?></div>
          <div><input id="field-shipping-email" type="text" value="floricel.colibao@chromedia.com" name="email" size="25" /></div>
        </div>        
      
        <button class="shipping-packages">Check Available Shipments</button>
    </form>

      <!-- Shipping Speeds Block -->
      <div class="block-shipping-speeds" style="display:none;">
        <h2>Shipping Speeds</h2>
      </div>
    </div>
  </div>

  <div id="block-payment-info" style="padding:30px;">

    <h2>Payment Info</h2>
     
     <!-- Payment Form -->
    <form action="" method="POST" id="payment-form">
    <span class="payment-errors" id="payment-errors"></span>

    <!-- <input type="text" name="email" id="email" value="floricel.colibao@chromedia.com" /> -->

      <!-- Card Number -->
      <div id="column-card-number">
        <div>Credit Card Number</div>
        <div><input type="text" id="field-card-number" maxlength="16" data-stripe="number" autocomplete="off" class="card-number input-medium field-payment" value="4242424242424242"></div>
      </div>
      <div id="error-card-number" class="error-payment"></div>

      <!-- Expiry Date -->
      <div id="column-card-expiration">
        <div>Expiration Date</div>
        <select id="field-card-month" data-stripe="exp-month" class="card-expiry-month input-mini field-payment">
            <?php 
            for ($i=1; $i<13; $i++){
                // Add 0 to single digit numbers
                if (strlen((string)$i) < 2) { 
                    $show = '0' . (string)$i;
                }
                else {
                    $show = $i;
                }
                ?><option value="<?php echo $i; ?>"><?php echo $show; ?></option><?php
            }
            ?>  
         </select>
          
          <select id="field-card-year" data-stripe="exp-year" class="card-expiry-year input-mini field-payment">
                <?php 
                $year = date("Y");
                $yearlimit = $year + 8;
                for ($i=$year; $i<$yearlimit; $i++){
                  ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
                  }
                ?>  
          </select>
      </div>

      <!-- Security Code -->
    <div id="column-card-security">
      <div>Security Code</div>
      <div><input type="text" id="field-card-code" maxlength="4" data-stripe="cvc" autocomplete="off" class="card-cvc input-mini field-payment" value="424"></div>
    </div>
    <div id="error-security-code" class="error-payment"></div>

      <input type="submit" class="btn" value="Process Order"/>
      <!-- <button id="pay-button">Pay</button> -->
    </form>

  </div>



<!-- Get shipping address -->
<script type="text/javascript">
  var getCurrentTotal = function() {
    var total = $('#total').find('td:last').html();
    total = total.replace(/[$,]+/g, "");

    return parseFloat(total);
  }

  var currentTotal = getCurrentTotal();

  jQuery(function($) {
    $('.block-shipping-speeds').on('click', '.shipping-speed', function() {
      var shippingAmount = parseFloat($(this).attr('amount'));
      var newTotal = shippingAmount + currentTotal;

      if ($('#total').find('.shipping-total-amount').length == 0) {
        var shipmentTotalDisplay = '<tr class="shipping-total-amount"><td class="right"><b>Shipment</b></td><td class="right shipmentValue"></td><tr>';

        $(shipmentTotalDisplay).insertBefore($('#total').find('tr:last'));
      }

      $('.shipmentValue').html('$ '+shippingAmount);
      $('#total').find('td:last').html('$ '+newTotal);
    });


    $('.shipping-packages').on('click', function(e) {
       $(this).hi
      e.preventDefault();
    
      // Serialize form
      var data = $('#shipping-form-new').serialize();    
      var self = $(this);
      // Send POST data to server
      
      $.ajax({
        type: "POST",
        url: "<?php echo $this->url->link('checkout/shipping/checkShippingInfo', '', 'SSL'); ?>",
        data: data,
        dataType: 'json',
        beforeSend: function() {
          self.hide();
          //$('<em class="shipment-loader">Processing...</em>').insertAfer(self);
        },
        success: function(jsondata) {
          self.show();
          if (jsondata.rates) {
            var rates = jsondata.rates;
            $('.block-shipping-speeds .shipping-speed').remove();

            $.each(rates, function(index, rate) {
              $('.block-shipping-speeds').append('<input type="radio" name="shipping-speed" class="shipping-speed" amount="'+rate.total+'" value="'+rate.service+'" /> <span> '+rate.service+'  '+rate.total+' </span><br />');
            });

            $('.block-shipping-speeds').find('.shipping-speed:first').prop('checked', true).trigger('click');
            $('.block-shipping-speeds').show();
          }
        },
        error: function(error) {
          self.show();
          alert('error');
        }
      }); 
  });
});
</script> 


<!-- Payment Details -->

<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<!-- Set Stripe Public Key -->
<?php echo '<script type="text/javascript"> Stripe.setPublishableKey("' . STRIPE_PUBLIC_KEY . '"); </script>'; ?> 

<!-- Validation Trigger -->
<script type="text/javascript">

  jQuery(function($) {
    $('#payment-form').on('submit', function(e) {
      e.preventDefault();

      Stripe.card.createToken($(this), stripeResponseHandler);
    });

  });

</script>

<!-- Stripe Response Handler -->
<script type="text/javascript">
  var stripeResponseHandler = function(status, response) {
  var $form = $('#payment-form');
 
  // Clear errors
  $form.find('.error-payment').empty();

  if (response.error) {
    
    // // Show error status
    // $('#status-payment-info').html(error_text);

    // Error: incorrect or invalid card number
    if (response.error.code == "incorrect_number" || response.error.code == "incorrect_number") {
      $form.find('#error-card-number').text("Please enter a valid card number");
    }
    // Error: invalid expiry month
    else if (response.error.code == "invalid_expiry_month") {
      $form.find('#error-expiry-month').text("Please enter a valid expiry month");
    }
    // Error: invalid expiry year
    else if (response.error.code == "invalid_expiry_year") {
      $form.find('#error-expiry-year').text("Please enter a valid expiry year");
    } 
    // Error: invalid cvc
    else if (response.error.code == "cvc") {
      $form.find('#error-security-code').text("Please enter a valid security code");
    }
    // Error: other
    else {
      $form.find('.payment-errors').text(response.error.message);
    }

  } else {

    // token contains id, last4, and card type
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    //$form.append($('<input type="hidden" name="stripeToken" />').val(token));
    // and re-submit
    //$form.get(0).submit();

    // Serialize the form
    //var data = $('#payment-form').serialize(); 

    var data = {
      service_name : $('.shipping-speed:checked').val(),
      customer_email : $('#field-shipping-email').val(),
      token : token
    }

    // Send form data to server with POST method, then retrieve json data
    $(function() {
      $.ajax({
        type: "POST",
        url: "<?php echo $this->url->link('checkout/order/processOrder', '', 'SSL'); ?>",
        data: data,
        dataType: 'json',     
        success: function(jsondata){

          // Set jsondata's token property as variable
          var token = jsondata.token;

          // Clear errors
          $form.find('.payment-errors').text('');

          // Show Success Text
          $('#status-payment-info').html(success_text);

          // Success Status
          payment_info = true;

        }
      });
    });

    // Prevent page from refreshing
    return false;
  }
};
</script>

<?php echo $footer;?>