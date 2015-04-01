<?php get_header(); ?>

<?php if(have_posts()) while(have_posts()): the_post(); ?>

<?php get_template_part('element', 'page-header'); ?>

<div id="main" class="main">
	<div class="container">
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
			<div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
				<div class="page-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages', 'cpotheme').':', 'after' => '</div>')); ?>
				</div>
			</div>
			<?php comments_template('', true); ?>
		</section>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>