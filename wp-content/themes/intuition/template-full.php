<?php
/*
  Template Name: Full-width Page
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) while(have_posts()): the_post(); ?>

<?php if(get_post_meta(get_the_ID(), 'page_title', true) != 0) get_template_part('element', 'page-header'); ?>
		
<div id="main" class="main">	
	<div class="container">
		<section id="content" class="content content-wide">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="page-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages', 'cpotheme').':', 'after' => '</div>')); ?>
				</div>
			</div>
			<?php comments_template('', true); ?>
		</section>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>