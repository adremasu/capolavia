<?php
/**
 * @package Tables
 * @version 0.1
 */
/*
Plugin Name: Tables
Plugin URI: http://capolavia.org
Description: Manage availability of products
Author: Matt Mullenweg
Version: 0.1
Author URI: http://capolavia.it/
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Products_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {

        parent::__construct( [
            'singular' => __( 'Prodotto', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Prodotti', 'sp' ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ] );

    }


    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_products( $per_page = 5, $page_number = 1 ) {
        $type = 'products';
        $args=array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts'=> 1
        );
        $my_query = null;
        $my_query = new WP_Query($args);
        $products =  $my_query->posts;
        $array_products = json_decode(json_encode($products),TRUE);

        foreach ($array_products as $product){
            $availability = get_post_meta( $product[ID], 'disponibilita', TRUE);
            $product['availability'] = $availability;
            $result[]=$product;
        }

        return $result;
    }


    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     * @param int $value availability
     */
    public static function update_product( $id, $value ) {

        update_post_meta($id, "disponibilita", $value);
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        $type = 'products';
        $args=array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts'=> 1
        );
        $my_query = null;
        $my_query = new WP_Query($args);
        $count =  $my_query->post_count;

        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}customers";

        //return $wpdb->get_var( $sql );
        return $count;
    }


    /** Text displayed when no customer data is available */
    public function no_items() {
        _e( 'No products available.', 'sp' );
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'post_title':
            case 'city':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {

        $available_yes = ($item[availability] ? "checked" : "");
        $available_no = ($item[availability] ? "" : "checked");

        return sprintf(
            '<input type="radio" name="bulk-update[%1$s]" %2$s value="1">Sì <input type="radio" name="bulk-update[%1$s]" %3$s value="0">No', $item['ID'], $available_yes, $available_no
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name( $item ) {

        $update_nonce = wp_create_nonce( 'sp_update_product' );

        $title = '<strong>' . $item['post_title'] . '</strong>';

        $actions = [
            'update' => sprintf( '<a href="?page=%s&action=%s&product=%s&_wpnonce=%s">Aggiorna</a>', esc_attr( $_REQUEST['page'] ), 'update', absint( $item['ID'] ), $update_nonce )
        ];

        return $title . $this->row_actions( $actions );
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'post_title'    => __( 'Prodotto', 'sp' ),

            'cb'      => '<input type="checkbox" />'
        ];

        return $columns;
    }


    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'post_title' => array( 'post_title', true )
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-update' => 'Aggiorna'
        ];

        return $actions;
    }


    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'products_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );

        $this->items = self::get_products( $per_page, $current_page );
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...

        if ( 'update' === $this->current_action() ) {
            foreach($_GET['bulk-update'] as $event) {
                var_dump($event);
            }
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );
            if ( ! wp_verify_nonce( $nonce, 'sp_update_product' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::update_product( absint( $_GET['product'] ), $value );

                wp_redirect( esc_url( add_query_arg() ) );
                exit;
            }

        }

        // If the update bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-update' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-update' )
        ) {

            $update_ids = esc_sql( $_POST['bulk-update'] );

            // loop over the array of record IDs and update them
            foreach ( $update_ids as $id => $value ) {
                self::update_product( $id, $value );

            }
            wp_redirect( esc_url( add_query_arg() ) );
        }
    }

}


class SP_Plugin {

    // class instance
    static $instance;

    // customer WP_List_Table object
    public $products_obj;

    // class constructor
    public function __construct() {
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
    }


    public static function set_screen( $status, $option, $value ) {
        return $value;
    }

    public function plugin_menu() {

        $hook = add_menu_page(
            'Gestione disponibilità',
            'Disponibilità',
            'manage_options',
            'wp_list_table_class',
            [ $this, 'plugin_settings_page' ]
        );

        add_action( "load-$hook", [ $this, 'screen_option' ] );

    }


    /**
     * Plugin settings page
     */
    public function plugin_settings_page() {
        echo '<style type="text/css">';
        echo '.wp-list-table .column-cb { width: 30%; }';
        echo '</style>';
        ?>

        <div class="wrap">
            <h2>Disponibilità dei prodotti</h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <?php
                                $this->products_obj->prepare_items();
                                $this->products_obj->display(); ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
    <?php
    }

    /**
     * Screen options
     */
    public function screen_option() {

        $option = 'per_page';
        $args   = [
            'label'   => 'Prodotti',
            'default' => 5,
            'option'  => 'products_per_page'
        ];

        add_screen_option( $option, $args );

        $this->products_obj = new Products_List();
    }


    /** Singleton instance */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}


add_action( 'plugins_loaded', function () {
    SP_Plugin::get_instance();
} );
