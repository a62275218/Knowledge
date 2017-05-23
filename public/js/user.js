(function(){
    'use strict';
    angular.module('user',[])
        .service('UserService', ['$http', '$state',
            function ($http, $state) {
                var user = this;
                user.signup_data = {};
                //submit form to sign up
                user.signup = function () {
                    $http.post('api/user/signup', user.signup_data)
                        .then(function (result) {
                            //status = 1
                            if (result.data.status) {
                                user.signup_data = {};
                                $state.go('login');
                            }
                        }, function (error) {
                            console.log(error);
                        });
                };
                //user login
                user.login_data = {};
                user.login = function () {
                    $http.post('api/user/login', user.login_data)
                        .then(function (result) {
                            if (result.data.status) {
                                $state.go('home');
                            } else {
                                user.login_data.login_status = false;
                            }
                        }, function (error) {
                            console.log(error);
                        });
                };
                //judge whether the username is registered
                user.exist = function () {
                    $http.post('api/user/exist',
                        {
                            username: user.signup_data.username
                        }).then(function (result) {
                        result.data.status && result.data.data.count ?
                            user.signup_data.exist = true :
                            user.signup_data.exist = false;
                    }, function (error) {
                        console.log(error);
                    })
                };
                /*user.check_login = function(){
                 $http.get('api/user/check_login')
                 .then(function(result){
                 console.log(result);
                 })
                 }*/
            }])
        .controller('SignupController', ['$scope', 'UserService',
            function ($scope, UserService) {
                //initiate user
                $scope.User = UserService;
                //make validation every time user change username
                $scope.$watch(function () {
                    return UserService.signup_data;
                }, function (n, o) {
                    if (n.username != o.username) {
                        UserService.exist();
                    }
                }, true);
            }])
        .controller('LoginController', ['$scope', 'UserService',
            function ($scope, UserService) {
                $scope.User = UserService;
            }
        ])
        .controller('UserController',['$scope','UserService',
            function($scope,UserService){

        }])
})();