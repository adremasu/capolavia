<?php

//Generate settings
add_action('customize_register', 'cpotheme_customizer');
function cpotheme_customizer($customize){
	
	//Add panels to the customizer
	$settings = cpotheme_metadata_panels();
	foreach($settings as $setting_id => $setting_data){
		$customize->add_panel($setting_id, $setting_data);
		
	}
	
	//Add sections to the customizer
	$settings = cpotheme_metadata_sections();
	foreach($settings as $setting_id => $setting_data){
		$customize->add_section($setting_id, $setting_data);
	}
	
	//Add settings & controls
	$settings = cpotheme_metadata_customizer();
	foreach($settings as $setting_id => $setting_data){
		$default = isset($setting_data['default']) ? $setting_data['default'] : '';
		
		$setting_args = array(
		'type' => 'option',
		'default' => $default,
		'capability' => 'edit_theme_options',
		'transport' => 'refresh');
		if(isset($setting_data['sanitize']) && $setting_data['sanitize'] != ''){
			$setting_args['sanitize_callback'] = $setting_data['sanitize'];
		}
		
		//Add setting to the customizer
		$customize->add_setting('cpotheme_settings['.$setting_id.']', $setting_args); 
		
		//Define control metadata
		$args = $setting_data;
		$args['settings'] = 'cpotheme_settings['.$setting_id.']';
		$args['priority'] = 10;
		if(!isset($setting_data['type'])) $setting_data['type'] = 'text';
		
		switch($setting_data['type']){
			case 'text': 
			case 'textarea': 
			case 'checkbox': 
			case 'select': 
			$customize->add_control('cpotheme_'.$setting_id, $args); break;
			case 'label': 
			$customize->add_control(new CPO_Customize_Label_Control($customize, 'cpotheme_'.$setting_id, $args)); break;
			case 'color': 
			$customize->add_control(new WP_Customize_Color_Control($customize, 'cpotheme_'.$setting_id, $args)); break;
			case 'image': 
			$customize->add_control(new WP_Customize_Image_Control($customize, 'cpotheme_'.$setting_id, $args)); break;
			case 'collection': 
			$customize->add_control(new CPO_Customize_Collection_Control($customize, 'cpotheme_'.$setting_id, $args)); break;
		}		
	}
}