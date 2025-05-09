<?php
/**
 * Template Name: XMas page
 */

get_header();
$my_query = new WP_Query(
    array(
        'post_type' => 'xmasproducts',
        'nopaging'=> true,
        'meta_key' => 'price',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    )
);
$products =  $my_query->posts;
$array_products = json_decode(json_encode($products),TRUE);
$html_code = "";
?>
        <?php //include('xmas.svg'); ?>

<div class="row" id="kt-main" data-ng-app="xmasbookingApp"  data-ng-controller="xmasbookingController">
        
        <?php 
            /*
                <div ng-show="::false" style="position: fixed; height: 100%; width: 100%; background-color: #353535; top: 0; left: 0; z-index: 10000; opacity: 0.5">
                <div style="position: relative; top: 50%; display: table; margin: 0 auto; font-size: 26px; color: #CCC;">
                Sta arrivando il Natale...
                </div>
            </div>
                */
    ?>
        <div class="col-md-12" ng-cloack>
            <div id="kt-latest-title" class="h3" >

                <p data-ng-hide="success">

                  <span><?php the_title(); ?></span>
                </p>
                <p data-ng-show="success" data-ng-cloak ><span></span></p>
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
                                <tr data-ng-hide="!product.qt" data-ng-repeat="product in xmasproducts">
                                <td><img src="{{product.smallthumb}}"/></td>
                                <td>{{product.name}}</td>
                                <td data-ng-if="product.qt">{{product.qt}}</td>
                                </tr>
                            </table>
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
                    <form class="form-inline">
                        <?php
                foreach ($array_products as $product) {
                    $url = $product['guid'];
                    $content = $product['post_content'];
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);
                    $_price = get_post_meta($product['ID'], 'price', true);
                    $price = ($_price ? $_price : '');
                    $_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product['ID']), 'small_square');
                    $_small_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product['ID']), 'smallest_square');
                    $stock = get_post_meta($product['ID'], '_my_meta', true);
                    $thumbnail = (has_post_thumbnail($product['ID']) ? $_thumbnail[0] : get_header_image());
                    $small_thumbnail = (has_post_thumbnail($product['ID']) ? $_small_thumbnail[0] : get_header_image());
                    $html_code .= "<div 
                        data-ng-init='
                            xmasproducts[".$product['ID']."][\"qt\"] = 0; 
                            xmasproducts[".$product['ID']."][\"name\"] = \"".$product['post_title']."\";
                            xmasproducts[".$product['ID']."][\"smallthumb\"] = \"".$small_thumbnail."\";
                            '></div>";
                    $html_code .= '
                        <div class="booking-product-wrapper col-xs-12 col-sm-12">
                            <div class="row display-flex-center">
                                <div class="col-md-4 col-xs-12 text-center">
                                    <img src="' . $thumbnail . '" alt="' . $product['post_title'] . $price . '"/>                            
                                </div>
                                <div class="col-md-4 col-xs-12">
                                <h3>' . $product['post_title'] .'</h3>';
                                    if ($price){
                                        $html_code .= '<h5>'. $price . '&euro;</h5>';
                                    }   
                                    $html_code .= '</p>'.$content;                                 
                                    $html_code .= '<ul>';                                 
                                    $product_name = addslashes($product['post_title']);
                                    
                                    $connections = new WP_Query( array(
                                        'connected_type' => 'products_to_xmasproducts',
                                        'connected_items' => $product,
                                        'nopaging' => true
                                    ) );
                                    while ( $connections->have_posts() ) : $connections->the_post();                                    
                                        $html_code .= '<li>' . $post->post_title . ' <i data-toggle="modal" data-target="#productModal" data-ng-click="select('.$post->ID.')" class="info-point fa-solid fa-circle-info"></i></li>';
                                    endwhile;
                                    $html_code .= '</ul>
                                </div>';
                                $html_code  .= '
                                    <div class="col-md-4 col-xs-12">
                                        <div class="display-flex-center">
                                            <label class="sr-only" for="'.$product['ID'].'"></label>
                                            <div class="form-group-lg">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button ng-click="minus('.$product['ID'].')" class="btn btn-lg btn-xmas" type="button">-</button>
                                                    </span>                                      
                                                    <input data-ng-model="xmasproducts['.$product['ID'].'][\'qt\']" type="number" ng-pattern="/^[0-9]{1,}$/" class="form-control" placeholder="0" value="0" min="0" max="50" step="1">
                                                    <span class="input-group-btn">
                                                        <button ng-click="plus('.$product['ID'].')" class="btn btn-lg btn-xmas" type="button">+</button>
                                                    </span>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                    

                    $html_code  .= '</div>
                    </div>';
                }
                echo $html_code;

                ?>
                    </form>
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
                        <div class="col-xs-12 col-md-6">
                            <label for="date"> Data di consegna
                                
                                <input data-ng-model="user.date" type="date" id="date" name="user.date"  min="<?php
                                $datetime = new DateTime('tomorrow');
                                echo $datetime->format('Y-m-d');
                                ?>" max="2025-01-10" name="date" class="input-lg form-control"/>
                            </label>
                        </div>

                        <div class="col-xs-12 col-md-12">
                            <div class="row" style="margin-top: 1em">

                            
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
$products =  $my_query->posts;
$array_products = json_decode(json_encode($products),TRUE);
$html_code = "";
get_footer();
?>
