<?php
/**
 * Template Name: Booking page
 */
date_default_timezone_set('Europe/Rome');
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/dev_key.php';
define('APPLICATION_NAME', 'Gestione consegne');
define('CREDENTIALS_PATH', __DIR__ . '/calendar-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

define('SCOPES', implode(' ', array(
        Google_Service_Calendar::CALENDAR_READONLY)
));
/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
    }
    return str_replace('~', realpath($homeDirectory), $path);
}


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
$client->setApplicationName("My Application");
$client->setDeveloperKey(DEV_KEY);

$service = new Google_Service_Calendar($client);

// Print the next 10 events on the user's calendar.
$calendarId = 'r7t3hsima10qg4m7ioai4dp0ek@group.calendar.google.com';
$optParams = array(
    'maxResults' => 1,
    'orderBy' => 'startTime',
    'singleEvents' => TRUE,
    'timeMin' => date('c', strtotime('tomorrow')),
);
$events = $service->events->listEvents($calendarId);

$results = $service->events->listEvents($calendarId, $optParams);

foreach ($results->getItems() as $item) {
    $start = $item->getStart();
}

?>

    <div class="row" id="kt-main" data-ng-app="bookingApp">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>Ecco i prodotti disponibili per <?php echo date_i18n('l j F ',strtotime($start['dateTime'])) ?></span></p>
            </div>
        </div>
        <div id="booking-wrapper" class="col-md-12" data-ng-controller="bookingController">
            <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Riepilogo</h4>
                        </div>
                        <div class="modal-body">
                            <p>Nome: {{user.name}}</p>
                            <p>Indirizzo e-mail: {{user.email}}</p>
                            <p>Telefono: {{user.phone}}</p>
                            <table class="table table-striped">
                                <tr data-ng-hide="!product.weight && !product.items" data-ng-repeat="product in products">
                                    <td>{{product.name}}</td>
                                    <td data-ng-if="product.items">{{product.items}} {{product.items_name}}</td>
                                    <td data-ng-if="product.weight">{{product.weight}} {{product.weight_name}}</td>
                                </tr>
                            </table>
                            <p>Note: <br>{{user.note}}</p>
                            <p data-ng-if="user.delivery == '1'">Consegna presso:{{user.address}}</p>
                            <p data-ng-if="user.delivery == '0'">Ritiro in azienda in via Rodolfo Rossi 101, Rovigo(loc. Grignano Polesine)</p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Modifica</button>
                            <button type="button" class="btn btn-primary">Conferma ordine</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="row" data-ng-show="success" >
                <div class="col-md-12 ">
                    <p class="alert alert-success">
                        {{userMessage}}
                    </p>
                </div>
            </div>
            <div data-ng-hide="success">

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
                                <p class="product-name"><h4>' . $product[post_title] .'</h4><h5>'. $price . '</h5></p>';
                    $product_name = addslashes($product[post_title]);
                    $html_code .= "

                                <div
                                data-ng-init='
                                products[$product[ID]][\"name\"] = \"$product_name\";
                                products[$product[ID]][\"items_name\"] =\"$items_name\";
                                products[$product[ID]][\"weight_name\"] =\"$weight_name\"
                                '>
                                </div>
                                <div class='booking-qty-selectors-wrapper row'>";

                    if ($has_items){
                    $html_code .= '<div class="form-group col-xs-6">
                                        <label class="sr-only" for="'.$product[ID].'">Pezzi</label>
                                        <div class="input-group">
                                          <input type="number" data-ng-model="products['.$product[ID].'][\'items\']" class="form-control" id="'.$product[ID].'" placeholder="n°" name="products['.$product[ID].'][items]">
                                          <div class="input-group-addon">'.$items_name.'</div>
                                        </div>

                                    </div>';}
                    if ($has_weight){


                    $html_code  .= '<div class="form-group col-xs-6">
                                        <label class="sr-only" for="'.$product[ID].'">Peso (in '.$weight_name.')</label>
                                        <div class="input-group">
                                          <input type="number" data-ng-model="products['.$product[ID].'][\'weight\']" class="form-control" id="'.$product[ID].'" placeholder="peso"  name="products['.$product[ID].'][weight]">
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
                <div class="row" >
                    <form class="custom_form" novalidate action="" id="booking-form" name="booking_form">
                        <div class="col-xs-6">
                            <label for="name"> Nome*
                                <input required data-ng-model="user.name" type="text" name="name" class="input-lg form-control"/>
                                <p class="validation-text">Inserisci il tuo nome</p>
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <label for="email"> Indirizzo e-mail*
                                <input required data-ng-model="user.email" type="email" name="email" class="input-lg form-control"/>
                                <p class="validation-text">Inserisci un indirizzo e-mail valido</p>
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <label for="phone"> Telefono
                                <input data-ng-model="user.phone" type="phone" name="phone" class="input-lg form-control"/>
                            </label>
                        </div>

                                <?php
                                foreach ($results->getItems() as $item) {
                                    $start = $item->getStart();
                                    $end = $item->getEnd();
                                    //echo '<input type="hidden" name="date" data-ng-model="date" data-ng-value="'.strtotime($start['dateTime']).'" / >';
                                }
                                ?>
                        <div ng-init="date = '<?php echo strtotime($start['dateTime']);?>'"></div>
                        <div class="col-md-12">
                            <div class="row" style="margin-top: 1em">
                                <label style="margin:0" class="col-md-12" for="delivery-check">Scegli la modalità di consegna
                                </label>
                                <div class="col-md-6">
                                    <div class="radio">
                                        <input class="radio input-lg" required data-ng-model="user.delivery" id="delivery" type="radio" value="1" name="delivery"/>
                                        <label for="delivery">Consegna a domicilio (2€)</label>
                                        <div data-ng-show="user.delivery == 1">
                                            <input data-ng-disabled="user.delivery != 1" data-ng-required="user.delivery == 1" data-ng-model="user.address" class="form-control" type="text" name="address" placeholder="Indirizzo a cui effettuare la consegna">
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="radio">
                                        <input class="radio input-lg" required data-ng-model="user.delivery" id="in-company" type="radio" value="0" name="delivery"/>
                                        <label for="in-company"> Ritiro in azienda
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">Note:</label>
                                <textarea id="notes" placeholder="Inserisci qui eventuali note" class="form-control" name="notes" data-ng-model="user.notes" id="" cols="30" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <strong>La consegna avverrà in ogni caso <span  class="text-success"><?php echo date_i18n('l j F Y',strtotime($start['dateTime'])).' tra le '.date_i18n('G:i',strtotime($start['dateTime'])).' e le '.date_i18n('G:i',strtotime($end['dateTime'])) ?></span></strong>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button data-toggle="modal" data-target="#myModal" data-ng-click="$event.preventDefault()" class="btn btn-success btn-lg" data-ng-disabled="!booking_form.$valid">
                                    <span data-ng-show="loading" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                                    Ordina
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
?>