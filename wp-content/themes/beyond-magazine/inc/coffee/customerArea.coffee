customerAreaApp = angular.module('customerAreaApp', [])

customerAreaApp.controller "profileController", ['$scope','$http', ($scope, $http) ->

]
customerAreaApp.directive 'userData', ->
  {
  link: ($scope, element, attr, ctrl) ->
    data = (element[0].innerHTML)
    data = JSON.parse(data)
    $scope.userData = data
  }

customerAreaApp
