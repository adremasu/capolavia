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
		$title .= " $sep " . sprintf( __( 'Page %s', 'accesspress-ray' ), max( $paged, $page ) );
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
		'name'          => __( 'Left Sidebar', 'accesspress-ray' ),
		'id'            => 'left-sidebar',
		'description'   => __( 'Display items in the Left Sidebar of the inner pages', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'accesspress-ray' ),
		'id'            => 'right-sidebar',
		'description'   => __( 'Display items in the Right Sidebar of the inner pages', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'accesspress-ray' ),
		'id'            => 'shop-sidebar',
		'description'   => __( 'Display items in the Right Sidebar of the inner pages for Woocommerce', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Featured Left Widget', 'accesspress-ray' ),
		'id'            => 'textblock-1',
		'description'   => __( 'Display items in the left of Featured Bar', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Featured Middle Widget', 'accesspress-ray' ),
		'id'            => 'textblock-2',
		'description'   => __( 'Display items in the middle of Featured Bar', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Featured Right Widget', 'accesspress-ray' ),
		'id'            => 'textblock-3',
		'description'   => __( 'Display items in the right of Featured Bar', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer One', 'accesspress-ray' ),
		'id'            => 'footer-1',
		'description'   => __( 'Display items in First Footer Area', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Two', 'accesspress-ray' ),
		'id'            => 'footer-2',
		'description'   => __( 'Display items in Second Footer Area', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Three', 'accesspress-ray' ),
		'id'            => 'footer-3',
		'description'   => __( 'Display items in Third Footer Area', 'accesspress-ray' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Four', 'accesspress-ray' ),
		'id'            => 'footer-4',
		'description'   => __( 'Display items in Fourth Footer Area', 'accesspress-ray' ),
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
	wp_enqueue_style( 'accesspress-ray-font-css', get_template_directory_uri() . '/css/fonts.css' );
	wp_enqueue_style( 'accesspress-ray-google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ) );
	wp_enqueue_style( 'accesspress-ray-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'accesspress-ray-fancybox-css', get_template_directory_uri() . '/css/nivo-lightbox.css' );
	wp_enqueue_style( 'accesspress-ray-bx-slider-style', get_template_directory_uri() . '/css/jquery.bxslider.css' );
	wp_enqueue_style( 'accesspress-ray-style', get_stylesheet_uri() );

	wp_enqueue_script( 'accesspress-ray-bx-slider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', array('jquery'), '4.1', true );
	wp_enqueue_script( 'accesspress-ray-fancybox', get_template_directory_uri() . '/js/nivo-lightbox.min.js', array('jquery'), '2.1', true );
	wp_enqueue_script( 'accesspress-ray-jquery-actual', get_template_directory_uri() . '/js/jquery.actual.min.js', array('jquery'), '1.0.16', true );
	wp_enqueue_script( 'accesspress-ray--skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'accesspress-ray-custom-js', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.1', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

/**
* Loads up responsive css if it is not disabled
*/
	if ( $accesspress_ray_settings[ 'responsive_design' ] == 0 ) {	
		wp_enqueue_style( 'accesspress-ray-responsive', get_template_directory_uri() . '/css/responsive.css' );
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
	echo '<link rel="shortcut icon" type="image/png" href="'. esc_url($accesspress_ray_settings[ 'media_upload' ]).'"/>';
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
	echo '<div class="header-text">'.esc_html(wpautop($accesspress_ray_settings['featured_text'])).'</div>';
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
	echo esc_attr($accesspress_ray_alignment_class);
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
	empty($accesspress_ray_settings['slider_pause']) ? ($e ='5000') : ($e = esc_attr($accesspress_ray_settings['slider_pause']));

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
				speed:'<?php echo esc_attr($accesspress_ray_settings['slider_speed']); ?>'
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
						
							<img src="<?php echo esc_url($image[0]); ?>">
							
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
						
						<img src="<?php echo esc_url($image[0]); ?>">
							
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
						<h1 class="caption-title"><?php _e('AccessPress Ray','accesspress-ray'); ?></h1><br />
						<h2 class="caption-description"><?php _e('Responsive, multi-purpose, business wordpress theme, perfect for any business on any device.','accesspress-ray'); ?></h2>
						<br>
						<a href="#"><?php _e('Read More','accesspress-ray'); ?></a>
					</div>
				</div>
                <?php  endif; ?>
			</div>
					
			<div class="slides">
				<img src="<?php echo get_template_directory_uri(); ?>/images/demo/slider2.jpg" alt="slider2">
                <?php if($accesspress_ray_settings['slider_caption']=='yes4' || empty($accesspress_ray_settings['slider_caption'])):?>
				<div class="slider-caption">
					<div class="ak-container">
						<h1 class="caption-title"><?php _e('Easy Customization','accesspress-ray'); ?></h1>
						<h2 class="caption-description"><?php _e('A theme with powerful theme options for customization. Style your wordpress and see changes live!','accesspress-ray'); ?></h2>
						<br>
						<a href="#"><?php _e('Read More','accesspress-ray'); ?></a>
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
		if( is_404()){
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

function accesspress_ray_custom_css(){
	global $accesspress_ray_options;
	$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
	echo '<style type="text/css">';
		echo esc_attr($accesspress_ray_settings['custom_css']);
	echo '</style>';
}

add_action('wp_head','accesspress_ray_custom_css');

function accesspress_ray_call_to_action_cb(){
	global $accesspress_ray_options;
	$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
	if(!empty($accesspress_ray_settings['action_text'])){
	?>
	<section id="call-to-action">
	<div class="ak-container">
		<h4><?php echo esc_attr($accesspress_ray_settings['action_text']); ?></h4>
		<a class="action-btn" href="<?php echo esc_url($accesspress_ray_settings['action_btn_link']); ?>"><?php echo esc_attr($accesspress_ray_settings['action_btn_text']); ?></a>
	</div>
	</section>
	<?php
	}
}

add_action('accesspress_ray_call_to_action','accesspress_ray_call_to_action_cb', 10);

function accesspress_ray_exclude_cat_from_blog($query) {
global $accesspress_ray_options;
$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
$accesspress_ray_exclude_cat = array($accesspress_ray_settings['blog_cat'],$accesspress_ray_settings['testimonial_cat'], $accesspress_ray_settings['slider_cat'], $accesspress_ray_settings['portfolio_cat']);
	
if(!empty($accesspress_ray_exclude_cat)):
    $cats = array();
    foreach($accesspress_ray_exclude_cat as $value){
        if(!empty($value) && $value != 0){
            $cats[] = -$value; 
        }
    }
    if(!empty($cats)){
	    $category = join( "," , $cats);
	    if ( $query->is_home() ) {
	    $query->set('cat', $category);
	    }
    }
    return $query;
endif;
}

add_filter('pre_get_posts', 'accesspress_ray_exclude_cat_from_blog');

function accesspress_ray_admin_notice() {
    global $pagenow;
    if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
    ?>
    <div class="updated">
        <p><?php echo sprintf(__( 'Go to <a href="%s">Theme Options Panel</a> to set up the website.', 'accesspress-ray' ), esc_url(admin_url('/themes.php?page=theme_options'))); ?></p>
    </div>
    <?php
    }
}
add_action( 'admin_notices', 'accesspress_ray_admin_notice' );

function accesspress_ray_register_required_plugins() {

    $plugins = array(
        array(
            'name'      => 'AccessPress Custom CSS',
            'slug'      => 'accesspress-custom-css',
            'required'  => false,
        ),
        array(
            'name'      => 'AccessPress Twitter Feed',
            'slug'      => 'accesspress-twitter-feed',
            'required'  => false,
        ),
        array(
            'name'      => 'AccessPress Social Icons',
            'slug'      => 'accesspress-social-icons',
            'required'  => false,
        ),
        array(
            'name'      => 'AccessPress Social Counter',
            'slug'      => 'accesspress-social-counter',
            'required'  => false,
        ),
        array(
            'name'      => 'AccessPress Social Share',
            'slug'      => 'accesspress-social-share',
            'required'  => false,
        )
    );

    $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'accesspress-ray' ),
            'menu_title'                      => __( 'Install Plugins', 'accesspress-ray' ),
            'installing'                      => __( 'Installing Plugin: %s', 'accesspress-ray' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'accesspress-ray' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop(
                'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop(
                'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop(
                'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                'accesspress-ray'
            ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'accesspress-ray'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'accesspress-ray'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'accesspress-ray'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'accesspress-ray' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'accesspress-ray' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'accesspress-ray' ),
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'accesspress-ray' ),  // %1$s = plugin name(s).
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'accesspress-ray' ),  // %1$s = plugin name(s).
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'accesspress-ray' ), // %s = dashboard link.
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tgmpa' ),

            'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'accesspress_ray_register_required_plugins' );

add_filter('widget_text', 'do_shortcode');