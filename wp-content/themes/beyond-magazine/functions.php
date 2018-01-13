<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/google-api-php-client/src');

define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));
add_action('admin_init','my_meta_init');

function my_meta_init()
{
// review the function reference for parameter details
// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
    wp_enqueue_style('my_meta_css', ltrim(MY_THEME_PATH, 'https:') . '/custom/meta.css');
// review the function reference for parameter details
// http://codex.wordpress.org/Function_Reference/add_meta_box
// add a meta box for each of the wordpress page types: posts and pages
    foreach (array('products') as $type)
    {
        add_meta_box('my_all_meta', 'Gestione inventario', 'my_meta_setup', $type, 'normal', 'high');
    }
// add a callback function to save any data a user enters in
    add_action('save_post','my_meta_save');
}

function my_meta_setup()
{
    global $post;
// using an underscore, prevents the meta variable
// from showing up in the custom fields section
    $meta = get_post_meta($post->ID,'_my_meta',TRUE);
// instead of writing HTML here, lets do an include
    include(MY_THEME_FOLDER . '/meta.php');
// create a custom nonce for submit verification later
    echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
function my_meta_save($post_id)
{
// authentication checks
// make sure data came from our meta box
    if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;
// check user permissions
    if ($_POST['post_type'] == 'page')
    {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }
// authentication passed, save data
// var types
// single: _my_meta[var]
// array: _my_meta[var][]
// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]
    $current_data = get_post_meta($post_id, '_my_meta', TRUE);
    $new_data = $_POST['_my_meta'];
    my_meta_clean($new_data);
    if ($current_data)
    {
        if (is_null($new_data)) delete_post_meta($post_id,'_my_meta');
        else update_post_meta($post_id,'_my_meta',$new_data);
    }
    elseif (!is_null($new_data))
    {
        add_post_meta($post_id,'_my_meta',$new_data,TRUE);
    }
    return $post_id;
}


function my_meta_clean(&$arr)
{
    if (is_array($arr))
    {
        foreach ($arr as $i => $v)
        {
            if (is_array($arr[$i]))
            {
                my_meta_clean($arr[$i]);
                if (!count($arr[$i]))
                {
                    unset($arr[$i]);
                }
            }
            else
            {
                if (trim($arr[$i]) == '')
                {
                    unset($arr[$i]);
                }
            }
        }
        if (!count($arr))
        {
            $arr = NULL;
        }
    }
}


require_once('vendor/autoload.php');


require_once('libs/class-tgm-plugin-activation.php');
require_once('libs/subscription.php');
require_once('libs/bookings.php');
require_once('libs/bookings_admin-ajax.php');
require_once('libs/subscription-ajax.php');
require_once('libs/book_products-ajax.php');
require_once('libs/options.php');
require_once('libs/bookingManager.php');
require_once('libs/GoogleCalendar.php');
require_once('libs/email_templates.php');
require_once('libs/pdf-bookings.php');

//require_once('libs/class-holidays.php');



add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

define('CREDENTIALS_PATH',  __DIR__ . '/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

define('SCOPES', implode(' ', array(
        Google_Service_Calendar::CALENDAR_READONLY)
));

function getGoogleClient() {
    $client = new Google_Client();
    $client->setApplicationName('Gestione consegne');
    $client->setScopes(SCOPES);
    $client->setAuthConfigFile(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = CREDENTIALS_PATH;
    if (file_exists($credentialsPath)) {
        $accessToken = file_get_contents($credentialsPath);
    } else {

        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        //printf("Open the following link in your browser:\n%s\n", $authUrl);
        //print 'Enter verification code: ';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $a = curl_exec($ch); // $a will contain all headers

        $authCode = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL
        //$authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->authenticate($authCode);

        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, $accessToken);
        //printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->refreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, $client->getAccessToken());
    }
    return $client;
}




/***
 *
TGM PLUGIN ACTIVATION
 *
 ***/

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
        'width'                  => '570',
        'height'                 => '243',
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
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // no php needed above it
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); // php is not closed in the last line
    wp_enqueue_script('beyond_init',ltrim(get_template_directory_uri(),'https:').'/main.min.js','13', null);
    //remove jQuery
    add_filter( 'wp_default_scripts', 'change_default_jquery' );

    function change_default_jquery( &$scripts){
        if(!is_admin()){
            $scripts->remove( 'jquery');
        }
    }

    function my_deregister_scripts(){
      wp_deregister_script( 'wp-embed' );
    }
    add_action( 'wp_footer', 'my_deregister_scripts' );
    wp_localize_script('beyond_init', 'init_vars', array(
        'label' => __('Menu', 'beyondmagazine')
    ));

    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );
}
add_action('wp_enqueue_scripts', 'beyond_load_scripts');

