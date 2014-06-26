<?php if(!empty($products)): ?>
   <!-- id="shop"  -->
  <span id="shop" style="margin:110px;"></span>
  <section class="row mtb20">
    <h1 style="margin: 1em auto 1em auto;" name="latest_news">Products</h1>

    <?php foreach ($products as $product): ?>
      <div class="large-4 columns">
        <div class="card-bordered">
          <div class="card-title">
            <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>"><?php echo $product['name']; ?></a>  
          </div>
          <div class="card-thumb">
            <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>">
              <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            
            <?php if(0): ?>
              <?php if(isset($product['videoEmbedTag']) && $product['videoEmbedTag']): ?>
                <a class="product-video-trigger" embed-video='<?php echo $product['videoEmbedTag'];?>'>
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
                </a>
              <?php else: ?>
                <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>">
                  <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
                </a>
              <?php endif;?>
            <?php endif; ?>
          </div>

          <div class="card-body">
            <?php echo strip_tags($product['description']); ?>
          </div>
          <div class="card-footer">
            <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>" class="btn-view-small">View</a>
            <!-- <a href="javascript:void(0);" class="btn-view-small">Add to Cart</a> -->
            <span class="price">
              <?php echo $product['price'];?>  
            </span>  
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <!-- <a href="<?php //echo $this->url->link('product/display/all'); ?>">View Product List</a> -->
  </section>
<?php endif;?>
