angular.module('aloha').controller('exploreController', function ($scope, $log, $window, ExploreService,$rootScope){
  
  $scope.service = ExploreService;
  $scope.hostelero = false;
  $scope.identifiedUser = true;
  
  $scope.objUser = {
    name:'',
    surname:'Aloha user',
    password: '',
    email: '',
    hostelero: true,
    companyName: '',
    nif: ''  
  }

  $scope.objSearch = {
    zone: '',
    rooms: 'Habitaciones',
    dateini: 0,
    dateend: 0,
    rooms: 0,
    type: '',
    pension: false,
    garage: false,
    security: false,
    airconditioner: false,
    balcony: false,
    swimmingpool: false,
    internet: false,
    heating: false,
    tv: false
  }

  $scope.objRent = {
    dateini: 0,
    dateend: 0,
    idproperty: 0,
    email: ''
  }
  
  $scope.objSimilarProperties ={
    zone:'',
    idproperty: 0
  }
  
  $scope.objListUserProperties ={
    email:''
  }

  $scope.rooms =['Habitaciones',1,2,3,4,5];
  $scope.zones = ["Zona","Centro","Albaicin","Chana","Estacion de autobuses","Realejo","Ronda","Norte","Genil"];
  $scope.objSearch.zone = $scope.zones[0];
  $scope.objSearch.rooms = $scope.rooms[0];
  $scope.confirmPass = '';

  /**
   * Función que controla si un usuario es hostelero o no a la hora de registrarse
   */
  $scope.hosteleroToggle = function() {
     $scope.hostelero = !$scope.hostelero;
  } 
  $scope.registradoToggle = function(){
    $scope.identifiedUser = !$scope.identifiedUser;
  }
  /**
   * Funcion que registra al usuario
   */
   $scope.register = function(){
  
     if( !$scope.objUser.hostelero && $scope.objUser.name != '' && $scope.objUser.surname != '' 
        && $scope.objUser.password != '' && $scope.objUser.email != ''
        && $scope.objUser.email != '' && $scope.confirmPass != '' && ($scope.objUser.password == $scope.confirmPass)){
       
       $scope.serviceAction('registerUser',$scope.objUser);
     
     }else if($scope.objUser.hostelero && $scope.objUser.companyName != '' && $scope.objUser.nif != ''){
         
        $scope.serviceAction('registerUser',$scope.objUser);  

    }
  }
  /**
  * Funcion que inicia la sesión del usuario
  */ 
  $scope.signup = function(){
      
      if($scope.objUser !='' && $scope.objUser.password){
       
         //$scope.serviceAction('logIn',$scope.objUser);
         $scope.identifiedUser = true;
      } 
    

  }

  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today();
  $scope.dtstart = null;
  $scope.dtend = null;

  $scope.clear = function () {
    $scope.dt = null;
  };

    /**
   * Función y variables que controlan las fechas de busqueda de un alojamiento
   */

  $scope.clear = function () {
    $scope.dt = null;
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


  };
 /**
   * Funcion que realiza la acción de buscar
   */
  $scope.search = function(){
    
    if($scope.dtstart != null && $scope.dtend != null){
          
          $scope.objSearch.dateini = $scope.dtstart;
          $scope.objSearch.dateend = $scope.dtend;
          
         
          alertify.success('Busqueda lanzada');
          $scope.serviceAction('search',$scope.objSearch);
          
          
    }
  }
  $scope.broadcast;
  $scope.$on('hometoexplore', function(event, args) {

     $scope.broadcast = args;
      
  });
 
  $scope.serviceAction = function(action,obj){
    
      switch (action) {
        case 'registerUser':  
          $scope.service.post('registerUser', {
              action:'registeruser',
              name: obj.name,
              surname: obj.surname,
              email: obj.email,
              password: obj.password,
              hostelero: obj.hostelero 
            }).then(function(response){
              var response = angular.fromJson(response.data);
              $scope.registerUserR = response.data;
           
          });
         break;
      
        case 'logIn':
            $scope.service.post('logIn', {
              
                action:'login',
                email: obj.email,
                password: obj.password
              }).then(function(response){
                
                var result = angular.fromJson(response.data);
                
                //$scope.logIn = result.data;
               
                if(result.status == 0){
                    $scope.objUser.name = result.data.name;
                    $scope.objUser.surname = result.data.surname;
                     $scope.objUser.email = result.data.email;
                     $scope.identifiedUser = true;
                     
                }
          
            });
           break;
           
         case 'search':
            $scope.service.post('search', {
                action:'search',
                dateini: obj.dateini,
                dateend: obj.dateend
              }).then(function(response){
                var result = angular.fromJson(response.data);
                $scope.searchR = result.data;
                
            });
           break;
          
         case 'lastFourComments':
          $scope.service.post('lastFourComments', {
            action:'lastfourcomments'
          }).then(function(response){
            var result = angular.fromJson(response.data);
            $scope.lastFourCommentsR = result.data;
            
          });
          break;

          case 'lastSixProperties':
          $scope.service.post('lastSixProperties',{
            
            action:'lastsixproperties'

          }).then(function(response){
            var result = angular.fromJson(response.data);
            $scope.lastSixPropertiesR = result.data;
           
          });
          break;
          case 'rent':
          $scope.service.post('rent',{
            
            action:'rent',
            dateini: obj.dateini,
            dateend: obj.dateend,
            idproperty: obj.idproperty,
            email: obj.email

          }).then(function(response){
            var result = angular.fromJson(response.data);
            $scope.rentR = result.data;
       
          });
          break;
          case 'listUserProperties':
          $scope.service.post('listUserProperties',{
            
            action:'listuserproperties',
            email:obj.email
          
          }).then(function(response){
             var result = angular.fromJson(response.data);
             $scope.userListR = result.data;
            
          });
        break;
        case 'similarProperties':
          $scope.service.post('similarProperties',{
            
            action:'similarproperties',

          }).then(function(response){
            var result = angular.fromJson(response.data);
            $scope.similarPropertiesR = result.data;
      
          });
          break;
        
        default:
          break;
      }
    }
});