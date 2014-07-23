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
							<!--<ul class="inline-list blog-box-social">
					      <li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>"  target="_blank"><i class="fi-social-twitter"></i></a></li>
					      <li><a href="http://www.facebook.com/sharer/sharer.php?s=100&amp;p[url]=<?php the_permalink(); ?>&amp;p[title]=<?php the_title(); ?>&amp;p[summary]=" target="_blank"><i class="fi-social-facebook"></i></a></li>
					    </ul>-->
            </div>

          </div>
          <?php endwhile; ?>
        <?php else : ?>

    <h2>Not Found</h2>

    <?php endif; ?>

    </div>
</section>

<?php get_sidebar(); ?>

<?php  get_footer(); ?>
