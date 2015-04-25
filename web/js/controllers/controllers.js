angular.module('ui.bootstrap.demo', ['ui.bootstrap']);
angular.module('ui.bootstrap.demo').controller('DemoCtrl', function ($scope, $log, BDService) {

    $scope.service = BDService;


    $scope.service.post('helloWorld', {
      hello:'hello'
      
    }).then(function(response){

      $response = angular.fromJson(response.data);
      $response = $response.data;
      $scope.nombres = $response.nombre;
      console.log($response.nombre);
  });


    $scope.user = "Hola mundo";

    $scope.list = 
    {
      item1: {
      	title: 'Heading 1',
      	body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.'
      },

      item2: {
      	title: 'Heading 2',
      	body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.'
      },

      item3: {
      	title: 'Heading 3',
      	body: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe rem nisi accusamus error velit animi non ipsa placeat. Recusandae, suscipit, soluta quibusdam accusamus a veniam quaerat eveniet eligendi dolor consectetur.'
      },

    };

    $scope.mostrar = true;

    $scope.toggleMode = function() {
		$scope.mostrar = ! $scope.mostrar;
	}

});