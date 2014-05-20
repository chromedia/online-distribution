<?php echo $header; ?>

<!--

Cart Summary

$_SESSION['cart'][$product_id]['quantity'] - (item quantity)
$_SESSION['cart'][$product_id]['price'] - (item unit price)

Order Summary

$_SESSION['order']['subtotal']
$_SESSION['order']['shipcost']
$_SESSION['order']['tax']
$_SESSION['order']['total']
$_SESSION['order']['number']

Shipment Summary

$_SESSION['shipments']['1']['contents'][int $product_id]['quantity']
$_SESSION['shipments']['1']['length']
$_SESSION['shipments']['1']['width']
$_SESSION['shipments']['1']['height']
$_SESSION['shipments']['1']['weight']
$_SESSION['shipments']['1']['rates'][0]['id/service']

Address Summary

$_SESSION['shipping_address'] = array(

	'company' => $_POST['company'],
	'address' => $_POST['address'],
	'city' => $_POST['city'],
	'postcode' => $_POST['postcode'],
	'region' => $_POST['region'],
	'country' => $_POST['country'],
	'email' => $_POST['email']

)	

-->
<!--
<script src="catalog/view/template/default/checkout/cart.js" type="text/javascript"></script>
-->

<!-- Index

- Trigger Buttons
- Status Variables
- Success and Error Text


-->

<!-- Trigger Buttons -->
<div id="button-cart"></div>
<div id="button-payment-info"></div>
<div id="button-shipping-address"></div>
<div id="button-shipping-speeds"></div>				
<div id="selector"></div>

<!-- JS Variables -->
<script type="text/javascript">
// Set status variables
var shipping_info = false;
var packages = false;
var shipping_speeds = false;
var payment_info = false;

// Set Success and Error Text
var success_text = '<div class="inline-success">✔</div>';
var error_text = '<div class="inline-error">✖</div>';
var loading_text = '&nbsp;<img src="catalog/view/default/image/loading.gif" alt="" />';
</script>

<!-- Left Column -->
<div id="content-left">

	<!-- Load Shipping Info -->
	<?php require_once(DIR_APPLICATION . 'view/default/template/checkout/shipping_info.tpl'); ?>

	<!-- Load Payment Info -->
	<?php require_once(DIR_APPLICATION . 'view/default/template/checkout/payment_info.tpl'); ?>

</div>

<!-- Right Column -->
<div id="content-right">

	<!-- Cart Block -->
	<div id="block-cart">

		<div id="title-cart" class="title-cart">Current Order</div>
		<div id="status-cart" class="title-cart"></div>
		<br/>
		<br/>

		<!-- Cart -->
		<form action="" method="post" enctype="multipart/form-data" id="cart">

	    <div class="cart-info">
	      <table>

	    	<!-- Top Row -->
	        <thead>
	          <tr>
	            <td class="image"><?php echo 'Image'; ?></td>
	            <td class="name"><?php echo 'Description'; ?></td>
	            <td class="model"><?php echo "Product Code"; ?></td>
	            <td class="quantity"><?php echo 'Quantity'; ?></td>
	            <td class="price"><?php echo 'Unit Price'; ?></td>
	            <td class="total"><?php echo 'Extended Price'; ?></td>
	            <td class="total"><?php echo "Delete"; ?></td>
	          </tr>
	        </thead>

	        <!-- Product Rows -->
	        <tbody id="cart-table">

	        	<!-- For Each Product -->	
	          <?php foreach ($products as $product) { ?>

	            <tr id="<?php echo $product['model']; ?>">

		          	<!-- Product Image -->
		            <td class="image"><?php if ($product['thumb']) { ?>
		              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
		              <?php } ?></td>
		            <!-- Product Description -->  
		            <td class="name"><?php echo $product['name']; ?><?php echo $product['model']; ?>
		              <div>
		                <?php foreach ($product['option'] as $option) { ?>
		                - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
		                <?php } ?>
		              </div>
		            </td>
		            <!-- Product Code -->
		            <td class="model"><a href="<?php echo $product['href']; ?>"><?php echo $product['model']; ?></a></td>
		            <!-- Quantity Field --> <!-- Update Quantity Button -->
		            <td class="quantity quantity_field"><input type="text" id="quantity<?php echo $product['model']; ?>" name="<?php echo $product['model']; ?>" value="<?php echo $product['quantity']; ?>" size="1" /> &nbsp;             
		            <image src="catalog/view/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" /></td>            
		            <!-- Unit Price -->
		            <td class="price" id="price<?php echo $product['model']; ?>" name="<?php echo $product['price']; ?>"><?php echo $product['price']; ?></td>
		            <!-- Item Total -->
		            <td class="total" id="item_total<?php echo $product['model']; ?>"><?php echo $product['total']; ?></td>
		            <!-- Delete Item Button -->
		            <td class="total delete_button"><image type="image" src="catalog/view/default/image/remove.png" id="delete-button" name="<?php echo $product['model']; ?>"></image></td>
		        </tr>
	          <?php } ?>

	        </tbody>

	      </table>
	    </div>
	  </form>

	</div>

	<!-- Totals Block -->
	<div id="block-totals">

		<div id="column-totals">
			<table>
			  <tr>
			    <td ><b>Sub-Total: </b></td>
			    <td  id="subtotal"><?php echo $_SESSION['subtotal']; ?></td>
			  </tr>
			  <tr>
			    <td ><b>Shipping: </b></td>
			    <td  id="shipcost"> </td>
			  </tr>            
			  <tr>
			    <td ><b>Total: </b></td>
			    <td  id="total"> </td>
			  </tr>      
			</table>
		</div>
		
	</div>	

	<div id="block-cart-buttons">
		<button id="button-checkout" type="button">Place Order</button>
	</div>

