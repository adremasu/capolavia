bookingsApp = angular.module('bookingsApp', [])
bookingsApp.controller "ProductsController", ['$scope','$http', ($scope, $http) ->
  $scope.showNPWeight = false
  $scope.showNPItems = false
  $scope.newProductId = false
  jQuery('#pickertrigger').click ()->
    jQuery('#date').show().focus().hide()

  jQuery('#date').datepicker(
    'setDate', new Date($scope.date)
  )

  jQuery('#date').datepicker(
    firstDay: 1

    dateFormat: '@'

    beforeShow: ->
      console.log $scope.date
      jQuery(@).datepicker('setDate', new Date($scope.date))




    onChangeMonthYear: (Y,m,d)->
      jQuery('#ui-datepicker-div > *').css(
        opacity: 0.2
      )
      request = {
        action:   "get_month_events"
        month: m
        year: Y
      }
      $http(
        method: "POST"
        url: "/wp-admin/admin-ajax.php"
        data: jQuery.param(request)
        headers:
          "Content-Type": "application/x-www-form-urlencoded"
      ).success (data)->
        $scope.monthEvents = []

        addEvent = (date)->
          stringDate = date.dateTime
          dateObj = new Date(stringDate)
          $scope.monthEvents.push dateObj

        addEvent event for event in data
        $scope.datepickerRefresh()
        jQuery('#ui-datepicker-div > *').css(
          opacity: 1
        )

  )


  $scope.datepickerRefresh = ()->
    jQuery('#date').datepicker('refresh')

  $scope.datepickerDisable = (bool)->
    jQuery( "#date" ).datepicker( "option", "disabled", bool );

  $scope.getAvailableDates = (month) ->
    request = {
      action:   "get_month_events"
      date:     month
    }
    $http(
      method: "POST"
      url: "/wp-admin/admin-ajax.php"
      data: jQuery.param(request)
      headers:
        "Content-Type": "application/x-www-form-urlencoded"
    )
    .success (dates) ->
      dates

  jQuery('#newProductSelect').change (e)->
    optionSelected = jQuery("option:selected", this)
    valueSelected = this.value
    mu = jQuery("option:selected", this).data()

    id = mu.id.toString()
    $scope.newProductId = id

    $scope.newProduct = {
      name: valueSelected
      items: {
        mu: mu.items_name
      }
      weight: {
        mu: mu.weight_name
      }
    }


    if ($scope.newProduct.weight.mu == '')
      $scope.showNPWeight = false
    else
      $scope.showNPWeight = true

    if ($scope.newProduct.items.mu == '')
      $scope.showNPItems = false
    else
      $scope.showNPItems = true

  $scope.addProduct = ->
    id = $scope.newProductId.toString()

    if ($scope.products is null)
      $scope.products = {}
    $scope.products[id] = $scope.newProduct

    tb_remove()

  $scope.deleteProduct = ($event, id) ->
    $event.preventDefault()
    $scope.products[id] = undefined
    delete $scope.products[id]

]


bookingsApp.directive "getdate", ->
  link: (scope, element, attr, ctrl) ->
    data = (element[0].innerHTML)
    data = JSON.parse(data)
    scope[data.data_type] = data.data

bookingsApp