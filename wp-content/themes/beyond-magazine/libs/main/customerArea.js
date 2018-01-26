(function() {
  var customerAreaApp;

  customerAreaApp = angular.module('customerAreaApp', []);

  customerAreaApp.controller("profileController", ['$scope', '$http', function($scope, $http) {}]);

  customerAreaApp.directive('userData', function() {
    return {
      link: function($scope, element, attr, ctrl) {
        var data;
        data = element[0].innerHTML;
        data = JSON.parse(data);
        return $scope.userData = data;
      }
    };
  });

  customerAreaApp;

}).call(this);
