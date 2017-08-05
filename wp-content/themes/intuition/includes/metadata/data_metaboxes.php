<?php

//Create page meta fields
function cpotheme_metadata_pages(){

	$cpotheme_data = array();
	
	$cpotheme_data[] = array(
	'name' => 'page_title',
	'std'  => '',
	'label' => __('Display Title', 'cpotheme'),
	'desc' => __('Specifies whether the title header and breadcrumb navigation is displayed. Useful for creating landing pages.', 'cpotheme'),
	'type' => 'yesno');
	
	$cpotheme_data[] = array(
	'name' => 'page_featured',
	'std'  => '',
	'label' => __('Featured Item', 'cpotheme'),
	'desc' => __('Specifies whether this item is featured in the homepage.', 'cpotheme'),
	'type' => 'select',
	'option' => cpotheme_metadata_featureditem());
	
	$cpotheme_data[] = array(
	'name' => 'page_icon',
	'std'  => '',
	'label' => __('Featured Icon', 'cpotheme'),
	'desc' => __('Sets an icon to be used as the featured image.', 'cpotheme'),
	'type' => 'select',
	'option' => cpotheme_metadata_icons(),
	'class' => 'fontawesome');
	
	return $cpotheme_data;
}