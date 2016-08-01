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
    }

    function admin_menu() {
        add_submenu_page('edit.php?post_type=bookings',
            'Custom Post Type Admin',
            'Riepilogo prenotazioni',
            'edit_posts',
            basename(__FILE__),
            array($this, 'settings_page')
        );

    }
    function  settings_page() {
        ?>
        <h1>Opzioni prenotazioni</h1>

            <?php
            $calendar = new Calendar();
            $events = $calendar->getEvents();
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
                    echo "<table >
                        <thead>
                        <tr><th colspan='3' style='text-align: left'>$name</th></tr>
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

                        echo "<tr>
                            <td>".$product['name']."</td>
                            <td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>
                            <td>".$product['items']['qt'].' '.$product['items']['mu']."</td>
                            </tr>";
                    }


                }
                echo "<table >
                        <thead>
                        <tr><th colspan='3'>Totale</th></tr>
                        <tr><th>Prodotto</th><th>Peso</th><th>Pezzi</th></tr>
                        </thead>
                        <tbody>";
                foreach ($productCollection as $product){

                    echo "<tr>
                            <td>".$product['name']."</td>
                            <td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>
                            <td>".$product['items']['qt'].' '.$product['items']['mu']."</td>
                            </tr>";
                }
                echo "</tbody>
                        </table>";
            } ?>

        <?php

        echo '<a target="_blank" class="button" href="edit.php?post_type=bookings&page=pdf-bookings.php&date='.$date.'">Stampa riassunto prenotazioni</a>';
    }

}
add_action('admin_menu' , new OptionsPage());


