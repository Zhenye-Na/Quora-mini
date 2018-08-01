;(function () {
    'use strict';
    
    angular.module('question', [])

        .service('QuestionService', [
            '$http',
            '$state',
            'AnswerService',
            function ($http, $state, AnswerService) {
                var me = this;
                me.new_question = {};
                me.data = {};


                
                /** Jump to new page which supports creating questions */
                me.go_add_question = function () {
                    $state.go('question.add');
                };

                
                /** Create question */
                me.add = function () {
                    if (!me.new_question.title)
                        return;

                    $http.post('/api/question/add', me.new_question)
                        .then(function (r) {
                            if (r.data.status) {
                                console.log(r.data);
                                me.new_question = {};
                                $state.go('home', {}, {reload: true});
                            }
                        }, function (e) {
                            console.log('e', e);
                        })
                };
                
                
                /** Read question */
                me.read = function (params) {
                    return $http.post('/api/question/read', params)
                        .then(function (r) {
                            if (r.data.status) {
                                if (params.id) {
                                    me.data[params.id] = r.data.data;
                                    me.current_question = r.data.data;

                                    me.its_answers = me.current_question.answers_with_user_info;
                                    me.its_answers = AnswerService.count_vote(me.its_answers);
                                } else {
                                    me.data = angular.merge({}, me.data, r.data.data);
                                }
                                return r.data.data;
                            }
                            
                            return false;
                        })
                };


                /** Vote answers */
                me.vote = function (config) {

                    /* 调用统计票数函数 */
                    var $r = AnswerService.vote(config);
                    if ($r)
                        $r.then(function (r) {

                            /* 返回数据, 如果投票成功 */
                            if (r) {
                                me.update_answer(config.id);
                            }
                        })
                };


                /** Update votes of answers */
                me.update_answer = function (answer_id) {
                    $http.post('/api/answer/read', {id: answer_id})
                        .then(function (r) {
                            console.log('r', r);
                            if (r.data.status) {
                                for (var i = 0; i < me.its_answers.length; i++) {
                                    var answer = me.its_answers[i];
                                    if (answer.id == answer_id) {
                                        me.its_answers[i] = r.data.data.data;
                                        AnswerService.data[answer_id] = r.data.data.data;
                                    }
                                }
                            }
                        })
                };

            }
        ])


        .controller('QuestionController', [
            '$scope',
            'QuestionService',
            function ($scope, QuestionService) {
                $scope.Question = QuestionService;
            }
        ])


        .controller('QuestionAddController', [
            '$scope',
            'QuestionService',
            function ($scope, QuestionService) {
                $scope.Question = QuestionService;
            }
        ])


        .controller('QuestionDetailController', [
            '$scope',
            '$stateParams',
            'QuestionService',
            'AnswerService',
            function ($scope,
                      $stateParams,
                      QuestionService,
                      AnswerService) {
                $scope.Answer = AnswerService;
                QuestionService.read($stateParams);
                
                if ($stateParams.answer_id) {
                    QuestionService.current_answer_id = $stateParams.answer_id;
                } else {
                    QuestionService.current_answer_id = null;
                }
            }
        ])
    
})();