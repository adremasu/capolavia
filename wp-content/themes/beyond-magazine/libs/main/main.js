(function() {
  var productsApp;

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

}).call(this);
