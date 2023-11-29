<?php
/**
 * Template Name: Telegram page
 */
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 07/11/23
 * Time: 08.31
 */
$apiToken = "6583450275:AAH3CVOPr7YRTJAn_weHxaQbD_CZ6GWX5z4";
date_default_timezone_set('Europe/Rome'); 
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/dev_key.php';
define('APPLICATION_NAME', 'Riepilogo Ordini');
define('CREDENTIALS_PATH', __DIR__ . '/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

define('SCOPES', implode(' ', array(
        Google_Service_Calendar::CALENDAR_READONLY)
));

            $calendar = new Calendar();
            $events = $calendar->getEvents(1, 'today');
            foreach($events as $event){
                $date =  date('U',strtotime($event['dateTime']));
                $bookings = $calendar->getBookingsByDate($date);
                $productsSum = $productCollection = [];
                foreach ($bookings as $booking){
                    $productsSum[$booking->ID] = [];
                    $meta = $booking->meta;
                    $products = $meta['products'];
                    $name = $meta['userData']['name'];
                    $bookingLink = get_edit_post_link($booking->ID);
                    foreach ($products as $key => $product){
                        $productsSum[$booking->ID][$key]['weight'] = $product['weight']['qt'];
                        $productsSum[$booking->ID][$key]['items'] = $product['items']['qt'];
                        $productCollection[$key]['name'] = $product['name'];
                        $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
                        $productCollection[$key]['items']['mu'] = $product['items']['mu'];
                        $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
                        $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];
                    }


                }

                // Obtain a list of columns
                foreach ($productCollection as $key => $row) {
                    $prodName[$key]  = $row['name'];
                }
                // Sort the data with volume descending, edition ascending
                // Add $data as the last parameter, to sort by the common key
                array_multisort($prodName, SORT_ASC, $productCollection);
                $text = '';
                foreach ($productCollection as $product){

                    /*$text .= "<tr>
                            <td>".$product['name']."</td>
                            <td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>
                            <td>".$product['items']['qt'].' '.$product['items']['mu']."</td>
                            </tr>";
                            */
                            $text .= "$product[name] ".$product['weight']['qt'].$product['weight']['mu'].' '.$product['items']['qt'].'pz.'. "\n";
                }

            }
                          $data = [
      'chat_id' => '155860140',
      'text' => $text
  ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
                                 http_build_query($data) );
