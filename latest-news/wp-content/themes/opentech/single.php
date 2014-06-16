<?php get_header(); ?>
<section class="blog-article">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php 
			if(has_post_thumbnail()){
		?>
				<div class="blog-image-header">
				<?php the_post_thumbnail(); } ?>
			</div>

		<div class="row blog-row">
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="small-10 small-centered columns ">
				<h1 class="blog-header-large"><?php the_title(); ?></h1>	

					<div class="entry blog-p">
						
						<?php the_content(); ?>

					</div>
					
					<?php edit_post_link('Edit this entry','','.'); ?>
					
				</div>

			

			<?php endwhile; endif; ?>
			</div>
			
			
			
	</div>
	
</section>

	
	


<?php get_footer(); ?>