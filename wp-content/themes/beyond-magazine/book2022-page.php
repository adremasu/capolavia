<?php
/**
 * Template Name: Booking page 2022
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

$storeCalendarId = esc_attr( get_option('booking_store_calendar_id') );
$deliveryCalendarId = esc_attr( get_option('booking_delivery_calendar_id') );
$maxResults = esc_attr( get_option('booking_eventsnumber_max') );
$optParams = array(
    'maxResults' => $maxResults,
    'orderBy' => 'startTime',
    'singleEvents' => TRUE,
    'timeMin' => date('c', strtotime('today +'.esc_attr( get_option('booking_searchdate_range_min') ).' days')),
    'timeMax' => date('c', strtotime('today +'.esc_attr( get_option('booking_searchdate_range_max') ).' days'))
);

$storeEvents = $service->events->listEvents($storeCalendarId);
$deliveryEvents = $service->events->listEvents($storeCalendarId);

$storeEventsList = array();
$deliveryEventsList = array();

if ($maxResults){
    $storeEventsObj = $service->events->listEvents($storeCalendarId, $optParams);
    $i = 0;
    foreach ($storeEventsObj->getItems() as $event) {    
        $storeEventsList[$i]['start'] = $event->getStart();
        $storeEventsList[$i]['end'] = $event->getEnd();
        $i++;
    }
    
    $deliveriyEventsObj = $service->events->listEvents($deliveryCalendarId, $optParams);
    $i = 0;
    foreach ($deliveriyEventsObj->getItems() as $event) {
        $deliveryEventsList[$i]['start'] = $event->getStart();
        $deliveryEventsList[$i]['end'] = $event->getEnd();
        $i++;
    }
} else {

}

$EUID = $_GET['uid'];
#$_user = new BookingUser($EUID);
#$user = $_user->get_user_data();
?>

    <div class="row" id="kt-main" data-ng-app="bookingApp"  data-ng-controller="bookingController">
        <div ng-show="::false" style="position: fixed; height: 100%; width: 100%; background-color: #353535; top: 0; left: 0; z-index: 10000; opacity: 0.5">
            <div style="position: relative; top: 50%; display: table; margin: 0 auto; font-size: 26px; color: #CCC;">
                Controllo cosa c'è nell'orto...
            </div>
        </div>
        <div class="col-md-12" ng-cloack>
            <div id="kt-latest-title" class="h3" >

                <p data-ng-hide="success">

                  <span>Cosa ci mangiamo di buono oggi?</span>
                </p>
                <p data-ng-show="success" data-ng-cloak ><span>La tua prenotazione per {{ date*1000 | date : 'd MMMM'}} è stata registrata.</span></p>
            </div>
        </div>
        <div class="col-md-12 description" data-ng-hide="success">

            <?php
            the_content();
            ?>
        </div>


        <div id="booking-wrapper" class="col-md-12">

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
                          <table class="table">
                              <tr data-ng-hide="!product.weight && !product.items" data-ng-repeat="product in products">
                                  <td>{{product.name}}</td>
                                  <td data-ng-if="product.items">{{product.items}} {{product.items_name}}</td>
                                  <td data-ng-if="product.weight">{{product.weight}} {{product.weight_name}}</td>
                              </tr>
                          </table>
                          <p> Modalità: 
                            <span data-ng-show="mode=='delivery'" >Consegna a domicilio ({{user.address}})</span>
                            <span data-ng-show="mode=='store'">Ritiro in azienda</span>
                          </p>
                          <p> Data: {{ date*1000 | date : 'd MMMM'}} 
                          <p>Note: <br>{{user.notes}}</p>
                          <p data-ng-if="user.delivery == '1'">Consegna presso:{{user.address}}</p>
                          <p data-ng-if="user.delivery == '0'">Ritiro in azienda in via Rodolfo Rossi 66, Rovigo(loc. Grignano Polesine)</p>

                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Modifica</button>
                          <button type="button" data-ng-click="saveBooking($event)" class="btn btn-success">
                              <span data-ng-show="loading" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                              Conferma ordine
                          </button>
                      </div>
                  </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->

          <div class="modal fade" tabindex="-1" role="dialog" id="errorModal">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Riepilogo</h4>
                      </div>
                      <div class="modal-body">
                        <p>Ooooops! Qualcosa è andato storto, prova a ripetere l'operazione o <a href="<?php echo get_site_url().'/contacts'; ?>">contattaci</a></p>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                      </div>
                  </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->


            <div class="row" data-ng-show="success" ng-cloak >
                <div class="col-md-12 col-xs-12">
                    <p class="alert text-center alert-success">
                        {{userMessage}}
                    </p>
                </div>
            </div>

            <div data-ng-hide="success" ng-cloak>

                <div class="row">
                <?php
                $is_odd = false;
                foreach ($array_products as $product) {
                    $is_odd = !$is_odd;
                    if ($is_odd){ $odd_class = 'odd';} else {$odd_class = 'even';}
                    $url = $product[guid];
                    $content = $product[post_content];
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);
                    $_price = get_post_meta($product[ID], 'prezzo', true);
                    $price = ($_price ? $_price : '');
                    $_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product[ID]), 'small_square');
                    $stock = get_post_meta($product[ID], '_my_meta', true);
                    $has_weight = $stock[weight];
                    $has_items = $stock[items];
                    $weight_name = $stock[weight_name];
                    $items_name = $stock[items_name];
                    $thumbnail = (has_post_thumbnail($product[ID]) ? $_thumbnail[0] : get_header_image());
                    $html_code .= '
                        <div class="booking-product-wrapper col-xs-12 col-sm-6 '.$odd_class.'">
                        <div class="row">
                            <div class="col-md-4 text-center">
<img data-toggle="modal" data-target="#productModal" data-ng-click="select('.$product[ID].')"  src="' . $thumbnail . '" alt="' . $product[post_title] . $price . '"/>                            </div>
                            <div class="col-md-8">
                                <p class="product-name text-center"><h4 data-toggle="modal" data-target="#productModal" data-ng-click="select('.$product[ID].')" >' . $product[post_title] .'</h4><h5>'. $price . '</h5></p>';
                    $product_name = addslashes($product[post_title]);

                    $html_code .= "

                                <div
                                data-ng-init='
                                products[$product[ID]][\"name\"] = \"$product_name\";
                                products[$product[ID]][\"items_name\"] =\"$items_name\";
                                products[$product[ID]][\"weight_name\"] =\"$weight_name\";

                                '>
                                </div>
                                <div class='booking-qty-selectors-wrapper row'>";

                    if ($has_items){
                    $html_code .= '<div class="form-group col-xs-12 col-md-6">
                                        <label class="sr-only" for="'.$product[ID].'">Pezzi</label>
                                        <div class="input-group">
                                          <input type="number" data-ng-disabled="products['.$product[ID].'][\'weight\']" data-ng-model="products['.$product[ID].'][\'items\']" class="form-control" id="'.$product[ID].'" placeholder="n°" name="products['.$product[ID].'][items]">
                                          <div class="input-group-addon">'.$items_name.'</div>
                                        </div>

                                    </div>';
                    } else {
                        $html_code  .= '<div class="form-group col-xs-12 col-md-6" style="visibility: hidden">
                                        <label class="sr-only" for="'.$product[ID].'"></label>
                                        <div class="input-group">
                                          <input type="number" class="form-control">
                                          <div class="input-group-addon"></div>
                                        </div>
                                    </div>';
                    }

                    if ($has_weight){
                    $html_code  .= '<div class="form-group col-xs-12 col-md-6">
                                        <label class="sr-only" for="'.$product[ID].'">Peso (in '.$weight_name.')</label>
                                        <div class="input-group">
                                          <input type="number" data-ng-disabled="products['.$product[ID].'][\'items\']" data-ng-model="products['.$product[ID].'][\'weight\']" class="form-control" id="'.$product[ID].'" placeholder="0.0"  name="products['.$product[ID].'][weight]">
                                          <div class="input-group-addon">'.$weight_name.'</div>
                                        </div>
                                    </div>';
                    } else {
                        $html_code  .= '<div class="form-group col-xs-12 col-md-6" style="visibility: hidden">
                                        <label class="sr-only" for="'.$product[ID].'"></label>
                                        <div class="input-group">
                                          <input type="number" class="form-control">
                                          <div class="input-group-addon"></div>
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
                    <div class="col-md-12 col-xs-12"><p>&nbsp;</p></div>
                </div>
                <div class="row" ng-cloak >
                    <form class="custom_form" novalidate action="" id="booking-form" name="booking_form">
                        <div class="col-xs-12 col-md-6">
                            <label for="name"> Nome*
                                <input required data-ng-model="user.name" type="text" name="name" class="input-lg form-control"/>
                                <p class="validation-text">Inserisci il tuo nome</p>
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <label for="email"> Indirizzo e-mail*
                                <input required data-ng-model="user.email" type="email" name="email" class="input-lg form-control"/>
                                <input data-ng-model="user.euid" type="hidden" name="euid"/>
                                <p class="validation-text">Inserisci un indirizzo e-mail valido</p>
                            </label>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <label for="phone"> Telefono
                                <input data-ng-model="user.phone" type="phone" name="phone" class="input-lg form-control"/>
                            </label>
                        </div>

                        <div ng-init="date = '<?php echo date_i18n('U',strtotime($start['dateTime'])) + date(Z);?>'"></div>
                        <div ng-init="delivery_date = '<?php echo date_i18n('U',strtotime($deliveryStart['dateTime'])) + date(Z);?>'"></div>

                        <div class="col-xs-12 col-md-12">
                            <div class="row" style="margin-top: 1em">
                                <label style="margin:0" class="col-md-12" for="delivery-check">Scegli la modalità di consegna
                                </label>
                                
                                <div class="col-md-6">
                                    <div class="panel panel-default" id="panel-store">
                                        <div class="panel-heading">
                                            Ritiro in azienda                                     
                                        </div>
                                        <div class="panel-body">
                                            <p class="list-group">

                                     
                                         <?php                                     
                                         foreach($storeEventsList as $event){
                                                $start = $event['start'];
                                                $end = $event['end'];
                                                echo "<label for='store'><button id='D_".strtotime($start->dateTime)."_S' type='button' data-ng-click='dateSelect(";
                                                echo strtotime($start->dateTime)+ date(Z);
                                                echo ",\"store\",\$event)' class='list-group-item mode-selector'><i class='fa fa-map-marker'></i> ";
                                                echo date_i18n('<b>l</b> j F',strtotime($start->dateTime)+ date(Z))." tra le "; 
                                                echo date_i18n('G:i',strtotime($start->dateTime)+ date(Z)).' e le '.date_i18n('G:i',strtotime($end->dateTime)+ date(Z));
                                                echo "
                                                </button>
                                                </label>"; 
                                         }?>
                                        </div>
                                        <div class="radio">
                                                        <input class="radio input-lg" required data-ng-model="mode" id="store" type="radio" value="store" name="mode" data-ng-change="deliverySelect()"/>
                                                    </div>
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="panel panel-default" id="panel-delivery">
                                        <div class="panel-heading">
                                            Consegna a domicilio (2€)
                                        </div>
                                        <div class="panel-body">
                                            <?php 
                                            if (count($deliveryEventsList)){
                                                ?>
                                                <p class="list-group">
                                                <?php
                                                foreach ($deliveryEventsList as $event){
                                                    $start=$event['start'];
                                                    $end = $event['end'];
                                                    ?>                                          
                                                    <label for="delivery">
                                                        <button id="D_<?php echo strtotime($start->dateTime).'_D'?>" type="button" data-ng-click="dateSelect(<?php 
                                                    
                                                    echo strtotime($start->dateTime)+ date(Z).", 'delivery', "?>$event)" class="list-group-item mode-selector">
        
                                                            <i class="fa fa-truck"></i> <?php 
                                                    
                                                    echo date_i18n('<b>l</b> j F',strtotime($start->dateTime)+ date(Z)).
                                                    ' tra le '.date_i18n('G:i',strtotime($start->dateTime)+ date(Z)).' e le '.date_i18n('G:i',strtotime($end->dateTime)+ date(Z)); ?>
                                                        </button>
                                                    </label>
        
                                                    <?php        
                                                    }
                                                    ?>
                                                    </p>
                                                    <?php
                                            } else { ?>
                                            <p class="alert alert-warning no-delivery">Nei prossimi giorni non sono previstre consegne a domicilio, vieni a trovarci in azienda!</p>
                                            <?php }
                                        
                                            ?>      
                                                <div class="radio">
                                                    <input class="radio input-lg" required data-ng-model="mode" id="delivery" type="radio" value="delivery" name="mode" data-ng-change="deliverySelect()"/>
                                                </div>
                                            </p>                               

                                            <div data-ng-show="mode == 'delivery'">
                                                <label for="address">Dove vuoi ricevere la verdura? *</label>
                                                <input data-ng-disabled="mode == 'store'" data-ng-required="mode == 'delivery'" data-ng-model="user.address" class="form-control" type="text" name="address" placeholder="Indirizzo a cui effettuare la consegna">
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label for="notes">Note:</label>
                                <textarea id="notes" placeholder="Inserisci qui eventuali note" class="form-control" name="notes" data-ng-model="user.notes" id="" cols="30" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12 visible-xs-block visible-sm-block">
                            <div class="form-group">
                                <button data-toggle="modal" data-target="#myModal" data-ng-click="$event.preventDefault()" class="btn-block btn btn-success btn-lg" data-ng-disabled="!booking_form.$valid">
                                    Ordina
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 hidden-xs hidden-sm">
                            <div class="form-group">
                                <button data-toggle="modal" data-target="#myModal" data-ng-click="$event.preventDefault()" class="btn btn-success btn-lg" data-ng-disabled="!booking_form.$valid">
                                    Ordina
                                </button>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12"><p><i>*campi obbligatori</i></p></div>
                        <div class="col-md-12 col-xs-12"><p><small>Questo non è un sito di vendita on-line; si tratta di un'applicazione per facilitare l'ordine da parte dei clienti. La prenotazione non impegna legalmente alcuna delle due parti.</small></p></div>
                    </form>

                </div>
            </div>
        </div>
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="productModalLabel">{{selectedProduct.name}}</h4>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <img src="/wp-includes/js/thickbox/loadingAnimation.gif" id="productLoadingGif" class="loading_gif">
                        <img src="{{selectedProduct.img}}" id="productImage" class="productImage col-xs-12 col-md-6">
                        <div class="productBody col-xs-12 col-md-6">
                            {{selectedProduct.content}}
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
get_footer();
?>
 