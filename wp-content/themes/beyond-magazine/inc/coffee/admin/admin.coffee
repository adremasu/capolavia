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
      jQuery(@).datepicker('setDate', new Date($scope.date))

    beforeShowDay: (date)->
      savedDate = new Date($scope.date)
      checkDate = new Date(date)

      availableDates = $scope.monthEvents
      availability = 'not_available'
      if typeof $scope.monthEvents isnt 'undefined'
        $scope.monthEvents.push savedDate
        check = (eventDate) ->
          _eventDay = eventDate.getDate().toString()
          _eventMonth = eventDate.getMonth().toString()
          _eventYear = eventDate.getFullYear().toString()
          eventDateString  = _eventYear + _eventMonth + _eventDay
          _Day = checkDate.getDate().toString()
          _Month = checkDate.getMonth().toString()
          _Year = checkDate.getFullYear().toString()

          DateString  = _Year + _Month + _Day
          _availability = (eventDateString == DateString)
          if _availability is true
            availability = 'available'

        check eventDate for eventDate in $scope.monthEvents

      [true, availability]


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


bookingmanagerApp = angular.module('bookingmanagerApp', [])

bookingmanagerApp.controller "perdateController", ['$scope','$http', ($scope, $http) ->
  datepickerOptions = {
    "dateFormat": "mm,dd,yy"
  }
  jQuery('#startDatepicker').datepicker(datepickerOptions)
  jQuery('#endDatepicker').datepicker(datepickerOptions)

  $scope.getBookings = ->
    request = {
      action: "getBookingsByDate"
      start: $scope.start
      end: $scope.end
    }
    $http(
      method: "POST"
      url: "/wp-admin/admin-ajax.php"
      data: jQuery.param(request)
      headers:
        "Content-Type": "application/x-www-form-urlencoded"
    ).success (data)->
      jQuery('#postContainer').html(data)
]


xmasbookingsApp = angular.module('xmasbookingsApp', [])
xmasbookingsApp.controller "ProductsController", ['$scope','$http', ($scope, $http) ->
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
      jQuery(@).datepicker('setDate', new Date($scope.date))

    beforeShowDay: (date)->
      savedDate = new Date($scope.date)
      checkDate = new Date(date)

      availableDates = $scope.monthEvents
      availability = 'not_available'
      if typeof $scope.monthEvents isnt 'undefined'
        $scope.monthEvents.push savedDate
        check = (eventDate) ->
          _eventDay = eventDate.getDate().toString()
          _eventMonth = eventDate.getMonth().toString()
          _eventYear = eventDate.getFullYear().toString()
          eventDateString  = _eventYear + _eventMonth + _eventDay
          _Day = checkDate.getDate().toString()
          _Month = checkDate.getMonth().toString()
          _Year = checkDate.getFullYear().toString()

          DateString  = _Year + _Month + _Day
          _availability = (eventDateString == DateString)
          if _availability is true
            availability = 'available'

        check eventDate for eventDate in $scope.monthEvents

      [true, availability]


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
    }

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


xmasbookingsApp.directive "getdate", ->
  link: (scope, element, attr, ctrl) ->
    data = (element[0].innerHTML)
    data = JSON.parse(data)
    scope[data.data_type] = data.data



#load modules based on apps
initialModules = [
  { name: 'xmasbookingsApp'}  
  { name: 'bookingsApp'}
  { name: 'bookingmanagerApp' }
]
findOrCreateModule = (moduleName, deps) ->
  deps = deps or []
  try
    angular.module moduleName
  catch error
    angular.module moduleName, deps
  return

initialModules.forEach (moduleDefinition) ->
  findOrCreateModule moduleDefinition.name, moduleDefinition.deps
  return