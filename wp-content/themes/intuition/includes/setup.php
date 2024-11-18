<?php 

//Top elements
add_action('cpotheme_top', 'cpotheme_top_menu');

//Header elements
add_action('cpotheme_header', 'cpotheme_logo');
add_action('cpotheme_header', 'cpotheme_menu_toggle');
add_action('cpotheme_header', 'cpotheme_menu');

//Before main elements
add_action('cpotheme_before_main', 'cpotheme_home_slider');
add_action('cpotheme_before_main', 'cpotheme_home_tagline');
add_action('cpotheme_before_main', 'cpotheme_home_features');
add_action('cpotheme_before_main', 'cpotheme_home_portfolio');

//Page title elements
add_action('cpotheme_title', 'cpotheme_page_title');
add_action('cpotheme_title', 'cpotheme_breadcrumb');

//After main elements

//Subfooter elements
add_action('cpotheme_subfooter', 'cpotheme_subfooter');

//Footer elements
add_action('cpotheme_footer', 'cpotheme_footer_menu');
add_action('cpotheme_footer', 'cpotheme_footer');


//Add homepage slider
function cpotheme_home_slider(){ 
	if(is_front_page()) get_template_part('homepage', 'slider'); 
}

//Add homepage features
function cpotheme_home_features(){ 
	if(is_front_page()) get_template_part('homepage', 'features'); 
}

//Add homepage tagline
function cpotheme_home_tagline(){ 
	if(is_front_page()) cpotheme_block('home_tagline', 'tagline', 'container'); 
}

//Add homepage portfolio
function cpotheme_home_portfolio(){ 
	if(is_front_page()) get_template_part('homepage', 'portfolio'); 
}


add_filter('cpotheme_font_headings', 'cpotheme_theme_fonts');
add_filter('cpotheme_font_menu', 'cpotheme_theme_fonts');
function cpotheme_theme_fonts($data){ 
	return 'Open+Sans:300';
}


add_filter('cpotheme_font_body', 'cpotheme_theme_fonts_body');
function cpotheme_theme_fonts_body($data){ 
	return 'Open+Sans';
}


//set settings defaults
add_filter('cpotheme_customizer_controls', 'cpotheme_customizer_controls');
function cpotheme_customizer_controls($data){ 
	//Layout
	$data['home_posts']['default'] = true;
	$data['home_features']['default'] = '';
	$data['layout_style'] = array(
	'label' => __('Layout Style', 'cpocore'),
	'section' => 'cpotheme_layout_general',
	'type' => 'select',
	'choices' => cpotheme_metadata_layoutstyle(),
	'default' => 'fixed');
	
	//Colors
	$data['primary_color'] = array(
	'label' => __('Primary Color', 'cpocore'),
	'description' => __('Used in buttons, headings, and other prominent elements.', 'cpocore'),
	'section' => 'colors',
	'type' => 'color',
	'sanitize' => 'sanitize_hex_color',
	'default' => '#F59A0A');
	
	return $data;
}


add_filter('body_class', 'cpotheme_theme_body_class');
function cpotheme_theme_body_class($body_classes){
	$body_classes[] = ' wrapper-'.esc_attr(cpotheme_get_option('layout_style'));
	return $body_classes;
}


add_filter('cpotheme_background_args', 'cpotheme_background_args');
function cpotheme_background_args($data){ 
	$data = array(
	'default-color' => 'eeeeee',
	'default-image' => get_template_directory_uri().'/images/background.jpg',
	'default-repeat' => 'repeat',
	'default-position-x' => 'center',
	);
	return $data;
}


add_action('wp_head', 'cpotheme_styling_custom', 19);
function cpotheme_styling_custom(){
	$primary_color = esc_attr(cpotheme_get_option('primary_color'));
	?>
	<style type="text/css">
		<?php if($primary_color != ''): ?>
		.primary-color { color:<?php echo $primary_color; ?>; }
		.primary-color-bg { background-color:<?php echo $primary_color; ?>; }
		.primary-color-border { border-color:<?php echo $primary_color; ?>; }
		.menu-item.menu-highlight > a { background-color:<?php echo $primary_color; ?>; }
		.tp-caption.primary_color_background { background:<?php echo $primary_color; ?>; }
		.tp-caption.primary_color_text{ color:<?php echo $primary_color; ?>; }
		.widget_nav_menu a .menu-icon { color:<?php echo $primary_color; ?>; }
		
		.button, .button:link, .button:visited, input[type=submit] { background-color:<?php echo $primary_color; ?>; }
		.button:hover, input[type=submit]:hover { background-color:<?php echo $primary_color; ?>; }
		::selection  { color:#fff; background-color:<?php echo $primary_color; ?>; }
		::-moz-selection { color:#fff; background-color:<?php echo $primary_color; ?>; }
		
		.menu-main .current_page_ancestor > a,
		.menu-main .current-menu-item > a { color:<?php echo $primary_color; ?>; }
		.menu-portfolio .current-cat a,
		.pagination .current { background-color:<?php echo $primary_color; ?>; }
		<?php endif; ?>
    </style>
	<?php
}