<?php // Template Name: Home page ?>
<?php  get_header(); ?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Home</a>
    </li>
    <li>
        News List
    </li>
</ul>

<section class="row mtb-20 news-section">
  <div class="small-12 columns">
    <h1 style="margin: 1em auto 1em auto;" name="latest_news">Latest News</h1>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <div class="blog-post-box blog-box-left small-12 medium-6 columns ">
            <?php 
              if(has_post_thumbnail()){
            ?>
        
            <?php the_post_thumbnail(); } ?>
      
            <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="ptb20 post-excerpt">
            	<?php echo strip_tags(substr(get_the_content(), 0,150))."..."; ?>
            	<br>
            	<a href="<?php the_permalink(); ?>">(Read More)</a>       
							<ul class="inline-list blog-box-social">
					      <li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>"  target="_blank"><i class="fi-social-twitter"></i></a></li>
					      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&amp;p[url]=<?php the_permalink(); ?>&amp;p[title]=<?php the_title(); ?>&amp;p[summary]=" target="_blank"><i class="fi-social-facebook"></i></a></li>
					    </ul>
            </div>
            
          </div>
          <?php endwhile; ?>
        <?php else : ?>

    <h2>Not Found</h2>

    <?php endif; ?>
        
    </div>
</section>


<section class="news">
  <div class="large-2 small-4 columns">
    <a href="http://boingboing.net/2013/11/12/open-source-beehives-sensor-e.html"><img src="<?php bloginfo('template_directory'); ?>/images/BoingBoing.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.shareable.net/blog/open-tech-forever-challenges-proprietary-innovation"><img src="<?php bloginfo('template_directory'); ?>/images/Shareable.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.popsci.com/article/technology/open-source-hive-save-bees"><img src="<?php bloginfo('template_directory'); ?>/images/PopSci.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.fastcoexist.com/3021740/can-a-smart-beehive-network-of-open-source-hives-help-stop-the-bee-apocalypse"><img src="<?php bloginfo('template_directory'); ?>/images/FastCo.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.treehugger.com/clean-technology/smart-sensors-citizen-science-save-bees.html"><img src="<?php bloginfo('template_directory'); ?>/images/TreeHugger.png" alt=""></a>
  </div>
  <div class="large-2 small-4 columns">
    <a href="http://www.salon.com/2013/11/13/save_the_bees_save_yourself_newscred/"><img src="<?php bloginfo('template_directory'); ?>/images/Salon.png" alt=""></a>
  </div>
</section>


<?php  get_footer(); ?>