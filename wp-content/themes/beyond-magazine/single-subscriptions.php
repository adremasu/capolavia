<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 03/01/17
 * Time: 15.55
 */
?>
<?php get_header('subscriptions');?>
<body data-ng-app="subscriptionAdmin" data-ng-controller="subCtrl">
<?php
//$post = get_post( $id );
//$subMeta = get_post_meta($post->ID, 'subscription_data', true);
//$genMeta = get_post_meta($post->ID, '', true);


?>
<?php if(have_posts()):while(have_posts()):the_post();?>

<?php
    $blacklistedProducts = p2p_type( 'blacklist' )->get_connected( $post->ID )->posts;
    $deliveryProducts = p2p_type( 'products_to_deliveries' )->get_connected( $post->ID )->posts;
    $users = get_users( array(
        'connected_type' => 'subscription_owner',
        'connected_items' => $post
    ) );
    $user = array('display_name' => $users[0]->data->display_name, 'user_email' => $users[0]->data->user_email);
    $deliveries = p2p_type( 'deliveries_to_subscriptions' )->get_connected( $post->ID )->posts;
    $lastDeliveryID = $deliveries[0]->ID;
    $_lastDeliveryIProducts = p2p_type( 'products_to_deliveries' )->get_connected( $deliveries[0]->ID );
    while( $_lastDeliveryIProducts->have_posts() ) : $_lastDeliveryIProducts->the_post();
        $id = get_post()->p2p_id;
        $lastDeliveryIProducts[$id] = get_post();
        $lastDeliveryIProducts[$id]->quantity = p2p_get_meta( get_post()->p2p_id, 'quantity', true );
        $lastDeliveryIProducts[$id]->price = p2p_get_meta( get_post()->p2p_id, 'price', true );

    endwhile;

    $availableProducts = get_posts(array(
        'post_type' =>'products',
        'posts_per_page'   => -1,
    ));

    foreach($availableProducts  as $product):
        $id = $product->ID;
        $availableProductsList[] = array(
            'name' => $product->post_title,
            'single_price' => get_post_meta($id, 'single_price', true),
            'mu' => get_post_meta($id, 'mu', true),
            'id' => $id
        );
    endforeach;
    ?>
    <script subscriptiondata type="application/json">
        {"data_type":"user", "data": <?php echo json_encode($user); ?> }
    </script>
    <script subscriptiondata type="application/json">
        {"data_type":"blacklist", "data":<?php echo json_encode($blacklistedProducts); ?> }
    </script>
    <script subscriptiondata type="application/json">
        {"data_type":"lastDeliveryProducts", "data":<?php echo json_encode($lastDeliveryIProducts); ?> }
    </script>
    <script subscriptiondata type="application/json">
        {"data_type":"availableProducts", "data":<?php echo json_encode ($availableProductsList); ?> }
    </script>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Project name</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
                <form class="navbar-form navbar-right">
                    <input type="text" auto-complete ui-items="list" ng-model="yourModel" class="form-control" placeholder="Type something" />

                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 col-md-9 main">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="page-header"><?php echo $user['display_name']; ?></h1>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <p class="subtitle">Prodotto</p>

                        <ui-select ng-model="selectedItem" on-select="getSelected($item)">
                            <ui-select-match>
                                <span ng-bind="$select.selected.name"></span>
                            </ui-select-match>
                            <ui-select-choices repeat="item in (availableProducts | filter: $select.search)">
                                <span ng-bind="item.name"></span>
                            </ui-select-choices>
                        </ui-select>

                    </div>
                    <div class="col-md-3">
                        <p class="subtitle">Peso/Q.tà</p>
                        <div class="input-group input-group-lg">
                                <input type="number" min="0" data-ng-change="getValue()" class="form-control" data-ng-model="selectedItem.weight">
                                <span class="input-group-addon">{{selectedItem.mu}}</span>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <p class="subtitle">Prezzo</p>
                        <h3 class="price">{{selectedItem.single_price}}€/{{selectedItem.mu}}</h3>
                    </div>
                    <div class="col-md-2">
                        <p class="subtitle">Valore</p>
                        <h3 class="price">{{selectedItem.total_price}}€</h3>
                    </div>
                    <div class="col-md-1">
                        <p class="subtitle">Agg.</p>

                        <button type="button" data-ng-click="addItem(selectedItem.id)" class="btn btn-success btn-lg"><b>+</b></button>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="60%">Prodotto</th>
                                <th width="15%">Peso</th>
                                <th width="15%">Valore</th>
                                <th width="10%">  </th>

                            </tr>
                        </thead>
                        <tbody>

                            <tr data-ng-repeat="item in deliveryItems track by $index">
                                <td>{{item.name}}</td>
                                <td>{{item.weight}}</td>
                                <td>{{item.total_price}}</td>
                                <td><button class="btn btn-danger" ng-click="removeItem(item.id)">-</button></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 sidebar">
                <div class="nav nav-sidebar total-value-container">
                    <h4>Valore totale</h4>
                    <button class="total-value btn btn-success btn-lg">{{deliveryValue}} €</button>
                    <button class="total-value btn-lg btn-danger pull-right save-delivery-button" data-ng-click="saveDelivery()">SALVA CONSEGNA</button>
                </div>
                <div>
                    <ul class="nav nav-sidebar">

                    </ul>
                </div>
                <div class="nav nav-sidebar blacklist">
                    <h4>Blacklist</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr data-ng-repeat="(key, product) in blacklist">
                            <td>{{product.post_title}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="nav nav-sidebar total-value-container">
                    <h4>Consegna precedente</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr data-ng-repeat="(key, product) in lastDeliveryProducts">
                                <td>{{product.post_title}}</td>
                                <td>{{product.quantity}}g</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php    wp_reset_postdata(); // set $post back to original post ?>

<?php endwhile; endif;?>

</body>
<?php get_footer('subscriptions');?>
