(function(){
	var app = angular.module('app',[
		'ui.router',
        'base64',
        'swangular',
        'ngSanitize',
        'ui.select',
        'moment-picker',
        'ngCacheBuster',
		'moment-picker',
		'login.ctrl',
		'auth.ctrl',
        'common.ctrl',
        'verifikasi.ctrl',
        'datapendaftaran.ctrl',
        'datapendaftaranform.ctrl',
        'seleksi.ctrl',
        'session.ctrl',
        'agenda.ctrl',
        'config.ctrl',
		'biaya.ctrl',
	]);
    app.config(['httpRequestInterceptorCacheBusterProvider',function(httpRequestInterceptorCacheBusterProvider){
        httpRequestInterceptorCacheBusterProvider.setMatchlist([/.*views.*/,/.*js.*/,/.*libs.*/],true);
    }]);
    app.run(['config','$http', function (config,$http) {
        $http.defaults.headers.common['X-Api-Key'] = 'Bearer '+config.getToken();
    }]);
	app.config(["$stateProvider","$urlRouterProvider","$locationProvider", function($stateProvider, $urlRouterProvider,$locationProvider) {
        $urlRouterProvider.otherwise('/auth');
        $stateProvider.state('dash', {
            url: '/dash',
            templateUrl: 'views/dash.html',
            abstract:true,
            controller:'appCtrl'
        })
        .state('dash.verifikasi',{
            url: '/verifikasi',
            templateUrl: 'views/verifikasi.html',
            controller:'verifikasiCtrl'
        })
        .state('dash.datapendaftaran',{
            url: '/datapendaftaran',
            templateUrl: 'views/datapendaftaran.html',
            controller:'datapendaftaranCtrl'
        })
        .state('dash.datapendaftaranform',{
            url: '/datapendaftaranform?id&status',
            templateUrl: 'views/datapendaftaran_form.html',
            controller:'datapendaftaranformCtrl'
        })
        .state('dash.seleksi',{
            url: '/seleksi',
            templateUrl: 'views/seleksi.html',
            controller:'seleksiCtrl'
        })
        .state('dash.setting',{
            url: '/setting',
            templateUrl: 'views/config.html',
            controller:'configCtrl'
        })
        .state('dash.agenda',{
            url: '/agenda',
            templateUrl: 'views/agenda.html',
            controller:'agendaCtrl'
        })
        .state('dash.session',{
            url: '/session',
            templateUrl: 'views/session.html',
            controller:'sessionCtrl'
        })
        .state('dash.biaya',{
            url: '/biaya',
            templateUrl: 'views/biaya.html',
            controller:'biayaCtrl'
        })
        .state('auth',{
        	url: '/auth',
            templateUrl: 'views/auth.html',
            controller:'authCtrl'
        })
        .state('login',{
        	url: '/',
            templateUrl: 'views/login.html',
            controller:'loginCtrl'
        }).state("dash.hasil",{
            url:"/hasil",
            templateUrl:"views/hasil.html",
            controller:"hasilCtrl"
        })
        

        if(window.history && window.history.pushState){
            $locationProvider.html5Mode(true).hashPrefix('!');
        }
    }])
    app.controller('appCtrl', ['config','$scope','$rootScope', '$location','$window','$timeout','$http','$state', function(config,$scope,$rootScope, $location,$window,$timeout,$http,$state) {
        $rootScope.loading = false;
        $scope.isActive = function(destination) {
	        return destination === $location.path();
	    }
        $rootScope.rootBaseAdmin = config.baseAdmin()+'img/';
        $rootScope.root_logout = function(){
            $window.localStorage.removeItem("token");
            $window.location.href = config.baseAdmin()+'auth';
            
        }
        if (location.protocol != 'https:') {
            location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
        }
        $scope.menus = [];

	}]);
    app.directive('blur', function () {
      return function (scope, element, attrs) {
        attrs.$observe('blur', function (newValue) {
          newValue != "" && element[0].blur();
        });
      }
    });
    app.filter('highlight', function() {
        return function(text, phrase) {
          if (phrase) text = text.replace(new RegExp('('+phrase+')', 'gi'),
            '<span class="highlighted">$1</span>')

          return text;
        }
    });
    app.directive('ngEnter', function () {
        return function (scope, element, attrs) {
            element.bind("keydown keypress", function (event) {
                if (event.which === 13) {
                    scope.$apply(function () {
                        scope.$eval(attrs.ngEnter);
                    });
                    event.preventDefault();
                }
            });
        };
    });
    app.directive('fileModel', ['$parse', function ($parse) {
        return {
           restrict: 'A',
           link: function(scope, element, attrs) {
              var model = $parse(attrs.fileModel);
              var modelSetter = model.assign;
              
              element.bind('change', function(){
                 scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                 });
              });
           }
        };
    }]);

    app.filter('propsFilter', function() {
      return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
          var keys = Object.keys(props);

          items.forEach(function(item) {
            var itemMatches = false;

            for (var i = 0; i < keys.length; i++) {
              var prop = keys[i];
              var text = props[prop].toLowerCase();
              if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                itemMatches = true;
                break;
              }
            }

            if (itemMatches) {
              out.push(item);
            }
          });
        } else {
          // Let the output be the input untouched
          out = items;
        }

        return out;
      };
    });
    
})();