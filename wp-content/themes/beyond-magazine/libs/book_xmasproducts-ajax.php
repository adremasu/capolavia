<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 27/06/16
 * Time: 18.07
 */

function book_xmasproducts() {
    new book_xmasproductsClass();
}

add_action('wp_ajax_book_xmasproducts', 'book_xmasproducts');
add_action('wp_ajax_nopriv_book_xmasproducts', 'book_xmasproducts');


class book_xmasproductsClass {
    var $products = array();
    var $userData = array();
    var $userMessage = 'Qualcosa è andato storto, riprova';
    var $success = false;
    var $error = false;
    var $productsJson = null;

    public function __construct(){
        foreach($_POST['xmasproducts'] as $id=>$p){
            $p['id']=$id;
            $this->xmasproducts[$id]=$p;             
        }
        $this->userData = $_POST['user'];
        $this->date = $this->userData['timestampdate'];
        $this->notes = $this->userData['notes'];
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

    public function isDateInMilliseconds($date){
        if ($date < 100000000) {
            return false;
        } else {
            return true;
        }
    }

    public function dateInSeconds($date){
        if ($this->isDateInMilliseconds($date)){
            return (int) $date/1000;
        } else {
            return $date;
        }
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
            if ($product['items']['qt']){
                $items = $product['items']['qt'];
            } else {
                $items = '';
            }
            if ($items){
                $this->emailMessage .= "<tr>
                <td>$product_name</td>";
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
            $this->emailMessage .= "<td>Consegna prevista per ".date_i18n('l j F Y', $this->dateInSeconds($this->date)+86400)." al seguente indirizzo: ".$this->userData['address']."</td>";
        } else {
            $this->emailMessage .= "<td>Consegna prevista per ".date_i18n('l j F Y', $this->dateInSeconds($this->date)+86400)." in azienda (via Rodolfo Rossi,66)</td>";
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
        $date = date_i18n('l j F Y', $this->dateInSeconds($this->date)+86400);
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
        $date = date_i18n('l j F Y \d\a\l\l\e H:i', $this->dateInSeconds($this->date)+86400);
        $emailTemplate = new emailTemplate();
        $emailMessage = $emailTemplate->getTopTemplate($date);
        $emailMessage .= "<table width='100%'><tr>";
        $emailMessage .= "<td><b>Gentile ".$this->userData['name'].", abbiamo ricevuto da te il seguente ordine</b></td>";
        $emailMessage .= "</tr></table>";
        $emailMessage .= $this->_orderEmail();
        $emailMessage .= $emailTemplate->getTemplate_bottom();
        $headers = "MIME-Version: 1.0" . "\r\n"; 
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $header .= 'From: Sapori di Capolavia <ordini@capolavia.it>' . "\r\n";

        function set_html_mail_content_type() {
            return 'text/html';
        }
        add_filter('wp_mail_content_type', 'set_html_mail_content_type');
        
        if(wp_mail($emailAddress, 'Ordine confermato', $emailMessage)){
            return true;
        } else {
            return false;
        }
        
        // Reset content type to avoid affecting other emails
        remove_filter('wp_mail_content_type', 'set_html_mail_content_type');

    }

    public function isNewBooking(){
        $emailAddress = $this->userData['email'];
        $bookingDay = $this->dateInSeconds($this->date);
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
        $_xmasproducts = $this->xmasproducts;
        // order products by name
        usort($_xmasproducts, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        foreach ($_xmasproducts as $i => $_product){
            $id = $_product['id'];
            $product = get_post($id);
            $product_name = $product->post_title;

            $products[$id] = array(
                'name' => $product_name,
                'items' => array(
                    'qt' => $_product['qt']
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
            'post_title' => date('Y_m_d', $this->dateInSeconds($this->date)).' '.$this->userData['email'],
            'post_type' => 'xmasbookings',
            'post_author' => $post_author->ID,
            'meta_input' => array(
                'userData' => $this->userData,
                'xmasproducts' => $this->productsJson,
                'date' => $this->dateInSeconds($this->date),
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
