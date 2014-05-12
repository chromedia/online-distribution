  <!-- Product Variables Being Passed to Cart via POST method
  
  *description = POST name*
  quantity to add = quantity
  product id = product_id
  
  options = (option input names)

  ADD TO CART SUMMARY

  1. Add to Cart is CLICKED
  2. Javascript calls public function add in cart.php
  3. Product.php (model) gets product info from database
  4. 

  -->

<?php echo $header; ?>

<!-- Right Column -->
<div id="column-right-product-page">

	<!-- Breadcrumbs -->
	<div id="breadcrumbs-product-page" class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>

	<!-- Product Title -->
	<div id="title-product-page"><?php echo $heading_title; ?></div>

	<!-- Product Info Class-->
	<div class="product-info">

		<!-- Product Images -->
		<?php if ($thumb || $images) { ?>
		<div class="left">
			<?php if ($thumb) { ?>
			<div class="image"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
			<?php } ?>
			<?php if ($images) { ?>
			<div class="image-additional">
			<?php foreach ($images as $image) { ?>
			<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
			<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<!-- Right Column -->
	    	<div class="right">

			<div class="description">
				<!-- Product Code -->
				<span><?php echo $text_model; ?></span> <?php echo $model; ?><br />
				<!-- Stock -->
				<span><?php echo $text_stock; ?></span> <?php echo $stock; ?>
			</div>

		<!-- Price -->
		<div id="price-product-page"><?php echo $text_price; ?> <?php echo $price; ?></div>

		<!-- Add to Cart Block -->
		<div id="block-add-product">
			&nbsp;
			<form action="" method="POST" id="sendform">
			  <!-- Add to Cart Signal -->
			  <input type="hidden" name="addtocart" value="1" />      
			  <!-- Quantity Field -->
			  <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" />
			  <!-- Hidden Product ID -->
			  <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
			</form>  
			  <!-- Add to Cart Button -->
			  <button id="add-product-page">Add to Cart</button>
		    
		</div>		
	      

	      <!-- Profiles/Options -->
	      <?php if ($profiles): ?>
	      <div class="option">
	          <h2><span class="required">*</span><?php echo $text_payment_profile ?></h2>
	          <br />
	          <select name="profile_id">
	              <option value=""><?php echo $text_select; ?></option>
	              <?php foreach ($profiles as $profile): ?>
	              <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
	              <?php endforeach; ?>
	          </select>
	          <br />
	          <br />
	          <span id="profile-description"></span>
	          <br />
	          <br />
	      </div>
	      <?php endif; ?>
	      <?php if ($options) { ?>
	      <div class="options">
	        <h2><?php echo $text_option; ?></h2>
	        <br />
	        <?php foreach ($options as $option) { ?>
	        <?php if ($option['type'] == 'select') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <select name="option[<?php echo $option['product_option_id']; ?>]">
	            <option value=""><?php echo $text_select; ?></option>
	            <?php foreach ($option['option_value'] as $option_value) { ?>
	            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	            <?php if ($option_value['price']) { ?>
	            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	            <?php } ?>
	            </option>
	            <?php } ?>
	          </select>
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'radio') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <?php foreach ($option['option_value'] as $option_value) { ?>
	          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
	          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	            <?php if ($option_value['price']) { ?>
	            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	            <?php } ?>
	          </label>
	          <br />
	          <?php } ?>
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'checkbox') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <?php foreach ($option['option_value'] as $option_value) { ?>
	          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
	          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	            <?php if ($option_value['price']) { ?>
	            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	            <?php } ?>
	          </label>
	          <br />
	          <?php } ?>
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'image') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <table class="option-image">
	            <?php foreach ($option['option_value'] as $option_value) { ?>
	            <tr>
	              <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
	              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
	              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
	                  <?php if ($option_value['price']) { ?>
	                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
	                  <?php } ?>
	                </label></td>
	            </tr>
	            <?php } ?>
	          </table>
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'text') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'textarea') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'file') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
	          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'date') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'datetime') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
	        </div>
	        <br />
	        <?php } ?>
	        <?php if ($option['type'] == 'time') { ?>
	        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
	          <?php if ($option['required']) { ?>
	          <span class="required">*</span>
	          <?php } ?>
	          <b><?php echo $option['name']; ?>:</b><br />
	          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
	        </div>
	        <br />
	        <?php } ?>
	        <?php } ?>
	      </div>
	      <?php } ?>
	     

	    </div>
	  </div>

	  <div id="tabs" class="htabs"><a href="#tab-description"><?php echo $tab_description; ?></a>
	    <?php if ($attribute_groups) { ?>
	    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
	    <?php } ?>
	    <?php if ($products) { ?>
	    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>
	    <?php } ?>
	  </div>
	  <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
	  <?php if ($attribute_groups) { ?>
	  <div id="tab-attribute" class="tab-content">
	    <table class="attribute">
	      <?php foreach ($attribute_groups as $attribute_group) { ?>
	      <thead>
	        <tr>
	          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
	        </tr>
	      </thead>
	      <tbody>
	        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
	        <tr>
	          <td><?php echo $attribute['name']; ?></td>
	          <td><?php echo $attribute['text']; ?></td>
	        </tr>
	        <?php } ?>
	      </tbody>
	      <?php } ?>
	    </table>
	  </div>
	  <?php } ?>

	  <?php if ($tags) { ?>
	  <div class="tags"><b><?php echo $text_tags; ?></b>
	    <?php for ($i = 0; $i < count($tags); $i++) { ?>
	    <?php if ($i < (count($tags) - 1)) { ?>
	    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
	    <?php } else { ?>
	    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
	    <?php } ?>
	    <?php } ?>
	  </div>
	  <?php } ?>
</div>

  <span id="hooplah"> </span>

  <?php echo $content_bottom; ?>


<script type="text/javascript">
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
</script> 




<script type="text/javascript"> 
// $('#content').text('hello');
</script>

<!-- Add to Cart Javascript -->
<script type="text/javascript">  
jQuery(function($) {    
    $('#add-product-page').on('click', function(event) {

//$('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea') 

        // Get form
        var sendform = $('#sendform');

        // Serialize form
        var data = $('#sendform').serialize();  

        // Send POST data to server
        $(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo HTTPS_SERVER; ?>index.php",
                data: data,
                dataType: "json",
                success: function(jsondata) { 
                    $('.success, .warning, .attention, information, .error').remove();
                    
                    $('#button-cart').prop('disabled', false);

                    if (jsondata.quantity) {
                      $('#notification').html('<div class="success" style="display: none;">' + jsondata.quantity + ' added to cart!' + '<img src="catalog/view/default/image/close.png" alt="" class="close" /></div>');
                        
                      $('.success').fadeIn('slow');                      
                      
                      $('html, body').animate({ scrollTop: 0 }, 'slow'); 
                    } 

                }
            }); 
        }); 

        return false;

    });
});
//--></script>
          

<?php if ($options) { ?>

<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);
		
		$('.error').remove();
		
		if (json['success']) {
			alert(json['success']);
			
			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
		}
		
		if (json['error']) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
		}
		
		$('.loading').remove();	
	}
});
</script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#review .pagination a').on('click', function() {
	$('#review').fadeOut('slow');
		
	$('#review').load(this.href);
	
	$('#review').fadeIn('slow');
	
	return false;
});			

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}
			
			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');
								
				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
</script> 
<script type="text/javascript">
$('#tabs a').tabs();
</script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

<?php echo $footer; ?>