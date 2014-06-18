<?php echo $header;?>

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


    <?php if(false): ?>
        <div class="large-5 columns">
          You have <strong>2</strong> items in your shopping cart
          <ul class="cart-list">
            <li class="group">
              <div class="product-thumb"></div>
              <ul class="product-cart-details">
                <li><h3><a href="#">The Opensource Beehives Project</a></h3></li>
                <li>
                  <div class="cart-dd">
                    <em>Quantity </em>
                    1
                  </div> 
                  <div class="cart-dd">
                    <em>Price</em> <strong class="price">$120</strong>    
                  </div>
                </li>
              </ul>
            </li>
            <li class="group">
              <div class="product-thumb"></div>
              <ul class="product-cart-details">
                <li><h3><a href="#">The Opensource Beehives Project</a></h3></li>
                <li>
                  <div class="cart-dd">
                    <em>Quantity </em>
                    1
                  </div> 
                  <div class="cart-dd">
                    <em>Price</em> <strong class="price">$120</strong>    
                  </div>
                </li>
              </ul>
            </li>
          </ul>
          <div class="sub-total">
            <div class="shipping-cost">
              <em>Shipping cost</em>
              <strong>$18.50</strong>
            </div>
            <div class="sub-total-value">
              <em>Sub Total</em>
              <strong>$258.50</strong>
            </div>
          </div>
        </div>
    <?php endif;?>
  </div>
</div>

<?php echo $footer;?>