productsApp = angular.module('productsApp', [])
#We already have a limitTo filter built-in to angular,
#let's make a startFrom filter

productsApp.archiveCtrl = ($scope) ->
  $scope.currentPage = 0
  $scope.pageSize = 2
  $scope.numberOfPages = ->
    Math.ceil $scope.products.length / $scope.pageSize
  return

productsApp.filter 'startFrom', ->
  (input, start) ->
    start = +start
    #parse to int
    input.slice start

productsApp


homepageApp = angular.module('homepageApp', ['ui.bootstrap'])

homepageApp.controller  "CarouselDemoCtrl", ['$scope','$http', ($scope, $http) ->
  $scope.myInterval = 6000

  return
]


homepageApp.directive 'getslides', ->

  {
  link: (scope, element, attr, ctrl) ->
    scope.slides = {}

    data = (element[0].innerHTML)
    _slides = JSON.parse(data).data
    scope.slides = _slides


  }

homepageApp

subscriptionAdmin = angular.module('subscriptionAdmin', ['ngSanitize', 'ui.select'])
#We already have a limitTo filter built-in to angular,
#let's make a startFrom filter

subscriptionAdmin.controller  "subCtrl", ['$scope','$http', ($scope, $http) ->

  $scope.deliveryValue = 0
  $scope.deliveryItems = {}

  $scope.getSelected = ($item) ->
    $scope.selectedItem = $item


  $scope.addItem = (id) ->
    $scope.deliveryItems[id.toString()] = $scope.selectedItem;
    $scope.getDeliveryValue()

  $scope.removeItem = (id) ->
    delete $scope.deliveryItems[id.toString()]
    $scope.getDeliveryValue()

  $scope.getDeliveryValue = ->
    $scope.deliveryValue = 0
    for id, item  of $scope.deliveryItems
      $scope.deliveryValue = $scope.deliveryValue + item.total_price

  $scope.saveDelivery = ->

    console.log 'save'

  $scope.getValue = ->
    $scope.selectedItem.total_price = parseFloat(($scope.selectedItem.single_price*$scope.selectedItem.weight).toFixed(2))
  return
]


subscriptionAdmin.directive "subscriptiondata", ->
  link: (scope, element, attr, ctrl) ->
    data = (element[0].innerHTML)
    data = JSON.parse(data)
    scope[data.data_type] = data.data




subscriptionAdmin

