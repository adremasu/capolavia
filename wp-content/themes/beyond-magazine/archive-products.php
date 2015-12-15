<?php
get_header();?>
<?php
global $wp_query;
query_posts( array('post_type' => array( 'products' ),'showposts' => -1, 'paged'=>$paged )
);?>
<div class="row" id="kt-main" >
    <div class="col-md-12">
        <div id="kt-latest-title" class="h3">
            <p><span>I prodotti</span></p>
        </div>
    </div>
    <div>
        <?php
        if(have_posts()) : ?>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="product">Cerca i prodotti</label>
                    <input type="text" class="form-control" id="productSearch" ng-model="search" placeholder="Digita il nome di un prodotto">
                </div>
            </div>



            <div data-ng-init="products = [
                <?php
                while(have_posts()) : the_post();
                ?>
                        <?php
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'small_square' );
                        $url = $thumb['0'];
                        ?>
                        {'name': '<?php the_title(); ?>',
                            'thumb': '<?php echo $url; ?>',
                            'url': '<?php echo get_permalink(); ?>'
                        },

                        <?php

                        endwhile;

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
    endif; ?>

</div>
<?php
get_footer();
?>