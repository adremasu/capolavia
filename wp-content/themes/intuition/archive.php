<?php get_header(); ?>

	<div id="pagetitle" class="pagetitle">
		<div class="container">
			<?php cpotheme_breadcrumb(); ?>
			<?php if(is_category()){ ?>
			<h1 class="pagetitle-title"><?php echo single_cat_title(); ?></h1>
			<?php }elseif(is_day()){ ?>
			<h1 class="pagetitle-title"><?php _e('Daily Archive', 'cpotheme'); ?></h1>

			<?php }elseif(is_month()){ ?>
			<h1 class="pagetitle-title"><?php _e('Monthly Archive', 'cpotheme'); ?></h1>

			<?php }elseif(is_year()){ ?>
			<h1 class="pagetitle-title"><?php _e('Yearly Archive', 'cpotheme'); ?></h1>

			<?php }elseif(is_author()){ ?>
			<h1 class="pagetitle-title"><?php _e('Author Archive', 'cpotheme'); ?></h1>

			<?php }elseif(is_tag()){ ?>
			<h1 class="pagetitle-title"><?php echo single_tag_title('', true); ?></h1>
			<?php } ?>
		</div>
	</div>
		
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