angular_app.controller('metricsCtrl', ['$scope', '$http', '$window', '$rootScope', '$timeout', '$cookieStore', '$sce', function($scope, $http, $window, $rootScope, $timeout, $cookieStore, $sce) {

    /**
     * #Review: maybe we should use either camel-case or underscores, but probably not both - not urgent but helps with readability
     */
    $scope.urlBase            =  wpAngularVars.base;
    $scope.showConfig         = false;
    $scope.loadList           = 1;
    $scope.loadInvolvedList   = 1;
    $scope.frm                = {};
    $scope.over_last_day      = {};
    $scope.how_many_articles  = {};
    $scope.frm.hourly_wage           = 0;
    $scope.frm.overtime_wage         = 0;
    $scope.frm.total_hours_per_month = 0;
    $scope.user               = {};
    $scope.user.total_hours_used = 0;
    $scope.user.id            = 0;
    $scope.user.total_second  = 0;
    $scope.user.long_takes_to_write  = 0;
    $scope.user.articles_day  = 0;
    $scope.user.total_hours   = 0;
    $scope.user.hourly_wage   = 0;
    $scope.user.overtime_wage = 0;
    $scope.user.total_hours_per_month = 0;
    $scope.user.display_name  = '';
    $scope.user.reverse  = false;
    $scope.showListContentByAuthor = false;
    $scope.showListContentByEditor = false;
    $scope.showListContentInvolved = false;
    $scope.showHowLongTakesToWrite = false;
    $scope.showHowManyArticlesDay = false;
    $scope.not_overtime_wage   = true;

    $scope.start_timer_author = 0;
    $scope.stopTime           = false;
    $scope.num_changed        = 0;
    $scope.hour_in_second     = 0.000277778;
    $scope.last_content = '';
    $scope.current_content = '';
    $scope.diff = 0;

    $scope.dataLongTakesToWrite = [{
        "key" : "Quantity" ,
        "bar": true,
        "values" : []
    }];

    $scope.dataChart = [
        {
            "key" : "Quantity" ,
            "bar": true,
            "values" :  []
        }
    ];
    $scope.graphGeneral = [

        {
            "key" : "Cost" ,
            "values" :[]
        }
    ];

    $scope.optionsGeneral = {
        chart: {
            type: 'historicalBarChart',
            height: 450,
            x: function(d){return d[0]* 1000;},
            y: function(d){return d[1];},
            showValues: false,
            valueFormat: function(d){
                return d3.format('d');
            },
            xAxis: {
                axisLabel: 'Date',
                tickFormat: function(d) {
                    return d3.time.format('%x')(new Date(d))
                },
                staggerLabels: true
            },
            yAxis: {
                axisLabel: 'Number of articles published',
                tickFormat: d3.format("d"),
                showMaxMin: false

            },

            padData: true,
            tooltip: {
                keyFormatter: function(d) {
                    return d3.time.format('%x')(new Date(d));
                }
            },
        }
    };

    $scope.optionsGraphGeneral = {
        chart: {
            type: 'historicalBarChart',
            height: 450,
            x: function(d){return d[0]* 1000;},
            y: function(d){return d[1];},
            showValues: true,
            valueFormat: d3.format('$,.2f'),
            xAxis: {
                axisLabel: 'Date',
                tickFormat: function(d) {
                    return d3.time.format('%x')(new Date(d))
                },
                staggerLabels: true
            },
            yAxis: {
                axisLabel: 'Cost',
                tickFormat: d3.format('$,.2f')
            },
            padData: true,
            tooltip: {
                keyFormatter: function(d) {
                    return d3.time.format('%x')(new Date(d));
                }
            }
        }
    };

    $scope.options  = {
        chart: {
            type: 'multiBarChart',
            showControls: false,
            height: 450,
            margin : {
                top: 20,
                right: 20,
                bottom: 45,
                left: 45
            },
            clipEdge: true,
            staggerLabels: true,
            duration: 500,
            stacked: false,
            xAxis: {
                axisLabel: 'Time (days)',
                showMaxMin: false,
                tickFormat:  d3.format(',f')
            },
            yAxis: {
                axisLabel: 'Articles written',
                axisLabelDistance: -25,
                tickFormat: d3.format(',f')
            }
        }
    };

    $scope.optionsLongTakesToWrite = {
        chart: {
            type: 'historicalBarChart',
            height: 450,
            x: function(d){return d[0]* 1000;},
            y: function(d){return d[1];},
            showValues: true,
            valueFormat:  d3.format(',.2f'),
            xAxis: {
                axisLabel: 'Date',
                tickFormat: function(d) {
                    return d3.time.format('%x')(new Date(d))
                },
                staggerLabels: true
            },
            yAxis: {
                axisLabel: 'time (hours)',
                tickFormat: d3.format(',.2f'),
            },
            padData: true,
            tooltip: {
                keyFormatter: function(d) {
                    return d3.time.format('%x')(new Date(d));
                }
            }
        }
    };

    $scope.headPanels = true;

    $scope.viewUser = function ( params , display_name ) {
        $scope.user = {};
        $scope.frm = {};
        return $http({
            method: 'GET',
            url: $scope.urlBase ,
            params: {
                "action" : 'get_config',
                "id"     : params,
                "isAuthor" : true
            }
        }).
        success(function(data, status, headers, config) {
            $scope.showConfig = true;
            $scope.frm.hourly_wage           = data.hourly_wage;
            $scope.frm.overtime_wage         = data.overtime_wage;
            $scope.frm.total_hours_per_month = data.total_hours_per_month;
            $scope.user.display_name  = display_name;
            $scope.user.id = params;
        }).
        error(function(data, status, headers, config) {
            window.alert("We have been unable to access the feed :-(");
        });
    };

    $scope.showPanels = function ( ) {
        $scope.headPanels = !$scope.headPanels;
    };

    $scope.closeWinConfig = function ( ) {
        $scope.showConfig = !$scope.showConfig;
    };

    $scope.closeListContentByAuthor = function ( ) {
        $scope.showListContentByAuthor = !$scope.showListContentByAuthor;
    };

    $scope.closeListContentByEditor = function ( ) {
        $scope.showListContentByEditor = !$scope.showListContentByEditor;
    };

    $scope.closeListContentInvolved = function ( ) {
        $scope.showListContentInvolved = !$scope.showListContentInvolved;
    };
    $scope.closeHowLongTakesToWrite = function ( ) {
        $scope.showHowLongTakesToWrite = !$scope.showHowLongTakesToWrite;
    };

    $scope.closeHowManyArticlesDay = function ( ) {
        $scope.showHowManyArticlesDay = !$scope.showHowManyArticlesDay;
    };

    $scope.saveWinConfig = function ( params , user ) {
        return $http({
            method: 'GET',
            url: $scope.urlBase ,
            params: {
                "action" : 'update_config',
                "values" : params,
                "id" : user.id,
                "isAuthor" : true
            }
        }).
        success(function(data, status, headers, config) {
            $scope.showConfig = false;
        }).
        error(function(data, status, headers, config) {
            window.alert("We have been unable to access the feed :-(");
        });
    };

    $scope.listAllContentAuthor = function ( params, display_name) {
        $scope.user       = {};
        $scope.user.id = params;
        $scope.user.display_name = display_name;
        $scope.showListContentByAuthor = true;
        $scope.headPanels = false;

        $timeout(function () {
            $scope.loadList ++;
        },1300);
    };

    $scope.listAllContentEditor = function ( params, display_name) {
        $scope.user       = {};
        $scope.user.id = params;
        $scope.user.display_name = display_name;
        $scope.showListContentByEditor = true;
        $scope.headPanels = false;

        $timeout(function () {
            $scope.loadList ++;
        },1300);
    };

    $scope.listAllInvolvedContent = function ( params, display_name) {
        $scope.user       = {};
        $scope.user.id = params;
        $scope.user.display_name = display_name; 
        $scope.showListContentInvolved = true;
        $scope.headPanels = false;

        $timeout(function () {
            $scope.loadInvolvedList ++;
        },1300);
    };

    $scope.howLongTakesToWrite = function ( params, display_name) {
        $scope.user       = {};
        $scope.user.id = params;
        $scope.user.display_name = display_name;
        $scope.showHowLongTakesToWrite = true;
        $scope.headPanels = false;
        $scope.dataLongTakesToWrite = [
            {
                "key" : "Quantity" ,
                "bar": true,
                "values" :  []
            }];
        return $http({
            method: 'GET',
            url: $scope.urlBase ,
            params: {
                "action" : 'get_long_takes_to_write',
                "values" : params,
                "isAuthor" : true
            }
        }).
        success(function(data, status, headers, config) {
            $scope.over_last_day.over_last_7  = data.results.over_last_7;
            $scope.over_last_day.over_last_14 = data.results.over_last_14;
            $scope.over_last_day.over_last_28 = data.results.over_last_28;

            $scope.dataLongTakesToWrite = [
                {
                    "key" : "Quantity" ,
                    "bar": true,
                    "values" :  data.results.graph
                }];
        }).
        error(function(data, status, headers, config) {
            window.alert("We have been unable to access the feed :-(");
        });
    };

    $scope.howManyArticlesDay = function ( params, display_name) {
        $scope.user       = {};
        $scope.user.id = params;
        $scope.user.display_name = display_name;
        $scope.showHowManyArticlesDay = true;
        $scope.data = [];
        return $http({
            method: 'GET',
            url: $scope.urlBase ,
            params: {
                "action" : 'get_many_articles_day',
                "values" : params,
                "isAuthor" : true
            }
        }).
        success(function(data, status, headers, config) {
            $scope.how_many_articles.over_last_7  = data.results.over_last_7;
            $scope.how_many_articles.over_last_publish_7  = data.results.over_last_publish_7 ;
            $scope.how_many_articles.over_last_total_7  =  parseInt(data.results.over_last_publish_7) + parseInt(data.results.over_last_7);
            $scope.how_many_articles.over_last_14 = data.results.over_last_14;
            $scope.how_many_articles.over_last_publish_14 = data.results.over_last_publish_14;
            $scope.how_many_articles.over_last_total_14  =  parseInt(data.results.over_last_publish_14) + parseInt(data.results.over_last_14);
            $scope.how_many_articles.over_last_28 = data.results.over_last_28;
            $scope.how_many_articles.over_last_publish_28 = data.results.over_last_publish_28;
            $scope.how_many_articles.over_last_total_28  =  parseInt(data.results.over_last_publish_28) + parseInt(data.results.over_last_28) ;
            $scope.how_many_articles.total = $scope.how_many_articles.over_last_total_7 + $scope.how_many_articles.over_last_total_14 + $scope.how_many_articles.over_last_total_28;



            $scope.data = [{
                "values" : [{
                    "y" : parseInt($scope.how_many_articles.over_last_publish_7),
                    "x" : "7"
                }, {
                    "y" : parseInt($scope.how_many_articles.over_last_publish_14),
                    "x" : "14"
                }, {
                    "y" : parseInt($scope.how_many_articles.over_last_publish_28),
                    "x" : "28"
                }],
                "key" : "Published"
            }, {
                "values" : [{
                    "y" : parseInt($scope.how_many_articles.over_last_7),
                    "x" : "7"
                }, {
                    "y" : parseInt($scope.how_many_articles.over_last_14),
                    "x" : "14"
                }, {
                    "y" : parseInt($scope.how_many_articles.over_last_28),
                    "x" : "28"
                }],
                "key" : "Unpublished"
            }]
        }).
        error(function(data, status, headers, config) {
            window.alert("We have been unable to access the feed :-(");
        });
    };

    $scope.general = {};
    $scope.general = {};
    $scope.general.over_last_7          = 0;
    $scope.general.over_last_total_7    = 0;
    $scope.general.over_last_publish_7  = 0;
    $scope.general.over_last_14         = 0;
    $scope.general.over_last_publish_14 = 0;
    $scope.general.over_last_publish_14 = 0;
    $scope.general.over_last_28         = 0;
    $scope.general.over_last_publish_28 = 0;
    $scope.general.over_last_publish_28 = 0;
    $scope.general.graphGeneral         = [];
    $scope.general.articles_list        = [];
    $scope.dataChart = [
        {
            "key" : "Quantity" ,
            "bar": true,
            "values" :  $scope.general.articles_list
        }
    ];
    $scope.graphGeneral = [

        {
            "key" : "Cost" ,
            "values" : $scope.general.graphGeneral
        }
    ];

    $scope.init = function () {
        return $http({
            method: 'GET',
            url: $scope.urlBase ,
            params: {
                "action" : 'get_start_info'
            }
        }).
        success(function(data, status, headers, config) {

            $scope.general.over_last_7          = data.results.over_last_7;
            $scope.general.over_last_publish_7  = data.results.over_last_publish_7;
            $scope.general.over_last_total_7   =  parseInt(data.results.over_last_7 + data.results.over_last_publish_7);
            $scope.general.over_last_14         = data.results.over_last_14;
            $scope.general.over_last_publish_14 = data.results.over_last_publish_14;
            $scope.general.over_last_total_14   = parseInt(data.results.over_last_14 + data.results.over_last_publish_14);
            $scope.general.over_last_28         = data.results.over_last_28;
            $scope.general.over_last_publish_28 = data.results.over_last_publish_28;
            $scope.general.over_last_total_28   =  parseInt(data.results.over_last_28 + data.results.over_last_publish_28);
            $scope.general.graphGeneral         = data.results.graph;
            $scope.general.articles_list        = data.results.articles_list;

            if(angular.isDefined($scope.general)){
                $scope.dataChart = [
                    {
                        "key" : "Quantity" ,
                        "bar": true,
                        "values" :  $scope.general.articles_list
                    }
                ];

                $scope.graphGeneral = [
                     {
                        "key" : "Price" ,
                        "bar": true,
                        "values" : $scope.general.graphGeneral
                    }
                ];

            }


        }).
        error(function(data, status, headers, config) {
            window.alert("We have been unable to access the feed :-(");
        });
    };
    $scope.init();
    $scope.startTimer = false;

    // this will set the expiration to 12 months
    var now = new $window.Date();
    $scope.exp = new Date(now.getFullYear()+1, now.getMonth(), now.getDate());

    $cookieStore.put('timer_author',0, {expires: $scope.exp});
    $cookieStore.put('num_changed_author',0, {expires: $scope.exp});
    $scope.timerOut = 0;
    $scope.tinymceReadyCheck = function(){
        if(  $scope.startTimer &&  'undefined' !== typeof(tinyMCE)  && tinyMCE.activeEditor){
            clearInterval($scope.tinymce_ready_func);
            var myEl = angular.element( document.querySelector( '#content' ) );
            var ariaHidden = myEl.attr("aria-hidden");
            if( 'true' == ariaHidden ){
                //initialize the content vars
                $scope.old_content     = tinyMCE.activeEditor.getContent({format : 'text'});
                $scope.last_content    = tinyMCE.activeEditor.getContent({format : 'text'});
                $scope.current_content = tinyMCE.activeEditor.getContent({format : 'text'});

                tinyMCE.activeEditor.onChange.add(function(textbox,event){
                    $scope.current_content = tinyMCE.activeEditor.getContent({format : 'text'});
                });
                tinyMCE.activeEditor.onKeyPress.add(function(textbox,event){
                    $scope.current_content = tinyMCE.activeEditor.getContent({format : 'text'});
                });
            }
            else{
                //initialize the content vars
                $scope.old_content     = angular.element('#content').val();
                $scope.last_content    = angular.element('#content').val();
                $scope.current_content = angular.element('#content').val();

                angular.element('#content').on("keydown keypress", function() {
                    $scope.current_content =  angular.element('#content').val();
                });
                angular.element('#content').on('change', function (changeEvent) {
                    $scope.current_content =  changeEvent.target.value;
                });
            }
            var check_for_changes_func = setInterval($scope.changesCheck, 1000);
        }else if( $scope.startTimer &&  4 < $scope.timerOut && angular.element( document.querySelector( '#content' ) )){
            clearInterval($scope.tinymce_ready_func);
            //initialize the content vars
            $scope.old_content     = angular.element('#content').val();
            $scope.last_content    = angular.element('#content').val();
            $scope.current_content = angular.element('#content').val();

            angular.element('#content').on("keydown keypress", function() {
                $scope.current_content =  angular.element('#content').val();
            });
            angular.element('#content').on('change', function (changeEvent) {
                $scope.current_content =  changeEvent.target.value;
            });
            var check_for_changes_func = setInterval($scope.changesCheck, 1000);
        }else if( $scope.startTimer && 4 >= $scope.timerOut){
            /**
             * #REVIEW: this probably comes down to coding style, but $scope.timerOut ++; has an unnecessary space, $scope.timerOut++; is how it is usually done.
             */
            $scope.timerOut ++;
        }
};

    $scope.changesCheck =  function(){
        if($scope.current_content != $scope.last_content){
            $scope.start_timer_author ++;
            $cookieStore.put('timer_author',$scope.start_timer_author, {expires: $scope.exp});
            $scope.last_content = $scope.current_content;
        }
    };

    angular.element(document).ready(function () {
        if( 'undefined' !== typeof(angular.element( document.querySelector( '#content' ))) ){
            $scope.startTimer = true;
        }
        $scope.tinymce_ready_func = setInterval($scope.tinymceReadyCheck, 1000);
        angular.element('#content-tmce').on('click', function (changeEvent) {
            $scope.tinymce_ready_func = setInterval($scope.tinymceReadyCheck, 1000);
        });
        angular.element('#content-html').on('click', function (changeEvent) {
            $scope.tinymce_ready_func = setInterval($scope.tinymceReadyCheck, 1000);
        });
        angular.element('#publish').on('click', function (changeEvent) {
            $scope.diff += Math.abs($scope.old_content.replace(/[\s\r\n]{1,}/g,'').length - $scope.current_content.replace(/[\s\r\n]{1,}/g,'').length);
            $cookieStore.put('num_changed_author',$scope.diff, {expires: $scope.exp});
        });
    });

}]);