<?php get_header();?>
<div class="row hentry" id="kt-main" itemscope itemtype="http://schema.org/NewsArticle">
    <div class="col-md-12">
    <?php if(have_posts()):while(have_posts()):the_post();?>
        <div <?php post_class('kt-article'); ?> id="post-<?php the_ID(); ?>">
            <?php

            if (function_exists('get_field')){
                    $images = get_field('galleria');
                    $post_template = get_field('tipo_di_post');
                }


            $categories = wp_get_post_categories( $post->ID);
            if (is_array($categories)){
                $cat = get_category( $categories[0] );
                $t_id = $cat->term_id;
                $cat_meta = get_option( "category_$t_id");
                if (is_array($cat_meta)){
                    $icon = $cat_meta['img'];
                } else {
                    $icon = 'leaf';
                }
            }

            ?>
            <div id="kt-icon">
                <div id="kt-icon-inner"><i class="fa fa-<?php echo $icon;?>"></i></div>
            </div>
            <div id="kt-article-title" class="row">
                <h2 class="col-md-9 h3" itemprop="headline"><?php
                    $beyond_thetitle = get_the_title($post->ID);
                    $beyond_origpostdate = get_the_date('M d, Y', $post->post_parent);
                    if($beyond_thetitle == null):echo $origpostdate;
                    else:
                    the_title();
                    endif;
                    ?>
                </h2>
                <div class="social-links col-md-2">
                    <div class="row "><span class="h6 col-md-12"><?php echo __("Condividi su:"); ?></span></div>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <a href="#" class="col-md-3" rel="nofollow" onclick="return window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>', 'facebook-share-dialog','width=626,height=436'); ">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                        <a href="#" class="col-md-3" rel="nofollow" onclick="return window.open('https://twitter.com/home?status=<?php echo $beyond_thetitle; ?>%20-%20<?php echo get_permalink(); ?>', 'twitter-share-dialog','width=626,height=436'); ">

                            <i class="fa fa-twitter-square"></i>
                        </a>
                        <a href="#" class="col-md-3" rel="nofollow" onclick="return window.open('https://plus.google.com/share?url=<?php echo get_permalink(); ?>', 'twitter-share-dialog','width=626,height=436'); ">
                            <i class="fa fa-google-plus-square"></i>
                        </a>

                    </div>

                </div>
                <meta itemprop="datePublished" content="<?php echo get_the_date(get_option( 'date_format')) ;?>"/>
                <p class="small col-md-8">Pubblicato in data: <?php echo get_the_date(get_option( 'date_format'));?>,
                    <?php echo __('in','beyondmagazine');?> <?php echo get_the_category_list(','); ?> &nbsp;
                &nbsp; <i class="glyphicon glyphicon-comment small"></i>
                <?php comments_number( __('No Comments'), __('1 Comment'),__('% Comments')); ?></p>

            </div>

            <?php
            switch($post_template){
                case null:
                    include 'template/default.php';
                    break;
                case 'default':
                    include 'template/default.php';
                    break;
                case '2-columns';
                    include 'template/2-columns.php';
                    break;
                default:
                    include 'template/default.php';
                    break;
            }
            ?>
            <div class="kt-article-content">


              <h4>I prodotti di Capolavia in questa ricetta</h4>
              <div class="row">

            <?php
            $connected = new WP_Query( array(
              'connected_type' => 'products_to_recipes',
              'connected_items' => get_queried_object(),
              'nopaging' => true,
              'orderby' => 'title',
              'order' => 'ASC'

            ) );
            if ( $connected->have_posts() ) :
                ?>

                <?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
                <div class="product-box col-md-3 col-xxs-12 col-xs-6">

                  <div class="kt-article">

                      <a href="<?php the_permalink(); ?>">
                        <?php     $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); ?>
                          <div class="product-image" style="background-image: url(<?php echo $large_image_url[0];?>)">
                              <div class="search-bg"><i class="fa fa-search fa-5x"></i>
                              </div>
                              <h3 class="h4 product-name"><?php the_title(); ?></h3>

                          </div>
                      </a>
                  </div>
                </div>

                <?php endwhile; ?>

                <?php
                // Prevent weirdness
                wp_reset_postdata();

                endif;
            ?>



              </div>
            </div>
            <div class="row">
                <div class="col-md-6"><?php previous_post_link(); ?></div>
                <div class="col-md-6 text-right"><?php next_post_link(); ?></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="kt-comments">
                        <?php comments_template( '', true ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; endif;?>
</div>
<?php get_footer();?>
