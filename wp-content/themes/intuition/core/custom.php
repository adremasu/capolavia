<?php

//Adds the admin custom pages for Theme Settings, SEO and Update
add_action('admin_menu', 'cpotheme_custom');
function cpotheme_custom(){
	//Get the image path for the core icon
	$core_path = get_template_directory_uri().'/core/';
	if(defined('WP_CPODEV')) $core_path = get_template_directory_uri().'/../cpoframework/core/';
	
	//Set up data to add admin menus
	add_theme_page(__('Theme Options', 'cpocore'), __('Theme Options', 'cpocore'), 'edit_theme_options', 'cpotheme_settings', 'cpotheme_settings', $core_path.'images/icon_options.png', 50);
}

//Build Settings Form
function cpotheme_custom_form($option_name, $option_list){ 
	
	$option_name = $option_name.cpotheme_custom_wpml_option_suffix(); ?>
	
	<div class="wrap">
		<div class="icon32" id="icon-themes"></div>
		<h2><?php echo get_admin_page_title(); ?></h2>
		
		<?php cpotheme_custom_header($option_list); ?>
		
		<div id="settingsmenu">
			<?php cpotheme_custom_nav($option_list); ?>
		</div>

		<?php if(isset($_GET['ok'])): ?>
		<div id="message" class="updated">
			<p><strong><?php _e('Changes have been saved.', 'cpocore'); ?></strong></p>
		</div>
		<?php endif; ?>
		<?php if(isset($_GET['error'])): ?>
		<div id="message" class="error">
			<p><strong><?php _e('Changes could not be saved.', 'cpocore'); ?></strong></p>
		</div>
		<?php endif; ?> 
		
		<form name="cpotheme_custom_form" method="post" action="themes.php?page=<?php echo $_GET['page'].cpotheme_custom_wpml_option_url(); ?>" enctype="multipart/form-data">
            <?php if(isset($_GET['tab']) && $_GET['tab'] != '') $current_tab = htmlentities($_GET['tab']); else $current_tab = ''; ?>
			<input type="hidden" name="cpotheme_custom_tab" id="cpotheme_custom_tab" value="<?php echo $current_tab; ?>" />
            <input type="hidden" name="cpotheme_custom_action" id="cpotheme_custom_action" value="<?php echo $option_name; ?>" />
            <?php cpotheme_custom_fields($option_list, $option_name); ?>
            <?php if(function_exists('wp_nonce_field')) wp_nonce_field('cpotheme_nonce'); ?>
		</form>
	</div>
<?php }

//Create navigation menu for settings page
function cpotheme_custom_nav($options){
	$field_list = $options;
	$output = '<ul>';
    $output .= '<li class="logo">&nbsp;</li>';
	$current_tab = '';
	if(isset($_GET['tab']) && $_GET['tab'] != '')
		$current_tab = htmlentities($_GET['tab']);
	$nav_count = 0;
	
	foreach($field_list as $current_field){
		if($current_field['type'] == 'separator'){
			$field_id = $current_field["id"];
			$field_name = $current_field["name"];
			$field_desc = $current_field['desc'];
			$output .= '<li id="'.$field_id.'" title="'.$field_desc.'" class="settingsmenu_element';
			if($current_tab == $field_id || ($current_tab == '' && $nav_count == 0)) $output .= ' active';
			$output .= '">'.$field_name.'</li>';
			$nav_count++;
		}
	}
	
	$output .= '</ul>';
	$output .= '<div class="submenu">';
	$output .= cpotheme_custom_wpml_nav();
	//$output .= '<a href="http://www.cpothemes.com/support">'.__('Support', 'cpocore').'</a>';
	$output .= '</div>';
	echo $output;
}

