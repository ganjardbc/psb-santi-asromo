(function(){
	var app = angular.module('datapendaftaran.ctrl',[]);
	app.controller("datapendaftaranCtrl",["config","$scope","$state","$base64","$http","$rootScope","swangular",function(config,$scope,$state,$base64,$http,$rootScope,swangular){
	    $scope.datas_pendaftaran = [];
	    var page = 1;
	    $scope.url_pas_foto = config.baseImage()+"upload/";
	    $scope.search = '';
	    $scope.data = {};
	    async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'datapendaftaran/'+page
	            });
	            console.log(datasver)
				$scope.datas_pendaftaran = datasver.data;
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
	              url: config.baseApi()+'datapendaftaran/'+page
	            });
				for (var i = 0; i < datasver.data.length; i++) {
	            	$scope.datas_pendaftaran.push(datasver.data[i]);
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

        $scope.export_to_excel = function(){
            window.open(config.baseUrl()+"h312ud1/export.php",'_blank');
        }

        $scope.klik = async function(data){
        	$state.go('dash.datapendaftaranform',{
        		id:data.id,
        		status:'update'
        	});
        }

        $scope.act_remove = async function(data){
        	try {
	        	const confirm = await swangular.swal({
				  title: 'Confirm',
				  text: "Yakin akan menghapus "+data.nama+" ?",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Ya, hapus'
				});
				if (confirm.value) {
					var verData = angular.copy(data);
				    const datasgenerate = await $http({
		              method: 'POST',
		              url: config.baseApi()+'hapus_datapendaftaran',
		              data:{
		              	id_pendaftaran:verData.id
		              }
		            });
		            var index = $scope.datas_pendaftaran.indexOf(data);
                    $scope.datas_pendaftaran.splice(index,1);
					$rootScope.loading = false;
		            swangular.success("Success hapus");			
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


       

        $scope.act_search = async function(){
        	if ($scope.search === '') {
        		page = 1;
        		getAll();
        	}else{
        		$rootScope.loading = true;
				try {
				    const datasver = await $http({
		              method: 'GET',
		              url: config.baseApi()+'search_datapendaftaran?q='+$scope.search
		            });
		            $scope.datas_pendaftaran = datasver.data;
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