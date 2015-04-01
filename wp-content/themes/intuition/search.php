<?php get_header(); ?>

<div id="pagetitle" class="pagetitle">
	<div class="container">
		<h1 class="pagetitle-title"><?php _e('Search Results for', 'cpotheme') ?> '<?php echo the_search_query();?>'</h1>
	</div>
</div>
	
<div id="main" class="main">
	<div class="container">
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
			<div class="widget_search">
				<form role="search" method="get" id="search-form" class="search-form" action="<?php echo home_url('/'); ?>">
					<input type="text" value="<?php if(isset($_GET['s'])) echo $_GET['s']; ?>" name="s" id="s" />
					<input type="submit" id="search-submit" value="<?php _e('Search', 'cpotheme'); ?>" />
				</form>
			</div>
			<?php if(have_posts()): while(have_posts()): the_post(); ?>
			
			<article class="search" id="post-<?php the_ID(); ?>"> 
				
				<h2 class="search-title">
					<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Go to %s', 'cpotheme'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				
				<div class="search-byline">
					<?php the_permalink(); ?>
				</div>
				<div class="search-content">
					<?php the_excerpt(); ?>
				</div>
			</article>
			
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
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
