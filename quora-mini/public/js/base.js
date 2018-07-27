;(function () {
    'use strict';

    angular.module('quora-mini', [
        'ui.router'
    ])
        .config(function ($interpolateProvider,
                          $stateProvider,
                          $urlRouterProvider) {
            
            $interpolateProvider.startSymbol('[:');
            $interpolateProvider.endSymbol(':]');

            $urlRouterProvider.otherwise('/home');

            $stateProvider
                .state('home', {
                    url: '/home',
                    templateUrl: 'home.tpl'
                })
                .state('login', {
                    url: '/login',
                    templateUrl: 'login.tpl'
                })
                .state('signup', {
                    url: '/signup',
                    templateUrl: 'signup.tpl'
                })
                .state('question', {
                    abstract: true,
                    url: '/question',
                    template: '<div ui-view></div>'
                })
                .state('question.add', {
                    url: '/add',
                    templateUrl: 'question.add.tpl'
                })
        })


        .service('UserService', [
            '$http', '$state',
            function ($http, $state) {
                var me = this;
                me.signup_data = {};
                me.login_data = {};
                
                me.signup = function () {
                    $http.post('api/signup', me.signup_data)
                        .then(function (r) {
                            if (r.data.status) {
                                me.signup_data = {};
                                $state.go('login');
                            }
                        })
                };
                
                
                me.login = function () {
                    $http.post('api/login', me.login_data)
                        .then(function (r) {
                            if(r.data.status) {
                                location.href = '/';
                            } else {
                                me.login_failed = true;
                            }
                        }, function () {
                            
                        })
                };
                
                
                me.username_exists = function () {
                    $http.post('/api/user/exist',
                        {username: me.signup_data.username})
                        .then(function (r) {
                            if (r.data.status && r.data.data.count)
                                me.signup_username_exists = true;
                            else
                                me.signup_username_exists = false;
                        }, function (e) {
                            console.log('e', e);
                        })
                };
        }])

        
        .controller('SignupController', [
            '$scope',
            'UserService',
            function ($scope, UserService) {
                $scope.User = UserService;

                $scope.$watch(function () {
                    return UserService.signup_data;
                }, function (n, o) {
                    if (n.username != o.username)
                        UserService.username_exists();
                }, true);
        }])
        
        
        .controller('LoginController', [
            '$scope',
            'UserService',
            function ($scope, UserService) {
                $scope.User = UserService;
            }
        ])
    
        
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
        
        .service('TimelineService', [
            '$http',
            function ($http) {
                var me = this;
                me.data = [];
                me.current_page = 1;


                me.get = function (config) {
                    if (me.pending) return;

                    me.pending = true;

                    config = config || {page: me.current_page};

                    $http.post('/api/timeline', config)
                        .then(function (r) {
                            if (r.data.status) {
                                if (r.data.data.data.length) {
                                    me.data = me.data.concat(r.data.data.data);
                                    me.current_page++;
                                } else {
                                    me.no_more_data = true;
                                }
                                
                            } else {
                                console.error('Network error!');
                            }
                        }, function () {
                            console.error('Network error!');
                        })

                        .finally(function () {
                            me.pending = false;
                        })
                }
            }
        ])
        
        
        .controller('HomeController', [
            '$scope',
            'TimelineService',
            function ($scope, TimelineService) {
                $scope.Timeline = TimelineService;
                TimelineService.get();

                var $win = $(window);

                $win.on('scroll', function () {
                    if ($win.scrollTop() - ($(document).height() - $win.height()) > -30)
                        TimelineService.get();
                })


            }
        ])
    
})();