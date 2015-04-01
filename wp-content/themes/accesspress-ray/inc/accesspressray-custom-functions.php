<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package AccessPress Ray
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function accesspress_ray_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'accesspress_ray_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function accesspress_ray_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'accesspress_ray_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function accesspress_ray_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'accesspress_ray' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'accesspress_ray_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function accesspress_ray_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'accesspress_ray_setup_author' );

global $accesspress_ray_options;
$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function accesspress_ray_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'accesspress_ray' ),
		'id'            => 'left-sidebar',
		'description'   => __( 'Display items in the Left Sidebar of the inner pages', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'accesspress_ray' ),
		'id'            => 'right-sidebar',
		'description'   => __( 'Display items in the Right Sidebar of the inner pages', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'accesspress_ray' ),
		'id'            => 'shop-sidebar',
		'description'   => __( 'Display items in the Right Sidebar of the inner pages for Woocommerce', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Featured Left Widget', 'accesspress_ray' ),
		'id'            => 'textblock-1',
		'description'   => __( 'Display items in the left of Featured Bar', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Featured Middle Widget', 'accesspress_ray' ),
		'id'            => 'textblock-2',
		'description'   => __( 'Display items in the middle of Featured Bar', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Featured Right Widget', 'accesspress_ray' ),
		'id'            => 'textblock-3',
		'description'   => __( 'Display items in the right of Featured Bar', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer One', 'accesspress_ray' ),
		'id'            => 'footer-1',
		'description'   => __( 'Display items in First Footer Area', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Two', 'accesspress_ray' ),
		'id'            => 'footer-2',
		'description'   => __( 'Display items in Second Footer Area', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Three', 'accesspress_ray' ),
		'id'            => 'footer-3',
		'description'   => __( 'Display items in Third Footer Area', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Four', 'accesspress_ray' ),
		'id'            => 'footer-4',
		'description'   => __( 'Display items in Fourth Footer Area', 'accesspress_ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'accesspress_ray_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function accesspress_ray_scripts() {
	global $accesspress_ray_options;
	$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
	$query_args = array(
		'family' => 'Open+Sans:400,400italic,300italic,300,600,600italic|Lato:400,100,300,700|Josefin+Slab:400,100,100italic,300,300italic,400italic,600,600italic,700,700italic|Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700italic,700,900,900italic',
	);
	wp_enqueue_style( 'font-css', get_template_directory_uri() . '/css/fonts.css' );
	wp_enqueue_style( 'google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'fancybox-css', get_template_directory_uri() . '/css/nivo-lightbox.css' );
	wp_enqueue_style( 'bx-slider-style', get_template_directory_uri() . '/css/jquery.bxslider.css' );
	wp_enqueue_style( 'accesspress_ray-style', get_stylesheet_uri() );

	wp_enqueue_script('accesspress_parallax-googlemap', '//maps.googleapis.com/maps/api/js?v=3.exp?sensor=false', array('jquery'), '3.0', false);
	wp_enqueue_script( 'bx-slider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), '4.1', true );
	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/nivo-lightbox.min.js', array('jquery'), '2.1', true );
	wp_enqueue_script( 'jquery-actual', get_template_directory_uri() . '/js/jquery.actual.min.js', array('jquery'), '1.0.16', true );
	wp_enqueue_script( 'accesspress_ray-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.1', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

/**
* Loads up responsive css if it is not disabled
*/
	if ( $accesspress_ray_settings[ 'responsive_design' ] == 0 ) {	
		wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'accesspress_ray_scripts' );

/**
* Loads up favicon
*/
	function accesspress_ray_add_favicon(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		
		if( !empty($accesspress_ray_settings[ 'media_upload' ])){
		echo '<link rel="shortcut icon" type="image/png" href="'. $accesspress_ray_settings[ 'media_upload' ].'"/>';
		}
	}
	add_action('wp_head', 'accesspress_ray_add_favicon');


	function accesspress_ray_social_cb(){ 
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		?>
		<div class="socials">
		<?php if(!empty($accesspress_ray_settings['accesspress_ray_facebook'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_facebook']); ?>" class="facebook" title="Facebook" target="_blank"><span class="font-icon-social-facebook"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_twitter'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_twitter']); ?>" class="twitter" title="Twitter" target="_blank"><span class="font-icon-social-twitter"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_gplus'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_gplus']); ?>" class="gplus" title="Google Plus" target="_blank"><span class="font-icon-social-google-plus"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_youtube'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_youtube']); ?>" class="youtube" title="Youtube" target="_blank"><span class="font-icon-social-youtube"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_pinterest'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_pinterest']); ?>" class="pinterest" title="Pinterest" target="_blank"><span class="font-icon-social-pinterest"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_linkedin'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_linkedin']); ?>" class="linkedin" title="Linkedin" target="_blank"><span class="font-icon-social-linkedin"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_flickr'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_flickr']); ?>" class="flickr" title="Flickr" target="_blank"><span class="font-icon-social-flickr"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_vimeo'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_vimeo']); ?>" class="vimeo" title="Vimeo" target="_blank"><span class="font-icon-social-vimeo"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_stumbleupon'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_stumbleupon']); ?>" class="stumbleupon" title="Stumbleupon" target="_blank"><span class="font-icon-social-stumbleupon"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_instagram'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_instagram']); ?>" class="instagram" title="instagram" target="_blank"><span class="fa fa-instagram"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_sound_cloud'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_sound_cloud']); ?>" class="sound-cloud" title="sound-cloud" target="_blank"><span class="font-icon-social-soundcloud"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_skype'])){ ?>
		<a href="<?php echo "skype:".esc_attr($accesspress_ray_settings['accesspress_ray_skype']); ?>" class="skype" title="Skype"><span class="font-icon-social-skype"></span></a>
		<?php } ?>

		<?php if(!empty($accesspress_ray_settings['accesspress_ray_rss'])){ ?>
		<a href="<?php echo esc_url($accesspress_ray_settings['accesspress_ray_rss']); ?>" class="rss" title="RSS" target="_blank"><span class="font-icon-rss"></span></a>
		<?php } ?>
		</div>
	<?php } 

	add_action( 'accesspress_ray_social_links', 'accesspress_ray_social_cb', 10 );	


	function accesspress_ray_featured_text_cb(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		if(!empty($accesspress_ray_settings['featured_text'])){
		echo '<div class="header-text">'.wpautop($accesspress_ray_settings['featured_text']).'</div>';
		}
	}

	add_action('accesspress_ray_featured_text','accesspress_ray_featured_text_cb', 10);

	function accesspress_ray_logo_alignment_cb(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		if($accesspress_ray_settings['logo_alignment'] =="Left"){
			$accesspress_ray_alignment_class="logo-left";
		}elseif($accesspress_ray_settings['logo_alignment'] == "Center"){
			$accesspress_ray_alignment_class="logo-center";
		}else{
			$accesspress_ray_alignment_class="";
		}
		echo $accesspress_ray_alignment_class;
	}

	add_action('accesspress_ray_logo_alignment','accesspress_ray_logo_alignment_cb', 10);


	function accesspress_ray_excerpt( $accesspress_ray_content , $accesspress_ray_letter_count ){
		$accesspress_ray_striped_content = strip_shortcodes($accesspress_ray_content);
		$accesspress_ray_striped_content = strip_tags($accesspress_ray_striped_content);
		$accesspress_ray_excerpt = mb_substr($accesspress_ray_striped_content, 0, $accesspress_ray_letter_count );
		if($accesspress_ray_striped_content > $accesspress_ray_excerpt){
			$accesspress_ray_excerpt .= "...";
		}
		return $accesspress_ray_excerpt;
	}


	function accesspress_ray_bxslidercb(){
		global $accesspress_ray_options, $post;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
        ($accesspress_ray_settings['slider_show_pager'] == 'yes1' || empty($accesspress_ray_settings['slider_show_pager'])) ? ($a='true') : ($a='false');
        ($accesspress_ray_settings['slider_show_controls'] == 'yes2' || empty($accesspress_ray_settings['slider_show_controls'])) ? ($b='true') : ($b='false');
        ($accesspress_ray_settings['slider_mode'] == 'slide' || empty($accesspress_ray_settings['slider_mode'])) ? ($c='horizontal') : ($c='fade');
        ($accesspress_ray_settings['slider_auto'] == 'yes3' || empty($accesspress_ray_settings['slider_auto'])) ? ($d='true') : ($d='false');
		empty($accesspress_ray_settings['slider_pause']) ? ($e ='5000') : ($e = $accesspress_ray_settings['slider_pause']);

		if( $accesspress_ray_settings['show_slider'] !='no') { 
		if((isset($accesspress_ray_settings['slider1']) && !empty($accesspress_ray_settings['slider1'])) 
			|| (isset($accesspress_ray_settings['slider2']) && !empty($accesspress_ray_settings['slider2'])) 
			|| (isset($accesspress_ray_settings['slider3']) && !empty($accesspress_ray_settings['slider3']))
			|| (isset($accesspress_ray_settings['slider4']) && !empty($accesspress_ray_settings['slider4'])) 
			|| (isset($accesspress_ray_settings['slider_cat']) && !empty($accesspress_ray_settings['slider_cat']))
		){

		?>
 		<script type="text/javascript">
            jQuery(function(){
				jQuery('.bx-slider').bxSlider({
					adaptiveHeight:true,
					pager:<?php echo $a; ?>,
					controls:<?php echo $b; ?>,
					mode:'<?php echo $c; ?>',
					auto :<?php echo $d; ?>,
					pause: '<?php echo $e; ?>',
					<?php if($accesspress_ray_settings['slider_speed']) {?>
					speed:'<?php echo $accesspress_ray_settings['slider_speed']; ?>'
					<?php } ?>
				});
			});
        </script>
        <?php 

            if($accesspress_ray_settings['slider_options'] == 'single_post_slider'){
            	if(!empty($accesspress_ray_settings['slider1']) || !empty($accesspress_ray_settings['slider2']) || !empty($accesspress_ray_settings['slider3']) || !empty($accesspress_ray_settings['slider4'])){
            		$sliders = array($accesspress_ray_settings['slider1'],$accesspress_ray_settings['slider2'],$accesspress_ray_settings['slider3'],$accesspress_ray_settings['slider4']);
					$remove = array(0);
				    $sliders = array_diff($sliders, $remove);  ?>

				    <div class="bx-slider">
				    <?php
				    foreach ($sliders as $slider){
					$args = array (
					'p' => $slider
					);

						$loop = new WP_query( $args );
						if($loop->have_posts()){ 
						while($loop->have_posts()) : $loop-> the_post(); 
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
						?>
						<div class="slides">
							
								<img src="<?php echo $image[0]; ?>">
								
								<?php if($accesspress_ray_settings['slider_caption']=='yes4'):?>
								<div class="slider-caption">
									<div class="ak-container">
										<h1 class="caption-title"><?php the_title();?></h1><br />
										<h2 class="caption-description"><?php echo get_the_content();?></h2><br />
									</div>
								</div>
								<?php  endif; ?>
				
			            </div>
						<?php endwhile;
						}
					} ?>
				    </div>
            	<?php
            	}

            }elseif ($accesspress_ray_settings['slider_options'] == 'cat_post_slider') { ?>
            	<div class="bx-slider">
				<?php
				$loop = new WP_Query(array(
						'cat' => $accesspress_ray_settings['slider_cat'],
						'posts_per_page' => -1
					));
					if($loop->have_posts()){ 
					while($loop->have_posts()) : $loop-> the_post(); 
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); 
					?>
					<div class="slides">
							
							<img src="<?php echo $image[0]; ?>">
								
							<?php if($accesspress_ray_settings['slider_caption']=='yes4'):?>
							<div class="slider-caption">
								<div class="ak-container">
									<h1 class="caption-title"><?php the_title();?></h1><br />
									<h2 class="caption-description"><?php echo get_the_content();?></h2><br />
								</div>
							</div>
							<?php  endif; ?>
				
			        </div>
					<?php endwhile;
					} ?>
				</div>
            <?php
        	}
        	}else{ ?>

        	<script type="text/javascript">
	            jQuery(function(){
					jQuery('.bx-slider').bxSlider({
						adaptiveHeight:true,
						pager:<?php echo $a; ?>,
						controls:<?php echo $b; ?>,
						mode:'<?php echo $c; ?>',
						auto :<?php echo $d; ?>,
						pause: '<?php echo $e; ?>',
						<?php if($accesspress_ray_settings['slider_speed']) {?>
						speed:'<?php echo $accesspress_ray_settings['slider_speed']; ?>'
						<?php } ?>
					});
				});
	        </script>
            <div class="bx-slider">
				<div class="slides">
					<img src="<?php echo get_template_directory_uri(); ?>/images/demo/slider1.jpg" alt="slider1">
                    <?php if($accesspress_ray_settings['slider_caption']=='yes4' || empty($accesspress_ray_settings['slider_caption'])):?>
					<div class="slider-caption">
						<div class="ak-container">
							<h1 class="caption-title">AccessPress Ray</h1><br />
							<h2 class="caption-description">Responsive, multi-purpose, business wordpress theme, perfect for any business on any device.</h2>
							<br>
							<a href="#">Read More</a>
						</div>
					</div>
                    <?php  endif; ?>
				</div>
						
				<div class="slides">
					<img src="<?php echo get_template_directory_uri(); ?>/images/demo/slider2.jpg" alt="slider2">
                    <?php if($accesspress_ray_settings['slider_caption']=='yes4' || empty($accesspress_ray_settings['slider_caption'])):?>
					<div class="slider-caption">
						<div class="ak-container">
							<h1 class="caption-title">Easy Customization</h1>
							<h2 class="caption-description">A theme with powerful theme options for customization. Style your wordpress and see changes live!</h2>
							<br>
							<a href="#">Read More</a>
						</div>
					</div>
                    <?php  endif; ?>
				</div>
			</div>
		<?php
		}
	}
	}

   add_action('accesspress_ray_bxslider','accesspress_ray_bxslidercb', 10);

   function accesspress_ray_layout_class($classes){
   	global $post;
   		if( is_404() || is_home() || is_front_page()){
		$classes[] = ' ';
		}elseif(is_singular()){
		$post_class = get_post_meta( $post -> ID, 'accesspress_ray_sidebar_layout', true );
		$classes[] = $post_class;
		}else{
		$classes[] = 'right-sidebar';	
		}
		return $classes;
	}

   add_filter( 'body_class', 'accesspress_ray_layout_class' );
   
   function accesspress_ray_web_layout($classes){
    global $accesspress_ray_options, $post;
	$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
    $weblayout = $accesspress_ray_settings['accesspress_ray_webpage_layout'];
    if($weblayout =='Boxed'){
        $classes[]= 'boxed-layout';
    }
    return $classes;
   }
   
   add_filter( 'body_class', 'accesspress_ray_web_layout' );

   function accesspress_ray_post_count_queries( $query ) {
	  if (!is_admin() && $query->is_main_query()){
	    if(is_home()){
	       $query->set('posts_per_page', 1);
	    }
	  }
	}
	//add_action( 'pre_get_posts', 'accesspress_ray_post_count_queries' );

	function accesspress_ray_custom_css(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		echo '<style type="text/css">';
			echo $accesspress_ray_settings['custom_css'];
		echo '</style>';
	}

	add_action('wp_head','accesspress_ray_custom_css');

	function accesspress_ray_custom_code(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
			echo $accesspress_ray_settings['custom_code'];
	}

	add_action('wp_footer','accesspress_ray_custom_code');

	function accesspress_ray_call_to_action_cb(){
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
		if(!empty($accesspress_ray_settings['action_text'])){
		?>
		<section id="call-to-action">
		<div class="ak-container">
			<h4><?php echo $accesspress_ray_settings['action_text']; ?></h4>
			<a class="action-btn" href="<?php echo $accesspress_ray_settings['action_btn_link']; ?>"><?php echo $accesspress_ray_settings['action_btn_text']; ?></a>
		</div>
		</section>
		<?php
		}
	}

	add_action('accesspress_ray_call_to_action','accesspress_ray_call_to_action_cb', 10);
    
    add_filter('widget_text', 'do_shortcode');