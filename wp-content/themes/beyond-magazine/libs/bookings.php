<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 27/06/16
 * Time: 18.37
 */

add_action( 'init', array( 'bookings', 'init' ));

class bookings{
    public static function init(){
        $class = __CLASS__;
        new $class;
    }
    public function __construct() {
        $post_type = 'bookings';
        $labels = array(
            'name'               => _x( 'Prenotazioni', 'post type general name', 'beyondmagazine' ),
            'singular_name'      => _x( 'Prenotazione', 'post type singular name', 'beyondmagazine' ),
            'menu_name'          => _x( 'Prenotazioni', 'admin menu', 'beyondmagazine' ),
            'name_admin_bar'     => _x( 'Prenotazione', 'add new on admin bar', 'beyondmagazine' ),
            'add_new'            => _x( 'Aggiungi nuova', 'Prenotazione', 'beyondmagazine' ),
            'add_new_item'       => __( 'Aggiungi nuova Prenotazione', 'beyondmagazine' ),
            'new_item'           => __( 'Nuova Prenotazione', 'beyondmagazine' ),
            'edit_item'          => __( 'Modifica Prenotazione', 'beyondmagazine' ),
            'view_item'          => __( 'Vedi Prenotazione', 'beyondmagazine' ),
            'all_items'          => __( 'Tutti le Prenotazioni', 'beyondmagazine' ),
            'search_items'       => __( 'Cerca Prenotazioni', 'beyondmagazine' ),
            'not_found'          => __( 'Nessuna Prenotazione trovata.', 'beyondmagazine' ),
            'not_found_in_trash' => __( 'Nessuna Prenotazione trovata nel cestino.', 'beyondmagazine' )
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

        add_action( 'add_meta_boxes', array( $this, 'add_bookings_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save' ) );


    }
    public function save($post_id){

    }

    public function add_bookings_meta_boxes(){
        add_meta_box(
            'bookings_meta',
            __( 'Prenotazione'),
            array( $this, 'bookings_metabox' ),
            'bookings',
            'normal',
            'low'
        );
    }

    public function bookings_metabox($post){
        $products_meta = get_post_meta($post->ID, 'products', true);
        $user_meta = get_post_meta($post->ID, 'userData', true);
        echo "<table>";
        echo '<thead><tr><th>Prodotto</th><th>Peso</th><th>Pezzi</th></tr></thead>';
        foreach($products_meta as $id => $product){
            $product_name = $product['name'];
            if ($product['weight']['qt']){
                $weight = $product['weight']['qt'].$product['weight']['mu'];
            } else {
                $weight = '';
            }
            if ($product['items']['qt']){
                $items = $product['items']['qt'].$product['items']['mu'];
            } else {
                $items = '';
            }

            echo "<tr>
                <td>$product_name</td>
                <td>".$weight."</td>
                <td>".$items."</td>
                </tr>";
        }
        echo "</table>";



    }
}
