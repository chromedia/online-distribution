<?php if ($products_in_cart_count): ?>
    <div class="large-5 columns" style="margin-left: 80px;">
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
                                <input class="qty-input qty-in-cart" type="text" key="<?php echo $product['key'];?>" value="<?php echo $product['quantity']; ?>">
                                <span class="qty-input-display-only" style="display:none;"><?php echo $product['quantity']; ?></span>
                            </div> 
                            <div class="cart-dd">
                                <em>Price</em> <strong class="price"><?php echo $product['price']; ?></strong>    
                            </div>
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