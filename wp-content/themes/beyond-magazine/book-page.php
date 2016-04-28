<?php
/**
 * Template Name: Booking page
 */
date_default_timezone_set('Europe/Rome');

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
$client = new Google_Client();
$client->setApplicationName("Gestione consegne");
$client->setDeveloperKey("sqjLzmG3AsFlmFTD0GFubVPg");

$client = getGoogleClient();
$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'r7t3hsima10qg4m7ioai4dp0ek@group.calendar.google.com';
$optParams = array(
    'maxResults' => 2,
    'orderBy' => 'startTime',
    'singleEvents' => TRUE,
    'timeMin' => date('c', strtotime('tomorrow')),
);
$results = $service->events->listEvents($calendarId, $optParams);



?>

    <div class="row" id="kt-main" data-ng-app="bookingApp">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>Ecco i prodotti disponibili per venerdì prossimo</span></p>
            </div>
        </div>
        <div id="booking-wrapper" class="col-md-12" data-ng-controller="bookingController">
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
                                      <input type="number" data-ng-model="products.i'.$product[ID].'.items" class="form-control" id="'.$product[ID].'" placeholder="n°" name="products['.$product[ID].'][items]">
                                      <div class="input-group-addon">'.$items_name.'</div>
                                    </div>

                                </div>';}
                if ($has_weight){


                $html_code  .= '<div class="form-group col-xs-6">
                                    <label class="sr-only" for="'.$product[ID].'">Peso (in '.$weight_name.')</label>
                                    <div class="input-group">
                                      <input type="number" data-ng-model="products.i'.$product[ID].'.weight" class="form-control" id="'.$product[ID].'" placeholder="peso"  name="products['.$product[ID].'][weight]">
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
            <div class="row">
                <form class="custom_form" novalidate action="" id="booking-form" name="booking_form">
                    <div class="col-md-12 col-xs-6">
                        <div class="form-group">
                            <label for="name"> Nome*
                                <input required data-ng-model="user.name" type="text" name="name" class="form-control"/>
                                <p class="validation-text">Inserisci il tuo nome</p>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="email"> Indirizzo e-mail*
                                <input required data-ng-model="user.email" type="email" name="email" class="form-control"/>
                                <p class="validation-text">Inserisci il tuo indirizzo e-mail</p>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="phone"> Telefono
                                <input data-ng-model="user.phone" type="phone" name="phone" class="form-control"/>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Scegli il giorno di consegna:</label>
                            <select required name="date" class="form-control"  data-ng-model="user.date">

                                <?php
                                foreach ($results->getItems() as $item) {
                                    $start = $item->getStart();
                                    $end = $item->getEnd();
                                    echo '<option value="'.strtotime($start['dateTime']).'">'.date_i18n('l d F Y G:i',strtotime($start['dateTime'])).' - '.date_i18n('G:i',strtotime($end['dateTime'])).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <label class="col-md-12" for="delivery-check">Scegli la modalità di consegna</label>
                            <div class="col-md-6">
                                <div class="radio">
                                        <input class="radio" required data-ng-model="user.delivery" id="delivery" type="radio" value="1" name="delivery"/>
                                    <label for="delivery">Consegna a domicilio (3€)</label>
                                    <div data-ng-show="user.delivery == 1">
                                        <input data-ng-disabled="user.delivery != 1" data-ng-model="user.address" class="form-control" type="text" name="address" placeholder="Indirizzo a cui effettuare la consegna">
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="radio">
                                        <input class="radio" required data-ng-model="user.delivery" id="in-company" type="radio" value="0" name="delivery"/>
                                    <label for="in-company"> Ritiro in azienda
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-6">
                        <div class="form-group">
                            <input type="button" data-ng-click="saveBooking()" class="btn primary-btn" value="Ordina"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php
get_footer();
?>