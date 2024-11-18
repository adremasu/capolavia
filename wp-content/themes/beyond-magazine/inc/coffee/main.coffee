initialModules = [
  {
    name: 'bookingApp'
    deps: [ 'ngRoute' ]
  }  
  {
    name: 'xmasbookingAppApp'
    deps: [ 'ngRoute' ]
  }
  { name: 'productsApp' }
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