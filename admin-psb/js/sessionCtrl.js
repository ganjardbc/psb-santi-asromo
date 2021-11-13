(function(){
	var app = angular.module('session.ctrl',[]);
	app.controller("sessionCtrl",["config","$scope","$rootScope","$state","$base64","$http","swangular","$anchorScroll",function(config,$scope,$rootScope,$state,$base64,$http,swangular,$anchorScroll){
		$scope.show_form = false;
		$scope.datas_session = [];
		$scope.btn_save_text = 'Save';
		$scope.search = '';
		$scope.session = {};
		var page = 1;
		$scope.add = function(){
			if ($scope.show_form) {
				$scope.show_form = false;
			}else{
				$scope.btn_save_text = "Save";
				$scope.session = {};
				$scope.show_form = true;
			}
			
		}
		
		async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'session/'+page
	            });
				$scope.datas_session = datasver.data;
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

		$scope.load_more = async function(){
            $rootScope.loading = true;
			try {
				page += 1;
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'session/'+page
	            });
				for (var i = 0; i < datasver.data.length; i++) {
	            	$scope.datas_session.push(datasver.data[i]);
	            };
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

		$scope.act_search = async function(){
			$rootScope.loading = true;
			try {
				page = 1;
				var url_search = '';
				if ($scope.search === '') {
					url_search = config.baseApi()+'session/'+page;
				}else{
					url_search = config.baseApi()+'search_session?q='+$scope.search;
				}
			    const datasver = await $http({
	              method: 'GET',
	              url: url_search
	            });
				$scope.datas_session = datasver.data;
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
		              url: config.baseApi()+'save_session',
		              data:$scope.session
		            });
		            if (data.data.status === 'success') {
		            	page = 1;
		            	const datasver = await $http({
			              method: 'GET',
			              url: config.baseApi()+'session/'+page
			            });
						$scope.datas_session = datasver.data;
		            	$rootScope.loading = false;
		            	swangular.success("Success save session");
		            	$scope.session = {};
		            	$scope.search = '';
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error, Nama session sudah ada.");
	            		$scope.$apply();
		            }
		            
				}else{
					const data = await $http({
		              method: 'POST',
		              url: config.baseApi()+'update_session',
		              data:$scope.session
		            });
		            if (data.data.status === 'success') {
		            	page = 1;
		            	const datasver = await $http({
			              method: 'GET',
			              url: config.baseApi()+'session/'+page
			            });
						$scope.datas_session = datasver.data;
	            		$rootScope.loading = false;
	            		$scope.search = '';
	            		swangular.success("Success update session");
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error, Nama session sudah ada.");
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

		$scope.act_click = function(data){
			$scope.session = angular.copy(data);
			$scope.btn_save_text = "Update";
			$scope.show_form = true;
			$anchorScroll();
		}


		$scope.refresh = function(){
			$scope.show_form = false;
			$scope.datas_session = [];
			$scope.btn_save_text = 'Save';
			$scope.session = {};
			$scope.search = '';
			page = 1;
			getAll();
		}

		$scope.act_remove = async function(data){
			const confirm = await swangular.swal({
			  title: 'Confirm',
			  text: "Yakin akan menghapus session ini ?",
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
		              url: config.baseApi()+'delete_session',
		              data:{
		              	id_session:data.id_session
		              }
		            });
		            if (data_delete.data.status === 'success') {
		            	page = 1;
		            	const datasver = await $http({
			              method: 'GET',
			              url: config.baseApi()+'session/'+page
			            });
						$scope.datas_session = datasver.data;
		            	$rootScope.loading = false;
		            	$scope.search = '';
		            	swangular.success("Success delete session");
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error, session sedang digunakan");
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

		$scope.act_activate = async function(data){
			const confirm = await swangular.swal({
			  title: 'Confirm',
			  text: "Yakin ingin mengaktifkan session "+data.session_name+" ?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Oke, aktifkan'
			});
			if (confirm.value) {
				$scope.show_form = false;
				$rootScope.loading = true;
				$scope.$apply();
				try {
					const data_delete = await $http({
		              method: 'POST',
		              url: config.baseApi()+'activate_session',
		              data:{
		              	id_session:data.id_session
		              }
		            });
		            if (data_delete.data.status === 'success') {
		            	page = 1;
		            	const datasver = await $http({
			              method: 'GET',
			              url: config.baseApi()+'session/'+page
			            });
						$scope.datas_session = datasver.data;
		            	$rootScope.loading = false;
		            	$scope.search = '';
		            	swangular.success("Success aktifkan session");
	            		$scope.$apply();
		            }else{
		            	$rootScope.loading = false;
		            	swangular.alert("Error, load data");
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