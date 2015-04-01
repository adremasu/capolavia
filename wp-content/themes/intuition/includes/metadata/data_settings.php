<?php // Options declaration
// Declares all config data to be used in the theme settings page.
function cpotheme_metadata_settings(){
	$cpotheme_config = array();
	$prefix = 'cpo_';

	//GENERAL CONFIG
	$cpotheme_config[] = array(
	'id' => $prefix.'general_config',
	'name' => __('General Options', 'cpotheme'),
	'desc' => __('Global configuration options applied to the entire site.', 'cpotheme'),
	'type' => 'separator');
	
	//Branding
	$cpotheme_config[] = array(
	'id' => $prefix.'branding',
	'name' => __('Site Branding', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_logo',
	'name' => __('Custom Logo', 'cpotheme'),
	'desc' => __('Insert the URL of an image to be used as a custom logo.', 'cpotheme'),
	'type' => 'upload');

	$cpotheme_config[] = array(
	'id' => $prefix.'general_texttitle',
	'name' => __('Enable Text Title?', 'cpotheme'),
	'desc' => __('Activate this to display the site title as text.', 'cpotheme'),
	'type' => 'yesno',
	'std' => '0');

	$cpotheme_config[] = array(
	'id' => $prefix.'general_favicon',
	'name' => __('Custom Favicon', 'cpotheme'),
	'desc' => __('Insert the URL of an image to be used as a custom favicon. Recommended sizes are 16x16 pixels.', 'cpotheme'),
	'type' => 'upload');
	
	//Management
	$cpotheme_config[] = array(
	'id' => $prefix.'management',
	'name' => __('Management', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_editlinks',
	'name' => __('Activate Edit Links?', 'cpotheme'),
	'desc' => __('Activate this option to display shortcut edit links through out the site for logged in users.', 'cpotheme'),
	'type' => 'yesno',
	'std' => '1');
	
	//Customization
	$cpotheme_config[] = array(
	'id' => $prefix.'customization',
	'name' => __('Customization', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_css',
	'name' => __('Custom CSS', 'cpotheme'),
	'desc' => __('Here you can insert custom CSS styling for the entire site. This code will override the default stylesheet, but it might not have higher priority than plugins.', 'cpotheme'),
	'type' => 'textarea');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_js',
	'name' => __('Custom Javascript', 'cpotheme'),
	'desc' => __('Here you can insert custom Javascript code for the entire site.', 'cpotheme'),
	'type' => 'textarea');
	
	
	//LAYOUT TAB
	$cpotheme_config[] = array(
	'id' => $prefix.'layout_config',
	'name' => __('Layout', 'cpotheme'),
	'desc' => __('Specify how you want the structure of the site to look.', 'cpotheme'),
	'type' => 'separator');
	
	//General Layout
	$cpotheme_config[] = array(
	'id' => $prefix.'general_layout',
	'name' => __('General Layout', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'layout_style',
	'name' => __('Layout Style', 'cpotheme'),
	'desc' => __('Determines whether the site has a fluid or boxed layout.', 'cpotheme'),
	'type' => 'select',
	'option' => cpotheme_metadata_layoutstyle(),
	'std' => 'fixed');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'sidebar_position',
	'name' => __('Sidebar Position', 'cpotheme'),
	'desc' => __('Determines the location of the sidebar by default.', 'cpotheme'),
	'type' => 'select',
	'option' => cpotheme_metadata_sidebar(),
	'std' => 'right');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'layout_breadcrumbs',
	'name' => __('Enable Breadcrumbs', 'cpotheme'),
	'desc' => __('Enables or disables breadcrumb navigation on all pages. If you are going to use a breadcrumb plugin, disabling this option is recommended.', 'cpotheme'),
	'type' => 'yesno',
	'std'  => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_credit',
	'name' => __('Enable Credit Link', 'cpotheme'),
	'desc' => __('Enables a small, non-obtrusive credit link in the footer. If you decide to activate it, thanks a lot for supporting CPOThemes!', 'cpotheme'),
	'type' => 'yesno',
	'std' => '0');
	
	//Homepage
	$cpotheme_config[] = array(
	'id' => $prefix.'homepage',
	'name' => __('Homepage', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'slider_always',
	'name' => __('Always Display Slider', 'cpotheme'),
	'desc' => __('If enabled, the homepage slider will be displayed in all pages.', 'cpotheme'),
	'type' => 'yesno',
	'std' => '0');
		
	
	//Styling
	$cpotheme_config[] = array(
	'id' => $prefix.'styling_config',
	'name' => __('Styling', 'cpotheme'),
	'desc' => __('Set up the look & feel of the site.', 'cpotheme'),
	'type' => 'separator');
	
	//Coloring
	$cpotheme_config[] = array(
	'id' => $prefix.'coloring',
	'name' => __('Coloring', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'primary_color',
	'name' => __('Primary Color', 'cpotheme'),
	'desc' => __('Determines the main color scheme of the site. This color will be used in buttons, headings, and other prominent elements.', 'cpotheme'),
	'type' => 'color',
	'std' => '#F59A0A');
	
	
	//POSTS & PAGES
	$cpotheme_config[] = array(
	'id' => $prefix.'post_config',
	'name' => __('Posts & Pages', 'cpotheme'),
	'desc' => __('Styles the appearance of posts and pages.', 'cpotheme'),
	'type' => 'separator');
	
	//Post Appearance
	$cpotheme_config[] = array(
	'id' => 'post_appearance',
	'name' => __('Post Appearance', 'cpocore'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_dates',
	'name' => __('Display Post Dates', 'cpocore'),
	'desc' => __('Toggles display of authors in posts.', 'cpocore'),
	'type' => 'yesno',
	'std' => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_authors',
	'name' => __('Display Post Authors', 'cpocore'),
	'desc' => __('Toggles display of authors in posts.', 'cpocore'),
	'type' => 'yesno',
	'std' => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_comments',
	'name' => __('Display Comment Count', 'cpocore'),
	'desc' => __('Toggles display of comment count in posts.', 'cpocore'),
	'type' => 'yesno',
	'std' => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_categories',
	'name' => __('Display Post Categories', 'cpocore'),
	'desc' => __('Determines whether post categories are displayed or not.', 'cpocore'),
	'type' => 'yesno',
	'std' => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_tags',
	'name' => __('Display Post Tags', 'cpocore'),
	'desc' => __('Determines whether post tags are displayed or not.', 'cpocore'),
	'type' => 'yesno',
	'std' => '1');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'postpage_preview',
	'name' => __('Display Full Previews', 'cpocore'),
	'desc' => __('Determines whether post lists display the full content or not.', 'cpocore'),
	'type' => 'yesno',
	'std' => '0');
	
	
	//SOCIAL & CONTACT
	$cpotheme_config[] = array(
	'id' => $prefix.'contact_config',
	'name' => __('Social & Contact', 'cpotheme'),
	'desc' => __('Setup for social media and contact information used in forms.', 'cpotheme'),
	'type' => 'separator');
	
	//Contact Information
	$cpotheme_config[] = array(
	'id' => 'contact_information',
	'name' => __('Contact Information', 'cpotheme'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'contact_email',
	'name' => __('Contact Form Email', 'cpotheme'),
	'desc' => __('Entries in the contact form template will be sent to this email address.', 'cpotheme'),
	'type' => 'text');
	
	//Social Profiles
	$cpotheme_config[] = array(
	'id' => 'social_profiles',
	'name' => __('Social Profiles', 'cpocore'),
	'type' => 'divider');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_facebook',
	'name' => __('Facebook Page', 'cpocore'),
	'desc' => __('Enter the URL of your Facebook page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_twitter',
	'name' => __('Twitter Page', 'cpocore'),
	'desc' => __('Enter the URL of your Twitter page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_gplus',
	'name' => __('Google Plus Page', 'cpocore'),
	'desc' => __('Enter the URL of your Google Plus page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_youtube',
	'name' => __('YouTube Page', 'cpocore'),
	'desc' => __('Enter the URL of your YouTube profile to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_linkedin',
	'name' => __('LinkedIn Page', 'cpocore'),
	'desc' => __('Enter the URL of your LinkedIn page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_pinterest',
	'name' => __('Pinterest Page', 'cpocore'),
	'desc' => __('Enter the URL of your Pinterest page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_foursquare',
	'name' => __('Foursquare Page', 'cpocore'),
	'desc' => __('Enter the URL of your Foursquare page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_tumblr',
	'name' => __('Tumblr Page', 'cpocore'),
	'desc' => __('Enter the URL of your Tumblr page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_flickr',
	'name' => __('Flickr Page', 'cpocore'),
	'desc' => __('Enter the URL of your Flickr page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_instagram',
	'name' => __('Instagram Page', 'cpocore'),
	'desc' => __('Enter the URL of your Instagram page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_dribbble',
	'name' => __('Dribbble Page', 'cpocore'),
	'desc' => __('Enter the URL of your Dribbble page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'social_skype',
	'name' => __('Skype Page', 'cpocore'),
	'desc' => __('Enter the URL of your Skype page to display a link in the site.', 'cpocore'),
	'type' => 'text');
	
	return $cpotheme_config;
}