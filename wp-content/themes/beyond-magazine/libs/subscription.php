<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 24/04/16
 * Time: 12.04
 */

add_action( 'init', array( 'subscription', 'init' ));

class subscription {

    public static function init() {
        $class = __CLASS__;
        new $class;
    }

    public function __construct() {
        $post_type = 'subscriptions';
        $labels = array(
            'name'               => _x( 'Abbonamenti', 'post type general name', 'beyondmagazine' ),
            'singular_name'      => _x( 'Abbonamento', 'post type singular name', 'beyondmagazine' ),
            'menu_name'          => _x( 'Abbonamenti', 'admin menu', 'beyondmagazine' ),
            'name_admin_bar'     => _x( 'Abbonamento', 'add new on admin bar', 'beyondmagazine' ),
            'add_new'            => _x( 'Aggiungi nuovo', 'Abbonamento', 'beyondmagazine' ),
            'add_new_item'       => __( 'Aggiungi nuovo Abbonamento', 'beyondmagazine' ),
            'new_item'           => __( 'Nuovo Abbonamento', 'beyondmagazine' ),
            'edit_item'          => __( 'Modifica Abbonamento', 'beyondmagazine' ),
            'view_item'          => __( 'Vedi Abbonamento', 'beyondmagazine' ),
            'all_items'          => __( 'Tutti gli Abbonamenti', 'beyondmagazine' ),
            'search_items'       => __( 'Cerca Abbonamenti', 'beyondmagazine' ),
            'not_found'          => __( 'Nessun Abbonamento trovato.', 'beyondmagazine' ),
            'not_found_in_trash' => __( 'Nessun Abbonamento trovato nel cestino.', 'beyondmagazine' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Descrizione.', 'beyondmagazine' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => 'dashicons-awards',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'subscriptions' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title')
        );
        register_post_type( $post_type, $args );
        add_action( 'add_meta_boxes_subscriptions', array( $this, 'add_subscriptions_meta_box' ) );

        add_action( 'save_post', array( $this, 'save' ) );
        $result = add_role( 'customer', __(

                'Cliente' ),

            array(

                'read' => true, // true allows this capability
                'edit_posts' => false, // Allows user to edit their own posts
                'edit_pages' => false, // Allows user to edit pages
                'edit_others_posts' => false, // Allows user to edit others posts not just their own
                'create_posts' => false, // Allows user to create new posts
                'manage_categories' => false, // Allows user to manage post categories
                'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode

            )

        );
        p2p_register_connection_type( array(
            'name' => 'subscription_owner',
            'from' => 'user',
            'cardinality' => 'one-to-many',
            'to' => 'subscriptions',
            'to_query_vars' => array( 'role' => 'customer' ),
            'title' => array( 'from' => 'Abbonamenti', 'to' => 'Abbonato' ),
            'admin_column' => 'any',
            'from_labels' => array(
                'column_title' => 'Abbonamenti'
                ),
              'to_labels' => array(
                'column_title' => 'Cliente',
              ),

        ) );
        p2p_register_connection_type( array(
            'name' => 'blacklist',
            'from' => 'subscriptions',
            'cardinality' => 'one-to-many',
            'to' => 'products',
            'title' => array( 'from' => 'Blacklist'),
            'admin_box' => array(
                'show' => 'from',
                'context' => 'advanced'
            ),
            'to_labels' => array(
                'singular_name' => __( 'Prodotti'),
                'search_items' => __( 'Cerca Prodotti'),
                'not_found' => __( 'Nessun prodotto'),
                'create' => __( 'Aggiungi prodotto' ),
            ),
        ) );
        add_action( 'show_user_profile', array( $this, 'show_customer_profile_fields') );
        add_action( 'edit_user_profile', array( $this,'show_customer_profile_fields') );
        add_action( 'personal_options_update',  array( $this, 'save_customer_profile_fields') );
        add_action( 'edit_user_profile_update',  array( $this, 'save_customer_profile_fields') );



    }
    public function save_customer_profile_fields( $user_id ) {

        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;

        /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
        update_user_meta( $user_id, 'customer', $_POST['customer'] );
    }
    public function show_customer_profile_fields( $user ) {
        $meta = get_the_author_meta( 'customer', $user->ID )

        ?>

        <table class="form-table">

            <tr>
                <th><label for="fiscale">Codice fiscale</label></th>

                <td>
                    <input type="text" name="customer[fiscale]" id="fiscale" value="<?php echo esc_attr( $meta[fiscale] ); ?>" class="regular-text" /><br />
                    <span class="description">Codice fiscale</span>
                </td>
            </tr>
            <tr>
                <th><label for="phone">Recapito telefonico</label></th>

                <td>
                    <input type="text" name="customer[phone]" id="phone" value="<?php echo esc_attr( $meta[phone] ); ?>" class="regular-text" /><br />
                    <span class="description">Recapito telefonico</span>
                </td>
            </tr>
            <tr>
                <th><label for="phone">Indirizzo di spedizione</label></th>

                <td>
                    <input type="text" name="customer[address]" id="address" value="<?php echo esc_attr( $meta[address] ); ?>" class="regular-text" placeholder="Indirizzo"/><br/>
                    <input type="text" name="customer[zip]" id="zip" size="10" style="max-width: 60px" value="<?php echo esc_attr( $meta[zip] ); ?>" class="regular-text" placeholder="CAP"/>
                    <input type="text" name="customer[city]" id="city" value="<?php echo esc_attr( $meta[city] ); ?>" class="regular-text" placeholder="Comune"/><br />
                    <span class="description">Indirizzo, CAP e Comune</span>
                </td>
            </tr>

        </table>
    <?php }

