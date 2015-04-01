<?php
/*
  This template is used to display individual blog entries.
 */
?>

<?php $current_format = get_post_format(); ?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>"> 
	<?php if(!is_singular()): ?>
	<h2 class="post-title">
		<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Go to %s', 'cpotheme'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a>
	</h2>
	<?php endif; ?>
	
	<?php if($current_format == 'gallery'):
	$args = array(
	'post_type' => 'attachment',
	'numberposts' => null,
	'post_status' => null,
	'post_mime_type' => 'image',
	'post_parent' => $post->ID);
	cpotheme_post_slideshow($args);
	
	elseif(has_post_thumbnail()): ?>
	<div class="post-image">
		<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Go to %s', 'cpotheme'), the_title_attribute('echo=0')); ?>" rel="bookmark">
			<?php the_post_thumbnail('800'); ?>
		</a>
	</div>
	<?php endif; ?>
	
	<div class="post-byline">
		<?php cpotheme_postpage_date(); ?>
		<?php cpotheme_postpage_author(); ?>
		<?php cpotheme_postpage_categories(); ?>
		<?php cpotheme_postpage_comments(); ?>
		<?php cpotheme_edit(); ?>
	</div>
	<div class="post-content">
		<?php if(cpotheme_get_option('cpo_postpage_preview') == '1' || is_singular()) 
			the_content();
		else 
			the_excerpt(); ?>
		<?php if(!is_singular()): ?>
		<a class="primary-color-bg readmore" href="<?php the_permalink(); ?>"><?php _e('Read More', 'cpotheme'); ?> &raquo;</a>
		<?php else: ?>
		<?php cpotheme_postpage_tags(); ?>
		<?php endif; ?>
	</div>
</article>