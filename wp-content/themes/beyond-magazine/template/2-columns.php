<div class="kt-article-content">

    <div class="row top-fb-like">
        <div class="col-md-3">
            <div class="fb-like" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
        </div>
    </div>


        <div class="row">

            <div class="col-md-6">
                <?php
            if (function_exists('get_field')){
                $immagine_post_2_colonne = get_field('immagine_post_2_colonne');
                $map = get_field('map');

            }
            if( $images ):
                $count = count($images);
                if ($count>0):
                ?>
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="">
                    <ol class="carousel-indicators">
                        <?php for($i=0;  $i < $count; $i++):?>
                            <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i?>" <?php if ($i == 0):?>class="active"<?php endif; ?>></li>
                        <?php endfor;?>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <?php $j = 0?>
                        <?php foreach( $images as $image ): ?>
                            <div class="item<?php if ($j == 0):?> active<?php endif; ?>">
                                <img itemprop="image" src="<?php echo $image['sizes']['gallery']; ?>" alt="<?php echo $image['alt']; ?>">
                                <div class="carousel-caption">
                                    <?php echo $image['alt']; ?>
                                </div>
                            </div>

                            <?php $j++;?>

                        <?php endforeach; ?>
                    </div>
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <?php endif; ?>

            <?php elseif(is_array($immagine_post_2_colonne)):?>
                <img itemprop="image" class="post_image" src="<?php echo $immagine_post_2_colonne['sizes']['large']?>" alt="<?php $immagine_post_2_colonne['alt']?>"/>
            <?php elseif(has_post_thumbnail()):the_post_thumbnail('large',array('class'=>'img-responsive')); endif;?>
            <div class="col-md-6  hidden-md hidden-lg">

                <?php
                the_content();
                ?>
            </div>
            <?php if ($map): ?>
                <div class="map_wrapper">
                    <?php
                    echo do_shortcode( "[sgpx gpx='$map']" );
                    ?>
                </div>
            <?php endif; ?>
            </div>
            <div class="col-md-6 hidden-xs hidden-sm" itemprop="articleBody">

                <?php
                the_content();
                ?>
            </div>
        </div>


    <div class="row">
        <div class="col-md-12">
            <div class="tag-links">
                <?php if(has_tag()):?>
                    <i class="glyphicon glyphicon-tags small"></i> &nbsp;
                    <?php
                    echo get_the_tag_list(' ',', ',' ');
                    ?>
                <?php endif; ?>

            </div>

        </div>
    </div>
    <?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'beyondmagazine' ) . '</span>', 'after' => '</div>' ) ); ?>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-3">
            <div class="fb-like" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
        </div>
    </div>
</div>