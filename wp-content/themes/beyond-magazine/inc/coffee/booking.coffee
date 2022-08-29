document.addEventListener 'wheel', (event) ->
  if document.activeElement.type == 'number'
    document.activeElement.blur()
  return


bookingApp = angular.module('bookingApp', ['ngRoute'])

bookingApp.config ['$routeProvider', '$locationProvider', ($routeProvider, $locationProvider) ->
   $locationProvider.html5Mode(true)
]
bookingApp.controller "bookingController", ['$scope','$http', '$location', ($scope, $http, $location) ->
  paramValue = $location.search().myParam
  $scope.loading = false
  $scope.success = false
  a = if true then 5 else 10

  $scope.deliveryChange = ->
    $scope.emailDate = if $scope.user.delivery == '1' then $scope.delivery_date else $scope.date

  $scope.dateSelect = (date, mode, event)->
    console.log date
    console.log mode
    $scope.date = date
    $scope.mode = mode
    prevModeSelected = jQuery('.panel-success')
    newModeSelected = jQuery('#panel-'+mode) 
    clickedButtonId = event.currentTarget.id

    prevModeSelected.removeClass('panel-success').addClass('panel-default')
    prevModeSelected.find('button').removeClass('list-group-item-success')
    newModeSelected.addClass('panel-success').removeClass('panel-default')
    jQuery('#'+clickedButtonId).addClass('list-group-item-success')


    return false

    
  $scope.saveBooking = (e)->
    e.preventDefault()
    $scope.loading = true
    productsData = $scope.products
    userData = $scope.user
    
    #date value legacy: i have to keep the possibility to get emailDate value, 
    #pofor a smooth update to 2022 version
    date = if $scope.emailDate then $scope.emailDate else $scope.date
    
    bookingDate = $scope.date
    e.target.disabled =  true

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
bookingApp
