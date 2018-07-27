;(function () {
    'use strict';

    angular.module('common', [])

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
                    // $win.scrollTop() - ($(document).height() - $win.height()) > -30
                    if ($win.scrollTop() == ($(document).height() - $win.height()))
                        TimelineService.get();
                })

            }
        ])
    
    
})();