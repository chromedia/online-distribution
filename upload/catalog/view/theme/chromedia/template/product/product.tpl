<?php echo $header;?>

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


<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>

<div class="product-cover" style="background-image: url('<?php echo $header_img;?>')">
    <div class="notification green" style="display:none;"></div>
    <!-- <div class="notification green"></div> -->

    <div class="product-title">
        <div class="row">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
</div>
<div class="row">
    <ul class="product-tabs tabs">
        <li><a href="#product-overview" class="active">Product Overview</a></li>
        <li><a href="#details">Details</a></li>
        <li><a href="#documentation">Documentation</a></li>
    </ul>  
</div>

<div class="row">
    <?php if(false): ?>
      <ul class="large-8 columns tabs-content">
          <li class="product-description active" id="product-overview"> 
              <?php echo $description; ?>
          </li>

          <li class="product-description" id="details"> 
              <?php echo 'details testing'; ?>
          </li>

          <li class="product-description" id="documentation"> 
              <?php echo 'documentation testing'; ?>
          </li>
      </ul>
    <?php endif;?>

    <div class="large-8 columns tabs-content">
        <div class="product-description active" id="product-overview"> 
            <?php echo $description; ?>
        </div>

        <div class="product-description" id="details"> 
            <?php //echo 'details testing'; ?>
        </div>

        <div class="product-description" id="documentation"> 
            <?php  //echo //'documentation testing'; ?>
        </div>
    </div>

    <div class="large-4 columns">
        <div class="product-sidebar">
            <dl class="product-list-details">
                <dt>Price</dt>
                <dd class="dd-price"><?php echo $price; ?></dd>
            </dl>
            <dl class="product-list-details">
                <dt>Availability</dt>
                <dd><?php echo $stock; ?></dd>
            </dl>
            <dl class="product-list-details">
                <dt>Quantity</dt>
                <dd>
                  <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" class="qty-input"></dd>
            </dl>

            <!-- Hidden Product ID -->
            <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
            <input type="submit" value="Add to Cart" class="btn add-to-cart-btn">
      </div>
    </div>
</div>

<?php if(false): ?>
  <!-- Right Column -->
  <div id="column-right-product-page" style="margin-top:150px;">

      <!-- Breadcrumbs -->
      <div id="breadcrumbs-product-page" class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb): ?>
            <?php echo $breadcrumb['separator']; ?>

            <?php if($breadcrumb['href']): ?>
              <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php else: ?>
              <?php echo $breadcrumb['text']; ?>
            <?php endif;?>

          <?php endforeach; ?>
      </div>

      <!-- Product Title -->
      <h4><?php echo $heading_title; ?></h3>

      <!-- Product Info Class-->
      <div class="product-info">
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
                <button id="add-to-cart">Add to Cart</button>
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
        
  </div>

  <span id="hooplah"> </span>
<?php endif;?>

<!-- Add to Cart Javascript -->
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/cart.js"></script>
<script type="text/javascript">
    $('.add-to-cart-btn').addToCart({
        'product_id' : $('input[name="product_id"]').val(),
        'quantity'   : $('input[name="quantity"]').val(),
        'on_success' : function(json) {
            $('#notification, .green').hide();
            if (json.error) {
                alert('An error occured while processing request.');
            } 
            
            if (json.success) {
                $('.green').html('<div class="success">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    
                $('.green').fadeIn('slow');  
                $('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }  
        }
    });
</script>

<?php echo $footer;?>