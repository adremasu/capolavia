<?php get_header(); ?>

<div id="main" class="main">
	<div class="container">		
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
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
		</section>
		<?php get_sidebar('blog'); ?>
	</div>
</div>

<?php get_footer(); ?>