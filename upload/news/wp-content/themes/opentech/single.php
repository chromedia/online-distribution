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
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<nav class="nav-single">
						<h1 class="assistive-text"><?php the_title(); ?></h1>
					</nav><!-- .nav-single -->
					<?php
						$categories = get_the_category();
						$separator = ' ';
						$output = '';
						if($categories){
						foreach($categories as $category) {
						$output .= '<a class="alert-links" href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
						}
						echo trim($output, $separator);
						}
					?>
					<span class="post-date"> <?php the_date(); ?></span>
					<? if( has_post_thumbnail( $post_id ) ): ?>
						<div class="post-image">
							<img title="image title" alt="thumb image" class="wp-post-image"
							src="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" style="width:100%; height:auto;">
						</div>
					<? endif; ?>
					<div class="entry blog-p">
						<div class="row post-entry">
							<div class="col-lg-12"><?php the_content(); ?></div>
							<div class="row author">
								<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
								</div>
								<div class="col-lg-10 col-md-10 col-sm-9 col-xs-6">
									<span class="author"><?php the_author(); ?></span>
									<p><?php the_tags(); ?></p>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; endif; ?>
			</div>
			<!-- Footer widget area 4 -->
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Area 4') ) : else : ?>
			<?php endif; ?>
		</div>
		<div class="col-lg-4 col-md-4 sidebar">
				<?php get_sidebar(); ?>
				<!-- Footer widget area 4 -->
				<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Page Sidebar') ) : else : ?>
				<?php endif; ?>
				<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Area 3') ) : else : ?>
				<?php endif; ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>