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
						src="<?=wp_get_attachment_url( get_post_thumbnail_id() ); ?>" style="width:100%; height:auto;">
					</div>
				<? endif; ?>
				<div class="entry blog-p">
					<div class="row post-entry">
						<div class="col-lg-9"><?php the_content(); ?></div>
						<div class="col-lg-3 author">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
							<span class="author"><?php the_author(); ?></span>
							<p><?php the_tags(); ?></p>
						</div>
					</div>
				</div>
				<?php endwhile; endif; ?>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 sidebar">
				<h3>Latest Products</h3>
			<?php
				$attachments = get_posts( array(
				    'post_type' => 'attachment',
				    'posts_per_page' => 1,
				    'post_status' => null,
				    'post_mime_type' => 'image'
				) );

				if(isset($attachments[0]->ID)) {
				 echo wp_get_attachment_image( $attachments[0]->ID, 'full' );
				}
				?>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>
	<?php get_footer(); ?>