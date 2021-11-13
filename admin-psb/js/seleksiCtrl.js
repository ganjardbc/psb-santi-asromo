(function(){
	var app = angular.module('seleksi.ctrl',[]);
	app.controller("seleksiCtrl",["config","$scope","$state","$base64","$http","$rootScope","swangular",function(config,$scope,$state,$base64,$http,$rootScope,swangular){
	    $scope.datas_seleksi = [];
	    var page = 1;
	    $scope.url_pas_foto = config.baseImage()+"upload/";
	    $scope.search = '';
	    $scope.data = {};
	    async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'seleksi/'+page
	            });
				$scope.datas_seleksi = datasver.data;
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

        $scope.load_more = async function(){
            $rootScope.loading = true;
			try {
				page += 1;
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'seleksi/'+page
	            });
				for (var i = 0; i < datasver.data.length; i++) {
	            	$scope.datas_seleksi.push(datasver.data[i]);
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

        $scope.klik = function(data){
          	$scope.data = angular.copy(data);
          	$('#modal_seleksi').appendTo("body").modal('show');
        }

        $scope.act_luluskan = async function(data){
        	try {
	        	const confirm = await swangular.swal({
				  title: 'Confirm',
				  text: "Yakin ingin meluluskan "+data.nama+" ?",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Ya, luluskan'
				});
				if (confirm.value) {
					var verData = angular.copy(data);
				    const datasgenerate = await $http({
		              method: 'POST',
		              url: config.baseApi()+'luluskan_seleksi',
		              data:{
		              	no_kartu:verData.no_kartu,
                        id_pendaftaran:verData.id
		              }
		            });
		            var index = $scope.datas_seleksi.indexOf(data);
                    $scope.datas_seleksi[index].keterangan = "LULUS";
					$rootScope.loading = false;
		            swangular.success("Success meluluskan");			
					$scope.$apply();
				}
			} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error lulus data...");
            		$scope.$apply();
		  		}
		  	}
        }

        $scope.act_cancel = async function(data){
        	try {
	        	const confirm = await swangular.swal({
				  title: 'Confirm',
				  text: "Yakin ingin batal kelulusan  "+data.nama+" ?",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Ya, batalkan'
				});
				if (confirm.value) {
					var verData = angular.copy(data);
				    const datasgenerate = await $http({
		              method: 'POST',
		              url: config.baseApi()+'batalkan_seleksi',
		              data:{
		              	id_seleksi:verData.id_seleksi
		              }
		            });
		            var index = $scope.datas_seleksi.indexOf(data);
                    $scope.datas_seleksi[index].keterangan = "BELUM";
					$rootScope.loading = false;
		            swangular.success("Success membatalkan");			
					$scope.$apply();
				}
			} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error batalkan data...");
            		$scope.$apply();
		  		}
		  	}
        }

		$scope.act_search = async function(){
        	if ($scope.search === '') {
        		page = 1;
        		getAll();
        	}else{
        		$rootScope.loading = true;
				try {
				    const datasver = await $http({
		              method: 'GET',
		              url: config.baseApi()+'search_seleksi?q='+$scope.search
		            });
		            $scope.datas_seleksi = datasver.data;
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
            
        }
        $scope.refresh = function(){
        	page = 1;
        	$scope.search = '';
        	$scope.data = {};
        	getAll();
        }
		getAll();
	}]);
})();