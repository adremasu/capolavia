<?php get_header();?>
<?php $icon = get_post_meta( $post->ID, '_my_meta_value_key', true ) ? get_post_meta( $post->ID, '_my_meta_value_key', true ) : 'leaf'; ?>
<div class="row" id="kt-main">
    <div class="col-md-12">
    <?php if(have_posts()):while(have_posts()):the_post();?>
         <div <?php post_class('kt-article'); ?> id="post-<?php the_ID(); ?>">
            <div id="kt-icon">
                <div id="kt-icon-inner"><i class="fa fa-<?php echo $icon;?>"></i></div>
            </div>
            <div id="kt-article-title" class="row">
                <h2 class="col-md-9 h3"><?php
                    $beyond_thetitle = get_the_title($post->ID);
                    $beyond_origpostdate = get_the_date(get_option('date_format'), $post->post_parent);
                    if($beyond_thetitle == null):echo $beyond_origpostdate;
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
            </div>
            <div class="kt-article-content">

                <?php

                the_content();
                ?>

                <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'beyondmagazine' ) . '</span>', 'after' => '</div>' ) ); ?>
                <div class="clearfix"></div>
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