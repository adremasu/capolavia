<?php wp_enqueue_style('cpotheme-fontawesome'); ?>
<div class="portfolio-item">
	<a class="portfolio-item-image" href="<?php the_permalink(); ?>">
		<div class="portfolio-item-overlay"></div>
		<?php the_post_thumbnail('portfolio', array('title' => '')); ?>
	</a>
	<h3 class="portfolio-item-title">
		<?php the_title(); ?>
	</h3>
	<?php cpotheme_edit(); ?>
</div>