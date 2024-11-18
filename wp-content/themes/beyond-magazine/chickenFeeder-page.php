<?php
/**
 * Template Name: Chicke feeder
 */

get_header();?>

<div class="row" id="kt-main" ng-app="chickenfeederApp">
    <div class="col-md-12">
        <div id="kt-latest-title" class="h3">
            <p><span><?php the_title(); ?></span></p>
        </div>
    </div>

    <div id="chickenFeeder-wrapper" class="col-md-12" ng-controller="chickenCtrl">
    <iframe style="width:100%;height:650px" width="100%" height="100%" src="https://www.youtube.com/embed/n0E_aZ5C0x8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>


<?php
get_footer();
?>
