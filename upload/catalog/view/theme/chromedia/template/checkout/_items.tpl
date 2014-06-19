<?php if ($products_in_cart_count): ?>
    <div class="large-5 columns">
        <span class="items-header">You have <strong class="products-count"><?php echo $products_in_cart_count; ?></strong> <?php echo $products_in_cart_count > 1 ? 'items' : 'item';?> in your shopping cart</span>

        <ul class="cart-list">
            <?php foreach ($products as $product): ?>
                <li class="group">
                    <div class="product-thumb">
                       <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                    </div>

                    <ul class="product-cart-details">
                        <li><h3><a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['id']); ?>">
                            <?php echo $product['name']; ?></a></h3>
                        </li>
                        <li>
                            <div class="cart-dd">
                                <em>Quantity </em>
                                <input class="qty-input" type="text" value="<?php echo $product['quantity']; ?>">
                            </div> 
                            <div class="cart-dd">
                                <em>Price</em> <strong class="price"><?php echo $product['price']; ?></strong>    
                            </div>
                           <!--  <div class="cart-dd product-total-price">
                                <em>Extended Price</em> <strong class="total"><?php echo $product['total']; ?></strong>    
                            </div> -->
                            <input type="image" class="quantity-changed" key="<?php echo $product['key'];?>" src="catalog/view/theme/default/image/update.png"/>
                        </li>
                        <li><a href="javascript:void(0);" key="<?php echo $product['key'];?>" class="remove">remove</a></li>
                    </ul>
                </li>
            <?php endforeach;?>
        </ul>

        <div class="sub-total">
            <div class="shipping-cost">
                <em>Shipping cost</em>
                <strong>$ 0.00</strong>
            </div>
            <div class="sub-total-value">
                <em>Sub Total</em>
                <strong><?php echo $subTotalWithCurrency; ?></strong>
            </div>
            <div class="total-payment">
                <em>Total</em>
                <strong><?php echo $subTotalWithCurrency; ?></strong>
            </div>
        </div>
    </div>
<?php endif;?>

<?php if(false): ?>
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
<?php endif;?>