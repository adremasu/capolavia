jQuery(document).ready(function($){
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('.accesspress_ray_fav_icon .button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        $("#"+id).val(attachment.url);
        $('#accesspress_ray_media_image').fadeIn();
        $("#accesspress_ray_show_image").attr('src',attachment.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

  $('#accesspress_ray_fav_icon_remove').click(function(){
  	$('#accesspress_ray_media_image').hide();
  	$('#accesspress_ray_media_image img').attr('src','');
  	$('#accesspress_ray_media_upload').val('');
  });

  });