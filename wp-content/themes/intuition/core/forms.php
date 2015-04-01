<?php 

//Standard text field
function cpotheme_form_divider($name, $value, $args = null){
	$output = '<div class="settings_divider" id="'.$name.'">'.htmlentities(stripslashes($value), ENT_QUOTES, "UTF-8").'</div>';
	return $output;
}

//Standard text field
function cpotheme_form_text($name, $value, $args = null){
	if(isset($args['width'])) $field_width = ' style="width:'.$args['width'].';"'; else $field_width = '';
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';
	$output = '<input type="text" value="'.htmlentities(stripslashes($value), ENT_QUOTES, "UTF-8").'" name="'.$name.'" id="'.$name.'"'.$field_width.$field_placeholder.'/>';
	return $output;
}

//Textarea field
function cpotheme_form_textarea($name, $value, $args = null){	
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<textarea name="'.$name.'" id="'.$name.'"'.$field_placeholder.'>'.htmlentities(stripslashes($value), ENT_QUOTES, "UTF-8").'</textarea>';
	return $output;
}

//Checkbox field
function cpotheme_form_checkbox($name, $value, $args = null){
	$output = '<input type="checkbox" value="1" name="'.$name.'" id="'.$name.'" '.checked($value, '1', false).'/>';
	return $output;
}

//Yes/No radio selection field
function cpotheme_form_yesno($name, $value, $args = null){
	$output = '<input type="radio" name="'.$name.'" id="'.$name.'_yes" value="1"'; 
	if($value == '1') $output .= ' checked';
	$output .= '/> <label for="'.$name.'_yes">'.__('Yes', 'cpotheme').'</label> &nbsp;&nbsp;&nbsp;&nbsp;';
	$output .= '<input type="radio" name="'.$name.'" id="'.$name.'_no" value="0"'; 
	if($value != '1') $output .= ' checked';
	$output .= '/> <label for="'.$name.'_no">'.__('No', 'cpotheme').'</label>';
	return $output;
}

//Dropdown list field
function cpotheme_form_select($name, $value, $list, $args = null){
	$field_class = (isset($args['class']) ? $args['class'] : '');
	$output = '<select class="cpometabox_field_select '.$field_class.'" name="'.$name.'" id="'.$name.'">';
	if(sizeof($list) > 0)
		foreach($list as $list_key => $list_value){
			if(is_array($list_value)){
				$disabled = '';
				if(isset($list_value['type']) && $list_value['type'] == 'separator')
					$disabled = ' disabled';
				$output .= '<option value="'.htmlentities(stripslashes($list_key)).'"'.$disabled;
				$output .= '>'.str_replace('&amp;', '&', htmlentities(stripslashes($list_value['name']), ENT_QUOTES, "UTF-8")).'</option>';
			}else{
				$output .= '<option value="'.htmlentities(stripslashes($list_key)).'" ';
				$output .= selected($value, $list_key, false);
				$output .= '>'.str_replace('&amp;', '&', htmlentities(stripslashes($list_value), ENT_QUOTES, "UTF-8")).'</option>';
			}
		}
	$output .= '</select>';
	return $output;
}

//Image list selection
function cpotheme_form_imagelist($name, $value, $list, $args = null) {    
    $output = '<div id="'.$name.'_wrap">';
    foreach ($list as $list_key => $list_value) {
        $checked = null;
        $selected = null;
        if($list_key == $value) {
            $checked = ' checked="checked"';
            $selected = ' class="selected"';
        }
        $output .= '<label class="form_image_list_item" for="'.$name.'_'.$list_key.'"><img '.$selected.' src="'.$list_value.'" alt="'.$list_key.'"/><br/>';
        $output .= '<input type="radio" name="'.$name.'" id="'.$name.'_'.$list_key.'" value="'.$list_key.'" '.$checked.'/>';        
        $output .= '</label>';        
    }
    $output .= '</div>';
    return $output;
}

//Color Picker field
function cpotheme_form_color($name, $value, $args = null){
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<div id="'.$name.'_wrap">';
	$output .= '<input type="text" class="color" value="'.$value.'" name="'.$name.'" id="'.$name.'"'.$field_placeholder.' maxlength="7"/>';
	$output .= '<div class="colorselector" id="'.$name.'_sample"></div>';
	$output .= '</div>';	
	return $output;
}

//Uploader using Media Library
function cpotheme_form_upload($name, $value, $args = null, $post = null) {
    if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	if(stripslashes($value) != '')
		$image = stripslashes($value);
	elseif(defined('WP_CPODEV'))
		$image = get_template_directory_uri().'/../cpoframework/core/images/noimage.jpg';
	else
		$image = get_template_directory_uri().'/core/images/noimage.jpg';
	
	$output = '<input class="upload_field" type="upload" value="'.stripslashes($value).'" name="'.$name.'" id="'.$name.'-field"/>';
	$output .= '<input class="upload_button" type="button" value="'.__('Upload', 'cpotheme').'" name="'.$name.'" id="'.$name.'-button"/>';
	$output .= '<img class="upload_preview" id="'.$name.'-preview" src="'.$image.'"/>';
	return $output;	    
}

//Font selector field
function cpotheme_form_font($name, $value, $list, $args = null){
	$font_name = ''; 
	if(isset($list[$value])) $font_name = $list[$value];
    $output = cpotheme_form_select($name, $value, $list, array('class'=>'font_field'));
	$output .= '<div class="font_file" id="'.$name.'-file">';
	$output .= "<link href='http://fonts.googleapis.com/css?family=".$value."' rel='stylesheet' type='text/css'>";
	$output .= '</div>';
	$output .= '<div type="text" class="font_preview" id="'.$name.'-preview" style="font-family:\''.$font_name.'\';">'.__('This is a font preview', 'cpotheme').'</div>';
	return $output;
}

//Date picker field
function cpotheme_form_date($name, $value, $args = null){
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';
	if(isset($args['autocomplete'])) $field_autocomplete = ' autocomplete="'.$args['placeholder'].'"'; else $field_autocomplete = ' autocomplete="off"';
	$output = '<input type="text" class="dateselector" value="'.stripslashes($value).'" name="'.$name.'" id="'.$name.'"'.$field_placeholder.$field_autocomplete.'/>';
	?><script>
	jQuery(function(){
		jQuery(".dateselector").datepicker({dateFormat: 'yy-mm-dd'});
	});
	</script><?php
	return $output;
}