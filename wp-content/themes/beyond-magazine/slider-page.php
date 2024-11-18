<?php
/**
 * Template Name: Slider page
 */
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 24/10/16
 * Time: 16.12
 */

get_header();
?>
<?php
$gallery = get_field('galleria');

foreach ($gallery as $slide){
    $_s = array (
        'image' => $slide['sizes']['large'],
        'title' => $slide['title'],
        'text'=>$slide['description'],
        'buttonText'=>$slide['caption'],
        'buttonUrl'=>$slide['alt'],
    );
    $slides[] = (object)  $_s;
}

?>


<div class="row" id="kt-main">
    <div class="col-md-12">
        <div class="kt-article-content">
            <div ng-app="homepageApp">
                <?php
                echo '        <script type = "text/template" getslides>
            {"data_type":"date", "data":'.json_encode($slides).'}
        </script>';
                ?>
                <div ng-controller="CarouselDemoCtrl" id="slides_control">
                    <div>
                        <carousel interval="myInterval">
                            <slide ng-repeat="slide in slides" active="slide.active">
                                <img ng-src="{{slide.image}}">
                                <div class="carousel-caption">
                                    <h4>{{slide.title}}</h4>
                                    <p>{{slide.text}}</p>
                                    <a href="{{slide.buttonUrl}}" class="button">{{slide.buttonText}}</a>
                                </div>
                            </slide>
                        </carousel>
                    </div>
                </div>
            </div>
            <?php

            the_content();
            ?>
        </div>
    </div>
</div>
<?php
get_footer();