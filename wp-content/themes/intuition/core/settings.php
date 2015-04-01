<?php

//Display Settings page
function cpotheme_settings(){
	cpotheme_custom_form('cpotheme_settings', cpotheme_metadata_settings());
}

//Save Settings page
add_action('admin_init', 'cpotheme_settings_save');
function cpotheme_settings_save(){
	cpotheme_custom_save('cpotheme_settings', cpotheme_metadata_settings());
}

//Install settings upon theme switch
add_action('after_switch_theme', 'cpotheme_settings_defaults', 10, 2);
function cpotheme_settings_defaults(){
	cpotheme_custom_install('cpotheme_settings', cpotheme_metadata_settings(), false);
}

//Add Theme Settings link in WordPress toolbar
add_action('wp_before_admin_bar_render', 'cpotheme_settings_toolbarlink');
function cpotheme_settings_toolbarlink(){
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array('id' => 'cpotheme_toolbar_settings',
	'title' => __('Theme Options', 'cpotheme'),
	'href' => get_admin_url().'themes.php?page=cpotheme_settings',
	'parent' => 'site-name'));
}