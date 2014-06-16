<?php // Template Name: Home page ?>
<?php  get_header(); ?>


<section class="row latest-news">
  <div class="small-12 columns">
    <h1 style="margin: 1em auto 1em auto;" name="latest_news">Latest News</h1>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="blog-post-box blog-box-left small-12 medium-6 columns ">
            <?php 
      if(has_post_thumbnail()){
    ?>
        
        <?php the_post_thumbnail(); } ?>
      
            <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="ptb20"><?php echo strip_tags(substr(get_the_content(), 0,150))."..."; ?></div>
            <a href="<?php the_permalink(); ?>" class="btn btn-rounded outline">Read More</a>       
          </div>
          <?php endwhile; ?>
        <?php else : ?>

    <h2>Not Found</h2>

  <?php endif; ?>

  </div>
</section>





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

<?php  get_footer(); ?>