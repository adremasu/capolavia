(function() {
  var xmasbookingApp;

  document.addEventListener('wheel', function(event) {
    if (document.activeElement.type === 'number') {
      document.activeElement.blur();
    }
  });

  xmasbookingApp = angular.module('xmasbookingApp', ['ngRoute']);

  xmasbookingApp.config([
    '$routeProvider',
    '$locationProvider',
    function($routeProvider,
    $locationProvider) {
      return $locationProvider.html5Mode(true);
    }
  ]);

  xmasbookingApp.controller("xmasbookingController", [
    '$scope',
    '$http',
    '$location',
    function($scope,
    $http,
    $location) {
      var numInput,
    paramValue;
      paramValue = $location.search().myParam;
      $scope.loading = false;
      $scope.success = false;
      numInput = document.querySelector('input');
      numInput.addEventListener('input',
    function() {
        var num;
        num = this.value.match(/^\d+$/);
        if (num === null) {
          this.value = '';
        }
      });
      $scope.plus = function(product) {
        $scope.id = product;
        return $scope.xmasproducts[$scope.id]['qt']++;
      };
      $scope.minus = function(product) {
        $scope.id = product;
        if ($scope.xmasproducts[$scope.id]['qt'] > 0) {
          return $scope.xmasproducts[$scope.id]['qt']--;
        }
      };
      $scope.saveBooking = function(e) {
        var bookingDate,
    date,
    mode,
    product,
    productsData,
    request,
    selectedProducts,
    userData;
        e.preventDefault();
        $scope.loading = true;
        productsData = $scope.xmasproducts;
        userData = $scope.user;
        mode = $scope.mode;
        
        //date value legacy: i have to keep the possibility to get emailDate value, 
        //pofor a smooth update to 2022 version
        date = $scope.emailDate ? $scope.emailDate : $scope.date;
        bookingDate = $scope.date;
        e.target.disabled = true;
        // se il form è valido invia l'ordine 
        if ($scope.booking_form.$valid) {
          selectedProducts = {};
          for (product in productsData) {
            if (productsData[product].qt > 0) {
              selectedProducts[product] = productsData[product];
            }
          }
          request = {
            action: "book_xmasproducts",
            xmasproducts: selectedProducts,
            user: userData
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
            $scope.success = data.false;
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

}).call(this);


//# sourceMappingURL=xmas.js.map
//# sourceURL=coffeescript