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
    $$beyond_tag = get_query_var('tag'); 
    $$beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    'paged'=>$beyond_paged,
    'tag'=>$beyond_tag
    )); 
    
elseif(is_search()):
    
    $$beyond_search = get_query_var('s');
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    's'=>$$beyond_search,
    'paged'=>$beyond_paged));
else:
    $beyond_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $beyond_my_query = new WP_Query(
    array('post_type'=>'post',
    'paged'=>$beyond_paged));
endif;
    $beyond_divider = 3;
?>
<?php if( $beyond_my_query->have_posts() ) : ?>
                    <div class="kt-articles">  
                        <div class="row">
                        <?php while($beyond_my_query->have_posts() ) : $beyond_my_query->the_post(); ?>
                            <article class="col-md-4">
                                <div class="kt-article clearfix">
                                    <a href="<?php the_permalink();?>">
                                    <?php if(has_post_thumbnail()):the_post_thumbnail('',array('class'=>'img-responsive')); endif;?>
                                    </a>
                                    <h1><?php the_title();?></h1>
                                    <?php the_excerpt(); ?>
                                    <a href="<?php the_permalink();?>" class="btn btn-primary pull-right"><?php echo __('Read More','beyondmagazine');?></a>
                                </div>
                            </article>
                            <?php $beyond_current_position = $beyond_my_query->current_post + 1; ?>

                                <?php if( $beyond_current_position < $beyond_my_query->found_posts && $beyond_current_position % $beyond_divider == 0 ) : ?>

                                <!-- if position is equal to the divider and not the last result close the currently open div and start another -->
                                </div><div class="row">
                                <?php endif; ?>
                                 
                              <?php endwhile; ?>

                              </div>
                              <!-- close whichever div was last open -->
                              <?php else: ?>
                              <div class="row kt-no-found-posts"><?php echo __('No posts found.Sorry','beyondmagazine') ;?></div>
                              <?php endif; wp_reset_postdata();?>
                            
                    </div><!-- .kt-articles end here -->