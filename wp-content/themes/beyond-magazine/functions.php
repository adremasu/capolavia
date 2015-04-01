<?php
/***
*
TGM PLUGIN ACTIVATION
*
***/
require_once('libs/class-tgm-plugin-activation.php');
add_action( 'tgmpa_register', 'beyond_register_recommended_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function beyond_register_recommended_plugins() {
 
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $beyond_plugins = array(
        array(
            'name'      => __('Bootstrap 3 Shortcodes','beyondmagazine'),
            'slug'      => 'bootstrap-3-shortcodes',
            'required'  => false,
    ),
        array(
            'name'      => __('Ketchup Shortcodes','beyondmagazine'),
            'slug'      => 'ketchup-shortcodes-pack',
            'required'  => false,
    )
    

    );
    $beyond_config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'beyondmagazine' ),
            'menu_title'                      => __( 'Install Plugins', 'beyondmagazine' ),
            'installing'                      => __( 'Installing Plugin: %s', 'beyondmagazine' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'beyondmagazine' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','beyondmagazine' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.','beyondmagazine' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins','beyondmagazine' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins','beyondmagazine' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'beyondmagazine' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'beyondmagazine' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'beyondmagazine' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );
 
    tgmpa( $beyond_plugins, $beyond_config );
}
/***
*
THEME SETUP
*
***/
function beyond_theme_setup(){
    
        global $content_width;
        if (!isset( $content_width ))
        $content_width = 575;

        $beyond_background_args = array(
        'default-color' => 'ffffff',
        'default-image' => get_template_directory_uri() . '/img/bg.png',
        'wp-head-callback' => 'beyond_custom_background_cb',
        );
        add_theme_support( 'custom-background', $beyond_background_args );
        add_editor_style( 'style.css' );
        
        $beyond_header_defaults = array(
        'default-image'          => '',
        'random-default'         => false,
        'width'                  => '1140',
        'height'                 => '487',
        'flex-height'            => false,
        'flex-width'             => false,
        'default-text-color'     => '',
        'header-text'            => false,
        'uploads'                => true,
        'wp-head-callback'       => '',
        'admin-head-callback'    => '',
        'admin-preview-callback' => '',
        );
        add_theme_support( 'custom-header', $beyond_header_defaults );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        register_nav_menu( 'primary', __('Main Menu','beyondmagazine' ));
        load_theme_textdomain('beyondmagazine', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'beyond_theme_setup');
function beyond_custom_background_cb() {
  $background = set_url_scheme( get_background_image() );
  $color = get_theme_mod( 'background_color', get_theme_support( 'custom-background', 'default-color' ) );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
    if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
      $position = 'left';
    $position = " background-position: top $position;";

    $attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
    if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
      $attachment = 'scroll';
    $attachment = " background-attachment: $attachment;";

    $style .= $image . $repeat . $position . $attachment;
  }
?>
<style type="text/css" id="custom-background-css">
body.custom-background { <?php echo trim( $style ); ?> }
</style>
<?php
}
/***
*
LOAD CSS AND JS STYLES
*
***/
    function beyond_load_scripts() {
   
        wp_enqueue_script('beyond_bootstrap', get_template_directory_uri().'/js/bootstrap.min.js',array('jquery'),'',true);
        wp_enqueue_script('beyond_slicknav',get_template_directory_uri().'/js/jquery.slicknav.min.js',array('jquery'),'',true);
        wp_enqueue_script('beyond_init',get_template_directory_uri().'/js/init.js',array('jquery'),'',true);
        
        wp_localize_script('beyond_init', 'init_vars', array(
            'label' => __('Menu', 'beyondmagazine')
        ));

    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );
    }
    add_action('wp_enqueue_scripts', 'beyond_load_scripts');

    function beyond_load_styles()
    { 
        wp_enqueue_style( 'beyond_bootstrap-theme', get_template_directory_uri().'/css/bootstrap-theme.min.css','','','all' );
        wp_enqueue_style( 'beyond_bootstrap', get_template_directory_uri(). '/css/bootstrap.min.css','','','all' );
        wp_enqueue_style( 'beyond_slicknav',get_template_directory_uri().'/css/slicknav.css','','','all');
        wp_enqueue_style( 'beyond_elegant-font',get_template_directory_uri().'/fonts/elegant_font/HTML_CSS/style.css','','','all');
        wp_enqueue_style( 'beyond_openSans',get_template_directory_uri().'/css/web_fonts/opensans_regular_macroman/stylesheet.css','','','all');
        wp_enqueue_style( 'beyond_style', get_stylesheet_uri(),'','','all' );
    }    
    add_action('wp_enqueue_scripts', 'beyond_load_styles');
   
    function beyond_add_ie_html5_shim () {
        echo '<!--[if lt IE 9]>';
        echo '<script src="'.get_template_directory_uri().'/js/html5shiv.js"></script>';
        echo '<![endif]-->';
    }
    add_action('wp_head', 'beyond_add_ie_html5_shim');
