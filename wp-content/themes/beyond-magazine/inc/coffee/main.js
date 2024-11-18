(function() {
  var findOrCreateModule, initialModules;

  initialModules = [
    {
      name: 'bookingApp',
      deps: ['ngRoute']
    },
    {
      name: 'xmasbookingAppApp',
      deps: ['ngRoute']
    },
    {
      name: 'productsApp'
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


//# sourceMappingURL=main.js.map
//# sourceURL=coffeescript