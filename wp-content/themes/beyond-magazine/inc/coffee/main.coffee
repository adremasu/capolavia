productsApp = angular.module('productsApp', [])
#We already have a limitTo filter built-in to angular,
#let's make a startFrom filter

productsApp.archiveCtrl = ($scope) ->
  $scope.currentPage = 0
  $scope.pageSize = 2
  console.log 'ciao'
  $scope.numberOfPages = ->
    Math.ceil $scope.products.length / $scope.pageSize
  return

productsApp.filter 'startFrom', ->
  (input, start) ->
    start = +start
    #parse to int
    input.slice start

productsApp