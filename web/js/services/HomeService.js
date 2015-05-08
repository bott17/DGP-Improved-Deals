angular.module('Aloha').service('HomeService', function($http, $window) {
	
	this.urlObj = {
		helloWorld: {
			url: 'http://localhost/DGP-Improved-Deals/backend/DBController.php',
			//url: 'http://aloha2.tk/backend/DBController.php',
			parameters:{
				required: 
				[
					{
						name: 'hello',
						filter: 'same'
					}
				], 
				nonrequired:[]
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

			case 'helloWorld':
				params = this._transformParameters(obj, this.urlObj.helloWorld.parameters);
				params.action = 'helloworld';
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