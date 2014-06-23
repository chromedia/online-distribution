<?php echo $header; ?>


<!-- CONTENT STARTS HERE -->
<?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>

<div class="mtb40"> 
    <div class="row">
        <div class="large-6 columns step-content">
            <p>Your shopping cart is empty. </p>
            <p><a href="<?php echo $this->url->link('product/display/all');?>">Shop Now!</a></p>
        </div>
    </div>
</div>

<?php echo $footer;?>