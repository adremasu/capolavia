<?php
/*
  Template Name: Submenu Page
 */
?>
<?php get_header(); ?>

<?php if(have_posts()) while(have_posts()): the_post(); ?>

<?php if(get_post_meta(get_the_ID(), 'page_title', true) != 0) get_template_part('element', 'page-header'); ?>
	
<div id="main" class="main">
	<div class="container">
		<section id="content" class="content <?php cpotheme_sidebar_position(); ?>">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="page-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="page-link">'.__('Pages', 'cpotheme').':', 'after' => '</div>')); ?>
				</div>
			</div>
			<?php comments_template('', true); ?>
		</section>
		<aside id="sidebar" class="sidebar">
			<ul id="submenu" class="menu-sub">
				<?php $ancestors = array_reverse(get_post_ancestors(get_the_ID())); ?>
				<?php wp_list_pages("title_li=&child_of=".$ancestors[0]); ?>
			</ul>
			<?php dynamic_sidebar('primary-widget-area'); ?>
		</aside>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>