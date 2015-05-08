
angular.module('aloha').controller('homeController', function ($scope, $log) {

  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today();
  $scope.dtstart;
  $scope.dtend;

  $scope.clear = function () {
    $scope.dt = null;
  };

  // Disable weekend selection
  $scope.disabled = function(date, mode) {
    return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
  };

  $scope.toggleMin = function() {
    $scope.minDate = $scope.minDate ? null : new Date();
  };
  $scope.toggleMin();

  $scope.open = function($event,obj) {
    $event.preventDefault();
    $event.stopPropagation();

    if(obj == 'dp1'){
    	$scope.openeddt1 = true;
    }else if(obj == 'dp2'){
    	$scope.openeddt2 = true;
    }

    
    console.log(obj);
  };


});