</div>

<script type="text/javascript">
// Quantity Change Process
jQuery(function($) {
	// Quantity Changed Response
	$('.quantity_field').on('change', function(event) {

		// Get the product id and new quantity
		var product_id = event.target.name;	
		var quantity_value = event.target.value;			

		// Append update data into form
		var sendform = $('<form></form>');
		sendform.append($('<input type="hidden" name="update" />').val('1'));
		sendform.append($('<input type="hidden" name="product_id" />').val(product_id));
		sendform.append($('<input type="hidden" name="quantity_value" />').val(quantity_value));

		// Serialize form
		var data = sendform.serialize();

		// Remove Form
		sendform.remove();						

		// Send POST data to server
		$(function() {
			$.ajax({
				type: "POST",
				url: "<?php echo HTTPS_SERVER; ?>index.php",
				data: data,
				dataType: 'json',
				success: function(jsondata) {	

					// Receive updated item total and subtotal
					var item_total = jsondata.item_total;
					var subtotal = jsondata.subtotal;

					// Update item total
					$('#item_total' + product_id).text(item_total);	

					// Update subtotal
					$('#subtotal').text(subtotal);

					// If Shipping Info Filled, Recalculate Packages
					if (shipping_info == true && $('#cart-table').children().length != 0) {
						$('#button-cart').click();
					}				

				}
			});	
		});	
	});	
});	
</script>

<!-- Delete Button Response -->
<script type="text/javascript">
jQuery(function($) {
	$('.delete_button').on('click', function(event) {

		// Get product id
		var product_id = event.target.name;	

		// Append update data into form
		var sendform = $('<form></form>');
		sendform.append($('<input type="hidden" name="delete" />').val('1'));
		sendform.append($('<input type="hidden" name="product_id" />').val(product_id));		

		// Serialize form
		var data = sendform.serialize();		

		// Send POST data to server
		$(function() {
			$.ajax({
				type: "POST",
				url: "<?php echo HTTPS_SERVER; ?>index.php",
				data: data,
				dataType: 'json',
				success: function(jsondata) {		

					// Remove deleted item from page view
					$('#' + product_id).remove();	

					// If Shipping Info Filled, Recalculate Packages
					if (shipping_info == true && $('#cart-table').children().length != 0) {
						$('#button-cart').click();
					}	
					
				}
			}); 
		});	

		// Reset form by removing input elements
		$('#sendform').children().remove();			

	});
});
</script>

