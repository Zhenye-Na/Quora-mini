;(function () {
    'use strict';
    
    angular.module('question', [])

        .service('QuestionService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.new_question = {};

                me.go_add_question = function () {
                    $state.go('question.add');
                };

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
                }
            }
        ])


        .controller('QuestionAddController', [
            '$scope',
            'QuestionService',
            function ($scope, QuestionService) {
                $scope.Question = QuestionService;
            }
        ])
    
    
})();