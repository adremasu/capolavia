<?php

// Adds home link to navigation menus
add_filter('wp_page_menu_args', 'cpotheme_nav_menu_args');
function cpotheme_nav_menu_args($args){
	$args['show_home'] = true;
	return $args;
}

//Turn off inline styles for gallery shortcode
add_filter('use_default_gallery_style', '__return_false');

//Turn off styles in Recent Comments widget
add_action('widgets_init', 'cpotheme_remove_recent_comments_style');
function cpotheme_remove_recent_comments_style(){
	add_filter('show_recent_comments_widget_style', '__return_false');
}

add_filter('wp_get_attachment_link', 'cpotheme_gallery_lightbox', 10, 4);
function cpotheme_gallery_lightbox($link, $id, $size, $permalink){
	global $post;
	if(!$permalink)
		$link = str_replace('<a href', '<a rel="gallery[default]" href', $link);
	return $link;
}