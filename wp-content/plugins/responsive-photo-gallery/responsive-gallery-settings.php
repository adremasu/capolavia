<?php
    /**
     * Load Saved Image Gallery settings
     */
    $WL_RG_Settings  = unserialize( get_option("WL_IGP_Settings") );
    if(count($WL_RG_Settings)) {
        $WL_Hover_Animation     = $WL_RG_Settings['WL_Hover_Animation'];
        $WL_Gallery_Layout      = $WL_RG_Settings['WL_Gallery_Layout'];
        $WL_Hover_Color         = $WL_RG_Settings['WL_Hover_Color'];
        $WL_Font_Style          = $WL_RG_Settings['WL_Font_Style'];
        $WL_Image_View_Icon     = $WL_RG_Settings['WL_Image_View_Icon'];
		$WL_Gallery_Title       =  $WL_RG_Settings['WL_Gallery_Title'];
		$WL_Hover_Color_Opacity = $WL_RG_Settings['WL_Hover_Color_Opacity'];
		
    } else {
        $WL_Hover_Animation     = "fade";
        $WL_Gallery_Layout      = "col-md-6";
        $WL_Hover_Color         = "#74C9BE";
        $WL_Font_Style          = "Arial";
        $WL_Image_View_Icon     = "fa-picture-o";
		$WL_Gallery_Title		= "yes";
		$$WL_Hover_Color_Opacity = "1";
		
    }
    //print_r($WL_RG_Settings);
?>

