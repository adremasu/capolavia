<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 09/09/16
 * Time: 15.29
 */

add_action( 'init', array( 'xmasproducts', 'init' ));

class xmasproducts {
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    public function __construct() {
        $post_type = 'xmasproducts';
        $labels = array(
            'name'               => _x( 'Pacchetti', 'post type general name', 'beyondmagazine' ),
            'singular_name'      => _x( 'Pacchetto', 'post type singular name', 'beyondmagazine' ),
            'menu_name'          => _x( 'Pacchetti', 'admin menu', 'beyondmagazine' ),
            'name_admin_bar'     => _x( 'Pacchetto', 'add new on admin bar', 'beyondmagazine' ),
            'add_new'            => _x( 'Aggiungi nuova', 'Prenotazione', 'beyondmagazine' ),
            'add_new_item'       => __( 'Aggiungi nuovo Pacchetto', 'beyondmagazine' ),
            'new_item'           => __( 'Nuova Pacchetto', 'beyondmagazine' ),
            'edit_item'          => __( 'Modifica Pacchetto', 'beyondmagazine' ),
            'view_item'          => __( 'Vedi Pacchetto', 'beyondmagazine' ),
            'all_items'          => __( 'Tutti le Pacchetti', 'beyondmagazine' ),
            'search_items'       => __( 'Cerca Pacchetti', 'beyondmagazine' ),
            'not_found'          => __( 'Nessuna Pacchetto trovato.', 'beyondmagazine' ),
            'not_found_in_trash' => __( 'Nessuna Pacchetto trovato nel cestino.', 'beyondmagazine' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Descrizione.', 'beyondmagazine' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-grid-view',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'xmasproducts' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title','editor'),

        );
        register_post_type( $post_type, $args );

        add_action( 'add_meta_boxes', array( $this, 'add_xmasproducts_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save_xmasproducts' ) );

        add_action('admin_init', array( $this, 'my_meta_init'));

    }

    public function add_xmasproducts_meta_boxes(){
        add_meta_box(
            'xmasproducts_meta',
            __( 'Impostazioni'),
            array( $this, 'xmasproducts_metabox' ),
            'xmasproducts',
            'normal',
            'low'
        );
    }

    public function xmasproducts_metabox($post){
        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );

        $price = get_post_meta($post->ID, 'price', true);
        $size = get_post_meta($post->ID, 'size', true);
        echo "<table>";
        echo "<tr><td><label for='price'>Prezzo</label></td><td><input type='text' name='price' value='".$price."'/></td></tr>";
        echo "<tr><td><label for='size'>Misure</label></td><td><input type='text' name='size' value='".$size."'/></td></tr>";
        echo "</table>";
        }
    

    
    public function my_meta_init() {
        wp_enqueue_style('my_meta_css', ltrim(MY_THEME_PATH, 'https:') . '/custom/meta.css');
        foreach (array('products') as $type)
        {
            add_meta_box('my_all_meta', 'Gestione inventario', 'my_meta_setup', $type, 'normal', 'high');
        }
    // add a callback function to save any data a user enters in
        add_action('save_post', array($this, 'my_meta_save'));
    }
    public function my_meta_save($post_id){

    }

    public function save_xmasproducts($post_id){
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['sub_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['sub_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'sub_inner_custom_box' ) ) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.

        $price =  $_POST['price'];
        $size =  $_POST['size'];
        // Update the meta field.
        update_post_meta( $post_id, 'price', $price );
        update_post_meta( $post_id, 'size', $size );

    }

}