    public function add_subscriptions_meta_box() {
        // Limit meta box to certain post types.
        remove_meta_box( 'ny_open_graph', 'subscriptions', 'advanced' );

        add_meta_box(
            'subscription_data',
            __( 'Dati dell\'abbonamento'),
            array( $this, '_subscription_data' ),
            'subscriptions',
            'normal',
            'low'
        );
    }
    public function get_subscription_data($post_id){
        $_data = get_post_meta($post_id, 'subscription_data', true);
        foreach ($_data as $data => $value){
            $value = maybe_unserialize($value);
            $this->subscription_data->$data = $value;
        }
        return true;
    }
    public function _subscription_data( $post ){
        ?>
        <div class="misc-pub-section">

        <?php
        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );
        $this->get_subscription_data($post->ID);
        $data = $this->subscription_data;
        $checked_1 = (isset($this->subscription_data) && $data->payed_subscription == 1) ? 'checked' : ' ';
        $checked_0 = (!isset($this->subscription_data) || $data->payed_subscription == 0) ? 'checked' : ' ';

        echo '<label class="col-md-12" for="subscription_data[payed_subscription]">Abbonamento pagato</label><br/>';
        echo '
        <div class="radio-section">
        <input type="radio" '.$checked_1.'  name="subscription_data[payed_subscription]" id="subscription_data[payed_subscription][1]" value="1">
        <label for="subscription_data[payed_subscription][1]">Sì</label> ';
        echo '
        <input type="radio" '.$checked_0.'  name="subscription_data[payed_subscription]" id="subscription_data[payed_subscription][0]" value="0">
        <label for="subscription_data[payed_subscription][0]">No</label>
        </div>';

        ?>
            </div>
        <div class="misc-pub-section">

        <label class="col-md-2" for="subscription_data[name]">Nome</label>
            <input type="text" name="subscription_data[name]" id="subscription_data[name]" value="<?php echo $data->name; ?>">
        <label class="col-md-2" for="subscription_data[last_name]">Cognome</label>
            <input type="text" name="subscription_data[last_name]" id="subscription_data[last_name]" value="<?php echo $data->last_name; ?>">
        </div>
        <div class="misc-pub-section">
            <label class="col-md-2" for="subscription_data[address]">Indirizzo di consegna</label><br>
            <input type="text" name="subscription_data[address]" id="subscription_data[address]" value="<?php echo $data->address; ?>"><br>
            <label class="col-md-2" for="subscription_data[zip]">CAP</label><br>
            <input type="text" name="subscription_data[zip]" id="subscription_data[zip]" value="<?php echo $data->zip; ?>"><br>
            <label class="col-md-2" for="subscription_data[city]">Comune</label><br>
            <input type="text" name="subscription_data[city]" id="subscription_data[city]" value="<?php echo $data->city; ?>"><br>
            <label class="col-md-2" for="subscription_data[cf]">Codice fiscale</label><br>
            <input type="text" name="subscription_data[cf]" id="subscription_data[cf]" value="<?php echo $data->cf; ?>"><br>

        </div>

        <?php

    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

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

        $subscription_data =  $_POST['subscription_data'];
        // Update the meta field.
        update_post_meta( $post_id, 'subscription_data', $subscription_data );

    }

}