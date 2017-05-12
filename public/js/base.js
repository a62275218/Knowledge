(function()
{
    'use strict';
    angular.module('knowledge',[
        'ui.router'
    ])
        .config(['$interpolateProvider','$stateProvider','$urlRouterProvider',function($interpolateProvider,$stateProvider,$urlRouterProvider){
            $interpolateProvider.startSymbol('[[');
            $interpolateProvider.endSymbol(']]');
            $urlRouterProvider.otherwise('/home');
            $stateProvider
                .state('home',{
                    url:'/home',
                    templateUrl:'home.tpl'
                })
                .state('login',{
                    url:'/login',
                    templateUrl:'login.tpl'
                })
                .state('signup',{
                    url:'/signup',
                    templateUrl:'signup.tpl'
                })

        }])
        .service('UserService',[function(){
            var user = this;
            user.signup_data ={};
            user.signUp = function(){
                console.log('1111')
            }
        }])
        .controller('SignupController',['$scope','UserService',function($scope,UserService){
            $scope.User = UserService;
        }])
})();