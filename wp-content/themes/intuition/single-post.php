<?php get_header(); ?>

<?php if(have_posts()) while(have_posts()): the_post(); ?>

<?php if(get_post_meta(get_the_ID(), 'page_title', true) != 0) get_template_part('element', 'page-header'); ?>

<div id="main" class="main">
	<div class="container">
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
			<?php get_template_part('element', 'blog'); ?>
			<?php if(get_the_author_meta('description')) cpotheme_post_authorbio(); ?>
			<?php comments_template('', true); ?>
		</section>
		<?php get_sidebar('blog'); ?>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>