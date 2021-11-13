(function(){
	var app = angular.module('biaya.ctrl',[]);
	app.controller("biayaCtrl",["config","$scope","$rootScope","$state","$base64","$http","swangular","$anchorScroll",function(config,$scope,$rootScope,$state,$base64,$http,swangular,$anchorScroll){
		$scope.show_form = false;
		$scope.datas_biaya = [];
		$scope.btn_save_text = 'Save';
		$scope.search = '';
		$scope.biaya = {};
		var page = 1;
		$scope.add = function(){
			if ($scope.show_form) {
				$scope.show_form = false;
			}else{
				$scope.btn_save_text = "Save";
				$scope.biaya = {};
				$scope.show_form = true;
			}
			
		}
		
		async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'biaya_back'
	            });
				$scope.datas_biaya = datasver.data;
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

		$scope.save_or_update = async function(){
			$rootScope.loading = true;
			try {
				if ($scope.btn_save_text === 'Save') {
					const data = await $http({
		              method: 'POST',
		              url: config.baseApi()+'save_biaya_back',
		              data:$scope.biaya
		            });
		            const datasver = await $http({
		              method: 'GET',
		              url: config.baseApi()+'biaya_back'
		            });
					$scope.datas_biaya = datasver.data;
	            	swangular.success("Success save biaya");
	            	$scope.biaya = {};
	            	$scope.search = '';
	            	$rootScope.loading = false;
            		$scope.$apply();
				}else{
					const data = await $http({
		              method: 'POST',
		              url: config.baseApi()+'update_biaya_back',
		              data:$scope.biaya
		            });
		            const datasver = await $http({
		              method: 'GET',
		              url: config.baseApi()+'biaya_back'
		            });
					$scope.datas_biaya = datasver.data;
            		$rootScope.loading = false;
            		$scope.search = '';
            		swangular.success("Success update biaya");
            		$scope.$apply();
				}
		  	} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error load data, mohon isi dengan lengkapi");
            		$scope.$apply();
		  		}
		  	}

		}

		$scope.act_click = function(data){
			$scope.biaya = angular.copy(data);
			$scope.biaya.harga_smp = parseInt($scope.biaya.harga_smp);
			$scope.biaya.harga_sma = parseInt($scope.biaya.harga_sma);
			$scope.btn_save_text = "Update";
			$scope.show_form = true;
			$anchorScroll();
		}


		$scope.refresh = function(){
			$scope.show_form = false;
			$scope.datas_biaya = [];
			$scope.btn_save_text = 'Save';
			$scope.biaya = {};
			$scope.search = '';
			getAll();
		}

		$scope.act_remove = async function(data){
			const confirm = await swangular.swal({
			  title: 'Confirm',
			  text: "Yakin akan menghapus biaya ini ?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Oke, hapus'
			});
			if (confirm.value) {
				$scope.show_form = false;
				$rootScope.loading = true;
				$scope.$apply();
				try {
					const data_delete = await $http({
		              method: 'POST',
		              url: config.baseApi()+'delete_biaya_back',
		              data:{
		              	id_session:data.id_session
		              }
		            });
		            const datasver = await $http({
		              method: 'GET',
		              url: config.baseApi()+'biaya_back'
		            });
					$scope.datas_biaya = datasver.data;
	            	$rootScope.loading = false;
	            	$scope.search = '';
	            	swangular.success("Success delete biaya");
            		$scope.$apply();
				}catch(err){
					if (err.status === 401 && err.data.status==='error') {
			  			$rootScope.loading = false;
			    		$state.go('login');
			  		}else{
			  			$rootScope.loading = false;
	            		swangular.alert("Error delete data...");
	            		$scope.$apply();
			  		}
				}
			};
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

        $scope.getTotal = function(nama_harga){
		    var total = 0;
		    for (var i = 0; i < $scope.datas_biaya.length; i++) {
		    	var data = $scope.datas_biaya[i];
		    	total += parseInt(data[nama_harga]);
		    };
		    return total;
		}

		getAll();
	}]);
})();