bookingsApp = angular.module('bookingsApp', [])
bookingsApp.controller "ProductsController", ['$scope','$http', ($scope, $http) ->
  $scope.showNPWeight = false
  $scope.showNPItems = false
  $scope.newProductId = false

  jQuery('#newProductSelect').change (e)->
    optionSelected = jQuery("option:selected", this)
    valueSelected = this.value
    mu = jQuery("option:selected", this).data()

    $scope.newProduct = {
      name: valueSelected
      items: {
        mu: mu.items_name
      }
      weight: {
        mu: mu.weight_name
      }
    }
    $scope.newProductId = mu.id

    if ($scope.newProduct.weight.mu == '')
      $scope.showNPWeight = false
    else
      $scope.showNPWeight = true

    if ($scope.newProduct.items.mu == '')
      $scope.showNPItems = false
    else
      $scope.showNPItems = true

  $scope.addProduct = ->
    if ($scope.products)
      $scope.products[$scope.newProductId] = $scope.newProduct
      tb_remove()

  $scope.deleteProduct = ($event, id) ->
    $event.preventDefault()
    $scope.products[id] = undefined
    delete $scope.products[id]

]




bookingsApp