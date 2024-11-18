<?php

//Displays the blog title and descripion in home or frontpage
add_filter('wp_title', 'cpotheme_title');
function cpotheme_title($title){
	global $post, $page, $paged;
	
	$separator = ' | ';	
	$description = get_bloginfo('description', 'display');
	$name = get_bloginfo('name');
	$full_title = $title.get_bloginfo('name');
	
	//Homepage title
	if($description && (is_home() || is_front_page()))
		$full_title = $full_title.$separator.$description;
	
	//Page numbers
	if($paged >= 2 || $page >= 2) 
		$full_title .= ' | '.sprintf( __('Page %s', 'cpotheme'), max($paged, $page));
	
	return $full_title;
}

//Display custom favicon
add_action('wp_head','cpotheme_favicon');
function cpotheme_favicon(){
	$favicon_url = cpotheme_get_option('cpo_general_favicon');
	if($favicon_url != '')
    	echo '<link type="image/x-icon" href="'.esc_url($favicon_url).'" rel="icon" />';
}

//Display custom favicon
add_action('wp_head','cpotheme_viewport');
function cpotheme_viewport(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>';
}

//Display shortcut edit links for logged in users
function cpotheme_edit(){
	if(cpotheme_get_option('cpo_general_editlinks'))
		edit_post_link('<span class="icon-cogs"></span> '.__('Edit', 'cpotheme'));
}

//Display the site's logo
function cpotheme_logo($width, $height){
	$output = '<div id="logo" class="logo">';
	if(cpotheme_get_option('cpo_general_texttitle') == 0){
		if(cpotheme_get_option('cpo_general_logo') == '')
			$output .= '<a class="site-logo" href="'.home_url().'"><img src="'.get_template_directory_uri().'/images/logo.png" alt="'.get_bloginfo('name').'" width="'.$width.'" height="'.$height.'"/></a>';
		else
			$output .= '<a class="site-logo" href="'.home_url().'"><img src="'.cpotheme_get_option('cpo_general_logo').'" alt="'.get_bloginfo('name').'"/></a>';
	}
	
	$classes = '';
	if(cpotheme_get_option('cpo_general_texttitle') == 0) $classes = ' hidden';
	if(is_singular() && !is_front_page()){
		$output .= '<span class="title site-title'.$classes.'"><a href="'.home_url().'">'.get_bloginfo('name').'</a></span>';
	}else{
		$output .= '<h1 class="title site-title '.$classes.'"><a href="'.home_url().'">'.get_bloginfo('name').'</a></h1>';
	}
	
	$output .= '</div>';
	echo $output;
}


//Display language switcher
function cpotheme_languages(){
	if(cpotheme_get_option('cpo_layout_languages') == 1 && function_exists('icl_get_languages')):
	$output = '<div id="languages" class="languages">';
	$langs = icl_get_languages('skip_missing=0&orderby=code');
	foreach($langs as $current_lang):
		$output .= '<a class="language-item" href="'.$current_lang['url'].'" id="language-switch-'.$current_lang['language_code'].'">';
		$output .= $current_lang['translated_name'];
		$output .= '</a>';
	endforeach;
	$output .= '</div>';
	echo $output;
	endif;
}

//Display social links
function cpotheme_social(){
	$output = '<div id="social" class="social">';
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_facebook'), 'Facebook', 'facebook');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_twitter'), 'Twitter', 'twitter');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_gplus'), 'Google Plus', 'google-plus');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_youtube'), 'YouTube', 'youtube');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_linkedin'), 'LinkedIn', 'linkedin');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_pinterest'), 'Pinterest', 'pinterest');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_foursquare'), 'Foursquare', 'foursquare');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_tumblr'), 'Tumblr', 'tumblr');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_flickr'), 'Flickr', 'flickr');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_instagram'), 'Instagram', 'instagram');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_dribbble'), 'Dribbble', 'dribbble');
	$output .= cpotheme_social_item(cpotheme_get_option('cpo_social_skype'), 'Skype', 'skype');
	$output .= '</div>';
	echo $output;
}

function cpotheme_social_item($url, $title = '', $icon = ''){
	if($url != ''):
		$output = '<a class="social-profile" href="'.$url.'" title="'.$title.'">';
		if($icon != '') $output .= '<span class="social_icon social-icon icon-'.$icon.'"></span>';
		if($title != '') $output .= '<span class="social_title social-title">'.$title.'</span>';
		$output .= '</a>';
		return $output;
	endif;
}

//Display custom font stylesheets from Google Fonts
function cpotheme_fonts($font_name){
	echo '<link href="http://fonts.googleapis.com/css?family='.$font_name.'" rel="stylesheet" type="text/css">';
}

