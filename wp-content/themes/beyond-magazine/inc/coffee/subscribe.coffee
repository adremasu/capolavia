subscribeApp = angular.module('subscribeApp', [ ])
subscribeApp.controller "configCtrl", [
  '$scope'
  '$http'
  ($scope, $http) ->
    $scope.current_step = 'length'
    $scope.subscription = {}
    $scope.subscription.length = false
    $scope.goToStep = (event, size) ->
      $scope.current_step = size
      event.preventDefault()
    $scope.show = (step) ->
      if ($scope.current_step == step)
        return 'current_step'
      else
        return 'hidden_step'
    $scope.subscribeSave = (e)->
      e.preventDefault()
      request = {
        action: "save_new_subscription"
        subscription: $scope.subscription
      }
      $http(
        method: "POST"
        url: "/wp-admin/admin-ajax.php"
        data: jQuery.param(request)
        headers:
          "Content-Type": "application/x-www-form-urlencoded"
      ).success (data) ->
        if (data.success == true)
          $scope.goToStep e, 'payment'
          $scope.price = data.price
          $scope.paypal_ID = data.paypal_ID

]

subscribeApp.directive 'pwCheck', [ ->
  {
  require: 'ngModel'
  link: (scope, elem, attrs, ctrl) ->
    firstPassword = '#' + attrs.pwCheck
    elem.add(firstPassword).on 'keyup', ->
      scope.$apply ->
        # console.info(elem.val() === $(firstPassword).val());
        ctrl.$setValidity 'pwmatch', elem.val() == jQuery(firstPassword).val()
        return
      return
    return

  }
]
pattern = /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/

subscribeApp.directive 'fiscale', ->
  {
  require: 'ngModel'
  link: (scope, elm, attrs, ctrl) ->

    ctrl.$validators.integer = (modelValue, viewValue) ->
      if ctrl.$isEmpty(modelValue)
        # consider empty models to be valid
        return false


      if pattern.test(viewValue)
        # it is valid
        return true
      # it is invalid
      false

    return

  }
