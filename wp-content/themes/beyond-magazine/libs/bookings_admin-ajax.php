<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 18/07/16
 * Time: 13.06
 */

class bookings_admin {

    public function __construct(){

    }

    public function getBookingsByDate($start, $end){

      $start = explode(',', $start);
      $end = explode(",", $end);
      $_start = mktime(0,0,0,strval($start[0]),strval($start[1]),strval($start[2]));
      $_end = mktime(0,0,0,strval($end[0]),strval($end[1]),strval($end[2]));

      $args = array(
        'post_type' => 'bookings',
        'posts_per_page' => '-1',
        'orderby' => 'date',
        'meta_query' => array(
            array(
              'key'     => 'date',
              'value'   => array( $_start, $_end ),
              'compare' => 'BETWEEN'
            )
          )


      );
      $query = new WP_Query( $args );
      $completeProductList = [];
      $productsList = [];
      $productCollection = [];
      if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
          $query->the_post();
          $id = get_the_ID();
          $products = get_post_meta($id, 'products', TRUE);
          foreach ($products as $key => $product) {
            //if ($product['items']['qt']) {echo $product['name'].'->'.$product['items']['qt'].' pz.<br>';}
            //if ($product['weight']['qt']) {echo $product['name'].'->'.$product['weight']['qt'].'kg<br>';}
              $productCollection[$key]['name'] = $product['name'];
              if ($product['weight']['mu'] == "g"){
                $productWeightInKg = ($product['weight']['qt'])/1000;
              } else {
                $productWeightInKg = $product['weight']['qt'];

              }
              $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
              $productCollection[$key]['items']['mu'] = $product['items']['mu'];
              $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $productWeightInKg;
              $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];
              $_CSVBookings[$id]['orders'][$key]['weight']['mu'] = $product['weight']['mu'];
              $_CSVBookings[$id]['orders'][$key]['weight']['qt'] = $product['weight']['qt'];
              $_CSVBookings[$id]['orders'][$key]['items']['mu'] = $product['items']['mu'];
              $_CSVBookings[$id]['orders'][$key]['items']['qt'] = $product['items']['qt'];
              $completeProductList[$key] = $product['name'];
          }

          $customerName = $meta['userData']['name'];

          foreach ($products as $product){
              $productsList[] = $product['name'];
          }
        }
        echo '----<br>';
      }


      //

      foreach ($productCollection as $key => $row) {
          $prodNames[$key]  = $row['name'];
      }
      ksort($prodNames);
      ksort($productCollection);

      // Sort the data with volume descending, edition ascending
      // Add $data as the last parameter, to sort by the common key
      $CSVproductsQts = 'Totale, ,';
      foreach ($productCollection as $product){
        if ($product['weight']['qt']){
          echo $product['name'].'->'.$product['weight']['qt'].' Kg<br>';
        }
          if ($product['weight']['qt']){
              $CSVproductsQts .= $product['weight']['qt'].' '.$product['weight']['mu'].' ';
          }
          if ($product['items']['qt']){
            echo $product['name'].'->'.$product['items']['qt'].' pz.<br>';
          }
          if ($product['items']['qt']){
              $CSVproductsQts .= $product['items']['qt'].' pz.';
          }
          $CSVproductsQts .= ',';

      }
      $CSVproductsQts .= "\n";
      //echo $CSVproductsQts;

      wp_die();

    }


    public function getMonthEventsByDate($month, $year){
        $calendar =  new Calendar();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $intMonth = $month - 1;
        $stringMonth = $months[$intMonth];

        if ($month == 12){
            $nextMonth = 0;
            $nextMonthYear = $year + 1;
        } else {
            $nextMonth = $month;
            $nextMonthYear = $year;
        }
        $stringNextMonth = $months[$nextMonth];

        $timeMin = 'first day of '.$stringMonth.' '.$year;
        $timeMax = 'first day of '.$stringNextMonth.' '.$nextMonthYear;
        $events = $calendar->getEvents(30, $timeMin, $timeMax);
        echo json_encode($events);
    }
    // @returns: product weight and items MU
    public function getProductMetaById($id){

        if ($id){

            $product = get_post($id);
            $product_meta = get_post_meta($id,'_my_meta', true);
            $product_name = $product->post_title;
            $_product = [];
            // get requested weight
            if (array_key_exists('weight', $_product) && $_product['weight']){
                $w_qt = $_product['weight'];
            } else {
                $w_qt = '';
            }
            // get weight unity measure
            if (array_key_exists('weight_name', $product_meta)){
                $w_mu = $product_meta['weight_name'];
            } else {
                $w_mu = '';
            }

            // get requested itemails
            if (array_key_exists('items', $_product) && $_product['items']){
                $i_qt = $_product['items'];
            } else {
                $i_qt = '';
            }

            // get items unity measure
            if (array_key_exists('items_name', $product_meta)){
                $i_mu = $product_meta['items_name'];
            } else {
                $i_mu = '';
            }


            $products[$id] = array(
                'name' => $product_name,
                'weight' => array(
                    'qt' => $w_qt,
                    'mu' => $w_mu
                ),
                'items' => array(
                    'qt' => $i_qt,
                    'mu' => $i_mu
                )
            );

        }

        $this->productsJson = $products;
        return $this->productsJson;


    }

}
