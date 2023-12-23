<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 09/09/16
 * Time: 15.29
 */

add_action( 'init', array( 'giftcodes', 'init' ));

class products {
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    public function __construct() {
        $post_type = 'giftcodes';
        $labels = array(
            'name'               => _x( 'Gift Codes', 'post type general name', 'beyondmagazine' ),
            'singular_name'      => _x( 'Gift Code', 'post type singular name', 'beyondmagazine' ),
            'menu_name'          => _x( 'Gift Codes', 'admin menu', 'beyondmagazine' ),
            'name_admin_bar'     => _x( 'Gift Code', 'add new on admin bar', 'beyondmagazine' ),
            'add_new'            => _x( 'Aggiungi nuova', 'Prenotazione', 'beyondmagazine' ),
            'add_new_item'       => __( 'Aggiungi nuovo Gift Code', 'beyondmagazine' ),
            'new_item'           => __( 'Nuovo Gift Code', 'beyondmagazine' ),
            'edit_item'          => __( 'Modifica Gift Codes', 'beyondmagazine' ),
            'view_item'          => __( 'Vedi Gift Code', 'beyondmagazine' ),
            'all_items'          => __( 'Tutti I Gift Codes', 'beyondmagazine' ),
            'search_items'       => __( 'Cerca Gift Codes', 'beyondmagazine' ),
            'not_found'          => __( 'Nessuna Gift Code trovato.', 'beyondmagazine' ),
            'not_found_in_trash' => __( 'Nessuna Gift Code trovato nel cestino.', 'beyondmagazine' )
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
            'rewrite'            => array( 'slug' => 'giftcodes' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title')
        );
        register_post_type( $post_type, $args );

        add_action( 'add_meta_boxes', array( $this, 'add_giftcodes_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save' ) );

    }

    public function add_giftcodes_meta_boxes(){
        add_meta_box(
            'giftcodes_meta',
            __( 'Gift code'),
            array( $this, 'giftcodes_metabox' ),
            'giftcodes',
            'normal',
            'low'
        );
    }
    public function giftcodes_metabox($post){
        $giftcode_meta = get_post_meta($post->ID, 'giftcode', true);

        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );
        echo "<input value='".$giftcode_meta[value]."' type='hidden' name='giftcode[value]'>";
        echo "<input value='".$giftcode_meta[code]."' type='hidden' name='giftcode[code]'>";

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

        $gitftcode_data =  $_POST['gitftcode'];
        // Update the meta field.
        update_post_meta( $post_id, 'gitftcode', $gitftcode_data );

    }

}