/***
*
SIDEBARS INITIALIZATION
*
***/
function beyond_widgets_init() {
    
    register_sidebar(array(
        'name' => __('Sidebar', 'beyondmagazine' ),
        'id'   => 'sidebar',
        'description' => __('This is the widgetized sidebar.', 'beyondmagazine' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3><span>',
        'after_title'   => '</span></h3>'
    ));
    register_sidebar(array(
        'name' => __('Left footer Sidebar', 'beyondmagazine' ),
        'id'   => 'footer-sidebar-1',
        'description' => __('This is the widgetized sidebar.', 'beyondmagazine' ),
        'before_widget' => '<div id="%1$s" class="footerwidget widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('Right Footer Sidebar', 'beyondmagazine' ),
        'id'   => 'footer-sidebar-2',
        'description' => __('This is the widgetized sidebar.', 'beyondmagazine' ),
        'before_widget' => '<div id="%1$s" class="footerwidget widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('Header Sidebar', 'beyondmagazine' ),
        'id'   => 'header-sidebar',
        'description' => __('This is the header sidebar.', 'beyondmagazine' ),
        'before_widget' => '<div id="%1$s" class="headerwidget widget %2$s">',
        'after_widget'  => '</div>'
    ));
    }
add_action( 'widgets_init', 'beyond_widgets_init' );
/***
*
THEME FUNCTIONS
*
***/
function beyond_wp_title($title,$sep){

    global $page, $paged;
    $title .= get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    if ( $paged >= 2 || $page >= 2 )
        
        $title = "$title $sep " . sprintf( __( 'Page %s', 'beyondmagazine' ), max( $paged, $page ) );
        
        return $title;
}
add_filter( 'wp_title', 'beyond_wp_title', 10, 2 );
function beyond_excerpt_length( $length ) {
    return 50;
}
add_filter( 'excerpt_length', 'beyond_excerpt_length', 999 );
/** THEME OPTIONS **/
add_action( 'admin_menu', 'beyond_add_admin_menu' );
add_action( 'admin_init', 'beyond_settings_init' );


function beyond_add_admin_menu(  ) { 
    add_theme_page( __('beyondmagazine','beyondmagazine'), __('Beyond Magazine Settings','beyondmagazine'), 'manage_options', 'beyondmagazine', 'beyond_options_page' );

}


function beyond_settings_init(  ) { 

    register_setting( 'pluginPage', 'beyond_settings' );

    add_settings_section(
        'beyond_pluginPage_section', 
        __( 'General Settings For Theme.', 'beyondmagazine' ), 
        'beyond_settings_section_callback', 
        'pluginPage'
    );
    add_settings_field( 
        'beyond_add_favicon', 
        __( 'Favicon URL', 'beyondmagazine' ), 
        'beyond_add_favicon_render', 
        'pluginPage', 
        'beyond_pluginPage_section' 
    );
    add_settings_field( 
        'beyond_footer_sidebars', 
        __( 'Select Footer Sidebars', 'beyondmagazine' ), 
        'beyond_footer_sidebars_render', 
        'pluginPage', 
        'beyond_pluginPage_section' 
    );
    add_settings_field( 
        'beyond_post_columns', 
        __( 'Select Beyond Magazine Post Columns', 'beyondmagazine' ), 
        'beyond_columns_render', 
        'pluginPage', 
        'beyond_pluginPage_section' 
    );
    
    
}
function beyond_columns_render() { 

    $options = get_option( 'beyond_settings' );
    ?>
    <select name='beyond_settings[beyond_post_columns]'>
        <option value="two_col" <?php selected( strip_tags($options['beyond_post_columns']),'two_col' ); ?>><?php echo __('2 Columns','beyondmagazine'); ?> </option>
        <option value="three_col" <?php selected( strip_tags($options['beyond_post_columns']),'three_col' ); ?>><?php echo __('3 Columns','beyondmagazine'); ?></option>
    </select>

<?php }
function beyond_footer_sidebars_render() { 

    $options = get_option( 'beyond_settings' );
    ?>
    <select name='beyond_settings[beyond_footer_sidebars]'>
        <option value="1" <?php selected( strip_tags($options['beyond_footer_sidebars']), 1 ); ?>><?php echo __('1 Column','beyondmagazine'); ?> </option>
        <option value="2" <?php selected( strip_tags($options['beyond_footer_sidebars']), 2 ); ?>><?php echo __('2 Columns','beyondmagazine'); ?></option>
    </select>

<?php }
function beyond_add_favicon_render() { 

    $options = get_option( 'beyond_settings' );
    $value = esc_url_raw($options['beyond_add_favicon']);
    ?>
    <input type='text' name='beyond_settings[beyond_add_favicon]' value='<?php echo $value; ?>'>
    <?php
}
function beyond_settings_section_callback(  ) {
     
    echo __('Premium Features', 'beyondmagazine');
    echo'<ul style="background:#ffffff; padding:10px; width:90%;">
        <li>'.__('Favicon & Logo Upload through uploaded','beyondmagazine').'</li>
        <li>'.__('Upload Logo & Favicon','beyondmagazine').'</li>
        <li>'.__('Full Width Slider','beyondmagazine').'</li>
        <li>'.__('Advanced Post Formats Options','beyondmagazine').'</li>
        <li>'.__('Slider (enable/disable title & description)','beyondmagazine').'</li>
        <li>'.__('Testimonials','beyondmagazine').'</li>
        <li>'.__('Google Fonts','beyondmagazine').'</li>
        <li>'.__('Color Picker','beyondmagazine').'</li>
        <li>'.__('Gallery','beyondmagazine').'</li>
        <li>'.__('1-4 Columns Widgetized Footer Sidebar','beyondmagazine').'</li>
        </ul>
        <p>
        <a rel="nofollow" href="'.esc_url( 'http://ketchupthemes.com/beyond-magazine/').'" style="background:red; margin:5px 0; padding:10px 20px; color:#ffffff; margin-top:10px; text-decoration:none;">'.__('Update to Premium','beyondmagazine').'</a></p>';
}
function beyond_options_page() { 
?>
    <form action='options.php' method='post' name="settingsform">
     
        <h2><?php _e('Theme Options','beyondmagazine'); ?></h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Settings saved.','beyondmagazine') ?></strong></p>
            </div>
              
            <?php } ?>
        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>
    </form>
    <?php
}
function beyond_get_favicon(){
    $options = get_option('beyond_settings');
    $favicon = $options['beyond_add_favicon'];
    
    return $favicon;
}
function beyond_footer_sidebars(){
    $options = get_option('beyond_settings');
    $beyond_footer_sidebars = $options['beyond_footer_sidebars'];
    
    return $beyond_footer_sidebars;
}
function beyond_post_columns(){
    $options = get_option('beyond_settings');
    $beyond_post_columns = $options['beyond_post_columns'];
    
    return $beyond_post_columns;
}


//category enrichment

//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'extra_category_fields');
//add extra fields to category edit form callback function
function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Icona categoria'); ?></label></th>
        <td>
            <input type="text" name="Cat_meta[img]" id="Cat_meta[img]" size="3" style="width:20px" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
            <span class="description"><?php _e('Image for category: use full url with '); ?></span>
        </td>
    </tr>
<?php
}


// save extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fileds');
   // save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
        foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}
?>