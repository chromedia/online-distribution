<?php echo $header;?>

<?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/modal/video_modal.tpl'); ?> 

<?php include(DIR_APPLICATION . 'view/theme/chromedia/template/common/breadcrumbs.tpl'); ?>

<div 
    <?php if(!empty($video_tag)): ?>
        class="product-cover product-video-trigger" embed-video='<?php echo $video_tag;?>'
    <?php else: ?>
        class="product-cover"
    <?php endif;?>  
    style="background-image: url('<?php echo $header_img;?>')">

    <div class="notification green" style="display:none;"></div>

    <div class="product-title">
        <div class="row">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
</div>

<div class="row">
    <ul class="product-tabs">
        <li><a href="javascript:void(0);" data-content="#product-overview" class="active">Overview</a></li>
        <li><a href="javascript:void(0);" data-content="#details">Details</a></li>
        <li><a href="javascript:void(0);" data-content="#documentation">Documentation</a></li>
    </ul>  
</div>

<div class="row">
    <div class="large-8 columns tabs-content" style="display:none;">
        <div class="product-description active" id="product-overview"> 
            <?php echo $description; ?>
        </div>

        <div class="product-description" id="details"> 
            <?php echo $details; ?>
        </div>

        <div class="product-description" id="documentation"> 
            <?php  echo $documentation; ?>
        </div>
    </div>

    <?php if(0): ?>
        <div class="large-4 columns">
            <div class="product-sidebar">
                <dl class="product-list-details">
                    <dt>Price</dt>
                    <dd class="dd-price"><?php echo $price; ?></dd>
                </dl>
                <dl class="product-list-details">
                    <dt>Availability</dt>
                    <dd><?php echo $stock; ?></dd>
                </dl>
                <dl class="product-list-details">
                    <dt>Quantity</dt>
                    <dd>
                      <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" class="qty-input"></dd>
                </dl>

                <!-- Hidden Product ID -->
                <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
                <input type="submit" value="Add to Cart" class="btn add-to-cart-btn">
          </div>
        </div>
    <?php endif;?>
</div>


<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/tab.js"></script>
<script type="text/javascript">

    $('.product-tabs li a').tab();

    $('.product-video-trigger').off('click').on('click', function() {
        $('#videoModal').find('.flex-video').html($(this).attr('embed-video'));
        $('#videoModal').foundation('reveal', 'open');
});
</script>

<?php echo $footer;?>