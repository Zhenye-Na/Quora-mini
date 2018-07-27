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

                        /*  */
                        if (!item['question_id'] || !item['users']) {
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

                    return $http.post('/api/answer/vote', config)
                        .then(function (r) {
                            if (r.data.status) {
                                return true;
                            }
                            return false;

                        }, function () {
                            return false;
                        })

                };


                /** 实时更新 */
                me.update_data = function (id) {

                    return $http.post('/api/answer/read', {id: id})
                        .then(function (r) {
                            me.data[id] = r.data.data;
                        });
                    
                }

            }
        ])


})();