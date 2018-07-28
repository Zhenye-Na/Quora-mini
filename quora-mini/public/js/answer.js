;(function () {
    'use strict';

    angular.module('answer', [])

        .service('AnswerService', [
            '$http',
            function ($http) {
                var me = this;
                me.data = {};


                /** 统计 vote 票数
                 *  @param: answers 问题 || 回答 的数据
                 *  @return: answers 加上票数统计, 不统计问题票数
                 * */
                me.count_vote = function (answers) {

                    for (var i = 0; i < answers.length; i++) {

                        var votes, item = answers[i];

                        if (!item['question_id']) {
                            continue;
                        }

                        me.data[item.id] = item;

                        if (!item['users']) {
                            continue;
                        }

                        votes = item['users'];

                        item.upvote_count = 0;
                        item.downvote_count = 0;

                        for (var j = 0; j < votes.length; j++) {
                            var v = votes[j];

                            if (v['pivot'].vote == 1) {
                                item.upvote_count++;
                            }
                            if (v['pivot'].vote == 2) {
                                item.downvote_count++;
                            }
                        }

                    }
                    return answers;
                };


                /** 在 Timeline 首页中点赞/踩 
                 *  @param: config 传参
                 * */
                me.vote = function (config) {

                    if (!config.id || !config.vote) {
                        console.log('id and vote is required');
                        return;
                    }

                    var answer = me.data[config.id];

                    /* 判断当前用户是否已经点过赞 */
                    for (var i = 0; i < answer.users.length; i++) {
                        if (answer.users[i].id == his.id && config.vote == answer.users[i].pivot.vote) {
                            config.vote = 3;
                        }
                    }

                    return $http.post('/api/answer/vote', config)
                        .then(function (r) {
                            return r.data.status;
                        }, function () {
                            return false;
                        })

                };


                /** 实时更新 */
                me.update_data = function (id) {

                    return $http.post('/api/answer/read', {id: id})
                        .then(function (r) {
                            me.data[id] = r.data.data.data;
                        });
                    
                };
                
                
                /** Read answer */
                me.read = function (param) {
                    return $http.post('/api/answer/read', param)
                        .then(function (r) {
                            if (r.data.status) {
                                me.data = angular.merge({}, me.data, r.data.data);
                            }
                            return r.data.data;
                        })
                }

            }
        ])


})();