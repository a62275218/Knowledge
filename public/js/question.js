(function(){
    'use strict';
    angular.module('question',[])
        .service('QuestionService', ['$http', '$state',
            function ($http, $state) {
                var question = this;
                question.new_question = {};
                question.go_add_question = function () {
                    $state.go('question.add')
                };
                question.add = function () {
                    if (!question.new_question.title) {
                    } else {
                        $http.post('api/question/add', question.new_question)
                            .then(function (result) {
                                if (result.data.status) {
                                    question.new_question = {};
                                    $state.go('home');
                                }
                            }, function (error) {
                                console.log(error);
                            })
                    }
                }
            }])
        .controller('QuestionAddController', ['QuestionService', '$scope',
            function (QuestionService, $scope) {
                $scope.Question = QuestionService;
            }])
})();
