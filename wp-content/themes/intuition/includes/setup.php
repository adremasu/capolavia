<?php 
add_action('after_setup_theme', 'cpotheme_init');
if(!function_exists('cpotheme_init')):
function cpotheme_init(){
	add_image_size('portfolio', 600, 350, true);
	add_theme_support('post-formats', array('gallery', 'quote', 'link'));
	add_theme_support('custom-background', array('default-color' => 'bbbbbb'));
	add_theme_support('custom-header');
} endif;

// Registers all widget areas
add_action('widgets_init', 'cpotheme_sidebar_init');
function cpotheme_sidebar_init(){
	
    register_sidebar(array('name' => __('Default Sidebar', 'cpotheme'),
    'id' => 'primary-widget-area',
    'description' => __('Default sidebar shown in all standard pages.', 'cpotheme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Blog', 'cpotheme'),
    'id' => 'blog-widget-area',
    'description' => __('Sidebar shown in the blog page template.', 'cpotheme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 1', 'cpotheme'),
    'id' => 'first-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 2', 'cpotheme'),
    'id' => 'second-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'));

    register_sidebar(array('name' => __('Footer Sidebar 3', 'cpotheme'),
    'id' => 'third-footer-widget-area',
    'description' => __('Shown in the footer area.', 'cpotheme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'));
}

//Registers all menu areas
add_action('after_setup_theme', 'cpotheme_menu_init');
function cpotheme_menu_init(){
    register_nav_menus(array('top_menu' => __('Top Menu', 'cpotheme')));
    register_nav_menus(array('main_menu' => __('Main Menu', 'cpotheme')));
    register_nav_menus(array('footer_menu' => __('Footer Menu', 'cpotheme')));
}