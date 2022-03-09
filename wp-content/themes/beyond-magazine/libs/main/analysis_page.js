(function() {
  var analysisApp;

  analysisApp = angular.module('analysisApp', []);

  analysisApp.controller("ChartsController", [
    '$scope', '$http', function($scope, $http) {
      $scope.drawChart = function() {
        var arr, chart, data, options;
        data = new google.visualization.DataTable;
        data.addColumn('string', 'Settimane');
        data.addColumn('number', 'Prenotazioni');
        arr = Object.keys($scope.dates).map(function(key) {
          return $scope.dates[key];
        });
        console.log(arr);
        data.addRows(arr);
        options = {
          'title': 'Prenotazioni per settimana',
          'width': 1000,
          'height': 300
        };
        chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      };
      google.charts.load('current', {
        'packages': ['corechart']
      });
      return google.charts.setOnLoadCallback($scope.drawChart);
    }
  ]);

  analysisApp.directive("getdata", function() {
    return {
      link: function(scope, element, attr, ctrl) {
        var data;
        data = element[0].innerHTML;
        console.log(data);
        data = JSON.parse(data);
        return scope[data.data_type] = data.data;
      }
    };
  });

}).call(this);
