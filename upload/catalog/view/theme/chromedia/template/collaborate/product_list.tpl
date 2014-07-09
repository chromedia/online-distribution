<?php echo $header;?>

<!-- Breadcrumbs -->
<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>

<?php if(!empty($products) && $products): ?>
    <section class="row mtb20" style="margin-bottom: 65px;">
        <h2 style="margin: 1em auto 1em auto;"><?php echo $heading_title;?></h2>

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
                    </div>

                    <div class="card-body">
                        <?php echo strip_tags($product['description']); ?>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="small-12 columns">
                                <a href="<?php echo  $this->url->link('product/product', 'product_id=' . $product['product_id']); ?>" class="btn-view-small">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

<?php else: ?>
    <div class="mtb40"> 
        <div class="row">
            <div class="large-6 large-centered columns">
                <div class="text-center">
                    <img class="mtb40" src="catalog/view/theme/chromedia/image/ICON_LOGO.png" alt="">
                    <h3 class="text-center">Our products are all in store.</h3>
                    <p class="text-center">Surely, there is <a href="<?php echo $this->url->link('common/home', '', 'SSL'); ?>#shop">something</a> that would interest you.</p>
                </div>
            </div>
        </div>
    </div>  
<?php endif;?>

<?php echo $footer;?>