<!-- Calculate Packages -->
<script type="text/javascript"><!--
jQuery(function($) {
  $('#button-cart').on('click', function(event) {   

  	// Set POST data
  	var sendform = $('<input type="hidden" name="package_logic" value="1"/>');

	// Serialize form
	var data = sendform.serialize();  	

	// Send POST data to server
	$(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo HTTPS_SERVER; ?>index.php",
			data: data,
			dataType: 'json',
			beforeSend: function() {
				// Show loading gif
				$('#status-cart').html(loading_text);
			},				
			success: function(jsondata) {   

				success = jsondata.success;

				//show_success = 'Current Order' + success_text;
				//show_error = 'Current Order' + error_text;
				
				if(success == 1){
					// Show successful confirmation
					$('#status-cart').html(success_text);

					// Retrieve shipping rates
					$('#button-shipping-speeds').click();
								
				}
				else {
					// Show error
					$('#status-cart').html(error_text);
				}

			}

		}); 
	});     
  });
});
//--></script> 

<!-- Get shipping speeds -->
<script type="text/javascript"><!--
jQuery(function($) {
  $('#button-shipping-speeds').on('click', function(event) {

  	// Set POST data
  	var sendform = $('<input type="hidden" name="shipping_speeds" value="1"/>');  	

	// Serialize form
	var data = sendform.serialize();    

	// Send POST data to server
	$(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo HTTPS_SERVER; ?>index.php",
			data: data,
			dataType: 'json',
			beforeSend: function() {
				// Show loading gif
				$('#status-shipping-speeds').html(loading_text);
			},	
			success: function(jsondata) {   

				success = jsondata.success;
				
				if(success == 1){
					// Show successful confirmation
					$('#status-shipping-speeds').html(success_text);
				}
				else {
					// Show error
					$('#status-shipping-speeds').html(error_text);
				}

				rates = jsondata.rates;

				$('#form-shipping-speeds').html(rates);

			}

		}); 
	});     

  });
});
//--></script> 

<!-- Select Shipping Speed -->
<script type="text/javascript"><!--
jQuery(function($) {
  $('#form-shipping-speeds').on('change', function(event) {

  	// Set POST data
  	var sendform = $('<input type="hidden" name="select_shipping_speed" value="1"/>');  	

	// Serialize form
	var data = sendform.serialize();    

	// Send POST data to server
	$(function() {
		$.ajax({
			type: "POST",
			url: "<?php echo HTTPS_SERVER; ?>index.php",
			data: data,
			dataType: 'json',
			beforeSend: function() {
				// Show loading gif
				$('#status-shipping-speeds').html(loading_text);
			},	
			success: function(jsondata) {   

				success = jsondata.success;
				
				if(success == 1){
					// Show successful confirmation
					$('#status-shipping-speeds').html(success_text);
				}
				else {
					// Show error
					$('#status-shipping-speeds').html(error_text);
				}

			}

		}); 
	});     

  });
});
//--></script> 

<!-- Place Order-->
<script type="text/javascript">

// Place Order Call
jQuery(function($) {
	$('#button-checkout').on('click', function(e) {

		$('#button-checkout').prop('disabled', true);

		// Get selected rate
		var rate = $('input[name=service]:checked', '#form-shipping-speeds').val();

		// Append update data into form
		var sendform = $('<form></form>');
		sendform.append($('<input type="hidden" name="checkout" />').val('1'));	
		sendform.append($('<input type="hidden" name="rate" />').val(rate));	

		// Serialize form
		var data = sendform.serialize();	

		// Reset form by removing input elements
		//$('#sendform').children().remove();			

		// Send POST data to server
		$(function() {
			$.ajax({
				type: "POST",
				url: "<?php echo HTTPS_SERVER; ?>index.php",
				data: data,
				dataType: 'json',
				success: function(jsoncheckout) {	
					$('#button-checkout').prop('disabled', false);
					var state = jsoncheckout.state;
					$('#button-checkout').html(state);		
				}
			});	
		});			
	});
});		
</script>

<!-- Page Content End -->
</div>

<?php echo $footer; ?>