//Display the options forms fields
function cpotheme_custom_fields($cpo_options, $list_name){    
	$output = '';
	$tab_count = 0;
	$current_tab = '';
	if(isset($_GET['tab']) && $_GET['tab'] != '')
		$current_tab = htmlentities($_GET['tab']);
	$option_list = get_option($list_name, false);
   
    foreach($cpo_options as $current_field){
		
		//Set common attributes for each element
		$field_name = isset($current_field['id']) ? $current_field['id'] : '';
		$field_title = isset($current_field['name']) ? $current_field['name'] : '';
		$field_desc = isset($current_field['desc']) ? $current_field['desc'] : '';
		$field_type = isset($current_field['type']) ? $current_field['type'] : 'separator';
		
		$field_value = '';
		//$field_value = get_option($field_name);
		if($option_list && isset($option_list[$field_name])) $field_value = $option_list[$field_name];
		
		//Is a field block separator. Print a separate container.
		if($field_type == 'separator'){
			if($tab_count > 0):
				$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpocore').'" />';
				$output .= '</div>';
			endif;
			$output .= '<div class="cposettings_block" id="'.$field_name.'_block"';
			if(($current_tab != '' && $current_tab != $field_name) || ($current_tab == '' && $tab_count > 0)) $output .= ' style="display:none;"';
			$output .= '>';
			$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpocore').'" />';
			$output .= '<div class="cposettings_separator">';
			$output .= $field_title.'<br/><span class="desc">'.$field_desc.'</span>';
			$output .= '</div>';
			$tab_count++;
			
		// Is a field divider
		}elseif($field_type == 'divider'){
			$output .= '<div class="cposettings_divider">';
			$output .= '<h3 class="">'.$field_title.'</h3>';
			$output .= '</div>';
		
		//Is a normal field. Print field containers
		}else{
			$output .= '<div class="item">';
			$output .= '<label for="'.$field_name.'" class="field_title">'.$field_title.'</label>';
			$output .= '<div class="field_contents">';
		}
		
		if($field_type == 'text')
			$output .= cpotheme_form_text($field_name, $field_value, $current_field);
		
		elseif($field_type == 'textarea')
			$output .= cpotheme_form_textarea($field_name, $field_value, $current_field);
		
		elseif($field_type == 'select')
			$output .= cpotheme_form_select($field_name, $field_value, $current_field['option'], $current_field);
		
		elseif($field_type == 'checkbox')
			$output .= cpotheme_form_checkbox($field_name, $field_value, $current_field);
		
		elseif($field_type == 'yesno')
			$output .= cpotheme_form_yesno($field_name, $field_value, $current_field);
		
		elseif($field_type == 'color')
			$output .= cpotheme_form_color($field_name, $field_value);
        
        elseif($field_type == 'imagelist')
            $output .= cpotheme_form_imagelist($field_name, $field_value, $current_field['option'], $current_field);
			
        elseif($field_type == 'upload') 
            $output .= cpotheme_form_upload($field_name, $field_value);
        
		elseif($field_type == 'font') 
            $output .= cpotheme_form_font($field_name, $field_value, $current_field['option'], $current_field);
                
		//Separators
		if($field_type != 'separator' && $field_type != 'divider'){
			$output .= '</div>';
			$output .= '<div class="field_desc">'.$field_desc.'</div>';
			$output .= '</div>';
		}
		unset($current_field);
    }
	$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpocore').'" />';
    $output .= '</div>';
    echo $output;
}

//Save all settings upon submitting the settings form
function cpotheme_custom_save($option_name, $option_fields){
	
	$lang_url = cpotheme_custom_wpml_option_url();	
	$option_name = $option_name.cpotheme_custom_wpml_option_suffix();
	if(isset($_POST['cpotheme_custom_tab']) && $_POST['cpotheme_custom_tab'] != '')
		$current_tab = '&tab='.htmlentities($_POST['cpotheme_custom_tab']);
	else
		$current_tab = '';
		
	//Check if we're submitting a custom page
    if(isset($_POST['cpotheme_custom_action']) && $_POST['cpotheme_custom_action'] == $option_name){
		if(!wp_verify_nonce($_POST['_wpnonce'], 'cpotheme_nonce')) header("Location: themes.php?page=".$_GET['page'].$lang_url."&error");
		

		//Get the option array, then update the array values
		$options_list = get_option($option_name, false);
		foreach($option_fields as $current_option){
			$field_id = $current_option["id"];
				
			//If the field has an update, process it.
			if(isset($_POST[$field_id])){
				$field_value = '';
				$field_value = esc_attr(trim($_POST[$field_id]));

				$current_value = '';
				if(isset($options_list[$field_id]))
					$current_value = $options_list[$field_id];
				
				// Add option
				if($current_value == '' && $field_value != ''){
					$options_list[$field_id] = $field_value;
				}
				// Update option
				elseif($field_value != $current_value){
					$options_list[$field_id] = $field_value;
				}
				// Delete unused option
				elseif($field_value == ''){
					//TODO: Check default values
					$options_list[$field_id] = $field_value;
				}
			}
		}
		update_option($option_name, $options_list);
		
		header('Location: themes.php?page='.$_GET['page'].$current_tab.$lang_url."&ok");
	}
}



