document.addEventListener 'wheel', (event) ->
  if document.activeElement.type == 'number'
    document.activeElement.blur()
  return



xmasbookingApp = angular.module('xmasbookingApp', ['ngRoute'])

xmasbookingApp.config ['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) ->
   $locationProvider.html5Mode(true)
]
xmasbookingApp.controller "xmasbookingController", ['$scope','$http', '$location', ($scope, $http, $location) ->
  paramValue = $location.search().myParam
  $scope.loading = false
  $scope.success = false
  
  numInput = document.querySelector 'input'

  numInput.addEventListener 'input', ->
    num = @value.match(/^\d+$/)
    if num == null
      @value = ''
    return
  
  $scope.plus = (product) ->
    $scope.id = product
    $scope.xmasproducts[$scope.id]['qt']++
  $scope.minus = (product) ->
    $scope.id = product
    if $scope.xmasproducts[$scope.id]['qt'] > 0
      $scope.xmasproducts[$scope.id]['qt']--

   
  $scope.saveBooking = (e)->
    e.preventDefault()
    $scope.loading = true
    productsData = $scope.xmasproducts
    userData = $scope.user
    mode = $scope.mode
    
    #date value legacy: i have to keep the possibility to get emailDate value, 
    #pofor a smooth update to 2022 version
    date = if $scope.emailDate then $scope.emailDate else $scope.date
    
    bookingDate = $scope.date
    e.target.disabled =  true

    # se il form è valido invia l'ordine 
    if ($scope.booking_form.$valid)
      selectedProducts = {}
      for product of productsData
        if productsData[product].qt > 0
          selectedProducts[product] = productsData[product]
      request = {
        action:       "book_xmasproducts"
        xmasproducts: selectedProducts
        user:         userData
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
        e.target.disabled = false
        if !data.success
          jQuery('#errorModal').modal('show')

      .error ->
        $scope.loading = false
        $scope.success = data.false
        $scope.userMessage = 'Ops! Qualcosa è andato storto'
        jQuery('#myModal').modal('hide')
        jQuery('#errorModal').modal('show')
        e.target.disabled = false

  $scope.completed = ->
    false
  $scope.select = (productId) ->
    jQuery('#productLoadingGif').show()
    $scope.selectedProduct = {}
    $scope.selectedProduct.id = productId
    request = {
      action:   "get_product_info"
      id: productId
    }
    $http(
      method: "POST"
      url: "/wp-admin/admin-ajax.php"
      data: jQuery.param(request)
      headers:
        "Content-Type": "application/x-www-form-urlencoded"
    )
    .success (data) ->
      $scope.selectedProduct = data
      jQuery('#productLoadingGif').hide()


  $scope.recap = (e) ->

]
