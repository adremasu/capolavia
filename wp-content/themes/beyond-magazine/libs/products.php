<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 09/09/16
 * Time: 15.29
 */

add_action( 'init', array( 'products', 'init' ));

class products {
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    public function __construct() {
        $post_type = 'products';
        $labels = array(
            'name'               => _x( 'Prodotti', 'post type general name', 'beyondmagazine' ),
            'singular_name'      => _x( 'Prodotto', 'post type singular name', 'beyondmagazine' ),
            'menu_name'          => _x( 'Prodotti', 'admin menu', 'beyondmagazine' ),
            'name_admin_bar'     => _x( 'Prodotto', 'add new on admin bar', 'beyondmagazine' ),
            'add_new'            => _x( 'Aggiungi nuova', 'Prenotazione', 'beyondmagazine' ),
            'add_new_item'       => __( 'Aggiungi nuovo Prodotto', 'beyondmagazine' ),
            'new_item'           => __( 'Nuova Prodotto', 'beyondmagazine' ),
            'edit_item'          => __( 'Modifica Prodotto', 'beyondmagazine' ),
            'view_item'          => __( 'Vedi Prodotto', 'beyondmagazine' ),
            'all_items'          => __( 'Tutti le Prodotti', 'beyondmagazine' ),
            'search_items'       => __( 'Cerca Prodotti', 'beyondmagazine' ),
            'not_found'          => __( 'Nessuna Prodotto trovato.', 'beyondmagazine' ),
            'not_found_in_trash' => __( 'Nessuna Prodotto trovato nel cestino.', 'beyondmagazine' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Descrizione.', 'beyondmagazine' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-editor-ol',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'bookings' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title')
        );
        register_post_type( $post_type, $args );

        add_action( 'add_meta_boxes', array( $this, 'add_products_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save' ) );

    }

    public function save($post_id){
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

        $subscription_data =  $_POST['products'];
        // Update the meta field.
        update_post_meta( $post_id, 'products', $subscription_data );

    }

}
