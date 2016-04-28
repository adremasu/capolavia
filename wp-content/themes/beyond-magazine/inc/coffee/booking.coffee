bookingApp = angular.module('bookingApp', [])

bookingApp.controller "bookingController", ['$scope',($scope) ->

  $scope.saveBooking = ->
    productsData = $scope.products
    userData = $scope.user
    # se il form è valido invia l'ordine
    if ($scope.booking_form.$valid)
      console.log booking_form
]
bookingApp