function cpotheme_custom_header(){
	$theme_data = wp_get_theme(); ?>
	<div class="cpotheme-header">
		
		
		<div class="header-version">
			<div class="header-version-name">
				<?php _e('Theme Version', 'cpocore'); ?>
			</div>
			<div class="header-version-number">
				<?php echo $theme_data->get('Version'); ?>
			</div>
		</div>
		<!--<div class="header-version">
			<div class="header-version-name">
				<?php _e('Core Version', 'cpocore'); ?>
			</div>
			<div class="header-version-number">
				<?php echo cpotheme_get_option('cpo_core_version'); ?>
			</div>
		</div>-->
		
		<div class="header-social">
			<div class="header-social-item">
				<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fcpothemes&amp;width=120&amp;height=21&amp;colorscheme=light&amp;lang=en&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:120px; height:21px;" allowTransparency="true"></iframe>
			</div>
			<div class="header-social-item">
				<iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/follow_button.1379006964.html#_=1379511119899&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=cpothemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" style="width: 140px; height:20px;" title="Follow On Twitter" data-twttr-rendered="true"></iframe>
			</div>
		</div>
		
		<div class="header-title" id="cpo-header-title">
			<?php echo $theme_data->get('Name'); ?>
		</div>
		<div class="header-meta">
			<a target="_blank" href="http://www.cpothemes.com/support"><?php _e('Theme Documentation', 'cpocore'); ?></a>
			&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a target="_blank" href="http://www.cpothemes.com/forums"><?php _e('Support Forums', 'cpocore'); ?></a>
			&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a target="_blank" href="http://www.cpothemes.com/themes"><?php _e('More Themes', 'cpocore'); ?></a>
		</div>
	</div>
	
	<?php
}


//Installs options with default values, without overriding existing ones
function cpotheme_custom_install($option_name, $option_fields, $overwrite){
	
	$lang_url = cpotheme_custom_wpml_option_url();	
	$option_name = $option_name.cpotheme_custom_wpml_option_suffix();
			
	//Get the option array, then update the array values
	$options_list = get_option($option_name, false);
	foreach($option_fields as $current_option){
		if(isset($current_option['id'])){
			$field_id = $current_option['id'];
			
			//Check if there's no value already set
			//If overwrite is set, replace values always
			if(!isset($options_list[$field_id]) || $overwrite){
				//If there's no default defined, set an empty string
				if(isset($current_option['std']))
					$field_default = $current_option['std'];
				else
					$field_default = '';
							
				$options_list[$field_id] = $field_default;
			}
		}
	}
	update_option($option_name, $options_list);
}


//Create navigation menu for settings page
function cpotheme_custom_wpml_nav(){
	$output = '';
	if(cpotheme_custom_wpml_active()){
		$language_list = cpotheme_custom_wpml_languages();
		
		//Get current language
		if(isset($_GET['lang'])){
			$active_language = trim(htmlentities($_GET['lang']));
		}else{
			$active_language = cpotheme_custom_wpml_default_language();
		}
		
		$output = '';
		$first_link = true;
		foreach($language_list as $current_language){
			$language_code = $current_language['code'];
			$language_name = $current_language['display_name'];
			$language_active = false;
			//Disable link for default language
			if($active_language == $language_code)
				$language_active = true;
			
			if(!$first_link)
				$output .= ' | ';
			
			if($language_active)
				$output .= '<span><b>';
			else
				$output .= '<a href="themes.php?page='.$_GET['page'].'&lang='.$language_code.'">';
			
			$output .= $language_name;
			if(cpotheme_custom_wpml_default_language() == $language_code) 
				$output .= ' ('.__('default', 'cpocore').')';
			
			if($language_active)
				$output .= '</b></span>';
			else
				$output .= '</a>';
				
			$first_link = false;
		}
	return $output;
	}
}

function cpotheme_custom_wpml_active(){
	if(defined('ICL_LANGUAGE_CODE') && defined('ICL_SITEPRESS_VERSION'))
		return true;
	else
		return false;
}

function cpotheme_custom_wpml_languages(){
	if(cpotheme_custom_wpml_active()){
		global $sitepress;
		return $sitepress->get_active_languages();
	}
}

function cpotheme_custom_wpml_default_language(){
	if(cpotheme_custom_wpml_active()){
		global $sitepress;
		return $sitepress->get_default_language();
	}
}

function cpotheme_wpml_settings(){
	return get_option('icl_sitepress_settings');
}

//Check if WPML is present and append language code to option array
//The default language should not be appended
function cpotheme_custom_wpml_option_suffix(){
	$language_code = '';
	if(cpotheme_custom_wpml_active()){
		if(isset($_GET['lang']) && $_GET['lang'] != ''){
			$default_language = cpotheme_custom_wpml_default_language();
			$active_language = trim(htmlentities($_GET['lang']));
			if($active_language != $default_language)
				$language_code = '_'.$active_language;
		}
	}
	return $language_code;
}

//Return the language url for page redirections, empty if default language
function cpotheme_custom_wpml_option_url(){
	$lang_url = '';
	if(cpotheme_custom_wpml_active()){
		if(isset($_GET['lang']) && $_GET['lang'] != ''){
			$default_language = cpotheme_custom_wpml_default_language();
			$active_language = trim(htmlentities($_GET['lang']));
			if($active_language != $default_language)
				$lang_url = '&lang='.$active_language;
		}
	}
	return $lang_url;
}