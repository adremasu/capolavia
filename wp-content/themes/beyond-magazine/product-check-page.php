<?php
/**
 * Template Name: Products Check Page
 */

get_header();?>
    <div class="row" id="kt-main">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>I prodotti</span></p>
            </div>
        </div>

        <div class="col-md-12">

    <?php
                $type = 'products';
                $args=array(
                    'post_type' => $type,
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'caller_get_posts'=> 1
                );
                $my_query = null;
                $my_query = new WP_Query($args);
                if( $my_query->have_posts() ) {
                    while ($my_query->have_posts()) : $my_query->the_post(); ?>
                        <div class="checkbox">
                            <label><input type="checkbox" value=""><?php the_title(); ?></label>
                        </div>
                    <?php
                    endwhile;
                }
                wp_reset_query();  // Restore global post data stomped by the_post().
                ?>



        </div>
    </div>
<?php
get_footer();
?>