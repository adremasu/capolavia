(function() {
  var pattern, subscribeApp;

  subscribeApp = angular.module('subscribeApp', []);

  subscribeApp.controller("configCtrl", [
    '$scope',
    '$http',
    function($scope,
    $http) {
      var copyValues;
      $scope.current_step = 'length';
      $scope.subscription = {};
      $scope.subscription.length = false;
      $scope.message = 'Qualcosa è andato storto';
      $scope.goToStep = function(event,
    size) {
        $scope.current_step = size;
        return event.preventDefault();
      };
      $scope.show = function(step) {
        if ($scope.current_step === step) {
          return 'current_step';
        } else {
          return 'hidden_step';
        }
      };
      copyValues = function(o,
    n) {
        if (typeof $scope.user !== 'undefined') {
          if (!$scope.subscription.different_address) {
            return $scope.user.invoice = n;
          } else {
            return $scope.user.invoice = {};
          }
        }
      };
      $scope.subscribeSave = function(e) {
        var request;
        e.preventDefault();
        request = {
          action: "save_new_subscription",
          subscription: $scope.subscription,
          user: $scope.user
        };
        return $http({
          method: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: jQuery.param(request),
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        }).success(function(data) {
          if (data.success === true) {
            $scope.goToStep(e,
    'payment');
            $scope.price = data.price;
            return $scope.paypal_ID = data.paypal_ID;
          } else {
            if (data.message) {
              $scope.message = data.message;
            }
            return $scope.showMessage($scope.message);
          }
        });
      };
      $scope.showMessage = function(message) {
        return jQuery('#myModal').modal();
      };
      return $scope.$watch('subscription',
    function(oldValue,
    newValue) {
        return copyValues(oldValue,
    newValue);
      },
    true);
    }
  ]);

  subscribeApp.directive('pwCheck', [
    function() {
      return {
        require: 'ngModel',
        link: function(scope,
    elem,
    attrs,
    ctrl) {
          var firstPassword;
          firstPassword = '#' + attrs.pwCheck;
          elem.add(firstPassword).on('keyup',
    function() {
            scope.$apply(function() {
              // console.info(elem.val() === $(firstPassword).val());
              ctrl.$setValidity('pwmatch',
    elem.val() === jQuery(firstPassword).val());
            });
          });
        }
      };
    }
  ]);

  pattern = /^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/;

  subscribeApp.directive('fiscale', function() {
    return {
      require: 'ngModel',
      link: function(scope, elm, attrs, ctrl) {
        ctrl.$validators.integer = function(modelValue, viewValue) {
          if (ctrl.$isEmpty(modelValue)) {
            // consider empty models to be valid
            return false;
          }
          if (pattern.test(viewValue)) {
            // it is valid
            return true;
          }
          // it is invalid
          return false;
        };
      }
    };
  });

}).call(this);