<h2><?php _e("Responsive Gallery Settings", WEBLIZAR_RPG_TEXT_DOMAIN); ?></h2>
<form action="?post_type=responsive-gallery&page=image-gallery-settings" method="post">
    <input type="hidden" id="wl_action" name="wl_action" value="wl-save-settings">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label><?php _e("Image Hover Animation", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select name="wl-hover-animation" id="wl-hover-animation">
                        <optgroup label="Select Animation">
                            <option value="fade" <?php if($WL_Hover_Animation == 'fade') echo "selected=selected"; ?>><?php _e("Fade", WEBLIZAR_RPG_TEXT_DOMAIN); ?></option>
                            <!--<option value="stroke" <?php /*if($WL_Hover_Animation == 'stroke') echo "selected=selected"; */?>>Stroke</option>-->
                        </optgroup>
                    </select>
                    <p class="description"><strong><?php _e("Choose an animation effect apply on mouse hover.", WEBLIZAR_RPG_TEXT_DOMAIN); ?></strong> (Get More 6 animation effect in plugin, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label><?php _e("Gallery Layout", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select name="wl-gallery-layout" id="wl-gallery-layout">
                        <optgroup label="Select Gallery Layout">
                            <option value="col-md-6" <?php if($WL_Gallery_Layout == 'col-md-6') echo "selected=selected"; ?>><?php _e("Two Column", WEBLIZAR_RPG_TEXT_DOMAIN); ?></option>
                            <option value="col-md-4" <?php if($WL_Gallery_Layout == 'col-md-4') echo "selected=selected"; ?>><?php _e("Three Column", WEBLIZAR_RPG_TEXT_DOMAIN); ?></option>
                        </optgroup>
                    </select>
                    <p class="description"><strong><?php _e("Choose a column layout for image gallery.", WEBLIZAR_RPG_TEXT_DOMAIN); ?></strong> (Get More Column Layout in plugin, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>
			
			<tr>
                <th scope="row"><label><?php _e("Display Gallery Title", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-gallery-title" id="wl-gallery-title" value="yes" <?php if($WL_Gallery_Title == 'yes' ) { echo "checked"; } ?>> Yes
                    <input type="radio" name="wl-gallery-title" id="wl-gallery-title" value="no" <?php if($WL_Gallery_Title == 'no' ) { echo "checked"; } ?>> No

                    <p class="description"><?php _e("Select yes if you want show gallery title .", WEBLIZAR_RPG_TEXT_DOMAIN); ?> </p>
                </td>
            </tr>
			
            <tr>
                <th scope="row"><label><?php _e("Hover Color", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-hover-color" id="wl-hover-color" value="#74C9BE" <?php if($WL_Hover_Color == '#74C9BE' ) { echo "checked"; } ?>> <span style="color: #74C9BE; font-size: large; font-weight: bolder;"><?php _e("Color 1", WEBLIZAR_RPG_TEXT_DOMAIN); ?></span>
                    <input type="radio" name="wl-hover-color" id="wl-hover-color" value="#31A3DD" <?php if($WL_Hover_Color == '#31A3DD' ) { echo "checked"; } ?>> <span style="color: #31A3DD; font-size: large; font-weight: bolder;"><?php _e("Color 2", WEBLIZAR_RPG_TEXT_DOMAIN); ?></span>

                    <p class="description"><strong><?php _e("Choose a color apply on mouse hover.", WEBLIZAR_RPG_TEXT_DOMAIN); ?> </strong> (Get Unlimited Color Scheme for gallery, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>
			
			<tr>
                <th scope="row"><label><?php _e("Hover Color Opacity", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-hover-color-opacity" id="wl-hover-color-opacity" value="0.5" <?php if($WL_Hover_Color_Opacity == '0.5' ) { echo "checked"; } ?>> Yes
                    <input type="radio" name="wl-hover-color-opacity" id="wl-hover-color-opacity" value="1" <?php if($WL_Hover_Color_Opacity == '1' ) { echo "checked"; } ?>> No

                    <p class="description"><strong><?php _e("Select yes if you want show gallery title .", WEBLIZAR_RPG_TEXT_DOMAIN); ?>  </strong> (Get More 10 opacity effect in plugin, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>	
            <tr>
                <th scope="row"><label><?php _e("Image View Icon", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-image-view-icon" id="wl-image-view-icon" value="fa-picture-o"  <?php if($WL_Image_View_Icon == 'fa-picture-o' ) { echo "checked"; } ?>> <i class="fa fa-picture-o fa-3x"></i>
                    <input type="radio" name="wl-image-view-icon" id="wl-image-view-icon" value="fa-camera" <?php if($WL_Image_View_Icon == 'fa-camera' ) { echo "checked"; } ?>> <i class="fa fa-camera fa-3x"></i>
                    <input type="radio" name="wl-image-view-icon" id="wl-image-view-icon" value="fa-camera-retro" <?php if($WL_Image_View_Icon == 'fa-camera-retro' ) { echo "checked"; } ?>> <i class="fa fa-camera-retro fa-3x"></i>
                    <p class="description"><strong><?php _e("Choose image view icon.", WEBLIZAR_RPG_TEXT_DOMAIN); ?>  </strong> (Get Unlimited Font Awesome Icon in plugin, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label><?php _e("Caption Font Style", WEBLIZAR_RPG_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select  name="wl-font-style" class="standard-dropdown" id="wl-font-style">
                        <optgroup label="Default Fonts">
                            <option value="Arial"           <?php if($WL_Font_Style == 'Arial' ) { echo "selected"; } ?>>Arial</option>
                            <option value="Arial Black"    <?php if($WL_Font_Style == 'Arial Black' ) { echo "selected"; } ?>>Arial Black</option>
                            <option value="Courier New"     <?php if($WL_Font_Style == 'Courier New' ) { echo "selected"; } ?>>Courier New</option>
                            <option value="Georgia"         <?php if($WL_Font_Style == 'Georgia' ) { echo "selected"; } ?>>Georgia</option>
                            <option value="Grande"          <?php if($WL_Font_Style == 'Grande' ) { echo "selected"; } ?>>Grande</option>
                            <option value="Helvetica" <?php if($WL_Font_Style == 'Helvetica' ) { echo "selected"; } ?>>Helvetica Neue</option>
                            <option value="Impact"         <?php if($WL_Font_Style == 'Impact' ) { echo "selected"; } ?>>Impact</option>
                            <option value="Lucida"         <?php if($WL_Font_Style == 'Lucida' ) { echo "selected"; } ?>>Lucida</option>
                            <option value="Lucida Grande"         <?php if($WL_Font_Style == 'Lucida Grande' ) { echo "selected"; } ?>>Lucida Grande</option>
                            <option value="_OpenSansBold"   <?php if($WL_Font_Style == '_OpenSansBold' ) { echo "selected"; } ?>>OpenSansBold</option>
                            <option value="Palatino Linotype"       <?php if($WL_Font_Style == 'Palatino Linotype' ) { echo "selected"; } ?>>Palatino</option>
                            <option value="Sans"           <?php if($WL_Font_Style == 'Sans' ) { echo "selected"; } ?>>Sans</option>
                            <option value="sans-serif"           <?php if($WL_Font_Style == 'sans-serif' ) { echo "selected"; } ?>>Sans-Serif</option>
                            <option value="Tahoma"         <?php if($WL_Font_Style == 'Tahoma' ) { echo "selected"; } ?>>Tahoma</option>
                            <option value="Times New Roman"          <?php if($WL_Font_Style == 'Times New Roman' ) { echo "selected"; } ?>>Times New Roman</option>
                            <option value="Trebuchet"      <?php if($WL_Font_Style == 'Trebuchet' ) { echo "selected"; } ?>>Trebuchet</option>
                            <option value="Verdana"        <?php if($WL_Font_Style == 'Verdana' ) { echo "selected"; } ?>>Verdana</option>
                        </optgroup>
                    </select>
                    <p class="description"><strong><?php _e("Choose a caption font style.", WEBLIZAR_RPG_TEXT_DOMAIN); ?> </strong> (Get 500+ Google fonts for gallery, View <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new">detail</a> )</p>
                </td>
            </tr>



        </tbody>
    </table>
    <p class="submit">
        <input type="submit" value="<?php _e("Save Changes", WEBLIZAR_RPG_TEXT_DOMAIN); ?>" class="button button-primary" id="submit" name="submit">
    </p>
</form>

<div class="plan-name" style="margin-top:40px;">
	<h2 style="border-top: 5px solid #f9f9f9;padding-top: 20px;">Responsive Photo Gallery Pro</h2>
</div>
<div class="purchase_btn_div" style="margin-top:20px;">
	<a href="http://demo.weblizar.com/responsive-photo-gallery-pro/" target="_new" class="button button-hero">Try Live Demo</a>
	<a href="http://demo.weblizar.com/responsive-photo-gallery-admin-demo/" target="_new" class="button button-hero">Try Admin Demo</a>
	<a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new" class="button button-primary button-hero">Upgrade To Pro</a>
</div>
<div class="plan-name" style="margin-top:40px;text-align: left;">
        <h2 style="font-weight: bold;
font-size: 36px;
padding-top: 30px;
padding-bottom: 10px;">Responsive photo Gallery Pro Screenshots</h2>
<h6 style="
font-size: 22px;
padding-top: 10px;
padding-bottom: 10px;">Get All Lightboxs in Responsive Photo Gallery Pro only in 10$</h6>
    </div>
	<!-- SLIDER-INTRO-->
				<!--===============================================================-->
				<div class="col-sm-6 col-xs-4" style="width:49%;display:inline-block">
					<h2 style="font-weight: bold;font-size: 26px;padding-top: 20px;padding-bottom: 20px; text-align:center">Nivo Lightbox</h2>
					<img class="img-responsive" style="border: 1px solid #e3e3e3;
background: #f7f7f7;padding:10px;width:100%" src="http://weblizar.com/wp-content/themes/home-theme/images/lightbox/nivo.jpg" alt=""/>
				</div>
				<div class="col-sm-6 col-xs-4" style="width:49%;display:inline-block" >
					<h2 style="font-weight: bold;font-size: 26px;padding-top: 20px;padding-bottom: 20px; text-align:center">Photobox</h2>
					<img style="border: 1px solid #e3e3e3;
background: #f7f7f7;padding:10px;width:100%" class="img-responsive" src="http://weblizar.com/wp-content/themes/home-theme/images/lightbox/photobox.jpg" alt=""/>
				</div>
				<div class="col-sm-6 col-xs-4" style="width:49%;display:inline-block">
					<h2 style="font-weight: bold;font-size: 26px;padding-top: 20px;padding-bottom: 20px; text-align:center">Pretty Photo</h2>
					<img style="border: 1px solid #e3e3e3;
background: #f7f7f7;padding:10px;width:100%" class="img-responsive" src="http://weblizar.com/wp-content/themes/home-theme/images/lightbox/prettyphoto.jpg" alt=""/>
				</div>
				
				<div class="col-sm-6 col-xs-4" style="width:49%;display:inline-block">
					<h2 style="font-weight: bold;font-size: 26px;padding-top: 20px;padding-bottom: 20px; text-align:center">Swipe Box</h2>
					<img style="border: 1px solid #e3e3e3;
background: #f7f7f7;padding:10px;width:100%" class="img-responsive" src="http://weblizar.com/wp-content/themes/home-theme/images/lightbox/swipebox.jpg" alt=""/>
				</div>
		
					
	
	

<?php
//print_r($_POST);
if(isset($_POST['wl_action'])) {
    $Action = $_POST['wl_action'];
    //save settings
    if($Action == "wl-save-settings") {
        //print_r($_POST);

        $WL_Hover_Animation     = $_POST['wl-hover-animation'];
        $WL_Gallery_Layout      = $_POST['wl-gallery-layout'];
        $WL_Hover_Color         = $_POST['wl-hover-color'];
        $WL_Font_Style          = $_POST['wl-font-style'];
        $WL_Image_View_Icon     = $_POST['wl-image-view-icon'];
		$WL_Gallery_Title		= $_POST['wl-gallery-title'];
		$WL_Hover_Color_Opacity = $_POST['wl-hover-color-opacity'];

        $SettingsArray = serialize( array(
            'WL_Hover_Animation' => $WL_Hover_Animation,
            'WL_Gallery_Layout' => $WL_Gallery_Layout,
            'WL_Hover_Color' => $WL_Hover_Color,
            'WL_Hover_Color_Opacity' => $WL_Hover_Color_Opacity,
            'WL_Font_Style' => $WL_Font_Style,
            'WL_Image_View_Icon' => $WL_Image_View_Icon,
			'WL_Gallery_Title' =>	$WL_Gallery_Title		
        ) );

        update_option("WL_IGP_Settings", $SettingsArray);
        echo "<script>location.href = location.href;</script>";
    }
}
