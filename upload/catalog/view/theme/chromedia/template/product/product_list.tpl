<?php echo $header;?>


<!-- Breadcrumbs -->
<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>
 
<!-- DISPLAY PRODUCTS -->

  <section class="row mtb20">
    <?php if(!empty($products)): ?>
      <h1 style="margin: 1em auto 1em auto;" name="latest_news">All Offered Products</h1>
      <?php foreach ($products as $product): ?>
        <div  class="small-12 medium-6 columns blog-box-left">
          <div class="card-bordered">
            <div class="card-title">
              <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>"><?php echo $product['name']; ?></a>  
            </div>
            
              <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>">
                <img src="<?php echo $product['thumb'] ?>" alt="<?php echo $product['name']; ?>">
              </a>
            
            </div>
            <div class="card-body">
              <?php echo strip_tags($product['description']); ?>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="small-6 columns">
                  <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>" class="btn-view-small">View</a>
                </div>
                <div class="small-6 columns">
                    <span class="price">
                      <?php echo $product['price'];?>  
                    </span> 
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
        <em>No available products yet.</em>
    <?php endif;?>
  </section>

<!-- ALL PRODUCTS -->

<?php echo $footer;?>




                    