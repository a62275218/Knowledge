(function(){
    'use strict';
    angular.module('common',[])
        .service('TimelineService', ['$http','AnswerService',
            function ($http,AnswerService) {
                var timeline = this;
                timeline.data = [];
                timeline.current_page = 1;
                //get all the posts data
                timeline.get = function (conf) {
                    if(timeline.pending) return;
                    timeline.pending = true;
                    conf = conf || {page:timeline.current_page};
                    $http.post('api/timeline', conf)
                        .then(function (result) {
                            if (result.data.status) {
                                if(result.data.data.data.length) {
                                    timeline.data = timeline.data.concat(result.data.data.data);
                                    //count the votes for each answer
                                    timeline.data = AnswerService.count_vote(timeline.data);
                                    timeline.current_page++;
                                }else{
                                    timeline.no_more_data = true;
                                }
                                console.log(result);
                            } else {
                                console.log('network error');
                            }
                        }, function (error) {
                            console.log(error);
                        })
                        .finally(function(){
                            timeline.pending = false;
                        })
                };
                //vote for the answer
                timeline.vote = function(conf){
                    AnswerService.vote(conf)
                        .then(function(result){
                            //if the post is successful, update data
                            if(result){
                                AnswerService.update_data(conf.id);
                            }
                        })
                }
            }])
        .controller('HomeController', ['$scope','TimelineService','AnswerService',
            function ($scope,TimelineService,AnswerService) {
                $scope.Timeline = TimelineService;
                $scope.Timeline.get();
                var $win = $(window);
                //when scroll to bottom,fetch more data
                $win.on('scroll',function(){
                    if($win.scrollTop()-($(document).height() - $win.height()) > -30){
                        TimelineService.get();
                    }
                });
                //watch the change of votes
                $scope.$watch(function(){
                   return AnswerService.data;
                },function(new_data,old_data){
                    var timeline_data = TimelineService.data;
                    for(var k in new_data){
                        for(var i = 0;i < timeline_data.length;i++){
                            if(k == timeline_data[i].id){
                                timeline_data[i] = new_data[k];
                            }
                        }
                    }
                    TimelineService.data = AnswerService.count_vote(TimelineService.data)
                },true)
            }])
})();