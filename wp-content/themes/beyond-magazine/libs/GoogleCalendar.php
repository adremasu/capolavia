<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 09/07/16
 * Time: 15.43
 */
class Calendar {
    var $calendarId = 'r7t3hsima10qg4m7ioai4dp0ek@group.calendar.google.com';
    var $DEV_KEY = 'AIzaSyAjQWG185xA0VuVJyT2k8hagupYYd4DrRQ';

    public function __construct(){

        $this->tomorrow = date('c', strtotime('tomorrow'));
        date_default_timezone_set('Europe/Rome');
        define('APPLICATION_NAME', 'Gestione consegne');
        define('CREDENTIALS_PATH', get_template_directory() . '/calendar-php-quickstart.json');

        $this->id = get_option('booking_options[calendarId]');
        define('CLIENT_SECRET_PATH', get_template_directory() . '/client_secret.json');

        define('SCOPES', implode(' ', array(
                Google_Service_Calendar::CALENDAR_READONLY)
        ));


        $client = new Google_Client();
        $client->setApplicationName("My Application");
        $client->setDeveloperKey($this->DEV_KEY);
        $this->service = new Google_Service_Calendar($client);

        // Print the next event on the user's calendar.


    }

    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    public function expandHomeDirectory($path) {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

    public function getAvailableProducts(){
        $my_query = new WP_Query(
            array(
                'post_type' => 'products',
                'nopaging'=> true,
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'disponibilita',
                        'value' => '1'
                    )
                )

            )
        );

        $products =  $my_query->posts;
        $array_products = json_decode(json_encode($products),TRUE);
        return $array_products;
    }

    public function setCalendarId(){
        return update_option('booking_options[calendarId]', $this->id);
    }

    public function getEvents($eventsNumber = 1, $timeMin = 'today' ){


        $optParams = array(
            'maxResults' => $eventsNumber,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c', strtotime($timeMin)),
        );

        $results = $this->service->events->listEvents($this->calendarId, $optParams);

        foreach ($results->getItems() as $item) {
            $events[] = $item->getStart();
        }
        return $events;
    }

    public function getBookingsByDate($date){
        if (!$date){
            return false;
        } else {
            $my_query = new WP_Query(
                array(
                    'post_type' => 'bookings',
                    'nopaging'=> true,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'date',
                            'value' => $date
                        )
                    )

                )
            );

            $bookings = $my_query->posts;
            foreach($bookings as $id => $booking){
                $metas = get_post_meta($booking->ID, '', true);
                $_meta = [];
                foreach($metas as $key => $meta){
                    $val = get_post_meta($booking->ID, $key, true);
                    $_meta[$key] = $val;
                }
                $bookings[$id]->meta = $_meta;
            }
            return $bookings;

        }
    }
}