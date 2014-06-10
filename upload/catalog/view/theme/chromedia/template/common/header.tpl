<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Open Tech Collaborative</title>
    <link rel="shortcut icon" href="img/favicon.ico">

    <link rel="stylesheet" href="catalog/view/theme/chromedia/css/app.css" />
     <link rel="stylesheet" href="catalog/view/theme/chromedia/css/default.css" />

    <script src="catalog/view/javascript/bower_components/modernizr/modernizr.js"></script>
    <script src="catalog/view/javascript/bower_components/jquery/jquery.min.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46786512-1', 'opentechcollaborative.cc');
  ga('send', 'pageview');

</script>

  </head>
  <body>

<!-- BEG TOP BAR -->
<div class="fixed" id="top">
<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#"><img src="catalog/view/theme/chromedia/image/ICON-LOGO.png" width="46px" alt="" style="margin-top: -4px; margin-right: 10px;">Open Tech Collaborative</a></h1>
    </li>
  </ul>

  <section class="top-bar-section">
    <?php $totalItems = $this->cart->countProducts(); ?>

    <!-- Right Nav Section -->
    <ul class="right">
      <li><a href="about.html">Learn More</a></li>
      <li><a class="scroll" data-speed="500" data-easing="linear" href="#latest-news">Latest News</a></li>
      <li><a href="#">Contact Us</a></li>
      <li><a href="<?php echo $this->url->link('checkout/cart', '', 'SSL'); ?>"> 
        <span class="items-in-cart" style="color:red;"><?php echo ($totalItems ? $totalItems : '' ); ?></span> Cart
      </a></li>
    </ul>
  </section>
</nav>
</div>
<!-- END TOP BAR -->

<div id="notification"></div>