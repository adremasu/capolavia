(function() {
  var productsApp;

  productsApp = angular.module('productsApp', []);

  //We already have a limitTo filter built-in to angular,
  //let's make a startFrom filter
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
      //parse to int
      return input.slice(start);
    };
  });

}).call(this);