//Adds custom javascript code in the footer
add_action('wp_footer', 'cpotheme_layout_js');
function cpotheme_layout_js(){
	$output = cpotheme_get_option('cpo_general_js');
	if($output != ''){
		$output = '<script type="text/javascript">'.stripslashes($output).'</script>';
		echo $output;
	}
}

//Adds custom css code in the footer
add_action('wp_head','cpotheme_layout_css', 25);
function cpotheme_layout_css(){
	$output = cpotheme_get_option('cpo_general_css');
	if($output != ''){
		$output = '<style type="text/css">'.stripslashes($output).'</style>';
		echo $output;
	}
}

//Abstracted function for retrieving specific options inside option arrays
function cpotheme_get_option($option_name = '', $option_array = 'cpotheme_settings'){
	$option_list_name = $option_array.cpotheme_wpml_current_language();
	$option_list = get_option($option_list_name, false);
	if($option_list && isset($option_list[$option_name]))
		$option_value = $option_list[$option_name];
	else
		$option_value = false;
	return $option_value;
}

//Abstracted function for updating specific options inside arrays
function cpotheme_update_option($option_name, $option_value, $option_array = 'cpotheme_settings'){
	$option_list_name = $option_array.cpotheme_wpml_current_language();
	$option_list = get_option($option_list_name, false);
	if(!$option_list)
		$option_list = array();
	$option_list[$option_name] = $option_value;
	if(update_option($option_list_name, $option_list))
		return true;
	else
		return false;
}

//Returns the current language's code in the event that WPML is active
function cpotheme_wpml_current_language(){
	$language_code = '';
	if(cpotheme_custom_wpml_active()){		
		$default_language = cpotheme_custom_wpml_default_language();
		$active_language = ICL_LANGUAGE_CODE;
		if($active_language != $default_language)
			$language_code = '_'.$active_language;
	}
	return $language_code;
}

function cpotheme_sidebar_position(){
	if(cpotheme_get_option('cpo_sidebar_position') == 'left') echo 'content-right';
}

// Generates breadcrumb navigation 
function cpotheme_breadcrumb(){
	if(cpotheme_get_option('cpo_layout_breadcrumbs') && !is_home() && !is_front_page()):
		if(function_exists('yoast_breadcrumb')):
			$result = yoast_breadcrumb('','', false);
		else:
			global $post;
			if(is_object($post)) $pid = $post->ID; else $pid = '';
			$result = '';
			
			if($pid != ''):
				if(is_singular()):
					$post_data = get_post($pid);
					$result = "<span>".apply_filters('the_title', $post_data->post_title)."</span>\n";
					
					if(is_singular('cpo_portfolio') && cpotheme_get_option('cpo_postpage_portfolio') != 0):
					$selected_page = get_post(cpotheme_get_option('cpo_postpage_portfolio'));
					$result = '<a href="'.get_permalink(cpotheme_get_option('cpo_postpage_portfolio')).'">'.$selected_page->post_title.'</a>'.$result;
					endif;

					if(is_singular('post') && cpotheme_get_option('cpo_postpage_blog') != 0):
					$selected_page = get_post(cpotheme_get_option('cpo_postpage_blog'));
					$result = '<a href="'.get_permalink(cpotheme_get_option('cpo_postpage_blog')).'">'.$selected_page->post_title.'</a>'.$result;
					endif;
					
					while($post_data->post_parent):
						$post_data = get_post($post_data->post_parent);
						$result = "<a href='".get_permalink($post_data->ID)."'>".apply_filters('the_title', $post_data->post_title)."</a>\n".$result;
					endwhile;
			
				elseif(is_category()):			
					if(is_singular('post') && cpotheme_get_option('cpo_postpage_blog') != 0):
					$selected_page = get_post(cpotheme_get_option('cpo_postpage_blog'));
					$result = '<a href="'.get_permalink(cpotheme_get_option('cpo_postpage_blog')).'">'.$selected_page->post_title.'</a>'.$result;
					endif;
					
					$post_data = get_the_category($pid);
					if(isset($post_data[0])):
						$data = get_category_parents($post_data[0]->cat_ID, TRUE, ' &raquo; ');
						if(!is_object($data)):
							$result = ''.substr($data, 0, -8).$result;
						endif;
					endif;
				endif;
			endif;
			
			$result = '<a href="'.home_url().'">'.__('Home', 'cpotheme').'</a>'.$result;
			
		endif;
		$output = '<div id="breadcrumb" class="breadcrumb">'.$result.'</div>';
		echo $output;
	endif;
}

