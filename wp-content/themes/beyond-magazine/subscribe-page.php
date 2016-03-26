<?php
/**
 * Template Name: Subscribe page
 */

get_header();?>
    <div class="row" id="kt-main" ng-app="subscribeApp">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>La salute a casa tua in 3 mosse</span></p>
            </div>
        </div>
        <div id="subscription-wrapper" class="col-md-12" ng-controller="configCtrl">
            <div id="first-step" data-step="1" class="row">
                <div class="col-md-4 square subscription_length" data-subscription_length="3">
                    <div class="length">ciao ciao</div>
                </div>
                <div class="col-md-4 square subscription_length" data-subscription_length="6">
                    <div class="length">ciao ciao</div>
                </div>
                <div class="col-md-4 square subscription_length" data-subscription_length="9">
                    <div class="length">ciao ciao</div>
                </div>
            </div>
            <div id="second-step" data-step="2" class="row"></div>
            <div id="third-step" data-step="3" class="row"></div>

        </div>
    </div>
<?php
get_footer();
?>