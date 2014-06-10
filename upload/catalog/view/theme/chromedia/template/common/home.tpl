<?php echo $header;?>

<!-- BEG LANDING SECTION -->
<div class="home-hero text-center">
  <div class="small-12 columns">
    <div class="row">
      <div class="small-12 large-8 small-centered columns" >
      <img src="catalog/view/theme/chromedia/image/ICON-LOGO.png" alt="Open Tech Forever Logo" width="150px" style="margin: 2em auto 0 auto;" class="show-for-medium-up">
      <img src="catalog/view/theme/chromedia/image/ICON-LOGO.png" alt="Open Tech Forever Logo" width="80px" style="margin: 2em auto 0em auto;" class="show-for-small-only">
        <h1>Open Tech Collaborative</h1>
        <h3 class="show-for-medium-up subheader">Manufacture Locally, Collaborate Globally</h3>
        <ul class="button-inline">
      <li>
        <a href="about.html" class="button-box" alt="learn more">LEARN MORE</a>
      </li>
      <!-- <li>
        <a href="" class="button-box show-for-medium-up" data-tooltip class="has-tip tip-top" title="Coming January 31st - Stay Tuned!">COLLABORATE</a>
      </li> -->
    </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END LANDING SECTION -->

<!-- DISPLAY PRODUCTS -->

<!-- DISPLAY PRODUCTS -->

<!-- BEG EMAIL SECTION -->

<div id="newsletter">
  <div class="row" style="padding-top: 1.5em;">
    <div class="large-8 small-12 columns">
      <h4>Stay on top of what’s happening in open source hardware.</h4>
      <p>Sign up to receive monthly highlights.</p>
    </div>
    <div class="large-4 small-12 columns">

     <!-- Begin MailChimp Signup Form -->
  <div id="mc_embed_signup">
    <form action="http://opentechcollaborative.us7.list-manage.com/subscribe/post?u=a2a2b02c4c16296211f9e5a11&amp;id=58a59d4727" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div class="mc-field-group">
      <div class="mc-field-group input-group">
        <!-- <p class="right" style="margin: 1em 0; font-size: 80%;">Want to Collaborate in Denver? &nbsp;&nbsp;&nbsp;<input type="checkbox" style="margin-bottom: 0; vertical-align: middle;" value="1" name="group[7425][1]" id="mce-group[7425]-7425-0"><label for="mce-group[7425]-7425-0"></label></p> -->
        
    </div>
      <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="your@email.com">
    </div>
    
      <div id="mce-responses" class="clear">
        <div class="response" id="mce-error-response" style="display:none"></div>
        <div class="response" id="mce-success-response" style="display:none"></div>
      </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
        <div style="position: absolute; left: -5000px;"><input type="text" name="b_a2a2b02c4c16296211f9e5a11_58a59d4727" value=""></div>
      <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="postfix small button expand"></div>
    </form>
  </div>

<!--End mc_embed_signup-->

    </div>
  </div>
  <a href="#" id="latest-news"></a>
</div>

<!-- END EMAIL SECTION -->
  <?php if(!empty($products)): ?>
    <section class="row block-product-nav">
        <div class="small-12 columns">
          <h1 style="margin: 1em auto 1em auto;" name="latest_news">Latest Products</h1>
        </div>

        <ul>
            <?php foreach ($products as $product): ?>
                <li style="display:inline; ">
                    <?php if ($product['thumb']): ?>
                          <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
                          </div>
                    <?php else: ?>
                        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                    <?php endif ?>

                    <?php if ($product['price']): ?>
                          <div class="price">
                            <?php if (!$product['special']): ?>
                                <?php echo $product['price']; ?>
                            <?php else: ?>
                                <span class="price-old">Old: <?php echo $product['price']; ?></span>
                                <span class="price-new">Price: <?php echo $product['special']; ?></span>
                            <?php endif; ?>
                          </div>
                    <?php endif ?> 
                </li> 
            <?php endforeach;?>
        </ul>
    </section>
  <?php endif;?>
<!-- BEG LATEST NEWS SECTION -->

<section class="row">
<div class="small-12 columns">
  <h1 style="margin: 1em auto 1em auto;" name="latest_news">Latest News</h1>
