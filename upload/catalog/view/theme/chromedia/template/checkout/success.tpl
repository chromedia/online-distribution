<?php echo $header;?>

<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>

<!-- CONTENT STARTS HERE -->
<div class="bar">
  <div class="row">
    <ul class="steps-bar">
      <li><i class="icon-green-check"></i>Shipping information</li>
      <li><i class="icon-arrow-right"></i></li>
      <li><i class="icon-green-check"></i>Credit Card Information</li>  
    </ul>  
  </div>
</div>

<div class="mtb40"> 
  <div class="row">
    <div class="large-6 columns ">
      <h3>Thank you! Payment is successful</h3>
      <p>You have successfully purchased the items listed below. 
          You will receive a summary of your orders through your email. 
          If you have any questions just email us at <a href="#">shipping@opentechcollaborative.cc</a>
      </p>
      <p>Thank you, <br> OpenTechCollaborative</p>
    </div>
    <div class="large-5 columns">
        
        <span>You have <strong><?php echo $products_in_cart_count; ?></strong> <?php echo $products_in_cart_count > 1 ? 'items' : 'item';?> in your shopping cart</span>
        <ul>
            <?php foreach($products as $product): ?>
                <li class="group">
                    <div class="product-thumb"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></div>

                    <ul class="product-cart-details">
                        <li><h3><a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['id']); ?>">
                            <?php echo $product['name']; ?></a></h3>
                        </li>
                        <li>
                          <div class="cart-dd">
                            <em>Quantity </em>
                            <?php echo $product['quantity']; ?>
                          </div> 
                          <div class="cart-dd">
                                <em>Price</em> <strong class="price"><?php echo $product['price']; ?></strong>    
                            </div>
                        </li>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="sub-total">
            <div class="shipping-cost">
                <em>Shipping cost</em>
                <strong><?php echo $shippingCost;?></strong>
            </div>
            <div class="sub-total-value">
                <em>Sub Total</em>
                <strong><?php echo $subTotal; ?></strong>
            </div>
            <div class="total-payment">
                <em>Total</em>
                <strong><?php echo $total; ?></strong>
            </div>
        </div>
    </div>
  </div>
</div>

<?php echo $footer;?>