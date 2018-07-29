;(function () {
    'use strict';
    
    angular.module('question', [])

        .service('QuestionService', [
            '$http',
            '$state',
            function ($http, $state) {
                var me = this;
                me.new_question = {};

                
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
                                me.data = angular.merge({}, me.data, r.data.data);
                                return r.data.data;
                            }
                            
                            return false;
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