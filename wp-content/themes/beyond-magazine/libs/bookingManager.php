<?php
class BookingManager{
  public static function init(){
      $class = __CLASS__;
      new $class;
  }

  public function __construct(){
    echo '<div class="wrap">
      <h2>Prenotazioni</h2>
        <div ng-app="bookingmanagerApp" ng-controller="perdateController" >
          <div class="selectors">
          <p>inizio: <input ng-model="start" type="text" id="startDatepicker"> fine: <input ng-model="end" type="text" id="endDatepicker"></p>
          <p><a ng-click="getBookings()" class="btn btn-default" href="">Vedi tutte le Prenotazioni</a></p>
          <div id="postContainer"></div>
          </div>
        </div>

    </div>';
    }

public function booking_manager_page() {
  ?>


  <?php
}
}
