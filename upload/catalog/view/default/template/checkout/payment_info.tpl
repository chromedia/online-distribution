
<!-- Payment Details -->

<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<!-- Set Stripe Public Key -->
<?php echo '<script type="text/javascript"> Stripe.setPublishableKey("' . STRIPE_PUBLIC_KEY . '"); </script>'; ?> 

<!-- Validation Trigger -->
<script type="text/javascript">
jQuery(function($) {
  $('.field-payment').on('change', function(e) {

  	// Get values of payment fields
  	var empty = $('.field-payment').filter(function() {
        return $(this).value === "";
    });    

    // If at least one input is empty, stop function
    if(empty.length) {
        return;
    }    

	// Loading GIF
	$('#title-payment-info').append('&nbsp;<img src="catalog/view/default/image/loading.gif" alt="" />');

	// Set Form
	var $form = $('#payment-form');
	 
	// Send Form to Stripe and Activate Stripe Response Handler
	Stripe.card.createToken($form, stripeResponseHandler);
	 
	// Prevent the form from submitting with the default action
	return false;

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
    
		// Show error mark
		show_error = 'Payment Info   <div class="inline-error">✖</div>';
		$('#title-payment-info').html(show_error);

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
		$form.append($('<input type="hidden" name="stripeToken" />').val(token));
		// and re-submit
		//$form.get(0).submit();

		// Serialize the form
		var data=$('#payment-form').serialize(); 

		// Send form data to server with POST method, then retrieve json data
		$(function() {
		  $.ajax({
		    type: "POST",
		    url: "<?php echo HTTPS_SERVER; ?>index.php",
		    data: data,
		    dataType: 'json',     
		    success: function(jsondata){

		      // Set jsondata's token property as variable
		      var token = jsondata.token;

		      // Clear errors
		      $form.find('.payment-errors').text('');

		      // Show Successful Confirmation
		      show_success = 'Payment Info   <div class="inline-success">✔</div>';
		      $('#title-payment-info').html(show_success);

		      // Show jsdata in page
		      //$('#token-received2').html(token);
		      //$('#token-received2').text(jsdata);
		    }
		  });
		});

   

    // Prevent page from refreshing
    return false;
  }
};

</script>

<div id="block-payment-info">

    <div id="title-payment-info" class="title-cart">Payment Info</div>
     
     <!-- Payment Form -->
    <form action="" method="POST" id="payment-form">
    <span class="payment-errors" id="payment-errors"></span>

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
	           <span> / </span>
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
    	<div id="error-expiry-month" class="error-payment"></div>
    	<div id="error-expiry-year" class="error-payment"></div>

    	<!-- Security Code -->
     <div id="column-card-security">
    		<div>Security Code</div>
    		<div><input type="text" id="field-card-code" maxlength="4" data-stripe="cvc" autocomplete="off" class="card-cvc input-mini field-payment" value="424"></div>
     </div>
     <div id="error-security-code" class="error-payment"></div>

    </form>

</div>
