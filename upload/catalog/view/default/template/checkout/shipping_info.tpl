

<div id="block-shipping-info">

	<div id="title-shipping-address" class="title-cart">Ship To Address</div>

	<?php if($addresses) { ?>

		<!-- Existing address selector -->
		<input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
		<label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>

		<!-- Existing Addresses -->
		<form action="" method="POST" id="shipping-form-existing">
		<input type="hidden" name="shipping_address" value="1"/>
		<div id="shipping-existing" style="overflow:auto;width:100%;height:auto;margin-bottom:15px;" align="left">
		  <select name="address_id" size="5">
			<?php foreach ($addresses as $address) { ?>
			<?php if ($address['address_id'] == $address_id) { ?>


			<option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>

			<?php } ?>
			<?php } ?>
		  </select>
		</div>
		</form>

		<!-- New address selector -->
		<p>
		  <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
		  <label for="shipping-address-new"><?php echo $text_address_new; ?></label>
		</p>

	<?php } ?>

	<!-- New Address -->
	<div id="shipping-new">
		<form action="" method="POST" id="shipping-form-new">
			<input type="hidden" name="shipping_address" value="1"/>

			<div class="field-shipping">

				<!-- Name Column -->
				<div id="column-shipping-name">
					<div><?php echo "Name"; ?></div>
					<div><input id="field-shipping-name" type="text" name="name" value="Anon Tuni" size="25" /></div>
				</div>	

				<!-- Street Address Column -->
				<div id="column-shipping-street-address">
					<?php echo "Street Address"; ?>
					<input id="field-shipping-street-address" type="text" name="address" value="3002 York St" size="25" />
				</div>	

				<!-- City -->
				<div id="column-shipping-city">
					<div><?php echo 'City'; ?></div>
					<div><input id="field-shipping-city" type="text" name="city" value="Denver" size="25" /></div>
				</div>

				<!-- State/Province -->
				<div id="column-shipping-region">
					<div><?php echo "State/Province"; ?></div>
					<input id="field-shipping-region" type="text" name="region" value="CO" maxlength="2"/>
				</div>		

				<!-- Postal Code -->
				<div id="column-shipping-postcode">
					<div><?php echo "Postal Code"; ?></div>
					<div><input id="field-shipping-postcode" type="text" name="postcode" value="80205" size="10" /></div>
				</div>
				

				<!-- Country and Postal Code -->
				<div id="column-shipping-country">
					<div><?php echo "Country"; ?></div>
					<div><input id="field-shipping-country" type="text" name="country" value="US" size="25" /></div>
				</div>

				<!-- Email Column -->
				<div id="column-shipping-email">
					<div><?php echo "Email"; ?></div>
					<div><input id="field-shipping-email" type="text" name="email" value="ykang404@gmail.com" size="25" /></div>
				</div>				

			</div>	

		</form>
	</div>

</div>

<!-- Shipping Speeds Block -->
<div id="block-shipping-speeds">

	<div id="title-shipping-speeds" class="title-cart">Shipping Speeds</div>

	<div>Please enter your shipping information in order to retrieve speeds and rates</div>

	<form id="form-shipping-speeds">
		<div></div>
	</form>

</div>	

<!-- Default to new address -->
<script type="text/javascript"><!--
	var address = 'new';
//--></script> 

<?php if($addresses) { ?>

	<!-- Default to existing address -->
	<script type="text/javascript"><!--
		var address = 'existing';
	//--></script> 

<?php } ?>	

<!-- Address Triggers -->
<script type="text/javascript"><!--
	// Trigger Existing Addresses
	$('#shipping-address-existing').on('click', function(e) {
	$('#shipping-existing').show();
	$('#shipping-new').hide();
	var address = 'existing';
	});
	// Trigger New Address  
	$('#shipping-address-new').on('click', function(e) {
	$('#shipping-existing').hide();
	$('#shipping-new').show();
	var address = 'new';
	});  
//--></script> 

<!-- Get shipping address -->
<script type="text/javascript"><!--
jQuery(function($) {
  $('.field-shipping').on('change', function(event) {

  	shipping_info = false;
 
  	// If any shipping fields are empty, stop trigger
  	var index;
  	var ship_array = [
  		$('#field-shipping-name'),
  		$('#field-shipping-street-address'),
  		$('#field-shipping-city'),
  		$('#field-shipping-region'),
  		$('#field-shipping-postcode'),
  		$('#field-shipping-country'),
  		$('#field-shipping-email')
  	];
  	for (index = 0; index < ship_array.length; ++index) {
  		if (ship_array[index].val().length == 0) {
  			return;
  		}
  	}

	// Select shipping address form
	if (address=='new') {
	  	var sendform = $('#shipping-form-new').find('input');
	}
	else {
		var sendform = $('#shipping-form-existing').find('input');
	} 

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
				$('#title-shipping-address').append('&nbsp;<img src="catalog/view/default/image/loading.gif" alt="" />');
			},				
			success: function(jsondata) {   

				success = jsondata.success;
				yes = jsondata.yes;

				show_success = 'Ship To Address   <div class="inline-success">✔</div>';
				show_error = 'Ship To Address   <div class="inline-error">✖</div>';
				
				if(success == 1){
					// Show successful confirmation
					$('#title-shipping-address').html(show_success);
					// Set Shipping Success
					shipping_info = true;
					// Calculate Packages
					$('#button-cart').click();				
				}
				else {
					// Show error
					$('#title-shipping-address').html(show_error);
				}

			}

		}); 
	}); 

	// Reset form by removing input elements
	//$('#sendform').children().remove();     

  });
});
//--></script> 



<!-- Country Listing -->
<script type="text/javascript"><!--
$('#shipping-address select[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},          
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
						html += ' selected="selected"';
					}
	
					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('#shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#shipping-address select[name=\'country_id\']').trigger('change');
//--></script>