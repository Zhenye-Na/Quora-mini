;(function () {
    'use strict';

    angular.module('answer', [])

        .service('AnswerService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.data = {};
                me.answer_form = {};


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
                        // console.log('id and vote is required');
                        alert("id and vote is required");
                        return;
                    }

                    var answer = me.data[config.id];

                    if (answer.user_id == his.id) {
                        return false;
                    }
                    
                    /* 判断当前用户是否已经点过赞 */
                    for (var i = 0; i < answer.users.length; i++) {
                        if (answer.users[i].id == his.id && config.vote == answer.users[i].pivot.vote) {
                            config.vote = 3;
                        }
                    }

                    return $http.post('/api/answer/vote', config)
                        .then(function (r) {
                            if (isNaN(his.id)) {
                                alert("Please log in first!");
                                $state.go('login');
                            }
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
                };


                /** Add / Update answer */
                me.add_or_update = function (question_id) {
                    if (!question_id) {
                        console.error('question_id is missing!');
                        return;
                    }

                    me.answer_form.question_id = question_id;
                    if (me.answer_form.id) {
                        // Update answer
                        $http.post('/api/answer/change', me.answer_form)
                            .then(function (r) {
                                me.answer_form = {};
                                $state.reoad();

                            })
                    } else {
                        // Add answer
                        $http.post('/api/answer/add', me.answer_form)
                            .then(function (r) {
                                me.answer_form = {};

                            })
                    }
                };


                /** Delete answer */
                me.delete = function (id) {
                    if (!id) {
                        console.error('answer id is missing!');
                        return;
                    }

                    $htttp.post('/api/answer/remove', {id: id})
                        .then(function (r) {
                            if (r.data.status) {
                                console.log('Delete successfully!');
                                $state.reload();
                            }
                        })
                };


                /** Add comment */
                me.add_comment = function () {
                    return $http.post('/api/comment/add', me.new_comment)
                        .then(function (r) {
                            return r.data.status;
                        })
                }

            }
        ])

    
        .directive('commentBlock', [
            '$http',
            'AnswerService',
            function ($http, AnswerService) {
                var o = {};
                o.templateUrl = 'comment.tpl';
                
                o.scope = {
                    answer_id: '=answerId'

                };

                o.link = function (sco, ele, attr) {
                    sco.Answer = AnswerService;

                    sco.comment_create = {};
                    sco.data = {};
                    sco.helper = helper;

                    function get_comment_list() {
                        return $http.post('/api/comment/read', {answer_id: sco.answer_id})
                            .then(function (r) {
                                if (r.data.status)
                                    sco.data = angular.merge({}, sco.data, r.data.data);
                            });
                    }


                    if (sco.answer_id) {
                        get_comment_list();
                    }

                    sco.comment_create.add_comment = function () {
                        AnswerService.new_comment.answer_id = sco.answer_id;
                        AnswerService.add_comment()
                            .then(function (r) {
                                if (r) {
                                    AnswerService.new_comment = {};
                                    get_comment_list();
                                }

                            })
                    }

                };
                return o;
            }
        ])

})();