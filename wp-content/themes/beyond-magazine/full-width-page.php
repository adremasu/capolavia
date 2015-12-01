<?php 
/**
* Template Name: Full Width
*/
get_header();?>
<div class="row" id="kt-main">
                <div class="col-md-12">
                <?php if(have_posts()):while(have_posts()):the_post();?>
                     <div <?php post_class('kt-article'); ?> id="post-<?php the_ID(); ?>">
                        <div id="kt-icon">
                            <div id="kt-icon-inner"><span class="glyphicon glyphicon-tree-deciduous"></span></div>
                        </div>
                        <div id="kt-article-title">
                            <h2 class="col-md-9 h3"><?php
                                the_title();
                                ?>
                            </h2>
                        </div>
                        <div>
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