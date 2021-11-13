(function(){
	var app = angular.module('common.ctrl',[]);
	app.factory("config",["$state","$http","$base64","$rootScope",function($state,$http,$base64,$rootScope){
            // var base_url = "http://localhost/psbsanti/";
		var base_url = "https://psbonlinesantiasromo.or.id/";
		var base_admin = base_url+'admin-psb/';
		var base_image = base_url+'img/';
		var base_api = base_url+"api_v1/";
		var base_api_user = base_url;
		return {
			getToken:function(){
				var token = localStorage.getItem("token") || '';
				return token;
			},
            baseApi:function(){
            	return base_api;
            },
            baseUrl:function(){
            	return base_url;
            },
            baseApiUser:function(){
            	return base_api_user;
            },
            baseImage:function(){
            	return base_image;
            },
            baseAdmin:function(){
            	return base_admin;
            }
        };
		
	}]);
})();