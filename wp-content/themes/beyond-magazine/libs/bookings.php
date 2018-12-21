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
            'supports'           => array( 'title','author')
        );
        register_post_type( $post_type, $args );

        add_action( 'add_meta_boxes', array( $this, 'add_bookings_meta_boxes' ) );

        add_action( 'save_post', array( $this, 'save' ) );

        add_action('admin_enqueue_scripts', array( $this, 'enqueue_js' ));
        add_filter( 'mailchimp_sync_user_data', function( $data, $user ) {
            $data['EUID'] = $user->field_name;
            return $data;
        }, 10, 2 );

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
        $user_data =  $_POST['userData'];
        $date =  $_POST['date'];
        // Update the meta field.
        update_post_meta( $post_id, 'products', $subscription_data );
        update_post_meta( $post_id, 'userData', $user_data );
        update_post_meta( $post_id, 'date', $date/1000 );

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
    public function bookings_metabox($post){
        wp_nonce_field( 'sub_inner_custom_box', 'sub_inner_custom_box_nonce' );

        $products_meta = get_post_meta($post->ID, 'products', true);
        $user_meta = get_post_meta($post->ID, 'userData', true);
        $date = ( (int) get_post_meta($post->ID, 'date', true)) ;
        echo "<style type='text/css'>
            #ui-datepicker-div td.not_available a{
                background-color: #dddddd;
                color: #999999
            }
            #ui-datepicker-div td.available a{
                background-color: lightblue;
                color: black;
            }
            #ui-datepicker-div .ui-datepicker-today td.available a{
                text-decoration: underline;

            }
            input.productQtyInput{
              width: 60px;
            }
            </style>";
        echo "<div ng-app='bookingsApp' ng-controller='ProductsController'>";
        if ($products_meta){
            echo "<div data-ng-init='products = ".json_encode($products_meta)."'></div>";
        } else {
            echo "<div data-ng-init='products = {}'></div>";
        }
        echo '        <script type = "text/template" getdate>
            {"data_type":"date", "data":'.json_encode($date*1000).'}
        </script>';
        echo "<table>";
        echo '<thead><tr><th></th><th>Prodotto</th><th>Peso</th><th>Pezzi</th></tr></thead>';
        echo "
            <tr data-ng-repeat = '(id, product) in products'>
                <td>
                    <a href='#' data-ng-click='deleteProduct(\$event, id)' class='button'>X</a>
                    <input value='{{product.name}}' type='hidden' name='products[{{id}}][name]'>
                </td>
                <td>{{product.name}}</td>
                <td><input class='productQtyInput'  name='products[{{id}}][weight][qt]' data-ng-model='product.weight.qt'><input value='{{product.weight.mu}}' type='hidden' name='products[{{id}}][weight][mu]'>{{product.weight.mu}}</td>
                <td><input class='productQtyInput'  name='products[{{id}}][items][qt]' data-ng-model='product.items.qt'><input value='{{product.items.mu}}' type='hidden' name='products[{{id}}][items][mu]'>{{product.items.mu}}</td>
            </tr>";
        foreach($products_meta as $id => $product){



            $product_name = $product['name'];
            if (!$product['weight']['qt']){
                $product['weight']['qt'] = 0;
            }
            if (!$product['items']['qt']){
                $product['items']['qt'] = 0;
            }
            if ($product['weight']['mu']){
                $weight = "<input data-ng-model='meta[\"products\"][\"$id\"][\"weight\"]' value='".$product['weight']['qt']."' name='meta[products][$id][weight]'> ".$product['weight']['mu'];
            } else {
                $weight = '';
            }
            if ($product['items']['mu']){
                $items = "<input data-ng-model='meta[\"products\"][\"$id\"][\"items\"]' value='".$product['items']['qt']."' name='meta[products][$id][items]'> ".$product['items']['mu'];
            } else {
                $items = '';
            }



        }

        echo "</table>";
        add_thickbox(); ?>
            <div id="add-product" style="display:none;">
                <h4>Aggiungi un prodotto</h4>
                <p>
                    <select id="newProductSelect" update-new-product STYLE="margin-top: -3px" data-ng-model="newProduct.name">
                        <?php
                        $args = array(
                            'posts_per_page'    => -1,
                            'orderby'           => 'title',
                            'order'             => 'ASC',
                            'post_type'         => 'products',
                            'post_status'       => 'publish');

                            $_products = get_posts($args);
                        foreach ($_products as $_id => $_product){
                            $meta = get_post_meta($_product->ID,'_my_meta', true);

                            echo "<option
                                data-weight='$meta[weight]'
                                data-weight_name='$meta[weight_name]'
                                data-items='$meta[items]'
                                data-items_name='$meta[items_name]'
                                data-id='$_product->ID'
                                >$_product->post_title</option>";
                        }
                        ?>
                    </select>
                    <input type="text" size="5" style="width: 100px" placeholder="{{newProduct.items.mu}}" data-ng-hide="!showNPItems" data-ng-model="newProduct.items.qt">
                    <input style="width: 100px" type="text" size="5"  placeholder="{{newProduct.weight.mu}}" data-ng-hide="!showNPWeight" data-ng-model="newProduct.weight.qt">
                </p>
                <?php
                ?>
                <a class='button' data-ng-click='addProduct()'>Aggiungi</a>
            </div>

            <a href="#TB_inline?width=600&height=150&inlineId=add-product" class="button thickbox">Aggiungi prodotto</a>
        <?php
        echo "<table>";
        if ($user_meta[delivery]){
            $yes = 'checked';
            $no = '';
        } else {
            $no = 'checked';
            $yes = '';

        }
        echo "<tr><td><label for='date'>Data</label></td><td><a href='#!' class='button' id='pickertrigger'>{{date | date:'d/M/yyyy'}}</a>";
        echo "<input id='date' name='date' data-ng-model='date' type='text' style='display:none'>";
        echo "</td></tr>";
        echo "<tr><td><label for='userData[name]'>Nome</label></td><td><input type='text' name='userData[name]' value='".$user_meta[name]."'/></td></tr>";
        echo "<tr><td><label for='userData[email]'>Indirizzo E-mail</label></td><td><input type='text' name='userData[email]' value='".$user_meta[email]."'/></td></tr>";
        echo "<tr><td><label for='userData[phone]'>Telefono</label></td><td><input type='text' name='userData[phone]' value='".$user_meta[phone]."'/></td></tr>";
        echo "<tr><td><label for='userData[delivery]'>Consegna a domicilio</label></td><td><input type='radio' ".$yes." name='userData[delivery]' value='1'/>Sì<input type='radio' ".$no." name='userData[delivery]' value='0'/>No</td></tr>";
        echo "<tr><td><label for='userData[address]'>Indirizzo di consegna</label></td><td><input type='text' name='userData[address]' value='".$user_meta[address]."'/></td></tr>";
        echo "<tr><td><label for='userData[notes]'>Note</label></td><td><textarea cols='50' rows='10' name='userData[notes]'>".$user_meta[notes]."</textarea></td></tr>";
        echo "</table>";
        echo "</div>";


    }
}

function get_month_events() {
    $month = $_POST['month'];
    $year = $_POST['year'];
    if ($month && $year) {
        $admin = new bookings_admin();
        $admin->getMonthEventsByDate($month, $year);
    }
    wp_die();
}

function getBookingsByDate(){
  $start = $_POST['start'];
  $end = $_POST['end'];
  if ($start && $end){
    $request = new bookings_admin();
    $request->getBookingsByDate($start, $end);
  }
}

add_action('wp_ajax_get_month_events', 'get_month_events');
add_action('wp_ajax_nopriv_get_month_events', 'get_month_events');
add_action('wp_ajax_getBookingsByDate', 'getBookingsByDate');
add_action('wp_ajax_nopriv_getBookingsByDate', 'getBookingsByDate');
