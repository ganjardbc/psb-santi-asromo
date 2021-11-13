(function(){
	var app = angular.module('config.ctrl',[]);
	app.controller("configCtrl",["config","$scope","$rootScope","$state","$base64","$http","swangular","$anchorScroll",function(config,$scope,$rootScope,$state,$base64,$http,swangular,$anchorScroll){
		$scope.show_form = false;
		$scope.datas_config = [];
		$scope.btn_save_text = 'Save';
		$scope.config = {};
		$scope.add = function(){
			if ($scope.show_form) {
				$scope.show_form = false;
			}else{
				$scope.btn_save_text = "Save";
				$scope.config = {};
				$scope.show_form = true;
			}
			
		}
		
		async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'config'
	            });
				$scope.datas_config = datasver.data;
				$rootScope.loading = false;
				$scope.$apply();
		  	} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error load data...");
            		$scope.$apply();
		  		}
		  	}
		}	

		$scope.toRp = function(angka){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += '.';
                }
            }
            return rev2.split('').reverse().join('');
        }

		$scope.save_or_update = async function(){
			$rootScope.loading = true;
			try {
				const data = await $http({
	              method: 'POST',
	              url: config.baseApi()+'update_config',
	              data:$scope.config
	            });
	            const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'config'
	            });
				$scope.datas_config = datasver.data;
        		$rootScope.loading = false;
        		swangular.success("Success update config");
        		$scope.$apply();
		  	} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error load data config");
            		$scope.$apply();
		  		}
		  	}

		}
		$scope.act_click = function(verData){
			var data = angular.copy(verData);
			$scope.config = data;
			$scope.config.max_bangku = parseInt(data.max_bangku);
			$scope.config.biaya_pendaftaran = parseInt(data.biaya_pendaftaran);
			$scope.btn_save_text = "Update";
			$scope.show_form = true;
			$anchorScroll();
		}


		$scope.refresh = function(){
			$scope.show_form = false;
			$scope.datas_config = [];
			$scope.config = {};
			getAll();
		}

		getAll();
	}]);
})();