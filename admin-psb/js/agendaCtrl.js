(function(){
	var app = angular.module('agenda.ctrl',[]);
	app.controller("agendaCtrl",["config","$scope","$rootScope","$state","$base64","$http","swangular","$anchorScroll",function(config,$scope,$rootScope,$state,$base64,$http,swangular,$anchorScroll){
		$scope.show_form = false;
		$scope.datas_agenda = [];
		$scope.btn_save_text = 'Save';
		$scope.agenda = {};
		$scope.add = function(){
			if ($scope.show_form) {
				$scope.show_form = false;
			}else{
				$scope.btn_save_text = "Save";
				$scope.agenda = {};
				$scope.show_form = true;
			}
			
		}
		
		async function getAll(){
			$rootScope.loading = true;
			try {
			    const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'agenda'
	            });
				$scope.datas_agenda = datasver.data;
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

		$scope.tanggal_pendaftaran_format = function(date_1,date_2){
			var date1 = moment(date_1).format("DD MMMM YYYY");
			var date2 = moment(date_2).format("DD MMMM YYYY");
			return date1+" - "+date2;
		}	

		$scope.tanggal_ujian_format = function(date_1){
			var date1 = moment(date_1).format("DD MMMM YYYY");
			return date1;
		}	

		$scope.save_or_update = async function(){
			$rootScope.loading = true;
			try {
				var datas_agenda = {};
				datas_agenda.tanggal_pendaftaran_from = moment($scope.agenda.tanggal_pendaftaran_from).format("YYYY-MM-DD");
				datas_agenda.tanggal_pendaftaran_to = moment($scope.agenda.tanggal_pendaftaran_to).format("YYYY-MM-DD");
				datas_agenda.tanggal_ujian = moment($scope.agenda.tanggal_ujian).format("YYYY-MM-DD");
				datas_agenda.tanggal_pengumuman = moment($scope.agenda.tanggal_pengumuman).format("YYYY-MM-DD");
				datas_agenda.tanggal_daftar_ulang_from = moment($scope.agenda.tanggal_daftar_ulang_from).format("YYYY-MM-DD");
				datas_agenda.tanggal_daftar_ulang_to = moment($scope.agenda.tanggal_daftar_ulang_to).format("YYYY-MM-DD");
				datas_agenda.kuota_pendaftaran = $scope.agenda.kuota_pendaftaran;
				datas_agenda.id_agenda = $scope.agenda.id_agenda;
				const data = await $http({
	              method: 'POST',
	              url: config.baseApi()+'update_agenda',
	              data:datas_agenda
	            });
	            const datasver = await $http({
	              method: 'GET',
	              url: config.baseApi()+'agenda'
	            });
				$scope.datas_agenda = datasver.data;
        		$rootScope.loading = false;
        		swangular.success("Success update agenda");
        		$scope.$apply();
		  	} catch(err) {
		  		if (err.status === 401 && err.data.status==='error') {
		  			$rootScope.loading = false;
		    		$state.go('login');
		  		}else{
		  			$rootScope.loading = false;
            		swangular.alert("Error load data agenda");
            		$scope.$apply();
		  		}
		  	}

		}
		$scope.tanggal_pendaftaran_from;
		$scope.act_click = function(verData){
			var data = angular.copy(verData);
			$scope.agenda.tanggal_pendaftaran_from = moment(data.tanggal_pendaftaran_from);
			$scope.agenda.tanggal_pendaftaran_to = moment(data.tanggal_pendaftaran_to);
			$scope.agenda.tanggal_ujian = moment(data.tanggal_ujian);
			$scope.agenda.tanggal_pengumuman = moment(data.tanggal_pengumuman);
			$scope.agenda.tanggal_daftar_ulang_from = moment(data.tanggal_daftar_ulang_from);
			$scope.agenda.tanggal_daftar_ulang_to = moment(data.tanggal_daftar_ulang_to);
			$scope.agenda.kuota_pendaftaran = parseInt(data.kuota_pendaftaran);
			$scope.agenda.id_agenda = data.id_agenda;
			$scope.btn_save_text = "Update";
			$scope.show_form = true;
			$anchorScroll();
		}


		$scope.refresh = function(){
			$scope.show_form = false;
			$scope.datas_agenda = [];
			$scope.agenda = {};
			getAll();
		}

		
		getAll();
	}]);
})();