function beyond_load_styles()
{
//        wp_enqueue_style( 'beyond_bootstrap-theme', get_template_directory_uri().'/css/bootstrap-theme.min.css','','','all' );
//        wp_enqueue_style( 'beyond_bootstrap', get_template_directory_uri(). '/css/bootstrap.min.css','','','all' );
//        wp_enqueue_style( 'beyond_slicknav',get_template_directory_uri().'/css/slicknav.css','','','all');
//        wp_enqueue_style( 'beyond_elegant-font',get_template_directory_uri().'/fonts/elegant_font/HTML_CSS/style.css','','','all');
//        wp_enqueue_style( 'beyond_openSans',get_template_directory_uri().'/css/web_fonts/opensans_regular_macroman/stylesheet.css','','','all');
    wp_enqueue_style( 'beyond_style', ltrim(get_stylesheet_uri(),'https:'),'','2016.12.12','all' );
}
add_action('wp_enqueue_scripts', 'beyond_load_styles');

function beyond_add_ie_html5_shim () {
    echo '<!--[if lt IE 9]>';
    echo '<script src="'.ltrim(get_template_directory_uri(), '').'/js/html5shiv.js"></script>';
    echo '<![endif]-->';
}
add_action('wp_head', 'beyond_add_ie_html5_shim');
/***
 *
SIDEBARS INITIALIZATION
 *
 ***/
