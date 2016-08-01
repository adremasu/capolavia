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

} 