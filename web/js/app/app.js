angular.module('aloha', ['ui.bootstrap','ngAria','ngRoute'])

	

	.run(['$route', '$rootScope', '$location', function ($route, $rootScope, $location) {
	    var original = $location.path;

	    $location.path = function (path, reload) {
	        if (reload === false) {
	            var lastRoute = $route.current;
	            var un = $rootScope.$on('$locationChangeSuccess', function () {
	                $route.current = lastRoute;
	                un();
	            });
	        }
	        return original.apply($location, [path]);
	    };
}]);

angular.module('aloha', ['ui.bootstrap','ngAria','ngRoute'])

    .config(function($routeProvider, $locationProvider) {
		
	$routeProvider
            .when('index.html', {
                templateUrl : 'explore.html',
           
            });
    
        // use the HTML5 History API
        $locationProvider.html5Mode(true);
}); 
