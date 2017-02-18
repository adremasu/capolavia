(function() {
  var homepageApp, productsApp, subscriptionAdmin;

  productsApp = angular.module('productsApp', []);

  productsApp.archiveCtrl = function($scope) {
    $scope.currentPage = 0;
    $scope.pageSize = 2;
    $scope.numberOfPages = function() {
      return Math.ceil($scope.products.length / $scope.pageSize);
    };
  };

  productsApp.filter('startFrom', function() {
    return function(input, start) {
      start = +start;
      return input.slice(start);
    };
  });

  productsApp;

  homepageApp = angular.module('homepageApp', ['ui.bootstrap']);

  homepageApp.controller("CarouselDemoCtrl", [
    '$scope', '$http', function($scope, $http) {
      $scope.myInterval = 6000;
    }
  ]);

  homepageApp.directive('getslides', function() {
    return {
      link: function(scope, element, attr, ctrl) {
        var data, _slides;
        scope.slides = {};
        data = element[0].innerHTML;
        _slides = JSON.parse(data).data;
        return scope.slides = _slides;
      }
    };
  });

  homepageApp;

  subscriptionAdmin = angular.module('subscriptionAdmin', ['ngSanitize', 'ui.select']);

  subscriptionAdmin.controller("subCtrl", [
    '$scope', '$http', function($scope, $http) {
      $scope.deliveryValue = 0;
      $scope.deliveryItems = {};
      $scope.getSelected = function($item) {
        return $scope.selectedItem = $item;
      };
      $scope.addItem = function(id) {
        $scope.deliveryItems[id.toString()] = $scope.selectedItem;
        return $scope.getDeliveryValue();
      };
      $scope.removeItem = function(id) {
        delete $scope.deliveryItems[id.toString()];
        return $scope.getDeliveryValue();
      };
      $scope.getDeliveryValue = function() {
        var id, item, _ref, _results;
        $scope.deliveryValue = 0;
        _ref = $scope.deliveryItems;
        _results = [];
        for (id in _ref) {
          item = _ref[id];
          _results.push($scope.deliveryValue = $scope.deliveryValue + item.total_price);
        }
        return _results;
      };
      $scope.saveDelivery = function() {
        return console.log('save');
      };
      $scope.getValue = function() {
        return $scope.selectedItem.total_price = parseFloat(($scope.selectedItem.single_price * $scope.selectedItem.weight).toFixed(2));
      };
    }
  ]);

  subscriptionAdmin.directive("subscriptiondata", function() {
    return {
      link: function(scope, element, attr, ctrl) {
        var data;
        data = element[0].innerHTML;
        data = JSON.parse(data);
        return scope[data.data_type] = data.data;
      }
    };
  });

  subscriptionAdmin;

}).call(this);
