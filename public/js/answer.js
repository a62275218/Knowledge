(function () {
    'use strict';
    angular.module('answer', [])
        .service('AnswerService', ['$http',
            function ($http) {
                var answer = this;
                answer.data = {};
                //count the votes for a certain answer
                answer.count_vote = function (answers) {
                    //iterate all answers
                    for (var i = 0; i < answers.length; i++) {
                        var votes;
                        var item = answers[i];
                        item.upvote_count = 0;
                        item.downvote_count = 0;
                        if (!item['question_id']) {
                            continue;
                        }
                        answer.data[item.id] = item;
                        if (!item['users']) {
                            continue;
                        }
                        votes = item['users'];
                        for (var j = 0; j < votes.length; j++) {
                            var v = votes[j];
                            if (v['pivot'].vote === 1) {
                                item.upvote_count++;
                            }
                            if (v['pivot'].vote === 2) {
                                item.downvote_count++;
                            }
                        }
                    }
                    return answers;
                };
                answer.vote = function (conf) {
                    if (!conf.id || !conf.vote) {
                        console.log('id and vote are required');
                        return;
                    }
                    var ans = answer.data[conf.id];
                    var users = ans.users;
                    for(var i=0;i<users.length;i++){
                        //if the user has voted for one certain answer,cancel this vote
                        if(users[i].id == u.id && conf.vote == users[i].pivot.vote){
                            conf.vote = 3;
                        }else{
                            console.log('no data');
                        }
                    }
                    return $http.post('api/answer/vote', conf)
                        .then(function (result) {
                            if (result.data.status) {
                                return true;
                            } else {
                                return false;
                            }
                        }, function () {
                            return false;
                        });
                };
                answer.update_data = function (id) {
                    return $http.post('api/answer/read', {id: id})
                        .then(function (result) {
                            answer.data[id] = result.data.data;
                        })
                }
            }])
})();