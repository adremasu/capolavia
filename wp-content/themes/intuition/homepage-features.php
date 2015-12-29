<?php $feature_args = array(
'post_type' => array('post', 'page'),
'meta_key' => 'page_featured',
'meta_value' => 'features',
'posts_per_page' => -1,
'orderby' => 'menu_order',
'order' => 'ASC'); ?>
<?php $alt_query = new WP_Query($feature_args); ?>
<?php $new_query = new WP_Query('post_type=cpo_feature&posts_per_page=-1&order=ASC&orderby=menu_order'); ?>
<?php if($new_query->posts || $alt_query->posts): $feature_count = 0; ?>
<div id="features" class="features">
	<div class="container">		
		<?php cpotheme_block('home_features', 'features-heading heading'); ?>
		<?php if($new_query->posts) cpotheme_grid($new_query->posts, 'element', 'feature', 3, array('class' => 'column-narrow')); ?>
		
		<div class="row">
			<?php $feature_count = 0; foreach($alt_query->posts as $post): setup_postdata($post); $feature_count++; ?>
			<div class="column column-narrow col3">
				<div class="feature">
					<a class="feature-image primary-color" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('portfolio'); ?>
					</a>
					<div class="feature-body">
						<?php cpotheme_icon(get_post_meta(get_the_ID(), 'page_icon', true), 'feature-icon primary-color-bg'); ?>
						<h3 class="feature-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
						<div class="feature-content">
							<?php the_excerpt(); ?><?php cpotheme_edit(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php if($feature_count % 3 == 0) echo '</div><div class="row">'; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<?php endif; ?>