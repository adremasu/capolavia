<?php
/*
  Template Name: Blog Page
 */
?>
<?php get_header(); ?>


<?php if(get_post_meta(get_the_ID(), 'page_title', true) != 0) get_template_part('element', 'page-header'); ?>
	
<div id="main" class="main">
	<div class="container">
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
			<?php if(have_posts()) while(have_posts()): the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
			</div>
			<?php endwhile; ?>
			
			<?php query_posts("post_type=post&paged=".cpotheme_current_page()."&posts_per_page=".get_option('posts_per_page')); ?>
			<?php if(have_posts()): while(have_posts()): the_post(); ?>
			<?php get_template_part('element', 'blog'); ?>
			<?php endwhile; ?>
			
			<div class="pagination">
				<div class="pagination-prev">
					<?php previous_posts_link(__('Newer', 'cpotheme')); ?>
				</div>
				<div class="pagination-next">
					<?php next_posts_link(__('Older', 'cpotheme')); ?>
				</div>
			</div>
			<?php endif; ?>
			
			<?php comments_template('', true); ?>
		</section>
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>