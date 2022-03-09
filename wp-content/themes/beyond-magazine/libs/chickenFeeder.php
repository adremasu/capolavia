<?php
add_action( 'init', 'feedings_init' );

add_action( 'rest_api_init', function() {
    register_rest_route( 'chicken_feeder/api', '/token/', array(
        'methods' => 'POST',
        'callback' =>  'plugin_name_route_api'
    ) );
}
);
function plugin_name_route_api($req) {
    //$token = get_option('bearer_token');
    $headers = $req->get_headers();
    $params = $req->get_params();
    //$auth_token = $headers['authorization'][0];

    /*if ( $token != $auth_token ) { 
        return new WP_Error( '401', esc_html__( 'Not Authorized', 'text_domain' ), array( 'status' => 401 ) );
    }*/
    //var_dump($params);
    $json = json_encode($params);
    //var_dump($json);
    $my_post = array(
      'post_title'    => wp_strip_all_tags( 'pagamento' ),
      'post_type'     => 'feedings'
          );
    wp_insert_post($my_post);

    //echo json_encode(["message" => "Authorized"]);
}

function feedings_init() {
    $labels = array(
    'name'                  => _x( 'Feedings', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Feeding', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Feedings', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Feeding', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Aggiungi nuovo', 'textdomain' ),
    'add_new_item'          => __( 'Aggiungi nuovo Feeding', 'textdomain' ),
    'new_item'              => __( 'Nuovo Feeding', 'textdomain' ),
    'edit_item'             => __( 'Modifica Feeding', 'textdomain' ),
    'view_item'             => __( 'Vedi Feeding', 'textdomain' ),
    'all_items'             => __( 'Tutti i feedings', 'textdomain' ),
    'search_items'          => __( 'Cerca Feedings', 'textdomain' ),
    'not_found'             => __( 'Nessun Feeding trovato', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nessun Feeding trovato nel cestino', 'textdomain' ),
    'featured_image'        => _x( 'immagine di copertina del Feeding', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
    'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
    'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
    'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
    'archives'              => _x( 'Archivio Feedings', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
    'insert_into_item'      => _x( 'Insert into feeding', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
    'uploaded_to_this_item' => _x( 'Uploaded to this feeding', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
    'filter_items_list'     => _x( 'Filter feedings list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
    'items_list_navigation' => _x( 'feedings list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
    'items_list'            => _x( 'feedings list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
  );
  $args = array(
    'labels'             => $labels,
    'public'             => false,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'feedings' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'custom-fields')
);

    register_post_type('feedings', $args);
}

add_action( 'after_switch_theme', 'rewrite_flush' );
function rewrite_flush() {
    feedings_init();
    flush_rewrite_rules();
}


class holidaysCheck {

    public function __construct(){

    }

  }
?>
