<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 24/04/16
 * Time: 17.16
 */
function save_new_subscription() {

    new save_new_subscriptionClass();
}


add_action('wp_ajax_save_new_subscription', 'save_new_subscription');
add_action('wp_ajax_nopriv_save_new_subscription', 'save_new_subscription');

class save_new_subscriptionClass
{
    public function __construct()
    {
        $data = $_POST;

        $subscription = $data['subscription'];
        $user = $data['subscription']['user'];
        $this->subscription = $subscription;
        $this->email = $user[email];
        $this->user = $user;
        $this->blacklist = $subscription['products'];
        $this->IDs = array(
            '3S' => array(
                'paypal_ID' =>'URRPETNYHGP2N',
                'price' => '125'
            ),

            '3M' =>array(
                'paypal_ID' =>'',
                'price' => '195'
            ),
            '3L' =>array(
                'paypal_ID' =>'',
                'price' => '250'
            ),
            '6S' =>array(
                'paypal_ID' =>'D866VRALDQX8S',
                'price' => '245'
            ),
            '6M' =>array(
                'paypal_ID' =>'',
                'price' => '367'
            ),
            '6L' =>array(
                'paypal_ID' =>'',
                'price' => '490'
            ),
            '12S' =>array(
                'paypal_ID' =>'U7MFF52EDZZ68',
                'price' => '470'
            ),
            '12M' =>array(
                'paypal_ID' =>'',
                'price' => '705'
            ),
            '12L' =>array(
                'paypal_ID' =>'',
                'price' => '940'
            )
        );
        $this->chosen_subscription = $subscription['length'].strtoupper($subscription['size']);
        if ($this->saveData()) {
            echo json_encode($this->response);
        } else {
            echo 'no';
        }

        wp_die();
    }

    public function saveData(){
        //check if email address exists
        if( email_exists( $this->email )) {
            $this->error[] = 'email_exists';
            return false;

        } else {
            // get price and Paypal button for the chosen option
            $response['paypal_ID'] = $this->getPaypalButton();
            $response['price'] = $this->getPrice();
            $response['sub'] = $this->chosen_subscription;
            $this->user_id = $this->setNewUser();
            $this->setNewSubscription($this->user_id);

            $response['user_id'] = $this->user_id;
            if (is_array($response['error']))    {
                $response['error'] = $this->error;
                $response['success'] = false;
            } else {
                $response['success'] = true;

            }
            $this->response = $response;
            return true;
        }
    }

    private function getPrice(){
        $IDs = $this->IDs;
        if (isset($IDs[$this->chosen_subscription]['price'])){
            $id = $IDs[$this->chosen_subscription]['price'];
            return $id;
        } else {
            $this->error[] = 'paypal_button';
            return false;
        }
    }

    private function getPaypalButton(){
        $IDs = $this->IDs;
        if (isset($IDs[$this->chosen_subscription]['paypal_ID'])){
            $id = $IDs[$this->chosen_subscription]['paypal_ID'];
            return $id;
        } else {
            $this->error[] = 'paypal_button';
            return false;
        }

    }

    private function setNewSubscription($user_id){
        if (is_int($user_id)){
            $post_title = $this->email.' - '.$this->chosen_subscription;
            $user_data = $this->user;
            $args = array(
                'post_type'     => 'subscriptions',
                'post_title'    => $post_title,
                'meta_input'    => array(
                    'subscription_data' => array(
                        'address'       => $user_data['address'],
                        'zip'           => $user_data['zip'],
                        'city'          => $user_data['city'],
                        'delivery_day'  => $user_data['delivery_day'],
                    )
                )
            );
            if ($subscription_ID = wp_insert_post($args)){
                p2p_type( 'subscription_owner' )->connect( $user_id, $subscription_ID, array(
                    'date' => current_time('mysql')
                ) );
                foreach ($this->blacklist as $_product => $status){
                    $product = ltrim($_product, 'P');
                    p2p_create_connection( 'blacklist', array(
                        'from' => $subscription_ID,
                        'to' => $product,
                        'meta' => array(
                            'date' => current_time('mysql')
                        )
                    ) );
                }
            } else {
                $this->error[] = 'no_sub_created';
            }

        } else {
            $this->error[] = 'no_user_id';
        }
    }

    private function setNewUser(){
        $user_id = wp_create_user( $this->email, $this->user[password1], $this->email );
        $user = new WP_User( $user_id );
        $user->set_role('customer');
        $user_data = array(
            'ID' => $user_id,
            'first_name' =>$this->user[name],
            'last_name' => $this->user[last_name],
            'display_name' => $this->user[name].' '.$this->user[last_name],
        );
        update_user_meta( $user_id, 'customer', $this->user );
        wp_update_user( $user_data );
        return $user_id;
    }

}