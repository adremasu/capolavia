<?php

//Change the default walker for all menus, including widgets
add_filter('wp_nav_menu_args', 'cpotheme_menu_walker');
function cpotheme_menu_walker($args){
	return array_merge($args, array('walker' => new Cpotheme_Menu_Walker()));
}