(function(){
	var app = angular.module('auth.ctrl',[]);
	app.controller("authCtrl",["config","$scope","$state","$base64","$http","$rootScope",function(config,$scope,$state,$base64,$http,$rootScope){
	    async function check(){
			$rootScope.loading = true;
			try {
			    const datasauth = await $http({
	              method: 'GET',
	              url: config.baseApi()+'auth'
	            });
				if (datasauth.data.status==='ok') {
					$state.go('dash.verifikasi');
				}else{
					$rootScope.loading = false;
					$state.go('login');
				}
		  	} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		  			$scope.$apply();
		    		$state.go('login');
		  		}
		  	}
		}
		if (location.protocol != 'https:') {
            location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
        }
		check();
	}]);
})();