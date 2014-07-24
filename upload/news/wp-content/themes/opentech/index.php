<?php  get_header(); ?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Home</a>
    </li>
    <li>
        News List
    </li>
</ul>
<section class="row blog-article">
    <div class="row single-post main">

        <h1>Latest News</h1>
        <div class="col-lg-8 col-md-8" style="padding-left:0">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="col-md-12 blog-post-box blog-box-left">
                <?php
                    if(has_post_thumbnail()){
                ?>
                <?php the_post_thumbnail(); } ?>
                <h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="ptb20 post-excerpt">
                    <p><?php echo strip_tags(substr(get_the_content(), 0,150))."..."; ?>
                    <a href="<?php the_permalink(); ?>">(Read More)</a></p> 
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
        <div class="col-lg-4 col-md-4 sidebar">
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<!-- <section class="row mtb-20 news-section">

<?php get_sidebar(); ?>

</section> -->

<?php  get_footer(); ?>
