<?php
/**
 * Template Name: Booking page
 */

get_header();
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
$html_code = "";
?>
    <div class="row" id="kt-main" >
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>Ecco i prodotti disponibili per venerdì prossimo</span></p>
            </div>
        </div>
        <div id="booking-wrapper" class="col-md-12" >
            <div class="row">
            <?php
            foreach ($array_products as $product) {
                $url = $product[guid];
                $_price = get_post_meta($product[ID], 'prezzo', true);
                $price = ($_price ? ' a ' . $_price : '');
                $_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product[ID]), 'small_square');
                $stock = get_post_meta($product[ID], '_my_meta', true);
                $has_weight = $stock[weight];
                $has_items = $stock[items];
                $weight_name = $stock[weight_name];
                $items_name = $stock[items_name];
                $thumbnail = (has_post_thumbnail($product[ID]) ? $_thumbnail[0] : get_header_image());
                $html_code .= '
                    <div class="booking-product-wrapper col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col-md-4" >
                            <img src="' . $thumbnail . '" alt="' . $product[post_title] . $price . '"/>
                        </div>
                        <div class="col-md-8">
                            <p class="product-name"><h4>' . $product[post_title] .'</h4><h5>'. $price . '</h5></p>
                            <div class="booking-qty-selectors-wrapper row">';
                if ($has_items){
                $html_code .= '<div class="form-group col-xs-6">
                                    <label class="sr-only" for="'.$product[ID].'">Pezzi</label>
                                    <div class="input-group">
                                      <input type="number" class="form-control" id="'.$product[ID].'" placeholder="n°" name="booking['.$product[ID].'][items]">
                                      <div class="input-group-addon">'.$items_name.'</div>
                                    </div>

                                </div>';}
                if ($has_weight){


                $html_code  .= '<div class="form-group col-xs-6">
                                    <label class="sr-only" for="'.$product[ID].'">Peso (in kg)</label>
                                    <div class="input-group">
                                      <input type="number" class="form-control" id="'.$product[ID].'" placeholder="peso"  name="booking['.$product[ID].'][weight]">
                                      <div class="input-group-addon">'.$weight_name.'</div>
                                    </div>
                                </div>';
                }
                $html_code  .= '</div>
                        </div>
                    </div>
                </div>';
            }
            echo $html_code;
                ?>
            </div>

        </div>
    </div>
<?php
get_footer();
?>