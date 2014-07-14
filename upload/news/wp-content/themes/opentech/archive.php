<?php get_header(); ?>
<ul class="breadcrumbs">
	<li>
		<a href="/">Home</a>
	</li>
	<li>
		<a href="/">News List</a>
	</li>
	<li>
		<?php the_title(); ?>
	</li>
</ul>
<section class="blog-article">
	<div class="row single-post">
		<div class="col-lg-8 col-md-8">
			<?php if (have_posts()) : ?>

	 			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

				<?php /* If this is a category archive */ if (is_category()) { ?>
					<h2>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>

				<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h2>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>

				<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h2>Archive for <?php the_time('F jS, Y'); ?></h2>

				<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h2>Archive for <?php the_time('F, Y'); ?></h2>

				<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>

				<?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h2 class="pagetitle">Author Archive</h2>

				<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h2 class="pagetitle">Blog Archives</h2>
				
				<?php } ?>

				<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>

				<?php while (have_posts()) : the_post(); ?>
				
					<div <?php post_class() ?>>
						<div class="row archives">
							<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12"> <?php the_post_thumbnail( $size, $attr ); ?> </div>
							<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">	<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
								<?php include (TEMPLATEPATH . '/inc/meta.php' ); ?>
								<div class="entry">
									<p> <?php echo strip_tags(substr(get_the_content(), 0,150)).""; ?></p>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
				<br>
				<p><?php include (TEMPLATEPATH . '/inc/nav.php' ); ?></p> 
		<?php else : ?>

			<h2>Nothing found</h2>

		<?php endif; ?>
		</div>
		<div class="col-lg-4 col-md-4 sidebar">
			<?php get_sidebar(); ?>

		</div>
	</div>
</section>
<?php get_footer(); ?>
