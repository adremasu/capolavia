<?php
get_header();?>
<?php
global $wp_query;
$args = array(
    'post_type' => array( 'products' ),
    'showposts' => -1,
    'paged'=>$paged,
    'meta_key' => 'disponibilita',
    'meta_value' => '1'

);
$the_query = new WP_Query( $args );

/* Restore original Post Data */


?>
<div class="row" id="kt-main" >
    <div class="col-md-12">
        <div id="kt-latest-title" class="h3">
            <p><span>I prodotti</span></p>
        </div>
    </div>
    <div>
        <?php
        // The Loop

        if ( $the_query->have_posts() ) :
        ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="product">Cerca i prodotti</label>
                    <input type="text" class="form-control" id="productSearch" ng-model="search" placeholder="Digita il nome di un prodotto">
                </div>
            </div>



            <div data-ng-init="products = [
                <?php
                if ( $the_query->have_posts() ) :

                    ?>
                    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                        <?php
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'small_square' );
                        $url = $thumb['0'];
                        $p = $post;
                        ?>
                        {'name': '<?php the_title(); ?>',
                            'thumb': '<?php echo $url; ?>',
                            'url': '<?php echo get_permalink(); ?>'
                        },

                        <?php
                        endwhile;
                    endif;

                        ?>                ]">
            </div>


            <div data-ng-repeat="product in products | filter: search | orderBy: 'name'">

                <div class="product-box col-md-3 col-xxs-12 col-xs-6">
                    <div class="kt-article">

                        <a href="{{product.url}}">

                            <div class="product-image" style="background-image: url({{product.thumb}})">
                                <div class="search-bg"><i class="fa fa-search fa-5x"></i>
                                </div>
                                <h3 class="h4 product-name">{{pageSize}}{{product.name}}</h3>

                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php

    endif;
        wp_reset_postdata();

        ?>

</div>
<?php
get_footer();
?>