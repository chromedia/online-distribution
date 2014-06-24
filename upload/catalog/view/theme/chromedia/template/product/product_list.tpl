<?php echo $header;?>

<!-- Breadcrumbs -->
<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>
 
<!-- DISPLAY PRODUCTS -->
<?php if(!empty($products)): ?>

  <section class="row mtb20">
    <h1 style="margin: 1em auto 1em auto;" name="latest_news">All Offered Products</h1>
    <?php foreach ($products as $product): ?>
      <div class="large-4 columns">
        <div class="card-bordered">
          <div class="card-title">
            <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>"><?php echo $product['name']; ?></a>  
          </div>
          <div class="card-thumb">
            <a href="">
              <img src="<?php echo !empty($product['thumb']) ? $product['thumb'] : $default_image_src; ?>" alt="<?php echo $product['name']; ?>">
            </a>
            
          </div>
          <div class="card-body">
            <?php echo strip_tags($product['description']); ?>
          </div>
          <div class="card-footer">
            <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>" class="btn-view-small">View</a>
            <span class="price">
              <?php echo $product['price'];?>  
            </span>  
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

<?php else: ?>
    <em>No available products yet.</em>
<?php endif;?>
<!-- ALL PRODUCTS -->


<?php echo $footer;?>




                    