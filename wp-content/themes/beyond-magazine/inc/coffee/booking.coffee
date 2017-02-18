bookingApp = angular.module('bookingApp', [])

bookingApp.controller "bookingController", ['$scope','$http', ($scope, $http) ->
  $scope.loading = false
  $scope.success = false
  $scope.selectedProduct = {}
  $scope.saveBooking = (e)->
    e.preventDefault()
    $scope.loading = true
    productsData = $scope.products
    userData = $scope.user
    date = $scope.date

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
  $scope.select = (id) ->
    gif = jQuery('.loading_gif')
    if (id != $scope.selectedProduct.id)
      gif.show()
      $scope.selectedProduct.name = ''
      $scope.selectedProduct.content = ''
      $scope.selectedProduct.id = id
      request = {
        action:   "get_product_info"
        id: id
      }
      $http(
        method: "POST"
        url: "/wp-admin/admin-ajax.php"
        data: jQuery.param(request)
        headers:
          "Content-Type": "application/x-www-form-urlencoded"
      ) .success (data) ->
          $scope.selectedProduct.name = data.name
          $scope.selectedProduct.content = data.content
          $scope.selectedProduct.img = data.img
          gif.hide()
    else
      gif.hide()
      return
]
bookingApp