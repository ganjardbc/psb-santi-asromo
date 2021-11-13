(function(){
	var app = angular.module('verifikasi.ctrl',[]);
	app.controller("verifikasiCtrl",["config","$scope","$state","$base64","$http","$rootScope","swangular",function(config,$scope,$state,$base64,$http,$rootScope,swangular){
	    $scope.datas_verifikasi = [];
	    var page = 1;
	    $scope.url_pas_foto = config.baseImage()+"upload/";
	    $scope.search = '';
	    $scope.data = {};
	    $scope.data_kartu = {};
	    $scope.kuota = {};
	    async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'verifikasi/'+page
	            });
	            const datascheck = await $http({
	              method: 'GET',
	              url: config.baseApi()+'cek_kuota'
	            });
	            $scope.kuota = datascheck.data;
				$scope.datas_verifikasi = datasver.data;
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
	              url: config.baseApi()+'verifikasi/'+page
	            });
				for (var i = 0; i < datasver.data.length; i++) {
	            	$scope.datas_verifikasi.push(datasver.data[i]);
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

        $scope.klik = async function(data){
            $rootScope.loading = true;
			try {
			    const datasverujian = await $http({
	              method: 'GET',
	              url: config.baseApi()+'verifikasi_kartu_ujian/'+data.id
	            });
				$scope.data = angular.copy(data);
				if (datasverujian.data.length != 0) {
                    var ujian_data = datasverujian.data[0];
                    $scope.data_kartu.no_kartu = ujian_data.no_kartu;
                    $scope.data_kartu.no_ruangan = ujian_data.no_ruangan;
                    $scope.data_kartu.no_bangku = ujian_data.no_bangku;
                }else{
                	$scope.data_kartu = {};
                }
                
				$rootScope.loading = false;
                $('#modal_verifikasi').appendTo("body").modal('show');
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

        $scope.act_verifikasi = async function(data){
        	try {
	        	const confirm = await swangular.swal({
				  title: 'Confirm',
				  text: "Yakin akan memverifikasi "+data.nama+" ?",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Ya, verifikasi'
				});
				if (confirm.value) {
					var verData = angular.copy(data);
				    const datasgenerate = await $http({
		              method: 'POST',
		              url: config.baseApi()+'verifikasi_generate_kartu_ujian',
		              data:{
		              	id_pendaftaran:verData.id,
                        jenis_kelamin:verData.jenis_kelamin,
                        jenjang:verData.jenjang,
                        id:verData.id
		              }
		            });
		            var index = $scope.datas_verifikasi.indexOf(data);
                    $scope.datas_verifikasi[index].status = "VERIFIED";		
                    $scope.kuota.pendaftar += 1;
					$rootScope.loading = false;
		            swangular.success("Success verifikasi");			
					$scope.$apply();
				}
			} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error verifikasi data...");
            		$scope.$apply();
		  		}
		  	}
        }


        $scope.act_batal_verifikasi = async function(data){
        	const confirm = await swangular.swal({
			  title: 'Confirm',
			  text: "Yakin akan membatalkan verifikasi "+data.nama+" ?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Ya, batalkan'
			});
			if (confirm.value) {
				try {
					var verData = angular.copy(data);
				    const datasgenerate = await $http({
		              method: 'POST',
		              url: config.baseApi()+'batal_verifikasi',
		              data:{
		              	id_pendaftaran:verData.id,
		              }
		            });
		            var index = $scope.datas_verifikasi.indexOf(data);
                    $scope.datas_verifikasi[index].status = "UNVERIFIED";	
                    $scope.kuota.pendaftar -= 1;
		            swangular.success("Success batal verifikasi");			
					$rootScope.loading = false;
					$scope.$apply();
			  	} catch(err) {
			  		if (err.status === 401 && err.data.status==='error') {
			  			$rootScope.loading = false;
			    		$state.go('login');
			  		}else{
			  			$rootScope.loading = false;
	            		swangular.alert("Error batal verifikasi data...");
	            		$scope.$apply();
			  		}
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
		              url: config.baseApi()+'search_verifikasi?q='+$scope.search
		            });
		            $scope.datas_verifikasi = datasver.data;
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
        	$scope.data_kartu = {};
        	getAll();
        }
		getAll();
	}]);
})();