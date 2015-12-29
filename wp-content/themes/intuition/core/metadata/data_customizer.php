<?php 

//Define customizer sections
if(!function_exists('cpotheme_metadata_panels')){
	function cpotheme_metadata_panels(){
		$data = array();
		
		$data['cpotheme_layout'] = array(
		'title' => __('Layout', 'cpotheme'),
		'description' => __('Here you can find settings that control the structure and positioning of specific elements within your website.', 'cpotheme'),
		'priority' => 25);
		
		return apply_filters('cpotheme_customizer_panels', $data);
	}
}


//Define customizer sections
if(!function_exists('cpotheme_metadata_sections')){
	function cpotheme_metadata_sections(){
		$data = array();
		
		$data['cpotheme_management'] = array(
		'title' => __('General Theme Options', 'cpotheme'),
		'description' => __('Options that help you manage your theme better.', 'cpotheme'),
		'capability' => 'edit_theme_options',
		'priority' => 15);
		
		$data['cpotheme_layout_general'] = array(
		'title' => __('Site Wide Structure', 'cpotheme'),
		'description' => sprintf(__('Upgrade to %s to control the layout of your sidebars and other global elements.', 'cpotheme'), cpotheme_upgrade_link()),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 25);
		
		$data['cpotheme_layout_home'] = array(
		'title' => __('Homepage', 'cpotheme'),
		'description' => sprintf(__('Upgrade to %s to control the ordering of elements in the homepage as well as its behavior.', 'cpotheme'), cpotheme_upgrade_link()),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true){
			$data['cpotheme_layout_slider'] = array(
			'title' => __('Slider', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to customize the behavior of the slider.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['cpotheme_layout_features'] = array(
			'title' => __('Features', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to customize the columns and appearance of the feature blocks.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){
			$data['cpotheme_layout_portfolio'] = array(
			'title' => __('Portfolio', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to control the number of portfolio columns, related portfolio items, and overall appearance.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['cpotheme_layout_services'] = array(
			'title' => __('Services', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to control the number of columns for services.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['cpotheme_layout_team'] = array(
			'title' => __('Team Members', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to control the number of columns of the team section.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true){
			$data['cpotheme_layout_testimonials'] = array(
			'title' => __('Testimonials', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to customize the appearance of testimonials.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_CLIENTS') && CPOTHEME_USE_CLIENTS == true){
			$data['cpotheme_layout_clients'] = array(
			'title' => __('Clients', 'cpotheme'),
			'description' => sprintf(__('Upgrade to %s to customize the appearance of clients.', 'cpotheme'), cpotheme_upgrade_link()),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'cpotheme'),
		'description' => __('Custom typefaces for the entire site.', 'cpotheme'),
		'capability' => 'edit_theme_options',
		'priority' => 45);

		$data['cpotheme_layout_posts'] = array(
		'title' => __('Blog Posts', 'cpotheme'),
		'description' => sprintf(__('Upgrade to %s to control the appearance of specific elements in your blog posts such as dates, authors, or comments.', 'cpotheme'), cpotheme_upgrade_link()),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'cpotheme'),
		'description' => sprintf(__('Upgrade to %s to control the gain full control over the typography of your site.', 'cpotheme'), cpotheme_upgrade_link()),
		'capability' => 'edit_theme_options',
		'priority' => 45);
		
		return apply_filters('cpotheme_customizer_sections', $data);
	}
}


if(!function_exists('cpotheme_metadata_customizer')){
	function cpotheme_metadata_customizer($std = null){
		$data = array();
		
		$data['general_logo'] = array(
		'label' => __('Custom Logo', 'cpotheme'),
		'description' => __('Insert the URL of an image to be used as a custom logo.', 'cpotheme'),
		'section' => 'title_tagline',
		'sanitize' => 'esc_url',
		'type' => 'image');

		$data['general_favicon'] = array(
		'label' => __('Custom Favicon', 'cpotheme'),
		'description' => __('Recommended sizes are 16x16 pixels.', 'cpotheme'),
		'section' => 'title_tagline',
		'sanitize' => 'esc_url',
		'type' => 'image');
		
		$data['general_logo_width'] = array(
		'label' => __('Logo Width (px)', 'cpotheme'),
		'description' => __('Forces the logo to have a specified width.', 'cpotheme'),
		'section' => 'title_tagline',
		'type' => 'text',
		'placeholder' => '(none)',
		'sanitize' => 'absint',
		'width' => '100px');
		
		$data['general_texttitle'] = array(
		'label' => __('Enable Text Title?', 'cpotheme'),
		'description' => __('Activate this to display the site title as text.', 'cpotheme'),
		'section' => 'title_tagline',
		'type' => 'checkbox',
		'std' => false);
		
		$data['general_editlinks'] = array(
		'label' => __('Show Edit Links', 'cpotheme'),
		'description' => __('Display edit links on the site layout for logged in users.', 'cpotheme'),
		'section' => 'cpotheme_management',
		'type' => 'checkbox',
		'std' => '1');
		
		//Layout		
		$data['general_credit'] = array(
		'label' => __('Show Credit Link', 'cpotheme'),
		'section' => 'cpotheme_layout_general',
		'type' => 'checkbox',
		'default' => '1');
		
		$data['home_tagline'] = array(
		'label' => __('Tagline Title', 'cpotheme'),
		'section' => 'cpotheme_layout_home',
		'empty' => true,
		'multilingual' => true,
		'default' => __('Add your custom tagline here.', 'cpotheme'),
		'sanitize' => 'esc_html',
		'type' => 'textarea');
		
		//Homepage Slider
		if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true){
			$data['slider_settings'] = array(
			'label' => __('Slider Options', 'cpotheme'),
			'description' => __('Customize the speed, timeout and effects of the homepage slider.', 'cpotheme'),
			'section' => 'cpotheme_layout_slider',
			'type' => 'label');
		}
		
		//Homepage Features
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['home_features'] = array(
			'label' => __('Features Description', 'cpotheme'),
			'section' => 'cpotheme_layout_features',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Our core features', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		//Portfolio layout
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){			
			$data['home_portfolio'] = array(
			'label' => __('Portfolio Description', 'cpotheme'),
			'section' => 'cpotheme_layout_portfolio',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Take a look at our work', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['home_services'] = array(
			'label' => __('Services Description', 'cpotheme'),
			'section' => 'cpotheme_layout_services',
			'empty' => true,
			'multilingual' => true,
			'default' => __('What we can offer you', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['home_team'] = array(
			'label' => __('Team Members Description', 'cpotheme'),
			'section' => 'cpotheme_layout_team',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Meet our team', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		//Testimonials
		if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true){
			$data['home_testimonials'] = array(
			'label' => __('Testimonials Description', 'cpotheme'),
			'section' => 'cpotheme_layout_testimonials',
			'empty' => true,
			'multilingual' => true,
			'default' => __('What they say about us', 'cpotheme'),
			'sanitize' => 'esc_html',
			'type' => 'textarea');
		}
		
		//Blog Posts
		$data['home_posts'] = array(
		'label' => __('Enable Posts On Homepage', 'cpotheme'),
		'section' => 'cpotheme_layout_posts',
		'type' => 'checkbox',
		'default' => false);
		
		//Typography
		$data['type_settings'] = array(
		'label' => __('Typography Options', 'cpotheme'),
		'description' => __('Select custom fonts for the headings, navigation, and body text of your site.', 'cpotheme'),
		'section' => 'cpotheme_typography',
		'type' => 'label');
		
		//Colors		
		$data['color_settings'] = array(
		'label' => __('Color Options', 'cpotheme'),
		'description' => __('Customize the colors of primary and secondary elements, as well as headings, navigation, and text.', 'cpotheme'),
		'section' => 'colors',
		'type' => 'label');
		
		return apply_filters('cpotheme_customizer_controls', $data);
	}
}