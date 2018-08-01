;(function () {
    'use strict';

    angular.module('common', [])

        .service('TimelineService', [
            '$http',
            'AnswerService',
            function ($http, AnswerService) {
                var me = this;
                me.data = [];
                me.current_page = 1;
                me.no_more_data = false;
                
                /** 获取首页数据 */
                me.get = function (config) {
                    if (me.pending || me.no_more_data) return;

                    me.pending = true;

                    config = config || {page: me.current_page};

                    $http.post('/api/timeline', config)
                        .then(function (r) {
                            if (r.data.status) {
                                if (r.data.data.data.length) {
                                    me.data = me.data.concat(r.data.data.data);
                                    me.data = AnswerService.count_vote(me.data);
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
                };
                
                
                /** 统计票数 */
                me.vote = function (config) {

                    /* 调用统计票数函数 */
                    var $r = AnswerService.vote(config);
                    if ($r)
                        $r.then(function (r) {

                            /* 返回数据, 如果投票成功 */
                            if (r) {
                                AnswerService.update_data(config.id);
                            }
                        })
                };

            }
        ])


        .controller('HomeController', [
            '$scope',
            'TimelineService',
            'AnswerService',
            function ($scope, TimelineService, AnswerService) {
                $scope.Timeline = TimelineService;
                TimelineService.get();

                var $win = $(window);

                
                /** 自动加载 Load automatically */
                $win.on('scroll', function () {
                    // $win.scrollTop() - ($(document).height() - $win.height()) > -30
                    if ($win.scrollTop() == ($(document).height() - $win.height()))
                        TimelineService.get();
                });


                /** 监控赞/踩数目 monitor upvote / downvote */
                $scope.$watch(function () {

                    return AnswerService.data;

                }, function (new_data, old_data) {

                    var timeline_data = TimelineService.data;

                    for (var k in new_data) {

                        /* Update Timeline.data */
                        for (var i = 0; i < timeline_data.length; i++) {
                            if (k == timeline_data[i].id) {
                                timeline_data[i].users = new_data[k].users;
                            }
                        }
                    }

                    TimelineService.data = AnswerService.count_vote(TimelineService.data);
                    
                }, true);


            }
        ])
    
    
})();