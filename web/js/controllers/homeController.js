
angular.module('aloha').controller('homeController', function ($scope, $log) {

  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today();
  $scope.dtstart = new Date();
  $scope.dtend = new Date();

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

  $scope.open = function($event) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope.opened = true;
  };

});