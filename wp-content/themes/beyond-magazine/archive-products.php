<?php
get_header();?>
<div class="row" id="kt-main">
    <div class="col-md-12">
        <div id="kt-latest-title" class="h3">
            <p><span>I prodotti</span></p>
        </div>
    </div>
    <?php
    if(have_posts()) : while(have_posts()) : the_post();
        ?>
            <div class="product-box col-md-3 col-xxs-12 col-xs-6">
                <div class="kt-article">
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
    endwhile; endif; ?>

</div>
<?php
get_footer();
?>