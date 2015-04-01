<?php
/**
 * AccessPress Ray Theme Options
 *
 * @package AccessPress Ray
 */

if ( is_admin() ) : // Load only if we are viewing an admin page

function accesspress_ray_admin_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'accesspress_ray_custom_js', get_template_directory_uri().'/inc/admin-panel/js/custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'of-media-uploader', get_template_directory_uri().'/inc/admin-panel/js/media-uploader.js', array( 'jquery' ) );
	
	wp_enqueue_style( 'accesspress_ray_admin_style',get_template_directory_uri().'/inc/admin-panel/css/admin.css', array( 'farbtastic', 'thickbox'), '1.0', 'screen' );

}
add_action('admin_print_styles-appearance_page_theme_options', 'accesspress_ray_admin_scripts');



$accesspress_ray_options = array(
	'responsive_design'=>'',
	'accesspress_ray_favicon'=> '',
	'featured_text'=>'Featured Text',
	'show_search'=>true,
	'logo_alignment'=>'Left',
	'call_to_action_post' => '',
	'call_to_action_post_readmore' => 'Read More',
	'call_to_action_post_char' => '650',
	'show_fontawesome' => false,
    'big_icons' => false,
    'featured_title' => 'Featured Title',
	'featured_post1' => '',
	'featured_post2' => '',
	'featured_post3' => '',
	'featured_post4' => '',
	'featured_post_readmore' => 'Read More',
	'featured_post1_icon' => '',
	'featured_post2_icon' => '',
	'featured_post3_icon' => '',
	'featured_post4_icon' => '',
	'show_blog_number' => '3',
	'show_blogdate' => true,
	'testimonial_cat' => '',
	'blog_cat' => '',
	'portfolio_cat' => '',
	'footer_copyright' => get_bloginfo('name'),

	'show_slider' => 'yes',
	'slider_show_pager' => 'yes1',
	'slider_show_controls' => 'yes2',
	'slider_mode' => 'slide',
	'slider_auto' => 'yes3',
	'slider_speed' => '500',
	'slider_caption'=>'yes4',
	'slider_pause' => '4000',

	'slider1'=>'',
	'slider2'=>'',
	'slider3'=>'',
	'slider4'=>'',

	'leftsidebar_show_latest_events'=>true,
	'leftsidebar_show_testimonials'=>true,
	'rightsidebar_show_latest_events'=>true,
	'rightsidebar_show_testimonials'=>true,
	
	'accesspress_ray_facebook' => '',
	'accesspress_ray_twitter' => '',
	'accesspress_ray_gplus' => '',
	'accesspress_ray_youtube' => '',
	'accesspress_ray_pinterest' => '',
	'accesspress_ray_linkedin' => '',
	'accesspress_ray_flickr' => '',
	'accesspress_ray_vimeo' => '',
	'accesspress_ray_stumbleupon' => '',
	'accesspress_ray_instagram' => '',
	'accesspress_ray_sound_cloud' => '',
	'accesspress_ray_skype' => '',
	'accesspress_ray_rss' => '',
	'show_social_header'=>'',
	'show_social_footer'=>'',

	'google_map' => '',
	'contact_address' => '',
	'accesspress_ray_home_page_layout' => 'Default',
    'accesspress_ray_webpage_layout' => 'Fullwidth',
    'gallery_code' => '',

    'slider_options' => 'single_post_slider',
    'slider_cat' => '',
    'view_all_text' =>'View All',
    'custom_css' => '',
    'custom_code' => '',
    'featured_bar' => true,
    'call_to_action_post_content' => true,

    'longitude' => '',
    'latitude' => '',

    'read_more_text' => 'Read More'
);


add_action( 'admin_init', 'accesspress_ray_register_settings' );
add_action( 'admin_menu', 'accesspress_ray_theme_options' );

function accesspress_ray_register_settings() {
	register_setting( 'accesspress_ray_theme_options', 'accesspress_ray_options', 'accesspress_ray_validate_options' );
}

function accesspress_ray_theme_options() {
	// Add theme options page to the addmin menu
	add_theme_page( __( 'Theme Options', 'accesspress_ray' ), __( 'Theme Options', 'accesspress_ray' ), 'edit_theme_options', 'theme_options', 'accesspress_ray_theme_options_page' );
}


// Store Posts in array
$accesspress_ray_postlist[0] = array(
	'value' => 0,
	'label' => '--choose--'
);
$arg = array('posts_per_page'   => -1);
$accesspress_ray_posts = get_posts($arg);
foreach( $accesspress_ray_posts as $accesspress_ray_post ) :
	$accesspress_ray_postlist[$accesspress_ray_post->ID] = array(
		'value' => $accesspress_ray_post->ID,
		'label' => $accesspress_ray_post->post_title
	);
endforeach;
wp_reset_postdata();

// Store categories in array
$accesspress_ray_catlist[0] = array(
	'value' => 0,
	'label' => '--choose--'
);
$arg1 = array(
	'hide_empty' => 0,
	'orderby' => 'name',
  	'parent' => 0,
  	);
$accesspress_ray_cats = get_categories($arg1);

foreach( $accesspress_ray_cats as $accesspress_ray_cat ) :
	$accesspress_ray_catlist[$accesspress_ray_cat->cat_ID] = array(
		'value' => $accesspress_ray_cat->cat_ID,
		'label' => $accesspress_ray_cat->cat_name
	);
endforeach;
wp_reset_postdata();

// Store slider setting in array
$accesspress_ray_slider = array(
	'yes' => array(
		'value' => 'yes',
		'label' => 'show'
	),
	'no' => array(
		'value' => 'no',
		'label' => 'hide'
	),
);

$accesspress_ray_slider_show_pager = array(
	'yes1' => array(
		'value' => 'yes1',
		'label' => 'yes'
	),
	'no1' => array(
		'value' => 'no1',
		'label' => 'no'
	),
);

$accesspress_ray_slider_show_controls = array(
	'yes2' => array(
		'value' => 'yes2',
		'label' => 'yes'
	),
	'no2' => array(
		'value' => 'no2',
		'label' => 'no'
	),
);

$accesspress_ray_slider_auto = array(
	'yes3' => array(
		'value' => 'yes3',
		'label' => 'yes'
	),
	'no3' => array(
		'value' => 'no3',
		'label' => 'no'
	),
);

$accesspress_ray_slider_mode = array(
	'fade' => array(
		'value' => 'fade',
		'label' => 'fade'
	),
	'slide' => array(
		'value' => 'slide',
		'label' => 'slide'
	),
);

$accesspress_ray_slider_caption = array(
	'yes4' => array(
		'value' => 'yes4',
		'label' => 'show'
	),
	'no4' => array(
		'value' => 'no4',
		'label' => 'hide'
	),
);