function beyond_widgets_init() {
    global $widgets;

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
        'before_widget' => '<div id="%1$s" class="footerwidget widget %2$s '. slbd_count_widgets( "footer-sidebar-1" ).'">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('Right Footer Sidebar', 'beyondmagazine' ),
        'id'   => 'footer-sidebar-2',
        'description' => __('This is the widgetized sidebar.', 'beyondmagazine' ),
        'before_widget' => '<div id="%1$s" class="footerwidget widget %2$s ">',
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
    register_sidebar(array(
        'name' => __('Mobile Blog Sidebar', 'beyondmagazine' ),
        'id'   => 'mobile-blog-sidebar',
        'description' => __('This is the mobile blog sidebar.', 'beyondmagazine' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h2><a href="#" class="h3"></a>',
        'after_title'   => '</h2>'
    ));
}
add_action( 'widgets_init', 'beyond_widgets_init' );
/**
 * Count number of widgets in a sidebar
 * Used to add classes to widget areas so widgets can be displayed one, two, three or four per row
 */
function slbd_count_widgets( $sidebar_id ) {
    // If loading from front page, consult $_wp_sidebars_widgets rather than options
    // to see if wp_convert_widget_settings() has made manipulations in memory.
    global $_wp_sidebars_widgets;
    if ( empty( $_wp_sidebars_widgets ) ) :
        $_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
    endif;

    $sidebars_widgets_count = $_wp_sidebars_widgets;

    if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
        $widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
        if ($widget_count != 0) {
            $widget_classes = 'col-md-' . count( $sidebars_widgets_count[ $sidebar_id ] );
            if ( 12 % $widget_count == 0 || $widget_count > 6 ):
                // Four widgets er row if there are exactly four or more than six
                $widget_classes =  'col-md-'.(12/$widget_count);
            else:
                // Otherwise show two widgets per row
                $widget_classes = ' col-md-12';
            endif;
        }


        return $widget_classes;
    endif;
}
//add_filter('dynamic_sidebar_params','widget_bs_class');
function my_edit_widget_func($params) {
    global $widgets;
    $sidebarContext = $params[0]['id'];

    if(is_array($widgets[$sidebarContext])){
        array_push($widgets[$sidebarContext], $params[0]['widget_id']);
    }
    else{
        $widgets[$sidebarContext] = array($params[0]['widget_id']);
    }
    $params[0]['before_title'] = '<h3 class="' . $params[0]['widget_name'] . '">' ;
    return $params;
}
//add_filter('dynamic_sidebar_params', 'my_edit_widget_func');
// add bootstrap class to widgets
function widget_bs_class($params) {
    global $widget_num, $wp_registered_widgets, $wp_registered_sidebars;
    $arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
    foreach($arr_registered_widgets as $sidebar=>$widget){

// Widget class
        $class = 'widget';

// Iterated class
        $num = count($widget);
        if($num%12 == 0){
            $class = 'col-md-'.$num;
        }

// Interpolate the 'my_widget_class' placeholder
        $params[0]['before_widget'] = str_replace('bs_class-'.$sidebar, $class, $params[0]['before_widget']);
    }

    return $params;
}
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
            <input type="text" name="Cat_meta[img]" id="Cat_meta[img]" size="25" style="width:100px" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
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



/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function icon_add_meta_box() {

    $screens = array( 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'icon_sectionid',
            __( 'Page Icon', 'icon_textdomain' ),
            'icon_meta_box_callback',
            $screen,
            'side'
        );
    }
}
add_action( 'add_meta_boxes', 'icon_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function icon_meta_box_callback( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'icon_meta_box', 'icon_meta_box_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, '_my_meta_value_key', true );

    echo '<label for="icon_new_field">';
    _e( 'Page Icon', 'icon_textdomain' );
    echo '</label> ';
    echo '<input type="text" id="icon_new_field" name="icon_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function icon_save_meta_box_data( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['icon_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['icon_meta_box_nonce'], 'icon_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['icon_new_field'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['icon_new_field'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_my_meta_value_key', $my_data );
}
add_action( 'save_post', 'icon_save_meta_box_data' );

//analytics
function GAnalytics() {
    $domain = ltrim(get_bloginfo('wpurl'), 'https://');
    if ( !is_admin() && ($domain=='capolavia.it') ) {

        echo "<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-9418500-11', 'auto');
          ga('send', 'pageview');

        </script>";
    }
    echo "<script>
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '940504799317225',
          xfbml      : true,
          version    : 'v2.3'
        });
      };
      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = '//connect.facebook.net/it_IT/sdk.js';
         fjs.parentNode.insertBefore(js, fjs);
       }(document,'script', 'facebook-jssdk'));
    </script>
<script src='https://apis.google.com/js/platform.js' async defer>
  {lang: 'it', parsetags: 'explicit'}
</script>
    ";

}
add_action('wp_footer', 'GAnalytics', 100);

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}


//add post list image size
add_image_size( "post-list", 287, 215, true );
add_image_size( "gallery", 518, 388, true );
add_image_size( "small_square", 250, 250, true );

//create vegetables posts
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'products',
        array(
            'labels' => array(
                'name' => __( 'Prodotti' ),
                'singular_name' => __( 'Prodotto' )
            ),
            'public' => true,
            'has_archive' => 'products',
            'rewrite' => array('slug' => 'products'),
            'supports' => array('thumbnail', 'title', 'editor', 'revisions'),

        )
    );
    register_post_type( 'recipes',
        array(
            'labels' => array(
                'name' => __( 'Ricette' ),
                'singular_name' => __( 'Ricetta' )
            ),
            'public' => true,
            'has_archive' => 'recipe',
            'rewrite' => array('slug' => 'recipe'),
            'supports' => array('thumbnail', 'title', 'editor', 'revisions'),

        )
    );
}


function my_connection_types() {
    p2p_register_connection_type( array(
        'name' => 'products_to_recipes',
        'from' => 'products',
        'to' => 'recipes'
    ) );
}
add_action( 'p2p_init', 'my_connection_types' );

?>
