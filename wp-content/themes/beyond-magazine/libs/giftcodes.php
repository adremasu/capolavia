<?php
/**
 * Created by andrea.
 * User: andrea
 * Date: 23/12/23
 * Time: 19.01
 */

add_action( 'init', array( 'giftcodes', 'init' ));

class giftcodes {
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
            'supports'           => array( 'title','author')
        );
        
        register_post_type( $post_type, $args );

        add_action( 'add_meta_boxes', array( $this, 'add_all_metaboxes' ) );
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_js' ));

        add_action( 'save_post', array( $this, 'save' ) );

    }

    public function enqueue_js( $hook ) {

        wp_enqueue_style('plugin_name-admin-ui-css',
            'https://code.jquery.com/ui/jquery-ui-git.css',
            false,
            PLUGIN_VERSION,
            false);
        wp_enqueue_script( 'angularjs',   get_bloginfo('template_directory'). '/js/angular.min.js' );
        wp_enqueue_script( 'bookingAdmin',   get_bloginfo('template_directory'). '/libs/main/admin.js', array(), '5.5' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }


    public function get_residual_value($post_id){
        if ($post_id){
            $giftcode_meta = get_post_meta($post->ID, 'code', true);
        } elseif (!$post_id && $post->ID) {
            $giftcode_meta = get_post_meta($post->ID, 'code', true);
        } else {
            return 0;
        }
        $code = $giftcode_meta[code]; 
        $shoppings = get_post_meta($post_id, 'shoppings', true);
        $giftcode_meta = get_post_meta($post_id, 'code', true);        
        $_residual_value = $giftcode_meta[value]; 
        foreach($shoppings as $shopping){
            $_residual_value = $_residual_value-$shopping[value];
        }        
        return $_residual_value;
    } 

    public function add_all_metaboxes(){

        $this->add_giftcodes_meta_boxes();
        $this->add_spent_meta_boxes();
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
    
    public function add_spent_meta_boxes(){
        add_meta_box(
            'spent_meta',
            __( 'Spese'),
            array( $this, 'spent_metabox' ),
            'giftcodes',
            'normal',
            'low'
        );
    }



    public function giftcodes_metabox($post){

        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );
        $giftcode_meta = get_post_meta($post->ID, 'code', true);        
        echo "Valore residuo: ".$this->get_residual_value($post->ID)."<br/>";
        echo "Utente: <br/>";
        echo "Codice: <input value='".$giftcode_meta[code]."' type='text' name='code[code]'> ";
        echo "Valore originale: <input value='".$giftcode_meta[value]."' type='text' name='code[value]'> ";

        echo "<div ng-app='giftcodesApp' ng-controller='ShoppingsController'>";

        $shoppings = get_post_meta($post->ID, 'shoppings', true);
        $i=0;
        foreach ($shoppings as $shopping) {           
            if ($shopping[date] && $shopping [value]){
                echo "Data: <input value='".$shopping[date]."' type='date' name='shoppings[".$i."][date]'> ";
                echo "Valore: <input value='".$shopping[value]."' type='text' name='shoppings[".$i."][value]'>€<br/> ";
                $i++;
            }
            
        }
        if ($i){
            $id = $i;
        } else {
            $id = 0;
        }
        echo "<b>Aggiungi nuovo</b><br/>";
        echo "Data: <input value='' type='date' name='shoppings[".$id."][date]'> ";
        echo "Valore: <input value='' type='text' name='shoppings[".$id."][value]'>€<br/> ";
        echo "</div>";
        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );

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

        $gitftcode_data =  $_POST['code'];
        $shoppings =  $_POST['shoppings'];
        // Update the meta field.
        update_post_meta( $post_id, 'code', $gitftcode_data );
        update_post_meta( $post_id, 'shoppings', $shoppings );

    }

}
