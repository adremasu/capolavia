<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />    
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="outer" id="top">
	
		<div class="wrapper wrapper-<?php echo cpotheme_get_option('cpo_layout_style'); ?>">
			<div id="topbar" class="topbar">
				<div class="container">
					<div id="topmenu" class="topmenu">
						<?php wp_nav_menu(array('menu_class' => 'menu-top', 'theme_location' => 'top_menu', 'depth' => 1, 'fallback_cb' => null)); ?>
					</div>
					
					<?php cpotheme_languages(); ?>
					<?php cpotheme_social(); ?>
					<div class="clear"></div>
				</div>
			</div>
			<header id="header" class="header">
				<div class="container">
					<?php cpotheme_logo(205, 50); ?>
					
					<?php cpotheme_menu(); ?>
					
					<?php cpotheme_mobile_menu(); ?>
					
					<div class='clear'></div>
				</div>
			</header>
			<?php if(cpotheme_get_option('cpo_slider_always') == 1 || is_home() || is_front_page()){ ?>
			<?php $feature_args = array(
			'post_type' => array('post', 'page'),
			'meta_key' => 'page_featured',
			'meta_value' => 'slider',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'ignore_sticky_posts' => 1,
			'order' => 'ASC'); ?>
			<?php $slider_posts = new WP_Query($feature_args); ?>
			<?php if($slider_posts->post_count > 0): $slide_count = 0; ?>
			<div id="slider" class="slider">
				<ul class="slider-slides">
					<?php while($slider_posts->have_posts()): $slider_posts->the_post(); ?>
					<?php $slide_count++; ?>
					<?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), array(1500, 7000), false, ''); ?>
					<?php $slide_position = get_post_meta(get_the_ID(), 'slide_position', true); ?>
					<li id="slide_<?php echo $slide_count; ?>" class="slide slide-<?php echo $slide_position; ?>" style="background:url(<?php echo $image_url[0]; ?>) no-repeat center; background-size:cover;">
						<div class="container">
							<a class="slide-textbox" href="<?php the_permalink(); ?>">
								<h2 class="slide-title"><?php the_title(); ?></h2>
								<div class="slide-content"><?php the_excerpt(); ?></div>
							</a>
						</div>
					</li>
					<?php endwhile; ?>
				</ul>
				<?php if($slider_posts->post_count > 1): ?>
				<div class='slider-prev'></div>
				<div class='slider-next'></div>
				<?php endif; ?>
			</div>  
			<?php endif; ?>
			
				
			<?php }else{ ?>
			
			<?php $header_image = get_header_image(); if($header_image != ''): ?>
			<img src="<?php echo $header_image; ?>" class="header-image" />
			<?php endif; ?>
			
			<?php } ?>
			
				
			<?php if(is_home() || is_front_page()){ ?>
			
			<?php $feature_args = array(
			'post_type' => array('post', 'page'),
			'meta_key' => 'page_featured',
			'meta_value' => 'features',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'ignore_sticky_posts' => 1,
			'order' => 'ASC'); ?>
			<?php $feature_posts = new WP_Query($feature_args); ?>
			<?php if($feature_posts->post_count > 0): $feature_count = 0; ?>
			<div id="minifeatures" class="minifeatures">
				<div class="container">					
					<?php while($feature_posts->have_posts()): $feature_posts->the_post(); ?>
					<?php if($feature_count % 3 == 0 && $feature_count != 0) echo '<div class="col-divide"></div>'; $feature_count++; ?>
					<div class="column col3 <?php if($feature_count % 3 == 0) echo ' feature_right col-last'; ?>">
						<div class="block feature">
							<?php $icon = get_post_meta(get_the_ID(), 'page_icon', true); ?>
							<?php if($icon != '0' && $icon != ''): ?>
							<div class="feature-icon primary-color-bg"><?php echo $icon; ?></div>
							<?php endif; ?>
							<h2 class="block-title feature-title">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>
							<div class="feature-content"><?php the_excerpt(); ?><?php cpotheme_edit(); ?></div>
							
						</div>
					</div>
					<?php endwhile; ?>
					<div class='clear'></div>
				</div>
			</div>
			<?php endif; ?>
			
			<?php } ?>