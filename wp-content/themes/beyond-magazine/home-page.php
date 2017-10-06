<?php
/**
* Template Name: Home Page
*/
get_header();?>
<?php if(have_posts()):while(have_posts()):the_post();?>


<div class="row" id="kt-main">
  <div class="col-md-12">
       <div <?php post_class('kt-article'); ?> id="post-<?php the_ID(); ?>">
          <div id="kt-icon">
              <div id="kt-icon-inner"><span class="glyphicon glyphicon-tree-deciduous"></span></div>
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
              <div id="carousel-home-page" class="carousel slide" data-ride="carousel">
                <?php
                if (function_exists('get_field')):

                $images = get_field('galleria');
                $size = 'large'; // (thumbnail, medium, large, full or custom size)

                if( $images ):
                  $ImCount = count($images);
                  ?>
                <!-- Indicators -->
                <ol class="carousel-indicators">
                  <?php for ($i=0; $i < $ImCount; $i++) { ?>
                    <li data-target="#carousel-home-page" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0){echo 'class="active"';} ?>></li>
                    <?php } ?>
                </ol>



              <div class="carousel-inner" role="listbox">

                <?php

                  $i = 0;
                  foreach( $images as $image ): ?>

                  <div class="item <?php if($i == 0) {echo "active";}?>">
                    <?php echo wp_get_attachment_image( $image['ID'], $size ); ?>
                    <div class="carousel-caption">
                      <h4 class="carousel-title"><?php echo $image['title']; ?></h4>
                      <p class="carousel-description"><?php echo $image['description'] ?></p>
                    </div>
                  </div>
                  <?php
                  $i++;
                endforeach; ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>

            <a class="left carousel-control" href="#carousel-home-page" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-home-page" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
            </div>
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
</div>
<?php endwhile; endif;?>

<?php get_footer();?>
