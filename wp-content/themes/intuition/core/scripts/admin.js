//Color picker fields
jQuery(document).ready(function(){
        
    //Load the colorpicker value and initializes the colorpicker
    jQuery('.colorselector').each(function() {
        
        var csel = this;
        var parent = jQuery(this).parent();
        var colorfield = jQuery(parent).find('.color');
        jQuery(this).css('backgroundColor', jQuery(colorfield).val());
        
        jQuery(this).ColorPicker({
            color: jQuery(colorfield).val(),
            onShow: function (colpkr){
                jQuery(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr){
                jQuery(colpkr).fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb){            
                jQuery(csel).css('backgroundColor', '#'+hex);            
                jQuery(colorfield).val('#'+hex);
            }
        })
    })   
    
    //Change the field value
    jQuery('.color').keyup(function() {
        var clr = this;
        var parent = jQuery(this).parent();
        var picker = jQuery(parent).find('.colorselector');
        jQuery(picker).css('backgroundColor', jQuery(this).val());
        jQuery(picker).ColorPickerSetColor(jQuery(this).val());        
    });	
});

/* SETTINGS MENU */
jQuery(document).ready(function(){
	
	/* Menu Transitions */
	jQuery('.settingsmenu_element').click(function(event){
		var current_id = event.target.id;
		if(!jQuery('#' + current_id).hasClass('active')){
			jQuery('.cposettings_block').fadeOut(300);
    		jQuery('#' + current_id + '_block').delay(500).fadeIn(300);
			jQuery('.settingsmenu_element').removeClass('active');
			jQuery('#' + current_id).addClass('active');
		}
    });
	
	/* Memorize Current Tab on Saves */
	jQuery('.settingsmenu_element').click(function(event){
		var current_id = jQuery(this).attr('id');
		jQuery('#cpotheme_custom_tab').val(current_id);
    });
	
	/* Save Settings */
	jQuery('.cposettings_submit').click(function(event){
		jQuery('.cposettings_submit').val('...');
    });

});


jQuery(document).ready(function(){	
	// When the Button is clicked...
    jQuery('.upload_button').click(function() {
        var text = jQuery(this).siblings('.upload_field');
 
        tb_show('Upload Image', 'media-upload.php?referer=cpotheme_settings&type=image&TB_iframe=true&post_id=0', false);
 
        window.send_to_editor = function(html){
            var src = jQuery('img', html).attr('src');
            text.attr('value', src).trigger('change');
            tb_remove();
        }
        return false;
    } );
	
	//Change image preview for the upload field
	jQuery('.upload_field').bind('change', function(){
		var url = this.value;
		var preview = jQuery(this).siblings('.upload_preview');
		jQuery(preview).attr('src', url);
	} );
	
	//Change text preview for the font field
	jQuery('.font_field').on('ready keyup change', function(event){ 
		var font = this.value;
		var font_file = jQuery(this).siblings('.font_file');
		var font_preview = jQuery(this).siblings('.font_preview');
		var font_name = font.split("+").join(" ");
		jQuery(font_file).html("<link href='http://fonts.googleapis.com/css?family=" + font + "' rel='stylesheet' type='text/css'>");
		jQuery(font_preview).css('font-family', '\'' + font_name + '\'');
	});
});


// Image list functionality
jQuery(document).ready(function() {
    
    //Change border color when selecting the image
    jQuery('.cposettings_block .form_image_list_item img').click(function() {
        
        //Change other borders
        var parent = jQuery(this).parent().parent();
        jQuery(parent).find('img').each(function() {
            jQuery(this).removeClass('selected');
        })
        
        //Selected image
        jQuery(this).addClass('selected');
        
        
    });   
});