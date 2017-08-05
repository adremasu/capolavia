<?php 

function cpotheme_metadata_pagelist_optional(){
	$cpotheme_data = array();	
	$page_list = get_pages('sort_column=post_parent,menu_order');    
	$cpotheme_data[0] = __('(Select a Page...)', 'cpotheme');
	foreach ($page_list as $current_page)
		$cpotheme_data[$current_page->ID] = $current_page->post_title;
	return $cpotheme_data;
}

function cpotheme_metadata_featureditem(){
	$cpotheme_data = array(
	'none' => __('None', 'cpotheme'),
	'slider' => __('In The Homepage Slider', 'cpotheme'),
	'features' => __('In The Homepage Boxes', 'cpotheme'));
	return $cpotheme_data;
}

function cpotheme_metadata_layoutstyle(){
	$cpotheme_data = array(
	'fixed' => __('Fixed', 'cpotheme'),
	'boxed' => __('Boxed', 'cpotheme'));
	return $cpotheme_data;
}

function cpotheme_metadata_alignment(){
	$cpotheme_data = array(
	'left' => 'To The Left',
	'right' => 'To The Right');
	return $cpotheme_data;
}

function cpotheme_metadata_slideposition(){
	$cpotheme_data = array(
	'left' => 'To The Left',
	'right' => 'To The Right');
	return $cpotheme_data;
}