// Function to generate options page
function accesspress_ray_theme_options_page() {
	global $accesspress_ray_options, $accesspress_ray_postlist, $accesspress_ray_slider, $accesspress_ray_slider_show_pager, $accesspress_ray_slider_show_controls, $accesspress_ray_slider_mode, $accesspress_ray_slider_auto, $accesspress_ray_slider_caption, $accesspress_ray_catlist;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap" id="optionsframework-wrap">

	<?php 	if ( false !== $_REQUEST['settings-updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' , 'accesspress_ray' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<?php // Shows all the tabs of the theme options ?>
	<div class="nav-tab-wrapper">
	<div class="accesspress_ray-header">
		<img src="<?php echo get_template_directory_uri(); ?>/inc/admin-panel/images/logo.png">
		<h3>Theme Options</h3>
	</div>
	<a id="options-group-1-tab" class="nav-tab nav-tab-active" href="#options-group-1"><?php _e('Basic Settings','accesspress_ray'); ?></a>
    <a id="options-group-2-tab" class="nav-tab" href="#options-group-2"><?php _e('Home Page Set Up','accesspress_ray'); ?></a>
	<a id="options-group-3-tab" class="nav-tab" href="#options-group-3"><?php _e('Slider Settings','accesspress_ray'); ?></a>
	<a id="options-group-5-tab" class="nav-tab" href="#options-group-5"><?php _e('Social Links','accesspress_ray'); ?></a>
	<a id="options-group-6-tab" class="nav-tab" href="#options-group-6"><?php _e('Tools','accesspress_ray'); ?></a>
	<a id="options-group-7-tab" class="nav-tab" href="#options-group-7"><?php _e('About AccessPress Ray','accesspress_ray'); ?></a>
	
	<div class="update-banner">
		<img src="<?php echo get_template_directory_uri(); ?>/inc/admin-panel/images/upgrade-top.jpg">
		<div class="button-link">
			<a href="<?php echo esc_url('https://accesspressthemes.com/accesspress-ray-pro/'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/inc/admin-panel/images/demo-btn.png"></a>
			<a href="<?php echo esc_url('https://accesspressthemes.com/wordpress-themes/accesspress-ray-pro/'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/inc/admin-panel/images/upgrade-btn.png"></a>
		</div>

		<div class="any-question">
			Any question!! Click <a href="<?php echo esc_url('https://accesspressthemes.com/contact/'); ?>" target="_blank">here</a> for Live Chat.
		</div>
	</div>
	</div>

	<div id="optionsframework-metabox" class="metabox-holder">
		<div id="optionsframework" class="postbox">
			<form id="form_options" method="post" action="options.php">

			<?php $settings = get_option( 'accesspress_ray_options', $accesspress_ray_options ); ?>
			
			<?php settings_fields( 'accesspress_ray_theme_options' );
			/* This function outputs some hidden fields required by the form,
			including a nonce, a unique number used to ensure the form has been submitted from the admin page
			and not somewhere else, very important for security */ ?>

			<!-- Basic Settings -->
			<div id="options-group-1" class="group">
			<h3><?php _e('Basic Settings','accesspress_ray'); ?></h3>
				<table class="form-table">
					<tr>
						<th><label for="footer_copyright"><?php _e('Disable Responsive Design?','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="responsive_design" name="accesspress_ray_options[responsive_design]" value="1" <?php checked( true, $settings['responsive_design'] ); ?> />
							<label for="responsive_design"><?php _e('Check to disable','accesspress_ray'); ?></label>
						</td>
					</tr>

					<tr><th scope="row"><label for="webpage_layouts"><?php _e('Web Page Layout','accesspress_ray'); ?></label></th>
					<td>
					<?php $accesspress_ray_webpage_layouts = array('Fullwidth','Boxed'); ?>
					<?php
					foreach ( $accesspress_ray_webpage_layouts as $accesspress_ray_webpage_layout ) : ?>
						<input type="radio" id="<?php echo $accesspress_ray_webpage_layout; ?>" name="accesspress_ray_options[accesspress_ray_webpage_layout]" value="<?php echo $accesspress_ray_webpage_layout; ?>" <?php checked( $settings['accesspress_ray_webpage_layout'], $accesspress_ray_webpage_layout ); ?> />
						<label for="<?php echo $accesspress_ray_webpage_layout; ?>"><?php echo $accesspress_ray_webpage_layout; ?></label><br />
					<?php endforeach;
					?>
					</td>
					</tr>
                    
                    <tr>
						<th><label for="show_search"><?php _e('Show Search in Header?','accesspress_ray') ?></th>
						<td>
							<input type="checkbox" id="show_search" name="accesspress_ray_options[show_search]" value="1" <?php checked( true, $settings['show_search'] ); ?> />
							<label for="show_search"><?php _e('Check to enable','accesspress_ray'); ?></label>
						</td>
					</tr>

					<tr>
						<th><label for="accesspress_ray_favicon"><?php _e('Upload Favicon','accesspress_ray'); ?></th>
						<td>
							<div class="accesspress_ray_fav_icon">
							  <input class="medium" type="text" name="accesspress_ray_options[media_upload]" id="accesspress_ray_media_upload" value="<?php if(!empty($settings['media_upload'])){ echo $settings['media_upload']; }?>" />
							  <input class="button" name="media_upload_button" id="accesspress_ray_media_upload_button" value="<?php _e('Upload','accesspress_ray'); ?>" type="button" /> <br />
							  <em class="f13"><?php _e('Upload favicon(.png) with size of 16px X 16px', 'accesspress_ray'); ?></em>

							  <?php if(!empty($settings['media_upload'])){ ?>
							  <div id="accesspress_ray_media_image">
							  <img src="<?php echo $settings['media_upload'] ?>" id="accesspress_ray_show_image">
							  <div id="accesspress_ray_fav_icon_remove"><?php _e('Remove','accesspress_ray'); ?></div>
							  </div>
							  <?php }else{ ?>
							  <div id="accesspress_ray_media_image" style="display:none">
							  <img src="<?php if(isset($settings['media_upload'])) { echo $settings['media_upload']; } ?>" id="accesspress_ray_show_image">
							  <a href="javascript:void(0)" id="accesspress_ray_fav_icon_remove" title="remove"><?php _e('Remove','accesspress_ray'); ?></a>
							  </div>
							  <?php	} ?>
							</div>
						</td>
					</tr>

					<tr>
						<th><label for="upload_log"><?php _e('Upload Logo','accesspress_ray'); ?></th>
						<td>
							<a class="button" target="_blank" href="<?php echo admin_url('/themes.php?page=custom-header'); ?>"><?php _e('Upload','accesspress_ray'); ?></a>
						</td>
					</tr>

					<tr><th scope="row"><label for="logo_alignment"><?php _e('Logo Alignment','accesspress_ray'); ?></label></th>
					<td>
					<?php $accesspress_ray_logo_alignments = array('Left','Center'); ?>
					<select id="logo_alignment" name="accesspress_ray_options[logo_alignment]">
					<?php
					foreach ( $accesspress_ray_logo_alignments as $accesspress_ray_logo_alignment ) :
						echo '<option value="' .$accesspress_ray_logo_alignment. '" ' . selected( $accesspress_ray_logo_alignment , $settings['logo_alignment'] ) . '>' . esc_attr($accesspress_ray_logo_alignment,'accesspress_ray')  . '</option>';
					endforeach;
					?>
					</select>
					</td>
					</tr>
					<tr><td colspan="2" class="seperator">&nbsp;</td></tr>

					

					<tr><th scope="row"><label for="portfolio_cat"><?php _e('Select the category to display as Portfolio/Products','accesspress_ray'); ?></label></th>
					<td>
					<select id="portfolio_cat" name="accesspress_ray_options[portfolio_cat]">
					<?php
					foreach ( $accesspress_ray_catlist as $single_cat ) :
						$label = $single_cat['label']; ?>
						<option value="<?php echo $single_cat['value'] ?>" <?php selected( $single_cat['value'], $settings['portfolio_cat'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					</td>
					</tr>

					<tr>
						<td colspan="2">
							<em><?php _e('You can show these categories in the menu by configuring','accesspress_ray'); ?> <a target="_blank" href="<?php echo admin_url('nav-menus.php'); ?>">Menus</a> <?php _e('Page.','accesspress_ray'); ?></em>
						</td>
					</tr>

					<tr><td colspan="2" class="seperator">&nbsp;</td></tr>

					<tr>
					<th scope="row"><label for="read_more_text"><?php _e('Read More Text','accesspress_ray'); ?></label></th>
					<td>
					<input class="medium" id="read_more_text" name="accesspress_ray_options[read_more_text]" type="text" value="<?php echo esc_attr($settings['read_more_text'],'accesspress_ray'); ?>" />
					<br>
					<em class="f13"><?php _e('Read More text for Archive page','accesspress_ray'); ?></em>
					</td>
					</tr>

					<tr>
					<th scope="row"><label for="footer_copyright"><?php _e('Footer Copyright Text','accesspress_ray'); ?></label></th>
					<td>
					<input class="medium" id="footer_copyright" name="accesspress_ray_options[footer_copyright]" type="text" value="<?php echo esc_attr($settings['footer_copyright'],'accesspress_ray'); ?>" />
					</td>
					</tr>
				</table>
			</div>
            
            <!-- Home page Settings -->
			<div id="options-group-2" class="group" style="display: none;">
			<h3><?php _e('Home Page Settings','accesspress_ray'); ?></h3> 
				<table class="form-table">
					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Featured Posts', 'accesspress_ray'); ?></h4>
					</td>
					</tr>
					<tr>
					<th scope="row"><label for="featured_title"><?php _e('Featured Title','accesspress_ray'); ?></label></th>
					<td>
					<input class="medium" type="text" id="featured_title" name="accesspress_ray_options[featured_title]" value="<?php echo $settings['featured_title']; ?>" />
					</td>
                    </tr>

					<tr>
					<th scope="row"><label for="featured_text"><?php _e('Featured Text','accesspress_ray'); ?></label></th>
					<td>
					<textarea id="featured_text" name="accesspress_ray_options[featured_text]" rows="3" cols="60"><?php echo $settings['featured_text']; ?></textarea><br />
                    <em class="f13"><?php _e('Html content allowed','accesspress_ray'); ?></em> </td>
                    </tr>

					<tr>
						<th><label for="show_fontawesome"><?php _e('Show Font Awesome icon in Featured Post?','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="show_fontawesome" name="accesspress_ray_options[show_fontawesome]" value="1" <?php checked( true, $settings['show_fontawesome'] ); ?> />
							<label for="show_fontawesome"><?php _e('Check to enable','accesspress_ray'); ?></label><br />
                            <em class="f13"><?php _e('(If enabled the image will be replaced by Font Awesome Icon. For lists of icons click','accesspress_ray'); ?> <a href="<?php echo esc_url('http://fontawesome.io/icons/'); ?>" target="_blank"><?php _e('here','accesspress_ray'); ?></a>)</em>
						</td>
					</tr>
                    
					<tr><th scope="row"><label for="featured_post1"><?php _e('Featured Post 1','accesspress_ray'); ?></label></th>
					<td>
					<select id="featured_post1" name="accesspress_ray_options[featured_post1]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label']; ?>
						<option value="<?php echo $single_post['value'] ?>" <?php selected( $single_post['value'], $settings['featured_post1'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					<input id="featured_post1_icon" name="accesspress_ray_options[featured_post1_icon]" type="text" value="<?php echo esc_attr($settings['featured_post1_icon'],'accesspress_ray'); ?>" placeholder="Font Awesome icon name" /><em class="f13">&nbsp;&nbsp;Example: fa-bell</em>
					</td>
					</tr>

					<tr><th scope="row"><label for="featured_post2"><?php _e('Featured Post 2','accesspress_ray'); ?></label></th>
					<td>
					<select id="featured_post2" name="accesspress_ray_options[featured_post2]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label']; ?>
						<option value="<?php echo $single_post['value'] ?>" <?php selected( $single_post['value'], $settings['featured_post2'] ); ?>><?php echo $label; ?></option>
					<?php
					endforeach;
					?>
					</select>
					<input id="featured_post2_icon" name="accesspress_ray_options[featured_post2_icon]" type="text" value="<?php echo esc_attr($settings['featured_post2_icon'],'accesspress_ray'); ?>" placeholder="Font Awesome icon name" /><em class="f13">&nbsp;&nbsp;Example: fa-bell</em>
					</td>
					</tr>

					<tr><th scope="row"><label for="featured_post3"><?php _e('Featured Post 3','accesspress_ray'); ?></label></th>
					<td>
					<select id="featured_post3" name="accesspress_ray_options[featured_post3]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label']; ?>
						<option value="<?php echo $single_post['value'] ?>" <?php selected( $single_post['value'], $settings['featured_post3'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					<input id="featured_post3_icon" name="accesspress_ray_options[featured_post3_icon]" type="text" value="<?php  echo esc_attr($settings['featured_post3_icon'],'accesspress_ray'); ?>" placeholder="Font Awesome icon name" /><em class="f13">&nbsp;&nbsp;Example: fa-bell</em>
					</td>
					</tr>

					<tr><th scope="row"><label for="featured_post4"><?php _e('Featured Post 4','accesspress_ray'); ?></label></th>
					<td>
					<select id="featured_post4" name="accesspress_ray_options[featured_post4]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label']; ?>
						<option value="<?php echo $single_post['value'] ?>" <?php selected( $single_post['value'], $settings['featured_post4'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					<input id="featured_post4_icon" name="accesspress_ray_options[featured_post4_icon]" type="text" value="<?php  echo esc_attr($settings['featured_post4_icon'],'accesspress_ray'); ?>" placeholder="Font Awesome icon name" /><em class="f13">&nbsp;&nbsp;Example: fa-bell</em>
					</td>
					</tr>

					<tr>
						<th><label for="featured_post_readmore"><?php _e('Read More Text','accesspress_ray'); ?></label></th>
						<td><input id="featured_post_readmore" type="text" name="accesspress_ray_options[featured_post_readmore]" value="<?php if ( isset($settings['featured_post_readmore'])){echo esc_attr($settings['featured_post_readmore'],'accesspress_ray'); } ?>"><br /><em class="f13"><?php _e('Leave blank if you don\'t want to show read more','accesspress_ray'); ?></em></td>
					</tr>

					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Call To Action', 'accesspress_ray'); ?></h4>
					</td>
					</tr>

					<tr><th scope="row"><label for="call_to_action_post"><?php _e('Call To Action Post','accesspress_ray'); ?></label></th>
					<td>
					<select id="call_to_action_post" name="accesspress_ray_options[call_to_action_post]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label']; ?>
						<option value="<?php echo $single_post['value'] ?>" <?php selected( $single_post['value'], $settings['call_to_action_post'] ); ?>><?php echo $label; ?></option>
					<?php endforeach;
					?>
					</select>
					</td>
					</tr>

					<tr>
						<th><label for="full_content"><?php _e('Show Full Content?','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="full_content" name="accesspress_ray_options[call_to_action_post_content]" value="1" <?php checked( true, $settings['call_to_action_post_content'] ); ?> />
							<label for="full_content"><?php _e('Check to enable','accesspress_ray'); ?></label><br />
						</td>
					</tr>

					<tr>
						<th><label for="call_to_action_post_char"><?php _e('Call To Action Post Excerpt Character','accesspress_ray'); ?></label></th>
						<td><input id="call_to_action_post_char" type="text" name="accesspress_ray_options[call_to_action_post_char]" value="<?php if (isset($settings['call_to_action_post_char'])){ echo esc_attr($settings['call_to_action_post_char'],'accesspress_ray'); } ?>"> <?php _e('Characters','accesspress_ray'); ?></td>
					</tr>

					<tr>
						<th><label for="call_to_action_post_readmore"><?php _e('Read More Text','accesspress_ray'); ?></label></th>
						<td><input class="medium" id="call_to_action_post_readmore" type="text" name="accesspress_ray_options[call_to_action_post_readmore]" value="<?php if (isset($settings['call_to_action_post_readmore'])){ echo esc_attr($settings['call_to_action_post_readmore'],'accesspress_ray'); } ?>"><br /><em class="f13"><?php _e('Leave blank if you don\'t want to show read more','accesspress_ray'); ?></em></td>
					</tr>

					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Featured Widgets', 'accesspress_ray'); ?></h4>
					</td>
					</tr>

                    <tr>
						<th><label for="featured_bar"><?php _e('Disable Featured Widget Bar','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="featured_bar" name="accesspress_ray_options[featured_bar]" value="1" <?php checked( true, $settings['featured_bar'] ); ?> />
							<label for="featured_bar"><?php _e('Check to disable','accesspress_ray'); ?></label><br />
						</td>
					</tr>

					<tr>
					<td colspan="2">
						<em class="f13"><?php echo sprintf(__('To set up Widgets, Go to <a href="%s" target="_blank">widget page</a>', 'accesspress_ray'),esc_url(admin_url('widgets.php'))); ?></em>
					</td>
					</tr>

					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Blog Slider', 'accesspress_ray'); ?></h4>
					</td>
					</tr>

					<tr><th scope="row"><label for="blog_cat"><?php _e('Select the category to display as Blog','accesspress_ray'); ?></label></th>
					<td>
					<select id="blog_cat" name="accesspress_ray_options[blog_cat]">
					<?php
					foreach ( $accesspress_ray_catlist as $single_cat ) :
						$label = $single_cat['label']; ?>
						<option value="<?php echo $single_cat['value'] ?>" <?php selected( $single_cat['value'], $settings['blog_cat'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					</td>
					</tr>

                    <tr>
						<th><label for="show_blog_number"><?php _e('No of Posts to display in Blog slider','accesspress_ray'); ?></label></th>
						<td><input id="show_blog_number" type="text" name="accesspress_ray_options[show_blog_number]" value="<?php if (isset($settings['show_blog_number'])){ echo esc_attr($settings['show_blog_number'],'accesspress_ray'); } ?>"></td>
					</tr>

					<tr>
						<th><label for="show_blogdate"><?php _e('Show Blog Posted Date?','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="show_blogdate" name="accesspress_ray_options[show_blogdate]" value="1" <?php checked( true, $settings['show_blogdate'] ); ?> />
							<label for="show_blogdate"><?php _e('Check to enable','accesspress_ray'); ?></label>
						</td>
					</tr>

					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Testimonail Slider', 'accesspress_ray'); ?></h4>
					</td>
					</tr>

					<tr><th scope="row"><label for="testimonial_cat"><?php _e('Select the category to display as Testimonials','accesspress_ray'); ?></label></th>
					<td>
					<select id="testimonial_cat" name="accesspress_ray_options[testimonial_cat]">
					<?php
					foreach ( $accesspress_ray_catlist as $single_cat ) :
						$label = $single_cat['label']; ?>
						<option value="<?php echo $single_cat['value'] ?>" <?php selected( $single_cat['value'], $settings['testimonial_cat'] ); ?>><?php echo $label; ?></option>
					<?php 
					endforeach;
					?>
					</select>
					</td>
					</tr>

				
					<tr class="setting-title">
					<td colspan="2">
					<h4><?php _e('Google Map', 'accesspress_ray'); ?></h4>
					</td>
					</tr>

					<tr>
					<td colspan="2">
						<em class="13"><?php _e(sprintf('To get Values of Latitude and Longitude by Location name, click on <a href="%s" target="_blank">http://www.latlong.net</a>', 'http://www.latlong.net' ),'accesspress_ray'); ?></em>
					</td>
					</tr>

					<tr><th scope="row"><label for="latitude"><?php _e('Enter the latitude','accesspress_ray'); ?></label></th>
						<td>
						<input type="text" id="latitude" name="accesspress_ray_options[latitude]" value="<?php echo $settings['latitude']; ?>"/>
						</td>
					</tr>

					<tr><th scope="row"><label for="longitude"><?php _e('Enter the longitude','accesspress_ray'); ?></label></th>
						<td>
						<input type="text" id="longitude" name="accesspress_ray_options[longitude]" value="<?php echo $settings['longitude']; ?>"/>
						</td>
					</tr>

					<tr><th scope="row"><label for="contact_address"><?php _e('Contact Address','accesspress_ray'); ?></label></th>
						<td>
						<textarea id="contact_address" name="accesspress_ray_options[contact_address]" rows="6" cols="60"><?php echo $settings['contact_address']; ?></textarea>
						<p class="f13"><em><?php _e('Enter the Contact Address Detail','accesspress_ray'); ?><em></p>
						</td>
					</tr>
                </table>
            </div>


			<!-- Slider Settings-->
			<div id="options-group-3" class="group" style="display: none;">
			<h3><?php _e('Home Page Slider Settings','accesspress_ray'); ?></h3>
				<table class="form-table">
				<tbody>
					<tr class="slider-options">
						<th>
							<?php _e('Show','accesspress_ray'); ?>
						</th>
						<td>
						<?php 
						if(!isset($settings['slider_options'])){
							$settings['slider_options']='single_post_slider';
						}
						?>
						<label class="checkbox" id="single_post_slider">
							<input value="single_post_slider" type="radio" name="accesspress_ray_options[slider_options]" <?php checked($settings['slider_options'],'single_post_slider'); ?> ><?php _e('Single Posts as a Slider','accesspress_ray'); ?>
						</label>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<label class="checkbox" id="cat_post_slider">
							<input value="cat_post_slider" name="accesspress_ray_options[slider_options]" type="radio" <?php checked($settings['slider_options'],'cat_post_slider'); ?> ><?php _e('Category Posts as a Slider','accesspress_ray'); ?>
						</label>
						</td>
					</tr>

					<tr><td colspan="2" class="seperator">&nbsp;</td></tr>
					</tbody>

					<tbody class="post-as-slider">
					<tr>
						<td colspan="2"><em class="f13"><?php _e('Select the post that you want to display as a Slider','accesspress_ray'); ?></em></td>
					</tr>

					<tr>
					
					<th scope="row"><label for="slider1"><?php _e('Silder 1','accesspress_ray'); ?></label></th>
					<td>
					<select id="slider1" name="accesspress_ray_options[slider1]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label'];
						echo '<option value="' . $single_post['value'] . '" ' . selected($single_post['value'] , $settings['slider1'] ). '>' . $label . '</option>';
					endforeach;
					?>
					</select>
					</td>
					</tr>

					<tr><th scope="row"><label for="slider2"><?php _e('Silder 2','accesspress_ray'); ?></label></th>
					<td>
					<select id="slider2" name="accesspress_ray_options[slider2]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label'];
						echo '<option value="' . $single_post['value']  . '" ' . selected($single_post['value'] , $settings['slider2'] ) . '>' . $label . '</option>';
					endforeach;
					?>
					</select>
					</td>
					</tr>

					<tr><th scope="row"><label for="slider3"><?php _e('Silder 3','accesspress_ray'); ?></label></th>
					<td>
					<select id="slider3" name="accesspress_ray_options[slider3]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label'];
						echo '<option value="' . esc_attr( $single_post['value'] ) . '" ' . selected($single_post['value'] , $settings['slider3'] ) . '>' . $label . '</option>';
					endforeach;
					?>
					</select>
					</td>
					</tr>

					<tr>
					<th scope="row"><label for="slider4"><?php _e('Silder 4','accesspress_ray'); ?></label></th>
					<td>
					<select id="slider4" name="accesspress_ray_options[slider4]">
					<?php
					foreach ( $accesspress_ray_postlist as $single_post ) :
						$label = $single_post['label'];
						echo '<option value="' . esc_attr( $single_post['value'] ) . '" ' . selected( $single_post['value'], $settings['slider4'] ) . '>' . $label . '</option>';
					endforeach;
					?>
					</select>
					</td>
					</tr>
					</tbody>

					<tbody class="cat-as-slider">
					<tr>
					<th><?php _e('Select the Category','accesspress_ray'); ?></th>
					<td>
					<?php 
					if(!isset($settings['slider_cat'])){
						$settings['slider_cat']=0;
					}
					?>
						<select id="slider_cat" name="accesspress_ray_options[slider_cat]">
						<?php
						foreach ( $accesspress_ray_catlist as $single_cat ) :
							$label = $single_cat['label'];
							echo '<option value="' . $single_cat['value'] . '" ' . selected( $single_cat['value'] , $settings['slider_cat'] ) . '>' . $label . '</option>';
						endforeach;
						?>
					</select>
					</td>
					</tr>
					</tbody>
					
					<tbody>
					<tr><td colspan="2" class="seperator">&nbsp;</td></tr>
					
					<tr>
						<td colspan="2"><em class="f13"><?php _e('Adjust the slider as per your need.','accesspress_ray'); ?></em></td>
					</tr>

					<tr><th scope="row"><?php _e('Show Slider','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider as $slider ) : ?>
					<input type="radio" id="<?php echo $slider['value']; ?>" name="accesspress_ray_options[show_slider]" value="<?php echo $slider['value']; ?>" <?php checked( $settings['show_slider'], $slider['value'] ); ?> />
					<label for="<?php echo $slider['value']; ?>"><?php echo $slider['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Show Slider Pager (Navigation dots)','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider_show_pager as $slider_pager ) : ?>
					<input type="radio" id="<?php echo $slider_pager['value']; ?>" name="accesspress_ray_options[slider_show_pager]" value="<?php echo $slider_pager['value']; ?>" <?php checked( $settings['slider_show_pager'], $slider_pager['value'] ); ?> />
					<label for="<?php echo $slider_pager['value']; ?>"><?php echo $slider_pager['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Show Slider Controls (Arrows)','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider_show_controls as $slider_controls ) : ?>
					<input type="radio" id="<?php echo $slider_controls['value']; ?>" name="accesspress_ray_options[slider_show_controls]" value="<?php echo $slider_controls['value']; ?>" <?php checked( $settings['slider_show_controls'], $slider_controls['value'] ); ?> />
					<label for="<?php echo $slider_controls['value']; ?>"><?php echo $slider_controls['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Slider Transition - fade/slide','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider_mode as $slider_modes) : ?>
					<input type="radio" id="<?php echo $slider_modes['value']; ?>" name="accesspress_ray_options[slider_mode]" value="<?php echo $slider_modes['value']; ?>" <?php checked( $settings['slider_mode'], $slider_modes['value'] ); ?> />
					<label for="<?php echo $slider_modes['value']; ?>"><?php echo $slider_modes['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Slider auto Transition','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider_auto as $slider_autos) : ?>
					<input type="radio" id="<?php echo $slider_autos['value']; ?>" name="accesspress_ray_options[slider_auto]" value="<?php echo $slider_autos['value']; ?>" <?php checked( $settings['slider_auto'], $slider_autos['value'] ); ?> />
					<label for="<?php echo $slider_autos['value']; ?>"><?php echo $slider_autos['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Slider Speed','accesspress_ray'); ?></th>
					<td>
					<input id="slider_speed" name="accesspress_ray_options[slider_speed]" type="text" value="<?php echo esc_attr($settings['slider_speed']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Slider Pause','accesspress_ray'); ?></th>
					<td>
					<input id="slider_pause" name="accesspress_ray_options[slider_pause]" type="text" value="<?php echo esc_attr($settings['slider_pause']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><?php _e('Show Slider Captions','accesspress_ray'); ?></th>
					<td>
					<?php foreach( $accesspress_ray_slider_caption as $slider_captions) : ?>
					<input type="radio" id="<?php echo $slider_captions['value']; ?>" name="accesspress_ray_options[slider_caption]" value="<?php echo esc_attr($slider_captions['value']); ?>" <?php checked( $settings['slider_caption'], $slider_captions['value'] ); ?> />
					<label for="<?php echo $slider_captions['value']; ?>"><?php echo $slider_captions['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
					</tr>
					</tbody>
				</table>
			</div>

			<!-- Social Settings-->
			<div id="options-group-5" class="group" style="display: none;">
			<h3><?php _e('Social links - Put your social url','accesspress_ray'); ?></h3>
				<table class="form-table social-urls">
					<tr>
						<td colspan="2"><em class="f13"><?php _e('Put your social url below.. Leave blank if you don\'t want to show it.','accesspress_ray'); ?></em></td>
					</tr>

					<tr>
						<th><label for="show_social_footer"><?php _e('Disable Social icons in Footer?','accesspress_ray'); ?></th>
						<td>
							<input type="checkbox" id="show_social_footer" name="accesspress_ray_options[show_social_footer]" value="1" <?php checked( true, $settings['show_social_footer'] ); ?> />
							<label for="show_social_footer"><?php _e('Check to disable','accesspress_ray'); ?></label>
						</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_facebook">Facebook</label></th>
					<td>
					<input id="accesspress_ray_facebook" name="accesspress_ray_options[accesspress_ray_facebook]" type="text" value="<?php echo $settings['accesspress_ray_facebook']; ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_twitter">Twitter</label></th>
					<td>
					<input id="accesspress_ray_twitter" name="accesspress_ray_options[accesspress_ray_twitter]" type="text" value="<?php echo esc_url($settings['accesspress_ray_twitter']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_gplus">Google plus</label></th>
					<td>
					<input id="accesspress_ray_gplus" name="accesspress_ray_options[accesspress_ray_gplus]" type="text" value="<?php echo esc_url($settings['accesspress_ray_gplus']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_youtube">Youtube</label></th>
					<td>
					<input id="accesspress_ray_youtube" name="accesspress_ray_options[accesspress_ray_youtube]" type="text" value="<?php echo esc_url($settings['accesspress_ray_youtube']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_pinterest">Pinterest</label></th>
					<td>
					<input id="accesspress_ray_pinterest" name="accesspress_ray_options[accesspress_ray_pinterest]" type="text" value="<?php echo esc_url($settings['accesspress_ray_pinterest']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_linkedin">Linkedin</label></th>
					<td>
					<input id="accesspress_ray_linkedin" name="accesspress_ray_options[accesspress_ray_linkedin]" type="text" value="<?php echo esc_url($settings['accesspress_ray_linkedin']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_flickr">Flickr</label></th>
					<td>
					<input id="accesspress_ray_flickr" name="accesspress_ray_options[accesspress_ray_flickr]" type="text" value="<?php echo esc_url($settings['accesspress_ray_flickr']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_vimeo">Vimeo</label></th>
					<td>
					<input id="accesspress_ray_vimeo" name="accesspress_ray_options[accesspress_ray_vimeo]" type="text" value="<?php echo esc_url($settings['accesspress_ray_vimeo']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_stumbleupon">Stumbleupon</label></th>
					<td>
					<input id="accesspress_ray_stumbleupon" name="accesspress_ray_options[accesspress_ray_stumbleupon]" type="text" value="<?php echo esc_url($settings['accesspress_ray_stumbleupon']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_instagram">Instagram</label></th>
					<td>
					<input id="accesspress_ray_instagram" name="accesspress_ray_options[accesspress_ray_instagram]" type="text" value="<?php if(isset($settings['accesspress_ray_instagram'])) { echo esc_url($settings['accesspress_ray_instagram']); } ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_sound_cloud">Sound Cloud</label></th>
					<td>
					<input id="accesspress_ray_sound_cloud" name="accesspress_ray_options[accesspress_ray_sound_cloud]" type="text" value="<?php if(isset($settings['accesspress_ray_sound_cloud'])) { echo esc_url($settings['accesspress_ray_sound_cloud']); } ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_skype">Skype</label></th>
					<td>
					<input id="accesspress_ray_skype" name="accesspress_ray_options[accesspress_ray_skype]" type="text" value="<?php echo esc_attr($settings['accesspress_ray_skype']); ?>" />
					</td>
					</tr>

					<tr><th scope="row"><label for="accesspress_ray_rss">RSS</label></th>
					<td>
					<input id="accesspress_ray_rss" name="accesspress_ray_options[accesspress_ray_rss]" type="text" value="<?php echo esc_url($settings['accesspress_ray_rss']); ?>" />
					</td>
					</tr>
				</table>
			</div>

			<!-- Footer-contact -->
			<div id="options-group-6" class="group" style="display: none;">
			<h3><?php _e('Tools','accesspress_ray'); ?></h3>
				<table class="form-table">
					<tr><th scope="row"><label for="custom_css"><?php _e('Custom CSS','accesspress_ray'); ?></label></th>
						<td>
						<textarea id="custom_css" name="accesspress_ray_options[custom_css]" rows="8" cols="60"><?php if(isset($settings['custom_css'])){ echo esc_attr($settings['custom_css']); } ?></textarea>
						<p class="f13"><em>Put your custom CSS</em></p>
						</td>
					</tr>

					<tr><th scope="row"><label for="custom_code"><?php _e('Custom Code (analytics)','accesspress_ray'); ?></label></th>
						<td>
						<textarea id="custom_code" name="accesspress_ray_options[custom_code]" rows="8" cols="60"><?php if(isset($settings['custom_code'])){ echo esc_html($settings['custom_code']); } ?></textarea>
						<p class="f13"><em>Put the script like anlytics or any other</em></p>
						</td>
					</tr>
				</table>
			</div>

			<!-- About AccessPress Ray -->
			<div id="options-group-7" class="group" style="display: none;">
			<h3><?php _e('Know more about AccessPress Themes','accesspress_ray'); ?></h3>
				<table class="form-table">
					<tr>
					<td colspan="2">
						<p><?php _e('AccessPress Ray - is a FREE WordPress theme by','accesspress_ray'); ?> <a target="_blank" href="<?php echo esc_url('http://www.accesspressthemes.com/'); ?>">AccessPress Themes</a> <?php _e('- A WordPress Division of Access Keys.','accesspress_ray'); ?></p>
						<p><?php _e('For demo, click','accesspress_ray'); ?> <a target="_blank" href="<?php echo esc_url('https://accesspressthemes.com/accesspress-ray/'); ?>"><?php _e('here','accesspress_ray'); ?></a></p>
						<p><?php _e('For documentation, click','accesspress_ray'); ?> <a target="_blank" href="<?php echo esc_url('https://accesspressthemes.com/theme-instruction-accesspress-ray/'); ?>"><?php _e('here','accesspress_ray'); ?></a></p>

						<hr />
						<?php
						$other_product  = "<h4>".__('Other products by AccessPressThemes','accesspress_ray')."</h4>";
						$other_product .= __('Our Themes - ','accesspress_ray'). __(sprintf('<a href="%s" target="_blank">https://accesspressthemes.com/themes</a>','https://accesspressthemes.com/themes'))."<br><br>" ;
						$other_product .= __('Our Plugins - ','accesspress_ray'). __(sprintf('<a href="%s" target="_blank">https://accesspressthemes.com/plugins</a>','https://accesspressthemes.com/plugins'))."<br><br>" ;
						echo $other_product;
						?>
						<hr />
						<h4><?php _e('Get in touch','accesspress_ray'); ?></h4>

						<p>
						<?php _e('If you have any question feedback, please get in touch:','accesspress_ray'); ?><br/>
						<?php _e('General enquiries:','accesspress_ray'); ?> <a href="mailto:<?php echo esc_url('info@accesspressthemes.com'); ?>">info@accesspressthemes.com</a><br/>
						<?php _e('Support:','accesspress_ray'); ?> <a href="mailto:<?php echo esc_url('support@accesspressthemes.com'); ?>">support@accesspressthemes.com</a><br/>
						<?php _e('Sales:','accesspress_ray'); ?> <a href="mailto:<?php echo esc_url('sales@accesspressthemes.com'); ?>">sales@accesspressthemes.com</a><br/>
						</p>

						<hr />

						<h4><?php _e('Get social','accesspress_ray'); ?></h4>

						<p><?php _e('Get connected with us on social media. Facebook is the best place to find updates on our themes/plugins:','accesspress_ray'); ?></p>

						<p><?php _e('Like us on facebook:','accesspress_ray'); ?></p>
						<iframe style="border: none; overflow: hidden; width: 740px; height: 230px;" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width=740&amp;height=230&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=1411139805828592" width="740" height="230" frameborder="0" scrolling="no"></iframe>	

						</td>
					</tr>
				</table>
			</div>

			<div id="optionsframework-submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Options','accesspress_ray'); ?>" />
			</div>

			</form>
		</div><!-- #optionsframework -->

	</div><!-- #optionsframework-metabox -->

	</div>

	<?php 
	$settings_array = get_option( 'accesspress_ray_options' );
	if(empty($settings_array)): ?>
	<div class="ap-popup-wrapper">
		<div class="ap-popup-close">&times;</div>
		<h4><?php _e('Like our Facebook page and don\'t miss any updates!','accesspresslite'); ?></h4>
		<iframe style="border: none; overflow: hidden; width: 340px; height: 260px;" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width=340&amp;height=260&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=true&amp;appId=1411139805828592" width="340" height="260" frameborder="0" scrolling="no"></iframe>
	</div>
	<div class="ap-popup-bg"></div>
	<?php endif; ?>

	<?php
}


function accesspress_ray_validate_options( $input ) {
	global $accesspress_ray_options, $accesspress_ray_logo_alignments, $accesspress_ray_postlist, $accesspress_ray_slider, $accesspress_ray_slider_show_pager, $accesspress_ray_slider_show_controls, $accesspress_ray_slider_mode, $accesspress_ray_slider_auto, $accesspress_ray_slider_caption;

	$settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties.
    $input['call_to_action_post'] = wp_filter_nohtml_kses( $input['call_to_action_post'] );
    $input['slider_options'] = wp_filter_nohtml_kses( $input['slider_options'] );
    $input['featured_title'] = wp_filter_nohtml_kses( $input['featured_title'] );
    $input['featured_post1'] = wp_filter_nohtml_kses( $input['featured_post1'] );
    $input['featured_post2'] = wp_filter_nohtml_kses( $input['featured_post2'] );
    $input['featured_post3'] = wp_filter_nohtml_kses( $input['featured_post3'] );
    $input['featured_post1_icon'] = sanitize_text_field( $input['featured_post1_icon'] );
    $input['featured_post2_icon'] = sanitize_text_field( $input['featured_post2_icon'] );
    $input['featured_post3_icon'] = sanitize_text_field( $input['featured_post3_icon'] );
    $input['blog_cat'] = wp_filter_nohtml_kses( $input['blog_cat'] );
    $input['testimonial_cat'] = wp_filter_nohtml_kses( $input['testimonial_cat'] );
    $input['portfolio_cat'] = wp_filter_nohtml_kses( $input['portfolio_cat'] );
    $input['slider_cat'] = wp_filter_nohtml_kses( $input['slider_cat'] );
    $input['logo_alignment'] = wp_filter_nohtml_kses( $input['logo_alignment'] );
    $input['slider_speed'] = sanitize_text_field( $input['slider_speed'] );
    $input['footer_copyright'] = sanitize_text_field( $input['footer_copyright'] );
    $input['featured_post_readmore'] = sanitize_text_field( $input['featured_post_readmore'] );
    $input['call_to_action_post_readmore'] = sanitize_text_field( $input['call_to_action_post_readmore'] );
    $input['custom_css'] = wp_kses_stripslashes( $input['custom_css'] );
    $input['custom_code'] = wp_kses_stripslashes( $input[ 'custom_code' ] );
    $input['longitude'] = sanitize_text_field( $input['longitude'] );
    $input['latitude'] = sanitize_text_field( $input['latitude'] );
    $input['read_more_text'] = sanitize_text_field( $input['read_more_text'] );

    // We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['featured_post1'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['featured_post1'], $accesspress_ray_postlist ) )
		$input['featured_post1'] = $prev;

	$prev = $settings['featured_post2'];
	if ( !array_key_exists( $input['featured_post2'], $accesspress_ray_postlist ) )
		$input['featured_post2'] = $prev;
        
    $prev = $settings['featured_post3'];
	if ( !array_key_exists( $input['featured_post3'], $accesspress_ray_postlist ) )
		$input['featured_post3'] = $prev;
	
	
	$prev = $settings['show_slider'];
	if ( !array_key_exists( $input['show_slider'], $accesspress_ray_slider ) )
		$input['show_slider'] = $prev;

	$prev = $settings['slider_show_pager'];
	if ( !array_key_exists( $input['slider_show_pager'], $accesspress_ray_slider_show_pager ) )
		$input['slider_show_pager'] = $prev;

	$prev = $settings['slider_show_controls'];
	if ( !array_key_exists( $input['slider_show_controls'], $accesspress_ray_slider_show_controls) )
		$input['slider_show_controls'] = $prev;

	$prev = $settings['slider_mode'];
	if ( !array_key_exists( $input['slider_mode'], $accesspress_ray_slider_mode ) )
		$input['slider_mode'] = $prev;

	$prev = $settings['slider_auto'];
	if ( !array_key_exists( $input['slider_auto'], $accesspress_ray_slider_auto ) )
		$input['slider_auto'] = $prev;

	$prev = $settings['slider_caption'];
	if ( !array_key_exists( $input['slider_caption'], $accesspress_ray_slider_caption ) )
		$input['slider_caption'] = $prev;
        
    if (isset( $input['slider_speed'] ) ){
        if(intval($input['slider_speed'])){
            $input['slider_speed'] = absint($input['slider_speed']);
        }
    }

    if (!isset( $input['slider_pause'] ) || empty( $input['slider_pause'] ) ){
        $input['slider_pause']= "5000";
    }else{
    	if(intval($input['slider_pause'])){
            $input['slider_pause'] = absint($input['slider_pause']);
        }
    }

    if (!isset( $input['call_to_action_post_char'] ) || empty( $input['call_to_action_post_char'] ) ){
        $input['call_to_action_post_char']= "650";
    }else{
    	if(intval($input['call_to_action_post_char'])){
            $input['call_to_action_post_char'] = absint($input['call_to_action_post_char']);
        }
    }

    if (!isset( $input['show_blog_number'] ) || empty( $input['show_blog_number'] )){
       	$input['show_blog_number']= "3";
    }else{
    	 if(intval($input['show_blog_number'])){
            $input['show_blog_number'] = absint($input['show_blog_number']);
        }
    }


	// If the checkbox has not been checked, we void it
	if ( ! isset( $input['responsive_design'] ) )
		$input['responsive_design'] = null;
	// We verify if the input is a boolean value
	$input['responsive_design'] = ( $input['responsive_design'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show_search'] ) )
		$input['show_search'] = null;
	$input['show_search'] = ( $input['show_search'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show_fontawesome'] ) )
		$input['show_fontawesome'] = null;
	$input['show_fontawesome'] = ( $input['show_fontawesome'] == 1 ? 1 : 0 );
    
    if ( ! isset( $input['big_icons'] ) )
		$input['big_icons'] = null;
	$input['big_icons'] = ( $input['big_icons'] == 1 ? 1 : 0 );

	if ( ! isset( $input['leftsidebar_show_latest_events'] ) )
		$input['leftsidebar_show_latest_events'] = null;
	$input['leftsidebar_show_latest_events'] = ( $input['leftsidebar_show_latest_events'] == 1 ? 1 : 0 );

	if ( ! isset( $input['leftsidebar_show_testimonials'] ) )
		$input['leftsidebar_show_testimonials'] = null;
	$input['leftsidebar_show_testimonials'] = ( $input['leftsidebar_show_testimonials'] == 1 ? 1 : 0 );

	if ( ! isset( $input['leftsidebar_show_social_links'] ) )
		$input['leftsidebar_show_social_links'] = null;
	$input['leftsidebar_show_social_links'] = ( $input['leftsidebar_show_social_links'] == 1 ? 1 : 0 );

	if ( ! isset( $input['rightsidebar_show_latest_events'] ) )
		$input['rightsidebar_show_latest_events'] = null;
	$input['rightsidebar_show_latest_events'] = ( $input['rightsidebar_show_latest_events'] == 1 ? 1 : 0 );

	if ( ! isset( $input['rightsidebar_show_testimonials'] ) )
		$input['rightsidebar_show_testimonials'] = null;
	$input['rightsidebar_show_testimonials'] = ( $input['rightsidebar_show_testimonials'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['rightsidebar_show_social_links'] ) )
		$input['rightsidebar_show_social_links'] = null;
	$input['rightsidebar_show_social_links'] = ( $input['rightsidebar_show_social_links'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show_social_header'] ) )
		$input['show_social_header'] = null;
	$input['show_social_header'] = ( $input['show_social_header'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show_social_footer'] ) )
		$input['show_social_footer'] = null;
	$input['show_social_footer'] = ( $input['show_social_footer'] == 1 ? 1 : 0 );

	if ( ! isset( $input['featured_bar'] ) )
		$input['featured_bar'] = null;
	$input['featured_bar'] = ( $input['featured_bar'] == 1 ? 1 : 0 );

	if ( ! isset( $input['call_to_action_post_content'] ) )
		$input['call_to_action_post_content'] = null;
	$input['call_to_action_post_content'] = ( $input['call_to_action_post_content'] == 1 ? 1 : 0 );

	if ( ! isset( $input['show_blogdate'] ) )
		$input['show_blogdate'] = null;
	$input['show_blogdate'] = ( $input['show_blogdate'] == 1 ? 1 : 0 );


	 // data validation for Social Icons
	if( isset( $input[ 'accesspress_ray_facebook' ] ) ) {
		$input[ 'accesspress_ray_facebook' ] = esc_url_raw( $input[ 'accesspress_ray_facebook' ] );
	};
	if( isset( $input[ 'accesspress_ray_twitter' ] ) ) {
		$input[ 'accesspress_ray_twitter' ] = esc_url_raw( $input[ 'accesspress_ray_twitter' ] );
	};
	if( isset( $input[ 'accesspress_ray_gplus' ] ) ) {
		$input[ 'accesspress_ray_gplus' ] = esc_url_raw( $input[ 'accesspress_ray_gplus' ] );
	};
	if( isset( $input[ 'accesspress_ray_youtube' ] ) ) {
		$input[ 'accesspress_ray_youtube' ] = esc_url_raw( $input[ 'accesspress_ray_youtube' ] );
	};
	if( isset( $input[ 'accesspress_ray_pinterest' ] ) ) {
		$input[ 'accesspress_ray_pinterest' ] = esc_url_raw( $input[ 'accesspress_ray_pinterest' ] );
	};
	if( isset( $input[ 'accesspress_ray_linkedin' ] ) ) {
		$input[ 'accesspress_ray_linkedin' ] = esc_url_raw( $input[ 'accesspress_ray_linkedin' ] );
	};
	if( isset( $input[ 'accesspress_ray_flickr' ] ) ) {
		$input[ 'accesspress_ray_flickr' ] = esc_url_raw( $input[ 'accesspress_ray_flickr' ] );
	};
	if( isset( $input[ 'accesspress_ray_vimeo' ] ) ) {
		$input[ 'accesspress_ray_vimeo' ] = esc_url_raw( $input[ 'accesspress_ray_vimeo' ] );
	};
	if( isset( $input[ 'accesspress_ray_stumbleupon' ] ) ) {
		$input[ 'accesspress_ray_stumbleupon' ] = esc_url_raw( $input[ 'accesspress_ray_stumbleupon' ] );
	};
	if( isset( $input[ 'accesspress_ray_instagram' ] ) ) {
		$input[ 'accesspress_ray_instagram' ] = esc_url_raw( $input[ 'accesspress_ray_instagram' ] );
	};
	if( isset( $input[ 'accesspress_ray_sound_cloud' ] ) ) {
		$input[ 'accesspress_ray_sound_cloud' ] = esc_url_raw( $input[ 'accesspress_ray_sound_cloud' ] );
	};
	if( isset( $input[ 'accesspress_ray_skype' ] ) ) {
		$input[ 'accesspress_ray_skype' ] = esc_attr( $input[ 'accesspress_ray_skype' ] );
	};
	if( isset( $input[ 'accesspress_ray_rss' ] ) ) {
		$input[ 'accesspress_ray_rss' ] = esc_url_raw( $input[ 'accesspress_ray_rss' ] );
	};
	if( isset( $input[ 'action_btn_link' ] ) ) {
		$input[ 'action_btn_link' ] = esc_url_raw( $input[ 'action_btn_link' ] );
	};

    if( isset( $input[ 'featured_text' ] ) ) {
	   $input[ 'featured_text' ] = wp_kses_post( $input[ 'featured_text' ] );
    }
    

    if( isset( $input[ 'contact_address' ] ) ) {
	   $input[ 'contact_address' ] = wp_kses_post( $input[ 'contact_address' ] );
    }
    
    if( isset( $input[ 'gallery_code' ] ) ) {
	   $input[ 'gallery_code' ] = wp_kses_post( $input[ 'gallery_code' ] );
	}
	return $input;
}

endif;  // EndIf is_admin()
?>