<?php get_header();?>
<div class="row" id="kt-main">
                <div class="col-md-12">
                <?php if(have_posts()):while(have_posts()):the_post();?>
                    <div <?php post_class('kt-article'); ?> id="post-<?php the_ID(); ?>">
                        <div id="kt-icon">
                            <div id="kt-icon-inner"><i class="glyphicon glyphicon-tree-deciduous"></i></div>
                        </div>
                        <div id="kt-article-title">
                            <h1><?php 
                                $beyond_thetitle = get_the_title($post->ID);
                                $beyond_origpostdate = get_the_date('M d, Y', $post->post_parent);
                                if($beyond_thetitle == null):echo $origpostdate; 
                                else:
                                the_title();
                                endif;
                                ?>
                            </h1>
                            <p class="small"><?php the_date('d, M Y') ;?> by <a href="<?php echo get_the_author_link();?>"><?php echo get_the_author(); ?></a> <?php echo __('in','beyondmagazine');?> <?php echo get_the_category_list(','); ?> &nbsp;
                            &nbsp; <i class="glyphicon glyphicon-comment small"></i>
                            <?php comments_number( __('No Comments','beyondmagazine'), __('1 Comment','beyondmagazine'),__('% Comment' ,'beyondmagazine')); ?></p>

                        </div>
                        <div class="kt-article-content">
                             <?php if(has_tag()):?>
                            <i class="glyphicon glyphicon-tags small"></i> &nbsp; 
                            <?php 
                            echo get_the_tag_list(' ',', ',' ');
                            ?> 
                            <?php endif; ?>

                            <?php 
                            if(has_post_thumbnail()):the_post_thumbnail('',array('class'=>'img-responsive')); endif; 
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
                <?php get_sidebar(); ?>
            </div>
<?php get_footer();?>