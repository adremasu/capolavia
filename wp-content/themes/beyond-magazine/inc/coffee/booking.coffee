bookingApp = angular.module('bookingApp', [])

bookingApp.controller "bookingController", ['$scope','$http', ($scope, $http) ->
  $scope.loading = false
  $scope.success = false
  console.log $scope.success
  $scope.saveBooking = (e)->
    e.preventDefault()
    $scope.loading = true
    productsData = $scope.products
    userData = $scope.user
    date = $scope.date

    # se il form è valido invia l'ordine
    if ($scope.booking_form.$valid)
      request = {
        action:   "book_products"
        products: productsData
        user:     userData
        date:     date
      }
      $http(
        method: "POST"
        url: "/wp-admin/admin-ajax.php"
        data: jQuery.param(request)
        headers:
          "Content-Type": "application/x-www-form-urlencoded"
      ).success (data) ->
        $scope.loading = false
        $scope.success = data.success
        $scope.userMessage = data.userMessage
  $scope.recap = (e) ->

]
bookingApp