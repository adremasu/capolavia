<?php
/**
 * Template Name: Subscribe page
 */

get_header();?>
    <div class="row" id="kt-main" ng-app="subscribeApp">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>La salute a casa tua in 3 mosse</span></p>
            </div>
        </div>
        <div id="subscription-wrapper" class="col-md-12" ng-controller="configCtrl">
            <form name="subscription_form" novalidate action="">


                <div data-ng-class="show('intro')" id="step-zero" data-step="0" class="step-wrapper row">
                    <div class="page-content col-xs-12 col-md-12" >
                        <?php the_content(); ?>
                    </div>
                    <div class="col-xs-12 col-md-12 step-nav">
                        <button class="btn-lg btn btn-success" data-ng-click='goToStep($event, "size")'>Scopri quale abbonamento fa per te <i class="glyphicon glyphicon-chevron-right"></i></button>
                    </div>
                </div>
                <div data-ng-class="show('size')" id="first-step" data-step="1" class="step-wrapper row">

                    <h3 class="col-md-12 col-xs-12">Quale formato fa per te?</h3>

                    <div class="col-md-4 col-xs-12 square subscription_size" data-subscription_length="3">
                        <input id="Ss" data-ng-model="subscription.size" type="radio" required name="subscription_size" value="s" class="hidden"/>
                        <label for="Ss" class="size">
                            <span class="main">S</span>
                            <span class="secondary">3-4 kg</span>
                            <p class="subscription-discount">
                                <span>Quantità consigliata per 1-2 persone</span>
                                <span>(valore di almeno 10€ dal listino settimanale)</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-xs-12 square subscription_size" data-subscription_length="6">
                        <input id="Sm" data-ng-model="subscription.size" type="radio" required name="subscription_size" value="m" class="hidden"/>
                        <label for="Sm" class="size">
                            <span class="main">M</span>
                            <span  class="secondary">5-6 kg</span>
                            <p class="subscription-discount">
                                <span>Quantità consigliata per 2-3 persone</span>
                                <span>(valore di almeno 15€ dal listino settimanale)</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-xs-12 square subscription_size" data-subscription_length="9">
                        <input id="Sl" data-ng-model="subscription.size" type="radio" required name="subscription_size" value="l" class="hidden"/>
                        <label for="Sl" class="size">
                            <span class="main">L</span>
                            <span class="secondary">7-8 kg</span>
                            <p class="subscription-discount">
                                <span>Quantità consigliata per 3-4 persone</span>
                                <span>(valore di almeno 20€ dal listino settimanale)</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-xs-12 col-md-6 step-nav">
                        <button class="btn-lg btn btn-default" data-ng-click="goToStep($event, 'intro')"><i class="glyphicon glyphicon-chevron-left"></i> Torna </button>
                    </div>
                    <div class="col-xs-12 col-md-6 step-nav">
                        <button class="btn-lg btn btn-success" data-ng-disabled="!subscription.size" data-ng-click="goToStep($event, 'length')">Continua <i class="glyphicon glyphicon-chevron-right"></i></button>
                    </div>
                </div>
                <div data-ng-class="show('length')" id="second-step" data-step="2" class="step-wrapper row">
                    <h3 class="col-md-12 col-xs-12">Scegli la durata dell'abbonamento</h3>

                    <div class="col-md-4 col-xs-12 square subscription_length" data-subscription_length="3">
                        <input id="L3" data-ng-model="subscription.length" type="radio" required name="subscription_length" value="3" class="hidden"/>
                        <label for="L3" class="length">
                            <span class="main">3</span>
                            <span class="secondary">mesi</span>
                            <p class="subscription-discount">
                                <span class="price">{{price.L3}}</span>
                                <span>Risparmi il </span>
                                <span class="discount-percent">4%</span>
                                <span class="delivery-string">+ consegna a domicilio gratuita</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-xs-12 square subscription_length" data-subscription_length="6">
                        <input id="L6" data-ng-model="subscription.length" type="radio" required name="subscription_length" value="6" class="hidden"/>
                        <label for="L6" class="length">
                            <span class="main">6</span>
                            <span class="secondary">mesi</span>
                            <p class="subscription-discount">
                                <span class="price">{{price.L6}}</span>
                                <span>Risparmi il </span>
                                <span class="discount-percent">6%</span>
                                <span class="delivery-string">+ consegna a domicilio gratuita</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-xs-12 square subscription_length" data-subscription_length="9">
                        <input id="L12" data-ng-model="subscription.length" type="radio" required name="subscription_length" value="12" class="hidden"/>
                        <label for="L12" class="length">
                            <span class="main">12</span>
                            <span class="secondary">mesi</span>
                            <p class="subscription-discount">
                                <span class="price">{{price.L12}}</span>
                                <span>Risparmi il </span>
                                <span class="discount-percent">10%</span>
                                <span class="delivery-string">+ consegna a domicilio gratuita</span>
                            </p>
                        </label>
                    </div>


                    <div class="col-xs-12 col-md-6 step-nav"><button class="btn-lg btn btn-default" data-ng-click="goToStep($event, 'size')"><i class="glyphicon glyphicon-chevron-left"></i> Torna </button></div>
                    <div class="col-xs-12 col-md-6 step-nav"><button class="btn-lg btn btn-success" data-ng-disabled="!subscription.length" data-ng-click="goToStep($event, 'blacklist')">Continua <i class="glyphicon glyphicon-chevron-right"></i></button></div>

                </div>
                <div data-ng-class="show('blacklist')" id="third-step" data-step="3" class="step-wrapper row blacklist">
                    <div class="col-md-12 products-wrapper">
                        <div class="row">
                            <h3 class="col-md-12 col-xs-12">Scegli i prodotti che non vuoi ricevere</h3>
                        <?php
                        $args = array(
                            'post_type' => 'products',
                            'nopaging' => true,
                            'orderby' => 'title'
                        );
                        // The Query
                        $the_query = new WP_Query( $args );
                        // The Loop
                        if ( $the_query->have_posts() ) {

                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                ?>
                                <div class="col-md-4">
                                    <input class="checkbox" data-ng-model="subscription.products.P<?php the_ID();?>" id="P<?php the_ID(); ?>" type="checkbox" value="0" name="products[<?php the_ID();?>]"/>
                                    <label for="P<?php the_ID(); ?>"> <?php the_title(); ?>
                                    </label>
                                </div>
                            <?php
                            }
                        } else {
                            // no posts found
                        }
                        /* Restore original Post Data */
                        wp_reset_postdata();
                        ?>
                        </div>
                    </div>
                    <div class="col-md-6 step-nav"><button class="btn-lg btn btn-default" data-ng-click="goToStep($event, 'length')"><i class="glyphicon glyphicon-chevron-left"></i> Torna </button></div>
                    <div class="col-md-6 step-nav"><button class="btn-lg btn btn-success" data-ng-click="goToStep($event, 'user_data')">Continua <i class="glyphicon glyphicon-chevron-right"></i></button></div>

                </div>
                <div data-ng-class="show('user_data')" id="fourth-step" data-step="4" class="step-wrapper row">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email *</label>
                            <div class="col-sm-10">
                                <input type="email" data-ng-model="user.email"  required class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-2 control-label">Scegli una password *</label>
                            <div class="col-sm-10">
                                <input type="password" required class="form-control" id="password1" name="password1" placeholder="Scegli una password" data-ng-model="subscription.password1" ng-required="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password2" class="col-sm-2 control-label">Ripeti la password *</label>
                            <div class="col-sm-10">
                                <input type="password" required id="password2" placeholder="Ripeti la password" class="form-control" name="password2" ng-model="subscription.password2" ng-required="" pw-check="password1" />
                                <div class="msg-block" ng-show="subscription_form.$error">
                                    <span class="msg-error" ng-show="subscription_form.password2.$error.pwmatch">Passwords don't match.</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Nome *</label>
                            <div class="col-sm-10">
                                <input type="text" data-ng-model="user.name"  required class="form-control" id="name" placeholder="Nome">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-2 control-label">Cognome *</label>
                            <div class="col-sm-10">
                                <input type="text" data-ng-model="user.last_name" required class="form-control" id="surname" placeholder="Cognome">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fiscale" class="col-sm-2 control-label">Codice Fiscale *</label>
                            <div class="col-sm-10">
                                <input type="text" data-ng-model="user.fiscale" required fiscale class="form-control" id="fiscale" placeholder="Codice Fiscale">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="piva" class="col-sm-2 control-label">Partiva IVA *</label>
                            <div class="col-sm-10">
                                <input type="text" data-ng-model="user.piva" class="form-control" id="piva" placeholder="Partita IVA (facoltativo)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">Indirizzo a cui ricevere la cassetta *</label>
                            <div class="col-sm-10">
                                <input type="text" data-ng-model="subscription.address"  required class="form-control" id="address" placeholder="via e n° civico">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="city" class="col-sm-2 control-label"></label>

                            <div class="col-xs-2">
                                <input type="text" data-ng-model="subscription.zip" required class="form-control" placeholder="CAP">
                            </div>
                            <div class="col-xs-4">
                                <select required class="form-control" placeholder="Comune" data-ng-model="subscription.city">
                                    <option value="Rovigo">Rovigo</option>
                                    <option value="Costa di Rovigo">Costa di Rovigo</option>
                                    <option value="Arquà Polesine">Arquà Polesine</option>
                                    <option value="Villamarzana">Villamarzana</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="delivery_day" class="col-sm-2 control-label">Giorno di consegna *</label>
                            <div class="col-sm-6">
                                <select required data-ng-model="subscription.delivery_day" class="form-control" id="delivery_day" name="delivery_day">
                                    <option value="2">Martedì</option>
                                    <option value="5">Venerdì</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Telefono *</label>
                            <div class="col-sm-10">
                                <input type="tel" data-ng-model="user.phone" required class="form-control" id="phone" placeholder="telefono">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input data-ng-model="subscription.different_address" type="checkbox"> L'indirizzo di fatturazione è diverso da quello di consegna
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div data-ng-show="subscription.different_address">
                            <div class="col-md-offset-2 col-md-10"><h4>Indirizzo di fatturazione</h4></div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nome o ragione sociale</label>
                                <div class="col-sm-10">
                                    <input type="text" data-ng-model="user.invoice.name" class="form-control" id="name" placeholder="Nome">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Cognome</label>
                                <div class="col-sm-10">
                                    <input type="text" data-ng-model="user.invoice.last_name" class="form-control" id="surname" placeholder="Cognome">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-2 control-label">Indirizzo di fatturazione</label>
                                <div class="col-sm-10">
                                    <input type="text" data-ng-model="user.invoice.address"  class="form-control" id="address" placeholder="via e n° civico">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label"></label>

                                <div class="col-xs-2">
                                    <input type="text" data-ng-model="user.invoice.zip" class="form-control" placeholder="CAP" maxlength="5">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" data-ng-model="user.invoice.city" class="form-control" placeholder="Comune">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12">* Campi obbligatori</div>
                    </div>

                    <div class="col-xs-12 col-md-6 step-nav"><button class="btn-lg btn btn-default" data-ng-click="goToStep($event, 'blacklist')"><i class="glyphicon glyphicon-chevron-left"></i> Torna </button></div>
                    <div class="col-xs-12 col-md-6 step-nav"><button class="btn-lg btn btn-success" data-ng-disabled="!subscription_form.$valid" data-ng-click="subscribeSave($event)">Continua <i class="glyphicon glyphicon-chevron-right"></i></button></div>

                </div>
            </form>

            <div data-ng-class="show('payment')" id="payment-step" data-step="4" class="step-wrapper row text-center">
                <div class="col-md-12 payment-title">
                    <h3>Scegli il metodo di pagamento</h3>
                </div>
                <div class="col-md-6">
                    <div class="transfer-wrapper">
                        <h4>Paga con bonifico</h4>
                        <p>Effettua un bonifico dell'importo di {{price}} a:<br>
                            Banca Popolare Etica
                            IBAN: xxxxx
                        </p>
                    </div>
                </div>
                <div class="col-md-6" data-ng-show="paypal_ID">
                    <div class="paypal-wrapper ">
                        <h4>Paga con Paypal</h4>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="{{paypal_ID}}">
                            <input type="image" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal è il metodo rapido e sicuro per pagare e farsi pagare online.">
                            <img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
                        </form>
                    </div>
                </div>
            </div>
            <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Errore</h4>
                        </div>
                        <div class="modal-body">
                            <p>{{message}}</p>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>

<?php
get_footer();
?>
