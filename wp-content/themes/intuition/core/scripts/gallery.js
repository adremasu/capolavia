jQuery(document).ready(function() {

	//Update gallery images on closing media popup
	function cpotheme_gallery_update() {
		var parent = jQuery('form#post input#post_ID').prop('value');	
		var data = {
			action: 'refresh_metabox',
			parent: parent
		};	
		jQuery.post(ajaxurl, data, function(response){
			var obj;
			try{
				obj = jQuery.parseJSON(response); 
			}catch(e){}
			
			//Success
			if(typeof obj !== 'undefined' && obj.success === true) {
				jQuery('#cpotheme-gallery-imagelist').replaceWith(obj.gallery);
				//cpotheme_gallery_remove();
			}
		});	
	}
	
	//Click update button
	jQuery('#cpotheme-gallery-update').on('click', function(event){
		event.preventDefault();
		cpotheme_gallery_update();
	});

	
	//Open and close WP media editor
	if(typeof wp !== 'undefined' && wp.media && wp.media.editor){
		// Open media library
		jQuery('#cpotheme-gallery-open').click(function() {
			wp.media.editor.open();
			return false;
		});

		//Update image on window close
		wp.media.view.Modal.prototype.on('close', function(){
			cpotheme_gallery_update();
		});
	}
 });
