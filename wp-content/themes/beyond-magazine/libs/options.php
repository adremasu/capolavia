<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 05/07/16
 * Time: 0.01
 */

class OptionsPage {

    function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'booking_settings_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ));

    }
    public function enqueue_js( $hook ) {

      if ( isset( $_GET['page']) && $_GET['page'] == 'analysis_page.php' ){
        wp_enqueue_script( 'angularjs',   get_bloginfo('template_directory'). '/js/angular.min.js' );

        wp_register_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
        wp_register_script('analysis_page', get_bloginfo('template_directory'). '/libs/main/analysis_page.js');
        wp_enqueue_script('google_charts');
        wp_enqueue_script('analysis_page');

      }
      if ('post.php' != $hook && 'post-new.php' != $hook) {
            return;
        }

        wp_enqueue_style('plugin_name-admin-ui-css',
            'https://code.jquery.com/ui/jquery-ui-git.css',
            false,
            PLUGIN_VERSION,
            false);
        wp_enqueue_script( 'angularjs',   get_bloginfo('template_directory'). '/js/angular.min.js' );
        wp_enqueue_script( 'bookingAdmin',   get_bloginfo('template_directory'). '/libs/main/admin.js', array(), '5.6' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }
    function admin_menu() {
      add_submenu_page('edit.php?post_type=bookings',
          'Riepilogo prenotazioni',
          'Riepilogo prenotazioni',
          'edit_posts',
          basename(__FILE__),
          array($this, 'settings_page')
      );
      add_submenu_page('edit.php?post_type=bookings',
          'Analisi prenotazioni',
          'Analisi prenotazioni',
          'edit_posts',
          'analysis_page.php',
          array($this, 'analysis_page')
        );
        add_submenu_page('edit.php?post_type=bookings',
            'Opzioni prenotazioni',
            'Opzioni prenotazioni',
            'edit_posts',
            'bookings_options_page.php',
            array($this, 'bookings_options_page')
        );
        add_submenu_page('edit.php?post_type=bookings',
            'Gestione prenotazioni',
            'Gestione prenotazioni',
            'edit_posts',
            'booking_manager.php',
            array('BookingManager', 'init')
        );

    }
    function booking_settings_init(  ) {

        register_setting( 'booking_settings', 'booking_email_address' );
        register_setting( 'booking_settings', 'booking_calendar_id' );
        register_setting( 'booking_settings', 'booking_google_developer_key' );
        register_setting( 'booking_settings', 'booking_store_calendar_id' );
        register_setting( 'booking_settings', 'booking_delivery_calendar_id' );
        register_setting( 'booking_settings', 'booking_searchdate_range_min' );
        register_setting( 'booking_settings', 'booking_searchdate_range_max' );
        register_setting( 'booking_settings', 'booking_eventsnumber_min' );
        register_setting( 'booking_settings', 'booking_eventsnumber_max' );

        add_settings_section(
            'bookings_options',
            __( 'Impostazioni delle prenotazioni', 'beyondmagazine' ),
            'bookings_options_page',
            'capolavia'
        );
        add_settings_field(
            'booking_email',
            __( 'Email di amministrazione'),
            array(this, 'email_field_callback'),
            'bookings_options_page',
            'bookings_options'
        );


    }
    function analysis_page(){


      echo "<h1>Analisi prenotazioni</h1>";


      echo "<div ng-app='analysisApp' ng-controller='ChartsController'>";



      $args = array(
        'numberposts'      => -1,
        'order'            => 'DESC',
        'post_type'        => 'bookings',
        'post_status'       => 'any'
    );
      $bookings = get_posts($args);
      $productArgs = array(
          'numberposts'      => -1,
          'order'            => 'ASC',
          'post_type'        => 'products',
          'post_status'       => 'any',
          'orderby'           => 'title'
      );
      $products = get_posts($productArgs);
      $emailAddresses = array();
      if (isset($_POST['emailAddress'])){
        $emailAddress = $_POST['emailAddress'];
      }
      if (isset($_POST['product'])){
        $product = $_POST['emailAddress'];
      }
      foreach ($bookings as $booking) {
        $bookingEmail = get_post_meta($booking->ID, 'userData', true);
        $booking->products = get_post_meta($booking->ID, 'products', true);
        $booking->email = $bookingEmail['email'];
        $emailAddresses[] = strtolower($booking->email);

        if (!$emailAddress ){
          $booking->week = date("Y-W", strtotime($booking->post_date));
          $weeks[] = $booking->week;
        } else {
          if ($emailAddress == $booking->email){
            $booking->week = date("Y-W", strtotime($booking->post_date));
            $weeks[] = $booking->week;
          } else {
            $booking->week = date("Y-W", strtotime($booking->post_date));
            $noWeeks[] = $booking->week;
          }
        }


      }

      $bookingsCount = array_count_values($weeks);

      $noBookingsCount = array_count_values($noWeeks);
      foreach($noBookingsCount as $date => $number){
        $noBookingsCount[$date] = 0;
      }
      if ($noBookingsCount){
        $bookingsCount = array_merge($noBookingsCount, $bookingsCount);
      }
      $weeksString = '';
      foreach ($bookingsCount as $key => $value) {
        $weeksString .= '["'.$key.'",'.$value.'],';
      }

      $weeksString = substr($weeksString,0,-1);

      $emailAddresses = array_unique($emailAddresses);
      asort($emailAddresses);
      echo "<form action='/wp-admin/edit.php?post_type=bookings&page=analysis_page.php' method='POST'>";
      echo "<select name='emailAddress'><option value=''>Tutte le prenotazioni</option>'";
      foreach ($emailAddresses as $emailAddress) {
        echo '<option value="'.$emailAddress.'">'.$emailAddress.'</option>';
      }
      echo "</select>";

      echo "<select name='product'><option value=''>Tutte i prodotti</option>'";
      foreach ($products as $product) {
        echo '<option value="'.$product->ID.'">'.$product->post_title.'</option>';
      }
      echo "</select><input type='submit' value='Cerca'></form>";

//      var_dump($bookings);


      echo '        <script type = "text/template" getdata>
          {"data_type":"dates", "data":'."[$weeksString]".'}
      </script>';
      echo '<div id="chart_div"></div></div>';
    }
    function bookings_options_page() {
        ?>
        <div class="wrap">
            <h1>Impostazioni delle prenotazioni</h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'booking_settings' ); ?>
                <?php do_settings_sections( 'booking_settings' );
                ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Indirizzo E-mail per gestione ordini</th>
                        <td><input type="text" name="booking_email_address" value="<?php echo esc_attr( get_option('booking_email_address') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Google Calendar ID</th>
                        <td><input type="text" name="booking_calendar_id" value="<?php echo esc_attr( get_option('booking_calendar_id') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Google Developer Key</th>
                        <td><input type="text" name="booking_google_developer_key" value="<?php echo esc_attr( get_option('booking_google_developer_key') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Store Calendar ID</th>
                        <td><input type="text" name="booking_store_calendar_id" value="<?php echo esc_attr( get_option('booking_store_calendar_id') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Search date range MAX - MIN</th>
                        <td><input type="text" name="booking_searchdate_range_min" value="<?php echo esc_attr( get_option('booking_delivery_calendar_id') ); ?>" /></td>
                        <td><input type="text" name="booking_searchdate_range_max" value="<?php echo esc_attr( get_option('booking_delivery_calendar_id') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Delivery Calendar ID</th>
                        <td><input type="text" name="booking_delivery_calendar_id" value="<?php echo esc_attr( get_option('booking_delivery_calendar_id') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Events number MAX - MIN</th>
                        <td><input type="text" name="booking_eventsnumber_min" value="<?php echo esc_attr( get_option('booking_delivery_calendar_id') ); ?>" /></td>
                        <td><input type="text" name="booking_eventsnumber_max" value="<?php echo esc_attr( get_option('booking_delivery_calendar_id') ); ?>" /></td>
                    </tr>                    
                </table>
                <?php submit_button(); ?>

            </form>
        </div>
    <?php
    }

    function  settings_page() {
        ?>
        <h1>Riepilogo prenotazioni</h1>

            <?php
            $calendar = new Calendar();
            $events = $calendar->getEvents(1, 'today');
            foreach($events as $event){
                $date =  date('U',strtotime($event['dateTime']));
                echo '<h3>Prenotazioni per '.date_i18n('l j F ',strtotime($event['dateTime'])).'</h3>';
                $bookings = $calendar->getBookingsByDate($date);
                $productsSum = $productCollection = [];
                foreach ($bookings as $booking){
                    $productsSum[$booking->ID] = [];
                    $meta = $booking->meta;
                    $products = $meta['products'];
                    $name = $meta['userData']['name'];
                    $bookingLink = get_edit_post_link($booking->ID);
                    echo "<table >
                        <thead>
                        <tr><th colspan='3' style='text-align: left'>$name</th><th><a href='".$bookingLink."'>[modifica]</a></th></tr>
                        </thead>
                        <tbody>";
                    foreach ($products as $key => $product){
                        $productsSum[$booking->ID][$key]['weight'] = $product['weight']['qt'];
                        $productsSum[$booking->ID][$key]['items'] = $product['items']['qt'];
                        $productCollection[$key]['name'] = $product['name'];
                        $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
                        $productCollection[$key]['items']['mu'] = $product['items']['mu'];
                        $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
                        $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];

                        echo "<tr>";
                        echo "<td>".$product['name']."</td>";

                        if($product['weight']['qt']){
                            echo "<td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>";
                        }
                        if($product['items']['qt']){
                            echo "<td>".$product['items']['qt'].' '.$product['items']['mu']."</td>";
                        }
                        echo "</tr>";
                    }


                }
                echo "<table >
                        <thead>
                        <tr><th colspan='3'>Totale</th></tr>
                        <tr><th>Prodotto</th><th>Peso</th><th>Pezzi</th></tr>
                        </thead>
                        <tbody>";
                // Obtain a list of columns
                foreach ($productCollection as $key => $row) {
                    $prodName[$key]  = $row['name'];
                }
                // Sort the data with volume descending, edition ascending
                // Add $data as the last parameter, to sort by the common key
                array_multisort($prodName, SORT_ASC, $productCollection);

                foreach ($productCollection as $product){

                    echo "<tr>
                            <td>".$product['name']."</td>
                            <td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>
                            <td>".$product['items']['qt'].' '.$product['items']['mu']."</td>
                            </tr>";
                }
                echo "</tbody>
                        </table>";
            }

        echo '<a target="_blank" class="button" href="edit.php?post_type=bookings&page=pdf-bookings.php&date='.$date.'">Stampa riassunto prenotazioni</a>';
        echo '<a target="_blank" class="button" href="edit.php?post_type=bookings&page=pdf-bookings.php&date='.$date.'&format=TT">Prenotazioni in CSV</a>';
    }

}
add_action('admin_menu' , new OptionsPage());
