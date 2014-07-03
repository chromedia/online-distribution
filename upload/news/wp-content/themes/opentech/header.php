
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/normalize.css" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/foundation.min.css" type="text/css" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

	<?php //echo  bloginfo('stylesheet_url'); exit;?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
	<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/js/vendor/jquery.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/js/smooth-scroll.js"></script>

</head>

<!-- <body class="f-topbar-fixed" <?php body_class(); ?>> -->
<!-- BEG TOP BAR -->
<div class="fixed" id="top">
	<nav class="top-bar" data-topbar>
	  <ul class="title-area">
	    <li class="name">
	      <h1><a href="/"><img src="<?php bloginfo('template_directory') ?>/images/ICON-LOGO.png" width="46px" alt="" style="margin-top: -4px; margin-right: 10px;">Open Tech Collaborative</a></h1>
	    </li>
	  </ul>	  
	  <section class="top-bar-section">
	    <!-- Right Nav Section -->
	    <ul class="right">
	      
            <li><a href="/index.php?route=information/learnmore">Learn More</a></li>

            <li><a class="scrollTo" href="/index.php?route=common/home#latest-news">Latest News</a></li>

            <li><a href="/index.php?route=information/learnmore#contact-us">Contact Us</a></li>

            <li><a href="/index.php?route=collaborate/products">Collaborate</a></li>

            <li><a class="scrollTo"  href="/index.php?route=common/home#shop">Shop</a></li>
	        <li><a class="cart-link" href="/index.php?route=checkout/cart">
	            Cart
	      </a></li>
	    </ul>
	  </section>

	</nav>
</div>

<!-- END TOP BAR -->

<script type="text/javascript">
	$(function() {
        $.ajax({
            url: '<?php echo DIR_HOME;?>/index.php?route=api/cart/countProducts',
            type: 'post',
            dataType: 'json',
            success: function(json) {
            	var count = json.productsCount;
            	
            	if (count) {
            		if ($('.items-in-cart').length == 0) {
            			$('.cart-link').prepend('<span class="items-in-cart">'+count+'</span>');
            		}
            	}
            },
            error: function(error) {
             	console.log(error);
            }
        });
    });
</script>

