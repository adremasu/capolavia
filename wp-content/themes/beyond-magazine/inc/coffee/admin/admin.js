(function() {
  var bookingmanagerApp, bookingsApp, findOrCreateModule, initialModules, xmasbookingsApp;

  bookingsApp = angular.module('bookingsApp', []);

  bookingsApp.controller("ProductsController", [
    '$scope',
    '$http',
    function($scope,
    $http) {
      $scope.showNPWeight = false;
      $scope.showNPItems = false;
      $scope.newProductId = false;
      jQuery('#pickertrigger').click(function() {
        return jQuery('#date').show().focus().hide();
      });
      jQuery('#date').datepicker('setDate',
    new Date($scope.date));
      jQuery('#date').datepicker({
        firstDay: 1,
        dateFormat: '@',
        beforeShow: function() {
          return jQuery(this).datepicker('setDate',
    new Date($scope.date));
        },
        beforeShowDay: function(date) {
          var availability,
    availableDates,
    check,
    checkDate,
    eventDate,
    i,
    len,
    ref,
    savedDate;
          savedDate = new Date($scope.date);
          checkDate = new Date(date);
          availableDates = $scope.monthEvents;
          availability = 'not_available';
          if (typeof $scope.monthEvents !== 'undefined') {
            $scope.monthEvents.push(savedDate);
            check = function(eventDate) {
              var DateString,
    _Day,
    _Month,
    _Year,
    _availability,
    _eventDay,
    _eventMonth,
    _eventYear,
    eventDateString;
              _eventDay = eventDate.getDate().toString();
              _eventMonth = eventDate.getMonth().toString();
              _eventYear = eventDate.getFullYear().toString();
              eventDateString = _eventYear + _eventMonth + _eventDay;
              _Day = checkDate.getDate().toString();
              _Month = checkDate.getMonth().toString();
              _Year = checkDate.getFullYear().toString();
              DateString = _Year + _Month + _Day;
              _availability = eventDateString === DateString;
              if (_availability === true) {
                return availability = 'available';
              }
            };
            ref = $scope.monthEvents;
            for (i = 0, len = ref.length; i < len; i++) {
              eventDate = ref[i];
              check(eventDate);
            }
          }
          return [true,
    availability];
        },
        onChangeMonthYear: function(Y,
    m,
    d) {
          var request;
          jQuery('#ui-datepicker-div > *').css({
            opacity: 0.2
          });
          request = {
            action: "get_month_events",
            month: m,
            year: Y
          };
          return $http({
            method: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: jQuery.param(request),
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            }
          }).success(function(data) {
            var addEvent,
    event,
    i,
    len;
            $scope.monthEvents = [];
            addEvent = function(date) {
              var dateObj,
    stringDate;
              stringDate = date.dateTime;
              dateObj = new Date(stringDate);
              return $scope.monthEvents.push(dateObj);
            };
            for (i = 0, len = data.length; i < len; i++) {
              event = data[i];
              addEvent(event);
            }
            $scope.datepickerRefresh();
            return jQuery('#ui-datepicker-div > *').css({
              opacity: 1
            });
          });
        }
      });
      $scope.datepickerRefresh = function() {
        return jQuery('#date').datepicker('refresh');
      };
      $scope.datepickerDisable = function(bool) {
        return jQuery("#date").datepicker("option",
    "disabled",
    bool);
      };
      $scope.getAvailableDates = function(month) {
        var request;
        request = {
          action: "get_month_events",
          date: month
        };
        return $http({
          method: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: jQuery.param(request),
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        }).success(function(dates) {
          return dates;
        });
      };
      jQuery('#newProductSelect').change(function(e) {
        var id,
    mu,
    optionSelected,
    valueSelected;
        optionSelected = jQuery("option:selected",
    this);
        valueSelected = this.value;
        mu = jQuery("option:selected",
    this).data();
        id = mu.id.toString();
        $scope.newProductId = id;
        $scope.newProduct = {
          name: valueSelected,
          items: {
            mu: mu.items_name
          },
          weight: {
            mu: mu.weight_name
          }
        };
        if ($scope.newProduct.weight.mu === '') {
          $scope.showNPWeight = false;
        } else {
          $scope.showNPWeight = true;
        }
        if ($scope.newProduct.items.mu === '') {
          return $scope.showNPItems = false;
        } else {
          return $scope.showNPItems = true;
        }
      });
      $scope.addProduct = function() {
        var id;
        id = $scope.newProductId.toString();
        if ($scope.products === null) {
          $scope.products = {};
        }
        $scope.products[id] = $scope.newProduct;
        return tb_remove();
      };
      return $scope.deleteProduct = function($event,
    id) {
        $event.preventDefault();
        $scope.products[id] = void 0;
        return delete $scope.products[id];
      };
    }
  ]);

  bookingsApp.directive("getdate", function() {
    return {
      link: function(scope, element, attr, ctrl) {
        var data;
        data = element[0].innerHTML;
        data = JSON.parse(data);
        return scope[data.data_type] = data.data;
      }
    };
  });

  bookingmanagerApp = angular.module('bookingmanagerApp', []);

  bookingmanagerApp.controller("perdateController", [
    '$scope',
    '$http',
    function($scope,
    $http) {
      var datepickerOptions;
      datepickerOptions = {
        "dateFormat": "mm,dd,yy"
      };
      jQuery('#startDatepicker').datepicker(datepickerOptions);
      jQuery('#endDatepicker').datepicker(datepickerOptions);
      return $scope.getBookings = function() {
        var request;
        request = {
          action: "getBookingsByDate",
          start: $scope.start,
          end: $scope.end
        };
        return $http({
          method: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: jQuery.param(request),
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        }).success(function(data) {
          return jQuery('#postContainer').html(data);
        });
      };
    }
  ]);

  xmasbookingsApp = angular.module('xmasbookingsApp', []);

  xmasbookingsApp.controller("ProductsController", [
    '$scope',
    '$http',
    function($scope,
    $http) {
      $scope.showNPWeight = false;
      $scope.showNPItems = false;
      $scope.newProductId = false;
      jQuery('#pickertrigger').click(function() {
        return jQuery('#date').show().focus().hide();
      });
      jQuery('#date').datepicker('setDate',
    new Date($scope.date));
      jQuery('#date').datepicker({
        firstDay: 1,
        dateFormat: '@',
        beforeShow: function() {
          return jQuery(this).datepicker('setDate',
    new Date($scope.date));
        },
        beforeShowDay: function(date) {
          var availability,
    availableDates,
    check,
    checkDate,
    eventDate,
    i,
    len,
    ref,
    savedDate;
          savedDate = new Date($scope.date);
          checkDate = new Date(date);
          availableDates = $scope.monthEvents;
          availability = 'not_available';
          if (typeof $scope.monthEvents !== 'undefined') {
            $scope.monthEvents.push(savedDate);
            check = function(eventDate) {
              var DateString,
    _Day,
    _Month,
    _Year,
    _availability,
    _eventDay,
    _eventMonth,
    _eventYear,
    eventDateString;
              _eventDay = eventDate.getDate().toString();
              _eventMonth = eventDate.getMonth().toString();
              _eventYear = eventDate.getFullYear().toString();
              eventDateString = _eventYear + _eventMonth + _eventDay;
              _Day = checkDate.getDate().toString();
              _Month = checkDate.getMonth().toString();
              _Year = checkDate.getFullYear().toString();
              DateString = _Year + _Month + _Day;
              _availability = eventDateString === DateString;
              if (_availability === true) {
                return availability = 'available';
              }
            };
            ref = $scope.monthEvents;
            for (i = 0, len = ref.length; i < len; i++) {
              eventDate = ref[i];
              check(eventDate);
            }
          }
          return [true,
    availability];
        },
        onChangeMonthYear: function(Y,
    m,
    d) {
          var request;
          jQuery('#ui-datepicker-div > *').css({
            opacity: 0.2
          });
          request = {
            action: "get_month_events",
            month: m,
            year: Y
          };
          return $http({
            method: "POST",
            url: "/wp-admin/admin-ajax.php",
            data: jQuery.param(request),
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            }
          }).success(function(data) {
            var addEvent,
    event,
    i,
    len;
            $scope.monthEvents = [];
            addEvent = function(date) {
              var dateObj,
    stringDate;
              stringDate = date.dateTime;
              dateObj = new Date(stringDate);
              return $scope.monthEvents.push(dateObj);
            };
            for (i = 0, len = data.length; i < len; i++) {
              event = data[i];
              addEvent(event);
            }
            $scope.datepickerRefresh();
            return jQuery('#ui-datepicker-div > *').css({
              opacity: 1
            });
          });
        }
      });
      $scope.datepickerRefresh = function() {
        return jQuery('#date').datepicker('refresh');
      };
      $scope.datepickerDisable = function(bool) {
        return jQuery("#date").datepicker("option",
    "disabled",
    bool);
      };
      $scope.getAvailableDates = function(month) {
        var request;
        request = {
          action: "get_month_events",
          date: month
        };
        return $http({
          method: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: jQuery.param(request),
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          }
        }).success(function(dates) {
          return dates;
        });
      };
      jQuery('#newProductSelect').change(function(e) {
        var id,
    mu,
    optionSelected,
    valueSelected;
        optionSelected = jQuery("option:selected",
    this);
        valueSelected = this.value;
        mu = jQuery("option:selected",
    this).data();
        id = mu.id.toString();
        $scope.newProductId = id;
        $scope.newProduct = {
          name: valueSelected,
          items: {
            mu: mu.items_name
          }
        };
        if ($scope.newProduct.items.mu === '') {
          return $scope.showNPItems = false;
        } else {
          return $scope.showNPItems = true;
        }
      });
      $scope.addProduct = function() {
        var id;
        id = $scope.newProductId.toString();
        if ($scope.products === null) {
          $scope.products = {};
        }
        $scope.products[id] = $scope.newProduct;
        return tb_remove();
      };
      return $scope.deleteProduct = function($event,
    id) {
        $event.preventDefault();
        $scope.products[id] = void 0;
        return delete $scope.products[id];
      };
    }
  ]);

  xmasbookingsApp.directive("getdate", function() {
    return {
      link: function(scope, element, attr, ctrl) {
        var data;
        data = element[0].innerHTML;
        data = JSON.parse(data);
        return scope[data.data_type] = data.data;
      }
    };
  });

  //load modules based on apps
  initialModules = [
    {
      name: 'xmasbookingsApp'
    },
    {
      name: 'bookingsApp'
    },
    {
      name: 'bookingmanagerApp'
    }
  ];

  findOrCreateModule = function(moduleName, deps) {
    var error;
    deps = deps || [];
    try {
      angular.module(moduleName);
    } catch (error1) {
      error = error1;
      angular.module(moduleName, deps);
    }
  };

  initialModules.forEach(function(moduleDefinition) {
    findOrCreateModule(moduleDefinition.name, moduleDefinition.deps);
  });

}).call(this);


//# sourceMappingURL=admin.js.map
//# sourceURL=coffeescript