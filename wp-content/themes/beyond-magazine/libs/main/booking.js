(function() {
  var bookingApp;

  bookingApp = angular.module('bookingApp', ['ngRoute']);

  bookingApp.config([
    '$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
      return $locationProvider.html5Mode(true);
    }
  ]);

  bookingApp.controller("bookingController", [
    '$scope', '$http', '$location', function($scope, $http, $location) {
      var a, paramValue;
      paramValue = $location.search().myParam;
      $scope.loading = false;
      $scope.success = false;
      a = true ? 5 : 10;
      $scope.deliveryChange = function() {
        return $scope.emailDate = $scope.user.delivery === '1' ? $scope.delivery_date : $scope.date;
      };
      $scope.dateSelect = function(date, mode, event) {
        var clickedButtonId, newModeSelected, prevModeSelected;
        $scope.date = date;
        $scope.mode = mode;
        prevModeSelected = jQuery('.panel-success');
        newModeSelected = jQuery('#panel-' + mode);
        clickedButtonId = event.currentTarget.id;
        prevModeSelected.removeClass('panel-success').addClass('panel-default');
        prevModeSelected.find('button').removeClass('list-group-item-success');
        newModeSelected.addClass('panel-success').removeClass('panel-default');
        jQuery('#' + clickedButtonId).addClass('list-group-item-success');
        return false;
      };
      $scope.saveBooking = function(e) {
        var date, product, productsData, request, selectedProducts, userData;
        e.preventDefault();
        $scope.loading = true;
        productsData = $scope.products;
        userData = $scope.user;
        date = $scope.emailDate;
        e.target.disabled = true;
        if ($scope.booking_form.$valid) {
          selectedProducts = {};
          for (product in productsData) {
            if (productsData[product]['weight'] || productsData[product]['items']) {
              selectedProducts[product] = productsData[product];
            }
          }
          request = {
            action: "book_products",
            products: selectedProducts,
            user: userData,
            date: date
          };
          return $http({
            method: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: jQuery.param(request),
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            }
          }).success(function(data) {
            $scope.loading = false;
            $scope.success = data.success;
            $scope.userMessage = data.userMessage;
            jQuery('#myModal').modal('hide');
            e.target.disabled = false;
            if (!data.success) {
              return jQuery('#errorModal').modal('show');
            }
          }).error(function() {
            $scope.loading = false;
            $scope.success = data["false"];
            $scope.userMessage = 'Ops! Qualcosa è andato storto';
            jQuery('#myModal').modal('hide');
            jQuery('#errorModal').modal('show');
            return e.target.disabled = false;
          });
        }
      };
      $scope.completed = function() {
        return false;
      };
      $scope.select = function(productId) {
        var request;
        jQuery('#productLoadingGif').show();
        $scope.selectedProduct = {};
        $scope.selectedProduct.id = productId;
        request = {
          action: "get_product_info",
          id: productId
        };
        return $http({
          method: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: jQuery.param(request),
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        }).success(function(data) {
          $scope.selectedProduct = data;
          return jQuery('#productLoadingGif').hide();
        });
      };
      return $scope.recap = function(e) {};
    }
  ]);

  bookingApp;

}).call(this);
