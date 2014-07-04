<?php echo $header;?>


<!-- BEG LANDING SECTION -->
<div class="home-hero text-center">
    <div class="small-12 columns">
        <div class="row">
            <div class="small-12 large-8 small-centered columns" >
                 <img src="catalog/view/theme/chromedia/image/ICON_LOGO.png" alt="Open Tech Forever Logo" width="150px" style="margin: 2em auto 0 auto;" class="show-for-medium-up">
                <!-- <img src="catalog/view/theme/chromedia/image/ICON_LOGO.png" alt="Open Tech Forever Logo" width="80px" style="margin: 2em auto 0em auto;" class="show-for-small-only"> -->
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
<!-- END LANDING SECTION -->

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
      <div class="mc-field-group input-group"></div>
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
</div>
<!-- END EMAIL SECTION -->
 
<!-- DISPLAY PRODUCTS -->
  <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/home/_products_homepage_list.tpl'); ?> 
<!-- END DISPLAY PRODUCTS -->
  

<!-- BEG LATEST NEWS SECTION -->
  <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/home/_news_homepage_list.tpl'); ?> 
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




                    