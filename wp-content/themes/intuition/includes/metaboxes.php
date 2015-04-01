<?php
//Inserts custom metabox as per the theme's features
add_action('add_meta_boxes', 'cpotheme_metabox');
function cpotheme_metabox(){
	add_meta_box('cpotheme_pages', __('Post Options', 'cpotheme'), 'cpotheme_metabox_posts', 'post', 'normal', 'high');
	add_meta_box('cpotheme_pages', __('Page Options', 'cpotheme'), 'cpotheme_metabox_pages', 'page', 'normal', 'high');
}

function cpotheme_metabox_posts($post){
	cpotheme_meta_fields($post, cpotheme_metadata_pages());
}
add_action('edit_post', 'cpotheme_metabox_posts_save');
function cpotheme_metabox_posts_save($post){
	cpotheme_meta_save(cpotheme_metadata_pages());
}


function cpotheme_metabox_pages($post){
	cpotheme_meta_fields($post, cpotheme_metadata_pages());
}
add_action('edit_post', 'cpotheme_metabox_pages_save');
function cpotheme_metabox_pages_save($post){
	cpotheme_meta_save(cpotheme_metadata_pages());
}