angular.module('aloha').service('HomeService', function($http, $window) {
	
	this.urlObj = {
		registerUser: {
			url: './backend/DBController.php',
			//url: 'http://aloha2.tk/backend/DBController.php',
			parameters:{
				required: 
				[
					{name: 'action',filter: 'same'},
					{name: 'name',filter: 'same'},
					{name: 'surname',filter: 'same'},
					{name: 'email',filter: 'same'},
					{name: 'password',filter: 'base64'},
					{name: 'hostelero',filter: 'same'}
									
				], 
				nonrequired:[
					{name: 'companyName',filter: 'same'},
					{name: 'nif',filter: 'same'}
				]
			}
		},
		
		logIn: {
			url: './backend/DBController.php',
			//url: 'http://aloha2.tk/backend',
			//url: 'http://aloha2.tk/backend/DBController.php',
			parameters:{
				required: 
				[
					{name: 'action',filter: 'same'},
					{name: 'email',filter: 'same'},
					{name: 'password',filter: 'base64'},
									
				], 
				nonrequired:[]
			}
		},
		
		searchHome: {
			url: './backend/DBController.php',
			//url: 'http://aloha2.tk/backend',
			//url: 'http://aloha2.tk/backend/DBController.php',
			parameters:{
				required: 
				[
					{name: 'action',filter: 'same'},
					{name: 'dateini',filter: 'same'},
					{name: 'dateend',filter: 'same'}
									
				], 
				nonrequired:[
					{name: 'zone',filter: 'same'},
					{name: 'rooms',filter: 'same'},
				]
			}
		}
		
	}
	
	this._checkParameters = function(actionName, parameters){
		var correct = true;
		for(var i in this.urlObj[actionName].parameters.required){
			if(!parameters.hasOwnProperty(this.urlObj[actionName].parameters.required[i].name)){
				correct = false;
				break;
			}
		}
		return correct;
	}
	
	this._checkAction = function(actionName){
		return this.urlObj.hasOwnProperty(actionName);
	}
	
	this._transformParameters = function(parameters, transformSpec){
		var params = {};
		for(var i in transformSpec.required){
			var paramName = transformSpec.required[i].name;
			if(parameters.hasOwnProperty(paramName)){
				if(transformSpec.required[i].filter == 'base64'){
					params[paramName] = Base64.encode(parameters[paramName]);
				}else{
					params[paramName] = parameters[paramName];
				}
			}
		}
		for(var i in transformSpec.nonrequired){
			var paramName = transformSpec.nonrequired[i].name;
			if(parameters.hasOwnProperty(paramName)){
				if(transformSpec.nonrequired[i].filter == 'base64'){
					params[paramName] = Base64.encode(parameters[paramName]);
				}else{
					params[paramName] = parameters[paramName];
				}
			}
		}
		return params;
	}
	
	this.setParameters = function(action,obj){
		var params = {};
		switch(action){

			case 'registerUser':
				params = this._transformParameters(obj, this.urlObj[action].parameters);
			break;
			case 'logIn':
				params = this._transformParameters(obj, this.urlObj[action].parameters);
			break;
			case 'searchHome':
				params = this._transformParameters(obj, this.urlObj[action].parameters);
			break;
			case 'helloWorld':
				params = this._transformParameters(obj, this.urlObj[action].parameters);
			break;
			
		}
		return params;
	}
	this.post = function(action,obj){
		//Se valida la accion (Tiene que estar dentro de objUrl)
		if(this._checkAction(action)){
			//checkeamos lo parametros requeridos
			// existe la accion y los parametros introducdos son validos
			if(this._checkParameters(action,obj)){
				params = this.setParameters(action,obj);
				return $http.post(this.urlObj[action].url,
                    $.param(params), {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        },
                        transformResponse: function(data, headers) {	
								return data;
                        }
                    }
                )
			}
		}
	}
	return this;
	
});