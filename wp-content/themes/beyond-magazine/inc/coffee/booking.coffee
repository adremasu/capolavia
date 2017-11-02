bookingApp = angular.module('bookingApp', [])

bookingApp.controller "bookingController", ['$scope','$http', ($scope, $http) ->
  $scope.loading = false
  $scope.success = false
  $scope.saveBooking = (e)->
    e.preventDefault()
    $scope.loading = true
    productsData = $scope.products
    userData = $scope.user
    date = $scope.date
    e.target
    console.log e.target

    # se il form è valido invia l'ordine
    if ($scope.booking_form.$valid)
      selectedProducts = {}
      for product of productsData
        if productsData[product]['weight'] || productsData[product]['items']
          selectedProducts[product] = productsData[product]
      request = {
        action:   "book_products"
        products: selectedProducts
        user:     userData
        date:     date
      }
      $http(
        method: "POST"
        url: "/wp-admin/admin-ajax.php"
        data: jQuery.param(request)
        headers:
          "Content-Type": "application/x-www-form-urlencoded"
      )
      .success (data) ->
        $scope.loading = false
        $scope.success = data.success
        $scope.userMessage = data.userMessage
        jQuery('#myModal').modal('hide')
      .error ->
        $scope.loading = false
        $scope.success = data.false
        $scope.userMessage = 'Ops! Qualcosa è andato storto'
        jQuery('#myModal').modal('hide')

  $scope.completed = ->
    false

  $scope.recap = (e) ->

]
bookingApp
