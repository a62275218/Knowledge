(function () {
    'use strict';
    //user id
    window.u = {
        id:parseInt($('html').attr('user-id'))
    };
    angular.module('knowledge', [
        'ui.router',
        'common',
        'question',
        'user',
        'answer'
    ])
        .config(['$interpolateProvider', '$stateProvider', '$urlRouterProvider',
            function ($interpolateProvider, $stateProvider, $urlRouterProvider) {
                $interpolateProvider.startSymbol('[[');
                $interpolateProvider.endSymbol(']]');
                $urlRouterProvider.otherwise('/home');
                $stateProvider
                    .state('home', {
                        url: '/home',
                        templateUrl: 'page/home'
                    })
                    .state('login', {
                        url: '/login',
                        templateUrl: 'page/login'
                    })
                    .state('signup', {
                        url: '/signup',
                        templateUrl: 'page/signup'
                    })
                    //abstract router to define question class
                    .state('question', {
                        abstract: true,
                        url: '/question',
                        template: '<div ui-view></div>'
                    })
                    //url is question/add
                    .state('question.add', {
                        url: '/add',
                        templateUrl: 'page/question_add'
                    })
                    .state('user', {
                        url: '/user/:id',
                        templateUrl: 'page/user'
                    })
            }])
})();