</div>
  <div class="small-12 medium-6 columns blog-box-left">
    <a href="news/osbh01.html"><img src="catalog/view/theme/chromedia/image/OSBHBlogv2.0.jpg" alt="slide image"></a>
    <h3><a href="news/osbh01.html">Open Source Beehives Update</a></h3>
    <p>The <a href="http://www.opensourcebeehives.net/">Open Source Beehives Project</a> is a collaborative response to the threat faced by bee populations in industrialised nations around the world. The project proposes to design hives that can support bee colonies in a sustainable way, to monitor and track the health and behaviour of a colony as it develops... <a href="news/osbh01.html">(read more)</a></p>
    <ul class="inline-list blog-box-social">
      <li><a href="http://twitter.com/home?status=Open%20Source%20Beehives%20Project%20Update%20Blog%20Post%20http://goo.gl/vlnrW5" TARGET="_blank"><i class="fi-social-twitter"></i></a></li>
      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=http://opentechforever.com/news/osbh01.html&p[images][0]=http://opentechforever.com/img/OSBHBlogv2.0.jpg&p[title]=Open%20Source%20Beehives%20Project%20Update&p[summary]=" TARGET="_blank"><i class="fi-social-facebook"></i></a></li>
    </ul>
  </div>
  <div class="small-12 medium-6 columns blog-box-right">
    <a href="news/fhdc01.html"><img src="catalog/view/theme/chromedia/image/ForeverHome01.jpg" alt="slide image"></a>
    <h3><a href="news/fhdc01.html">Forever Home Design Challenge</a></h3>
    <p><a href="http://denver.architectureforhumanity.org/">Architecture for Humanity Denver (AfH)</a> and Open Tech Forever (OTF) are demonstrating that it is possible to build an open source, compressed earth block (CEB) house that meets the <a href="http://living-future.org/lbc">Living Building Challenge 2.1</a> standard... <a href="news/fhdc01.html">(read more)</a></p>
    <ul class="inline-list blog-box-social">
      <li><a href="http://twitter.com/home?status=Forever%20Home%20Design%20Challenge%20Update%20http://goo.gl/okyT1T" TARGET="_blank"><i class="fi-social-twitter"></i></a></li>
      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=http://opentechforever.com/news/fhdc01.html&p[images][0]=http://opentechforever.com/img/ForeverHome01.jpg&p[title]=Forever%20Home%20Design%20Challenge%20Update&p[summary]=" TARGET="_blank"><i class="fi-social-facebook"></i></a></li>
    </ul>
  </div>
</section>

<section class="row latest-news">
  <div class="small-12 medium-6 columns blog-box-left">
    <a href="news/yoonseoblog01.html"><img src="catalog/view/theme/chromedia/image/YoonseoBlog01.jpg" alt="slide image"></a>
    <h3><a href="news/yoonseoblog01.html">Yoonseo Speaks at the Seoul Digital Forum</a></h3>
    <p>Open Tech Collaborative co-founder Yoonseo Kang spoke at the Seoul Digital Forum earlier this year... <a href="news/yoonseoblog01.html">(read more)</a></p>
    <ul class="inline-list blog-box-social">
      <li><a href="http://twitter.com/home?status=Yoonseo%20Speaks%20at%20the%20Seoul%20Digital%20Forum%20http://goo.gl/RyPvYQ" TARGET="_blank"><i class="fi-social-twitter"></i></a></li>
      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=http://opentechforever.com/news/yoonseoblog01.html&p[images][0]=http://opentechforever.com/img/YoonseoBlog01.jpg&p[title]=Yoonseo%20Speaks%20at%20the%20Seoul%20Digital%20Forum&p[summary]=" TARGET="_blank"><i class="fi-social-facebook"></i></a></li>
    </ul>
  </div>
  <div class="small-12 medium-6 columns blog-box-right">
    <a href="news/barn01.html"><img src="catalog/view/theme/chromedia/image/BarnBlog01.jpg" alt="slide image"></a>
    <h3><a href="news/barn01.html">Open Source Microfactory</a></h3>
    <p>We're renovating a barn on our 40 acre site in Denver, CO to make it our first production facility... <a href="news/barn01.html">(read more)</a>.</p>
    <ul class="inline-list blog-box-social">
      <li><a href="http://twitter.com/home?status=Open%20Source%20Microfactory%20Blog%20Update%20http://goo.gl/mc6cti" TARGET="_blank"><i class="fi-social-twitter"></i></a></li>
      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=http://goo.gl/mc6cti&p[images][0]=http://opentechforever.com/img/BarnBlog01.jpg&p[title]=Open%20Source%20Microfactory%20Blog%20Update&p[summary]=" TARGET="_blank"><i class="fi-social-facebook"></i></a></li>
    </ul>
  </div>
</section>

<!-- END LATEST NEWS SECTION -->

<!-- BEG NEWS SECTION -->
<section class="news">
  <div class="large-2 small-4 columns">
    <a href="http://boingboing.net/2013/11/12/open-source-beehives-sensor-e.html"><img src="catalog/view/theme/chromedia/image/BoingBoing.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.shareable.net/blog/open-tech-forever-challenges-proprietary-innovation"><img src="catalog/view/theme/chromedia/image/Shareable.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.popsci.com/article/technology/open-source-hive-save-bees"><img src="catalog/view/theme/chromedia/image/PopSci.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.fastcoexist.com/3021740/can-a-smart-beehive-network-of-open-source-hives-help-stop-the-bee-apocalypse"><img src="catalog/view/theme/chromedia/image/FastCo.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.treehugger.com/clean-technology/smart-sensors-citizen-science-save-bees.html"><img src="catalog/view/theme/chromedia/image/TreeHugger.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.salon.com/2013/11/13/save_the_bees_save_yourself_newscred/"><img src="catalog/view/theme/chromedia/image/Salon.png" alt=""></a>
  </div>
</section>
<!-- END NEWS SECTION -->

         

<?php echo $footer;?>




                    