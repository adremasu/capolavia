<?php $query = new WP_Query('post_type=cpo_portfolio&order=ASC&orderby=menu_order&meta_key=portfolio_featured&meta_value=1&numberposts=-1&posts_per_page=-1'); ?>
<?php if($query->posts): $feature_count = 0; ?>
<div id="portfolio" class="portfolio">
	<div class="container">
		<div class="portfolio-contents">
			<?php wp_enqueue_script('cpotheme_cycle'); ?>
			<?php cpotheme_block('home_portfolio', 'portfolio-heading heading'); ?>
			<div class="portfolio-list cycle-slideshow" data-cycle-pause-on-hover="true" data-cycle-slides=".portfolio-row" data-cycle-prev=".portfolio-prev" data-cycle-next=".portfolio-next" data-cycle-timeout="6000" data-cycle-speed="1500" data-cycle-fx="scrollHorz">
				<div class="portfolio-row">
					<div class="row">
						<?php foreach($query->posts as $post): setup_postdata($post); ?>
						<?php $feature_count++; ?>
						<div class="column column-narrow col3">
							<?php get_template_part('element', 'portfolio'); ?>
						</div>
						<?php if($feature_count % 3 == 0 && $feature_count < sizeof($query->posts)) echo '</div></div><div class="portfolio-row"><div class="row">'; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php if(sizeof($query->posts) > 3): ?>
			<div class="portfolio-prev"></div>
			<div class="portfolio-next"></div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>