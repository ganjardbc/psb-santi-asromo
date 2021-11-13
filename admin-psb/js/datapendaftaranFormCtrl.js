(function(){
	var app = angular.module('datapendaftaranform.ctrl',[]);
	app.controller("datapendaftaranformCtrl",["config","$scope","$state","$base64","$http","$rootScope","swangular",function(config,$scope,$state,$base64,$http,$rootScope,swangular){
	    $scope.url_pas_foto = config.baseImage()+"upload/";
	    $scope.data = {};
	    $scope.btn_save_text = $state.params.status === 'update' ? 'Update' : 'Save';
	    $scope.startViewTanggalLahir = moment().subtract(17, 'year').format('DD/MM/YYYY');
	    $scope.foto_source = "";
        $scope.file_foto = {};
        $scope.tanggal_lahir;
	    // $scope.datas_prov = [];
	    // $scope.select_provinsi = undefined;
	    // $scope.select_kabupaten = undefined;
	    // $scope.select_kecamatan = undefined;
	    // $scope.select_kelurahan = undefined;

	    // $scope.select_provinsi_ortu = undefined;
	    // $scope.select_kabupaten_ortu = undefined;
	    // $scope.select_kecamatan_ortu = undefined;
	    // $scope.select_kelurahan_ortu = undefined;
	    
	    async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'datapendaftaran_id/'+$state.params.id
	            });
	            $scope.data = datasver.data;
	            $scope.data.pindahan_kelas = $scope.data.pindahan_kelas === 'non' ? "" : $scope.data.pindahan_kelas;
	            $scope.tanggal_lahir = moment($scope.data.tanggal_lahir,"DD/MM/YYYY");
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

		$scope.browse_foto = function(img){
            if (img['0'].size < 550000) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $scope.foto_source = event.target.result;
                    $scope.$apply();
                }
                reader.readAsDataURL(img['0']);
            }else{
                swangular.alert("Resolusi terlalu besar")
            }
            
        }

		$scope.change_jenjang = function (data){
			// $scope.data.asal_sekolah = "";
		}

		$scope.change_pindahan_kelas = function (data){

		}

		$scope.submit = async function(){
			$rootScope.loading = true;
			try {
			    $scope.data.pindahan_kelas = $scope.data.pindahan_kelas === "" ? "non" : $scope.data.pindahan_kelas;
				var tanggal_lahir = moment($scope.tanggal_lahir).format('DD/MM/YYYY');
				$scope.data.tanggal_lahir = tanggal_lahir;
				if ($scope.foto_source !== "") {
					var fd = new FormData();
		            fd.append('file', $scope.file_foto);
		            fd.append('name', moment().unix().toString()+"_"+$scope.data.nama.replace(/\s/g, '_'));
		            const upload = await $http.post(config.baseApi()+'upload_image', fd, {
		                transformRequest: angular.identity,
		                headers: {'Content-Type': undefined,'Process-Data': false}
		            });
		            $scope.data.pas_foto = upload.data.image;
				};
				if ($scope.data.no_kartu === null) {
					$scope.data.status_kartu_ujian = 'no';
				}else{
					$scope.data.status_kartu_ujian = 'yes';
				}
				const dataspost = await $http({
	              method: 'POST',
	              url: config.baseApi()+'update_pendaftaran',
	              data:$scope.data
	            });
	            swangular.success("Success Update");
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
		getAll();
	}]);
})();