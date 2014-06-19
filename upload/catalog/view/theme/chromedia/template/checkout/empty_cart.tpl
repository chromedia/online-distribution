<?php echo $header; ?>


<!-- CONTENT STARTS HERE -->
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

<div class="mtb40"> 
    <div class="row">
        <div class="large-6 columns step-content">
            <p>Your shopping cart is empty. </p>
            <p><a href="<?php echo $this->url->link('product/display/all');?>">Shop Now!</a></p>
        </div>
    </div>
</div>

<?php echo $footer;?>