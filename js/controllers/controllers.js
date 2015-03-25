angular.module('ui.bootstrap.demo', ['ui.bootstrap']);
angular.module('ui.bootstrap.demo').controller('DemoCtrl', function ($scope, $log) {

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

    console.log($scope.list);

    $scope.mostrar = true;

    $scope.toggleMode = function() {
		$scope.mostrar = ! $scope.mostrar;
	}

});