//Displays the post date
function cpotheme_postpage_date($format = ''){
    if(cpotheme_get_option('cpo_postpage_dates') != 0){
		if($format = '') $format = get_option('date_format');
		echo '<div class="post-date">'.get_the_date($format).'</div>';
	}
}

//Displays the author link
function cpotheme_postpage_author(){
    if(cpotheme_get_option('cpo_postpage_authors') != 0){
		$author_alt = sprintf(esc_attr__('View all posts by %s', 'cpotheme'), get_the_author());
		$author = sprintf('<a href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), $author_alt, get_the_author());
		echo '<div class="post-author">'.$author.'</div>';
	}
}

//Displays the category list for the current post
function cpotheme_postpage_categories(){
    if(cpotheme_get_option('cpo_postpage_categories') != 0){
		echo '<div class="post-category">'.get_the_category_list(', ').'</div>';
	}
}

//Displays the number of comments for the post
function cpotheme_postpage_comments(){
	if(cpotheme_get_option('cpo_postpage_comments') != 0){
		$comments_num = get_comments_number();
		if($comments_num == 0)
			$comments = __('No Comments', 'cpotheme');
		elseif($comments_num == 1)
			$comments = __('One Comment', 'cpotheme');
		else
			$comments = sprintf(__('%1$s Comments', 'cpotheme'), number_format_i18n($comments_num));
		
		echo '<div class="post-comments">'.sprintf('<a href="%1$s">%2$s</a>', get_permalink(get_the_ID()).'#comments', $comments).'</div>';
	}
}

//Displays the post tags
function cpotheme_postpage_tags(){
	if(cpotheme_get_option('cpo_postpage_tags') != 0){
		$tag_list = get_the_tag_list('<ul class="post-tags"><li>','</li> <li>','</li></ul>');
		if($tag_list):
			echo $tag_list;		
		endif;
	}
}

//Displays a slideshow of the given query
function cpotheme_post_slideshow($args, $options = null){
	$attachments = get_posts($args);
	$thumb_count = 0;
	if($attachments){ ?>
	<div class="slideshow">
		<div class="slideshow-slides">
			<?php foreach($attachments as $attachment): $thumb_count++; ?>
			<div class="slideshow-slide" <?php if($thumb_count != 1) echo 'style="display:none;"'; ?>>
				<?php the_attachment_link($attachment->ID, true); ?>
				<?php if($attachment->post_excerpt != ''): ?>
				<div class="slide-content content"><?php echo $attachment->post_excerpt; ?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="slideshow-pages"></div>
	</div>
	<?php }
}

//Displays a gallery of the given query
function cpotheme_post_gallery($args, $columns){
	$attachments = get_posts($args);
	$feature_count = 0;
	if($attachments){ ?>
	<div class="gallery">
		<?php foreach($attachments as $attachment): ?>
		<?php if($feature_count % $columns == 0 && $feature_count != 0) echo '<div class="col-divide"></div>'; ?>
		<?php $feature_count++; ?>
		<div class="column col<?php echo $columns; if($feature_count % $columns == 0 && $feature_count != 0) echo ' col-last'; ?>">
			<?php if($attachment->post_excerpt != ''): ?>
			<div class="content"><?php echo $attachment->post_excerpt; ?></div>
			<?php endif; ?>
			<?php $source = wp_get_attachment_image_src($attachment->ID, 'portfolio_large'); ?>
			<?php $original_source = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
			<a href="<?php echo $original_source[0]; ?>" rel="gallery[portfolio]">
				<img src="<?php echo $source[0]; ?>"/>
			</a>
		</div>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
	<?php }
}


//Displays a video of the given query
function cpotheme_post_video($video_url){
	if($video_url != ''){ ?>
	<div class="video">
		<iframe src="<?php echo $video_url; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	</div>
	<?php }
}

//Retrieve page number for the current post or page
function cpotheme_current_page(){
	$current_page = 1;
	if(is_front_page()){
		if(get_query_var('page')) $current_page = get_query_var('page'); else $current_page = 1;
	}else{
		if(get_query_var('paged')) $current_page = get_query_var('paged'); else $current_page = 1;
	}
	return $current_page;
}

//Prints the main navigation menu
function cpotheme_menu(){
	if(has_nav_menu('main_menu'))
		wp_nav_menu(array('menu_id' => 'menu-main', 'menu_class' => 'menu-main nav_main', 'theme_location' => 'main_menu', 'depth' => '4', 'container' => false, 'walker' => new Cpotheme_Menu_Walker()));
	else
		wp_nav_menu(array('menu_id' => 'menu-main', 'menu_class' => 'menu-main nav_main', 'theme_location' => 'main_menu', 'depth' => '4'));
	echo '<div class="clear"></div>';
}

//Displays a dropdown list of the main menu items
function cpotheme_mobile_menu(){
	if(has_nav_menu('main_menu')){
		//Get all custom menus, then retrieve the one set to the main menu
		$menu_locations = get_nav_menu_locations();
		$menu_object = wp_get_nav_menu_object($menu_locations['main_menu']);		
		$current_parent = array();
		$current_level = 0;
		$last_id = 'root';
		if($menu_object){
			$menu_items = wp_get_nav_menu_items($menu_object->term_id);
			$output = '';
			$output .= '<select id="menu-mobile" class="menu-mobile">';
			$output .= '<option value="#">'.__('Go To...', 'cpotheme').'</option>';
			foreach($menu_items as $current_item){
				$item_title = '';
				//Go down a level
				if($current_item->menu_item_parent == $last_id){
					$current_level++;
					array_push($current_parent, $last_id);
				//Go back up a level, check if going up more than once
				}elseif($current_level > 0 && $current_item->menu_item_parent != end($current_parent)){
					while($current_item->menu_item_parent != end($current_parent)){
						$current_level--;
						array_pop($current_parent);
					}
				}
				
				$item_title .= str_repeat('-', $current_level).' ';
				$item_title .= $current_item->title;
				$item_url = $current_item->url;
				$last_id = $current_item->ID;
				$output .= '<option value="'.$item_url.'">'.$item_title.'</option>';
			}
			$output .= '</select>';
			echo $output;
		}
	}else{
		//Default page list
		$args = array('sort_column' => 'menu_order');
		$menu_items = get_pages($args);
		$current_parent = array();
		$current_level = 0;
		$last_id = -1;
		
		$output = '';
		$output .= '<select id="menu-mobile" class="menu-mobile">';
		$output .= '<option value="#">'.__('Go To...', 'cpotheme').'</option>';
		foreach($menu_items as $current_item){
			$item_title = '';
			//Go down a level
			if($current_item->post_parent == $last_id){
				$current_level++;
				array_push($current_parent, $last_id);
			//Go back up a level, check if going up more than once
			}elseif($current_level > 0 && $current_item->post_parent != end($current_parent)){
				while($current_item->post_parent != end($current_parent)){
					$current_level--;
					array_pop($current_parent);
				}
			}
			
			$item_title .= str_repeat('-', $current_level).' ';
			$item_title .= $current_item->post_title;
			$item_url = get_permalink($current_item->ID);
			$last_id = $current_item->ID;
			$output .= '<option value="'.$item_url.'">'.$item_title.'</option>';
		}
		$output .= '<select>';
		echo $output;
	}
}


//Removes WordPress automatic paragraphs
function cpotheme_submenu(){ 
	$page_navigation = get_post_meta(get_the_ID(), 'page_navigation', true);
	global $post;
	$output = '';
	
	//Submenu of children
	if($page_navigation == 'children'):
	$output = '<ul class="menu-sub">';
	$output .= '<li class="page_item page-item-'.get_the_ID().' current_page_item">';
	$output .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
	$output .= '</li>';
	$output .= wp_list_pages("echo=0&title_li=&child_of=".$post->ID);
	$output .= '</ul>';
	endif;
	
	//Submenu of siblings
	if($page_navigation == 'siblings'):
	$parent_post = get_post($post->post_parent);
	$output = '<ul class="menu-sub">';
	$output .= '<li class="page_item page-item-'.$post->post_parent.' current_page_item">';
	$output .= '<a href="'.get_permalink($parent_post->ID).'">'.$parent_post->post_title.'</a>';
	$output .= '</li>';
	$output .= wp_list_pages("echo=0&title_li=&child_of=".$post->post_parent);
	$output .= '</ul>';
	endif;
	
	echo $output;
}


//Removes WordPress automatic paragraphs
function cpotheme_remove_autop($content){ 
	$content = do_shortcode(shortcode_unautop($content)); 
	$content = preg_replace('#^<\/p>|^<br\s?\/?>|<p>$|<p>\s*(&nbsp;)?\s*<\/p>#', '', $content);
	return $content;
}


//Changes the brighness of a HEX color
function cpotheme_alter_brightness($colourstr, $steps) {
	$colourstr = str_replace('#','',$colourstr);
	$rhex = substr($colourstr,0,2);
	$ghex = substr($colourstr,2,2);
	$bhex = substr($colourstr,4,2);

	$r = hexdec($rhex);
	$g = hexdec($ghex);
	$b = hexdec($bhex);

	$r = max(0,min(255,$r + $steps));
	$g = max(0,min(255,$g + $steps));  
	$b = max(0,min(255,$b + $steps));
  
	$r = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
	$g = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);  
	$b = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
	return '#'.$r.$g.$b;
}