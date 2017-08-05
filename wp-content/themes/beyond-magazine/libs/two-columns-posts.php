<?php              
if(is_category()): 
    $beyond_category = get_query_var('cat'); 
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    'paged'=>$beyond_paged,
    'cat'=>$beyond_category
    ));
   
elseif(is_tag()):
    $beyond_tag = get_query_var('tag'); 
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    'paged'=>$beyond_paged,
    'tag'=>$beyond_tag
    )); 
    
elseif(is_search()):
    
    $beyond_search = get_query_var('s');
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    's'=>$beyond_search,
    'paged'=>$beyond_paged));
else:
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    'paged'=>$beyond_paged));
endif;
     
    $beyond_divider = 2;
                        ?>
<?php if( $beyond_my_query->have_posts() ) : ?>
<div class="row">
    <div class="col-md-8">
        <div id="kt-latest-title" class="h3">
            <p><span><?php echo __('Recent Posts');?></span></p>
        </div>
        <div class="kt-articles">
            <div class="row">
                <article class="col-md-6 visible-xs-block">
                    <div class="w
                     clearfix">
                        <?php dynamic_sidebar( 'mobile-blog-sidebar' ); ?>
                    </div>
                </article>
                <?php while( $beyond_my_query->have_posts() ) : $beyond_my_query->the_post(); ?>
            <article class="col-md-6">
                <div class="kt-article clearfix">
                    <time><?php echo get_the_date(get_option( 'date_format'));?></time>
                    <a href="<?php the_permalink();?>">
                    <?php
                    if(has_post_thumbnail()):
                        echo ltrim(get_the_post_thumbnail( null, 'post-list',array('class'=>'img-responsive') ), 'http:');
                    endif;?>
                    </a>
                    <h2>
                        <a href="<?php the_permalink();?>" title="<?php the_title();?>" class="h3">
                            <?php the_title();?>
                        </a>
                    </h2>
                    <?php the_excerpt();?>
                    <a href="<?php the_permalink();?>" class="btn btn-primary pull-right"><?php echo __('Read more...');?></a>
                </div>
            </article>
            <?php $beyond_current_position = $beyond_my_query->current_post + 1; ?>

                <?php if( $beyond_current_position < $beyond_my_query->found_posts && $beyond_current_position % $beyond_divider == 0 ) : ?>

                <!-- if position is equal to the divider and not the last result close the currently open div and start another -->
    <?php endif; ?>
    <?php endwhile; ?>
    </div>
    </div>

      <!-- close whichever div was last open -->
      <?php else: ?>
      <div class="row kt-no-found-posts"><?php echo __('No posts found.Sorry','beyondmagazine') ;?></div>
      <?php endif; wp_reset_postdata();?>

    </div><!-- .kt-articles end here -->
    <div class="col-md-4 hidden-xs">
    <?php get_template_part( 'sidebar' ); ?>
    </div>
</div>