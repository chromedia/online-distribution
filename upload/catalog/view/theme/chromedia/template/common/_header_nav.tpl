<nav class="top-bar" data-topbar>
  <ul class="title-area">
    <li class="name">
      <h1><a href="<?php echo $this->url->link('common/home', '', 'SSL'); ?>"><img src="<?php echo $logo;?>" width="46px" alt="" style="margin-top: -4px; margin-right: 10px;">Open Tech Collaborative</a></h1>
    </li>
  </ul>

  <section class="top-bar-section">
      <?php $totalItems = $this->cart->countProducts(); ?>

    <!-- Right Nav Section -->
    <ul class="right">
      <li><a href="<?php echo $this->url->link('information/learnmore', '', 'SSL'); ?>">Learn More</a></li>
      <li>
          <a class="news-link-scroll scroll" data-speed="500" data-easing="linear" href="#latest-news" style="display:none;">Latest News</a>
          <a class="news-link" href="<?php echo $this->url->link('information/news', '', 'SSL'); ?>">Latest News</a>
      </li>
      <li><a href="<?php echo $this->url->link('information/learnmore', '', 'SSL').'#contact-us'; ?>">Contact Us</a></li>

      <li><a href="javascript:void(0);">Shop</a></li>

      <li><a href="<?php echo $this->url->link('checkout/cart', '', 'SSL'); ?>">
            <span class="items-in-cart" <?php if($totalItems == 0): ?> style="display:none;"<?php endif;?>><?php echo $totalItems; ?></span>
            Cart
      </a></li>
    </ul>
  </section>
</nav>