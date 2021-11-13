(function(){
	var app = angular.module('login.ctrl',[]);
	app.controller("loginCtrl",["config","$scope","$http","$base64","$state","$rootScope","swangular","$window",function(config,$scope,$http,$base64,$state,$rootScope,swangular,$window){
		if (location.protocol != 'https:') {
        location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
    }
		$scope.data = {};
    async function check(){
      $rootScope.loading = true;
      try {
          const datasauth = await $http({
            method: 'GET',
            url: config.baseApi()+'auth'
          });
          $rootScope.loading = false;
          $scope.$apply();
          if (datasauth.data.status==='ok') {
              $state.go('dash.verifikasi');
          }
      } catch(err) {
          $rootScope.loading = false;
          $scope.$apply();
      }
    }
    check();
		$scope.login = function(){
      $rootScope.loading = true;
			$http({
        method: 'POST',
        url: config.baseApiUser()+'user_login',
        data:{
        	username:$scope.data.username,
        	password:$scope.data.password
        }
      }).then(function(response) {
      	if (response.data.token != 'error') {
      		localStorage.setItem("token",response.data.token);
          $rootScope.loading = false;
      		location.href = config.baseAdmin()+'auth';
      	}else{
          $rootScope.loading = false;
      		swangular.alert("gagal login");
      	}
      	
      }, function (err) {
        $rootScope.loading = false;
        swangular.alert("error login");
      });
		}
	}]);
})();