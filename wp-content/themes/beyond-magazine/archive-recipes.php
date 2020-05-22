<?php
get_header();?>
    <div class="row" id="kt-main">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>Le ricette</span></p>
            </div>
        </div>
        <?php
        $at_least_one_author_recipe = 0;
        if(have_posts()) : while(have_posts()) : the_post();
            ?>
            <div class="product-box col-md-3 col-xxs-12 col-xs-6">
                <div class="kt-article">
                  <?php
                  $author_meta = get_the_author_meta('customer');
                  $authorID = get_the_author_meta('ID');
                  if (is_array($author_meta) && $author_meta['recipes_author']){
                    $at_least_one_author_recipe = 1;
                    $author_url = get_author_posts_url($authorID);
                    ?>
                    <img src="<?php echo get_template_directory_uri();?>/img/Sunflower-icon.png" class="sunflower-icon"/>
                    <?php
                  }
                  ?>
                    <a href="<?php echo get_permalink(); ?>">
                        <?php
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'small_square' );
                        $url = $thumb['0'];
                        ?>
                        <div class="product-image" style="background-image: url(<?php echo $url; ?>)">
                            <div class="search-bg"><i class="fa fa-search fa-5x"></i>
                            </div>
                            <h3 class="h4 product-name"><?php the_title(); ?></h3>

                        </div>
                    </a>
                </div>
            </div>

        <?php
        endwhile; endif;
        if($at_least_one_author_recipe) { ?>
            <div class="col-xs-12">
              <div class="legend-frame">
                <img src="<?php echo get_template_directory_uri();?>/img/Sunflower-icon.png" class="sunflower-icon-legend"/>
                <span> Ricette a cura della <a href="<?php echo $author_url;?>">Cucina dei Girasoli</a>
              </div>
            </div>
            <?php
        }
        ?>

    </div>
<?php
get_footer();
?>
