<?php
add_shortcode( 'WRG', 'image_gallery_premium_short_code' );
function image_gallery_premium_short_code( $Id ) {
	ob_start();
	if(!isset($Id['id'])) {
	$Id['id'] = "";
	}
	/**
     * Load Responsive Gallery Settings
     */
    
	$WL_RG_Settings  = unserialize( get_option("WL_IGP_Settings") );
	if(count($WL_RG_Settings)) {
        $WL_Hover_Animation     = $WL_RG_Settings['WL_Hover_Animation'];
        $WL_Gallery_Layout      = $WL_RG_Settings['WL_Gallery_Layout'];
        $WL_Hover_Color         = $WL_RG_Settings['WL_Hover_Color'];
        $WL_Hover_Color_Opacity = 1;
        $WL_Font_Style          = $WL_RG_Settings['WL_Font_Style'];
        $WL_Image_View_Icon     = $WL_RG_Settings['WL_Image_View_Icon'];
		$WL_Gallery_Title       =  $WL_RG_Settings['WL_Gallery_Title'];
		$WL_Hover_Color_Opacity = $WL_RG_Settings['WL_Hover_Color_Opacity'];
    } else {
		$WL_Hover_Color_Opacity = 1;
		$WL_Hover_Animation     = "fade";
        $WL_Gallery_Layout      = "col-md-6";
        $WL_Hover_Color         = "#74C9BE";
        $WL_Font_Style          = "Arial";
        $WL_Image_View_Icon     = "fa-picture-o";
		$WL_Gallery_Title       = "yes";
		$WL_Hover_Color_Opacity = "1";
    }
	$RGB = RPGhex2rgbWeblizar($WL_Hover_Color);
    $HoverColorRGB = implode(", ", $RGB);
	?>
    <script>
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>

    <style>
	.modal-backdrop.in {
		display:none !important;
	}
    .b-link-fade .b-wrapper, .b-link-fade .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-flow .b-wrapper, .b-link-flow .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-stroke .b-top-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-stroke .b-bottom-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }

    .b-link-box .b-top-line{

        border: 16px solid <?php echo $WL_Hover_Color; ?>;
    }
    .b-link-box .b-bottom-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-stripe .b-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-apart-horisontal .b-top-line, .b-link-apart-horisontal .b-top-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-apart-horisontal .b-bottom-line, .b-link-apart-horisontal .b-bottom-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-apart-vertical .b-top-line, .b-link-apart-vertical .b-top-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-apart-vertical .b-bottom-line, .b-link-apart-vertical .b-bottom-line-up{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }
    .b-link-diagonal .b-line{
        background: rgba(<?php echo $HoverColorRGB; ?>, <?php echo $WL_Hover_Color_Opacity; ?>);;
    }

    .b-wrapper{
        font-family:<?php echo str_ireplace("+", " ", $WL_Font_Style); ?>; // real name pass here
    }
    </style>

    <?php
    /**
     * Load All Image Gallery Custom Post Type
     */
    $IG_CPT_Name = "responsive-gallery";
    $IG_Taxonomy_Name = "category";
	$all_posts = wp_count_posts( 'responsive-gallery')->publish;
	$AllGalleries = array( 'p' => $Id['id'], 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC','posts_per_page' =>$all_posts);

    $loop = new WP_Query( $AllGalleries );
    ?>
    <div id="gallery1" class="gal-container">
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <!--get the post id-->
        <?php $post_id = $Id['id']; ?>
		<div id="gal-container-<?php echo $post_id; ?>" style="display: block; overflow:hidden;">
			<?php if($WL_Gallery_Title==""){ $WL_Gallery_Title == "yes"; } if($WL_Gallery_Title == "yes") { ?>
			<!-- gallery title-->
			<div class="rpg-gal-title" >
				<?php echo get_the_title($post_id); ?>
			</div>
			<?php } ?>
			<!-- gallery photos-->
			<div style="">
				<?php

				/**
				 * Get All Photos from Gallery Post Meta
				 */
				$rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
				//print_r(($rpg_all_photos_details)); echo "<br><br>";
				$TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
				$i = 1;

				foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
					$name = $rpg_single_photos_detail['rpg_image_label'];
					$url = $rpg_single_photos_detail['rpg_image_url'];
					?>
					<div class="<?php echo $WL_Gallery_Layout; ?> col-sm-6 wl-gallery" >
						<div class="b-link-<?php echo $WL_Hover_Animation; ?> b-animate-go">
							<img src="<?php echo $url; ?>" class="gall-img-responsive">
							<div class="b-wrapper">
								<h2 class="b-from-left b-animate b-delay03"><?php echo $name; ?></h2>
								<p class="b-from-right b-animate b-delay03">
									<a href="<?php echo $url; ?>" title="<?php echo $name; ?>">
										<i class="fa <?php echo $WL_Image_View_Icon; ?> fa-2x"></i>
									</a>
								</p>
							</div>
						</div>
					</div>

					<?php if($WL_Gallery_Layout=="col-md-4")
							{
								 if($i%3==0){
								?>
									<div class="clearfix"></div>
									<?php
								}
							}
							else{
							 if($i%2==0){
								?>
									<div class="clearfix"></div>
									<?php
								}
							}
					
					$i++;
				}
				?>
			</div>
		</div>
    <?php endwhile; ?>
    </div>
   <?php wp_reset_query(); 
    return ob_get_clean();
}
?>