(function(){
	var app = angular.module('hasil.ctrl',[]);
	app.controller("hasilCtrl",["config","$scope","$rootScope","$state","$base64","$http","swangular","$anchorScroll",function(config,$scope,$rootScope,$state,$base64,$http,swangular,$anchorScroll){
		$scope.show_form = false;
		$scope.datas_hasil = [];
		$scope.btn_save_text = 'Save';
		$scope.search = '';
		$scope.hasil = {};
		$scope.file_source = {};
		$scope.add = function(){
			if ($scope.show_form) {
				$scope.show_form = false;
			}else{
				$scope.btn_save_text = "Save";
				$scope.file_source = {};
				$scope.hasil = {};
				$scope.show_form = true;
			}
		}
		
		async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'hasil',
	            });
				$scope.datas_hasil = datasver.data;
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
					var fd = new FormData();
		            fd.append('file', $scope.file_source);
		            fd.append('name', moment().unix().toString()+$scope.hasil.judul.replace(/\s/g, '_'));
		            const upload = await $http.post(config.baseApi()+'upload_hasil', fd, {
		                transformRequest: angular.identity,
		                headers: {'Content-Type': undefined,'Process-Data': false}
		            });
		            $scope.hasil.url_file = upload.data.file;
					const data = await $http({
		              method: 'POST',
		              url: config.baseApi()+'save_hasil',
		              data:$scope.hasil
		            });
		            if (data.data.status === 'success') {
		            	const datasdown = await $http({
			              method: 'GET',
			              url: config.baseApi()+'hasil',
			            });
						$scope.datas_hasil = datasdown.data;
		            	$rootScope.loading = false;
		            	swangular.success("Success save data");
		            	$scope.hasil = {};
		            	$scope.file_source = {};
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error save data");
	            		$scope.$apply();
		            }
		            
				}else{
					if ($scope.file_source.size) {
						$scope.hasil.status_upload = 'upload';
						var fd = new FormData();
			            fd.append('file', $scope.file_source);
			            fd.append('name', moment().unix().toString()+$scope.hasil.judul.replace(/\s/g, '_'));
			            const upload = await $http.post(config.baseApi()+'upload_hasil', fd, {
			                transformRequest: angular.identity,
			                headers: {'Content-Type': undefined,'Process-Data': false}
			            });
			            $scope.hasil.url_file = upload.data.file;
					}else{
						$scope.hasil.url_file = 'none';
						$scope.hasil.status_upload = 'notupload';
					}
					const data = await $http({
		              method: 'POST',
		              url: config.baseApi()+'update_hasil',
		              data:$scope.hasil
		            });
		            if (data.data.status === 'success') {
		            	const datasdown = await $http({
			              method: 'GET',
			              url: config.baseApi()+'hasil',
			            });
						$scope.datas_hasil = datasdown.data;
	            		$rootScope.loading = false;
	            		swangular.success("Success update data");
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error update data");
	            		$scope.$apply();
	            		
		            }

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

		$scope.click = function(data){
			var down = angular.copy(data);
			$scope.hasil = down;
			$scope.hasil.url_file_temp = down.url_file;
			$scope.btn_save_text = "Update";
			$scope.file_source = {};
			$scope.file_source.name = $scope.hasil.url_file;
			$scope.show_form = true;
		}

		$scope.refresh = function(){
			$scope.show_form = false;
			$scope.datas_hasil = [];
			$scope.btn_save_text = 'Save';
			$scope.hasil = {};
			getAll();
		}

		$scope.remove = async function(data){
			const confirm = await swangular.swal({
			  title: 'Confirm',
			  text: "Yakin akan menghapus file ini ?",
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
		              url: config.baseApi()+'delete_hasil',
		              data:{
		              	id_file_hasil_seleksi:data.id_file_hasil_seleksi,
		              	url_file:data.url_file
		              }
		            });
		            if (data_delete.data.status === 'success') {
		            	const datasdown = await $http({
			              method: 'GET',
			              url: config.baseApi()+'hasil',
			            });
						$scope.datas_hasil = datasdown.data;
		            	$rootScope.loading = false;
		            	swangular.success("Success delete data");
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error delete data");
	            		$scope.$apply();
		            }
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

		getAll();
	}]);
})();