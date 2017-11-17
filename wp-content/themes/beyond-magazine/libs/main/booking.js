(function() {
  var bookingApp;

  bookingApp = angular.module('bookingApp', []);

  bookingApp.controller("bookingController", [
    '$scope', '$http', function($scope, $http) {
      $scope.loading = false;
      $scope.success = false;
      $scope.saveBooking = function(e) {
        var date, product, productsData, request, selectedProducts, userData;
        e.preventDefault();
        $scope.loading = true;
        productsData = $scope.products;
        userData = $scope.user;
        date = $scope.date;
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
            console.log('fail');
            jQuery('#errorModal').modal('show');
            return e.target.disabled = false;
          });
        }
      };
      $scope.completed = function() {
        return false;
      };
      return $scope.recap = function(e) {};
    }
  ]);

  bookingApp;

}).call(this);
