<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 27/06/16
 * Time: 18.07
 */

function book_products() {

    new book_productsClass();
}
function get_product_info() {

    new get_product_infoClass();
}


add_action('wp_ajax_book_products', 'book_products');
add_action('wp_ajax_nopriv_book_products', 'book_products');

add_action('wp_ajax_get_product_info', 'get_product_info');
add_action('wp_ajax_nopriv_get_product_info', 'get_product_info');


class get_product_infoClass {

    public function __construct(){
        $this->id = $_POST['id'];
        echo $this->get_product_info();
        wp_die();
    }
    public function get_product_info() {
        if ($this->id){
            $_post = get_post($this->id);
            if ($_post->post_type == 'products'){
                $product = [];
                $product['name'] = $_post->post_title;
                $product['content'] = strip_tags($_post->post_content);
                $thumb_id = get_post_thumbnail_id($this->id);
                $thumb_url = wp_get_attachment_image_src($thumb_id,'medium', true);
                $product['img'] =  $thumb_url[0];
                return json_encode($product);
            } else  {
                return false;
            }
        } else {
            return false;
        }
    }
}


class book_productsClass {
    var $products = array();
    var $userData = array();
    var $userMessage = 'Qualcosa è andato storto, riprova';
    var $success = false;
    var $error = false;
    var $productsJson = null;

    public function __construct(){
        foreach($_POST['products'] as $id=>$p){
            $p['id']=$id;
            $this->products[$id]=$p;             
        }
        $this->userData = $_POST['user'];
        $this->date = $_POST['date'];
        $this->mode = $_POST['mode'];
        $this->notes = $this->userData['notes'];
        //$this->delivery = $this->userData['delivery'];
        $this->phone = $this->userData['phone'];

        $this->getBookingProducts();

        if ($this->saveBooking()){
            $this->userMessage = 'Grazie per aver prenotato, riceverai la conferma all\'indirizzo email da te indicato.';
            $this->success = true;
        } else  {

        }
        $this->return = json_encode(
            array(
                'userMessage' => $this->userMessage,
                'success' => $this->success,
                'error' => $this->error,
            )
        );
        echo $this->return;
        wp_die();
    }

    private function saveBooking(){
        if ($this->isNewBooking()){
            if ($this->sendUserEmail() && $this->sendAdminEmail()){
                return $this->saveNewBooking();
                }
        } else {
            return $this->updateBooking();
        }
    }


    private function _orderEmail($isAdmin = false){
        $this->emailMessage = '';
        $products = ($this->productsJson ? $this->productsJson : $this->getBookingProducts());
        $this->emailMessage .= "<table width='100%'>";
        foreach($products as $id => $product){
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
            if ($weight || $items){
                $this->emailMessage .= "<tr>
                <td>$product_name</td>";
                if ($weight){
                    $this->emailMessage .= "<td>".$weight."</td>";
                }
                if($items){
                    $this->emailMessage .= "<td>".$items."</td>";
                }
                $this->emailMessage .= "</tr>";
            }
        }
        $this->emailMessage .= "</table>";
        $this->emailMessage .= "<table width='100%'>";
        $this->emailMessage .= "<tr>";
        
        if ($this->mode != 'store'){
            $this->emailMessage .= "<td>Consegna prevista per ".date_i18n('l j F Y', $this->date)." dalle ore ".date_i18n('H:i ', $this->date)." al seguente indirizzo: ".$this->userData['address']."</td>";
        } else {
            $this->emailMessage .= "<td>Consegna prevista per ".date_i18n('l j F Y', $this->date)." dalle ore ".date_i18n('H:i ', $this->date)." in azienda (via Rodolfo Rossi,66)</td>";
        }

        $this->emailMessage .= "</tr>";
        $this->emailMessage .= "<tr>";
        $this->emailMessage .= "<td>NOTE: ".$this->notes."</td>";

        $this->emailMessage .= "</tr>";
        if ($isAdmin) {
          $this->emailMessage .= "<tr>";
          $this->emailMessage .= "<td>TELEFONO: <a href='tel:".$this->phone."'>".$this->phone."</a> </td>";
          $this->emailMessage .= "</tr>";
        }
        $this->emailMessage .= "</table>";


        return $this->emailMessage;
    }

    private function sendAdminEmail(){
        $emailAddress = get_option('booking_email_address');
        $date = date_i18n('l j F Y', $this->date);
        $emailMessage = '<p>Ordine per '.$date.'</p>';

        $emailMessage .= $this->_orderEmail(true);
        $customerEmail = $this->userData['email'];
        $header = 'From: '.$this->userData["name"].' <'.$this->userData['email'].'>' . "\r\n";

        if (wp_mail($emailAddress, 'Ordine da '.$customerEmail. ' ('.$this->userData["name"].')' , $emailMessage, $header)){
            return true;
        } else {
            return false;
        }

    }

    private function sendUserEmail(){
        $emailAddress = $this->userData['email'];
        $date = date_i18n('l j F Y \d\a\l\l\e H:i', $this->date);
        $emailTemplate = new emailTemplate();
        $emailMessage = $emailTemplate->getTopTemplate($date);
        $emailMessage .= "<table width='100%'>";
        $emailMessage .= "<tr>";
        $emailMessage .= "<td><b>Gentile ".$this->userData['name'].", abbiamo ricevuto da te il seguente ordine</b></td>";
        $emailMessage .= "</tr>";

        $emailMessage .= "</table>";
        $emailMessage .= $this->_orderEmail();
        $emailMessage .= $emailTemplate->getTemplate_bottom();
        $header = 'From: Sapori di Capolavia <ordini@capolavia.it>' . "\r\n";

        if(wp_mail($emailAddress, 'Ordine confermato', $emailMessage, $header)){

            return true;

        } else {
            return false;
        }


    }

    public function isNewBooking(){
        $emailAddress = $this->userData['email'];
        $bookingDay = $this->date;
        $args = array(
            'post_type' => 'bookings',
            'meta_query' => array(
                array(
                    'key' => 'userData[email]',
                    'value' => $emailAddress,
                    'compare' => '='

                ),
                array(
                    'key'     => 'booking_date',
                    'value'   => $bookingDay,
                    'compare' => '='
                )
            )

        );
        $query = new WP_Query( $args );
        if ($query->found_posts > 0){
            return false;
        } else {
            return true;
        }


    }
    private function isNewUser(){
    }

    /*
    create a json file with requested products
    */
    public function getBookingProducts(){
        $_products = $this->products;

        // order products by name
        usort($_products, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        foreach ($_products as $i => $_product){
            $id = $_product['id'];
            $product = get_post($id);
            $product_meta = get_post_meta($id,'_my_meta', true);
            $product_name = $_product['name'];

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

            // get requested items
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
    private function saveNewBooking(){
        $this->hash = md5($this->date.$this->userData['email']);
        $post_author = get_user_by('email', $this->userData['email']);
        if ($post_author){
          $post_author_ID = $post_author->ID;
        } else {
          $post_author_ID = '';
        }
        $args = array(
            'post_title' => date('Y_m_d', $this->date).' '.$this->userData['email'],
            'post_type' => 'bookings',
            'post_author' => $post_author->ID,
            'meta_input' => array(
                'userData' => $this->userData,
                'products' => $this->productsJson,
                'date' => $this->date,
                'mode' => $this->mode,
                'hash' => $this->hash
            )
        );

        $newBooking = wp_insert_post($args, true);
        if (!is_wp_error($newBooking)){
            return true;
        } else {
            $this->error = $newBooking;
            return false;
        }
    }

    private function updateBooking(){
        return true;
    }

}
