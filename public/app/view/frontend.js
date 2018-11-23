'use strict';

angular.module('myApp.frontend', ['ngRoute','wu.masonry'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/:param/imagenes', {
    templateUrl: 'view/view.html',
    controller: 'FrontendController'
  });
}])

.controller('FrontendController', ['$scope','$interval', '$routeParams', '$location','$http','$timeout',function($scope,$interval,$routeParams,$location,$http,$timeout) {
    $interval(consultarImagenes, 20000);
    $interval(consultarTop, 100000);
    $scope.param = $routeParams.param;
    $scope.title = 'Evento';
    //console.log("PARAMETRO: "+$scope.param);
    $scope.ultimo= 0;
    $scope.imagenes= [];
    $scope.usuarios= [];
    $scope.ganador= { full_name: '-' };
    consultarImagenes();
    consultarTop();
    //$("#myModal").modal()
    $.get("/api/event/"+$scope.param)
        .done(function(response){
            if(response.result) {
                $scope.title = response.records.name;
            }
        });

    $scope.bonus = function(){


            $.get( "/api/notification/bonus/3")
                .done(function( response ) {
                    console.log("ENCENDER TODOS");
                    console.log(response);
                    $scope.ganador = response.records;
                    $timeout(function(){
                        $.get( "/api/notification/bonus/4")
                            .done(function( response ) {
                                console.log("APAGAR TODOS");
                                $.get( "/api/notification/winner/1/"+$scope.ganador.id, {event_id: $scope.param, description: 'Rifas'} )
                                    .done(function( response ) {
                                        console.log("ENCENDER GANADOR");
                                        $timeout(function(){
                                            $("#myModal").modal();
                                        }, 2000);
                                        $timeout(function(){
                                            $.get( "/api/notification/winner/2/"+$scope.ganador.id )
                                                .done(function( response ) {
                                                    console.log("APAGAR GANADOR");
                                                });
                                        }, 10000);
    
                                    });
                            });
                    }, 10000);
                });
    
    
        }

    $scope.raffle = function(){


        $.get( "/api/notification/raffle/3")
            .done(function( response ) {
                console.log("ENCENDER TODOS");
                console.log(response);
                $scope.ganador = response.records;
                $timeout(function(){
                    $.get( "/api/notification/raffle/4")
                        .done(function( response ) {
                            console.log("APAGAR TODOS");
                            $.get( "/api/notification/winner/1/"+$scope.ganador.id, {event_id: $scope.param, description: 'Rifas'} )
                                .done(function( response ) {
                                    console.log("ENCENDER GANADOR");
                                    $timeout(function(){
                                        $("#myModal").modal();
                                    }, 2000);
                                    $timeout(function(){
                                        $.get( "/api/notification/winner/2/"+$scope.ganador.id )
                                            .done(function( response ) {
                                                console.log("APAGAR GANADOR");
                                            });
                                    }, 10000);

                                });
                        });
                }, 10000);
            });


    }

    $scope.auto = function(){


        $.get( "/api/notification/car/3")
            .done(function( response ) {
                console.log("ENCENDER TODOS");
                console.log(response);
                $scope.ganador = response.records;
                $timeout(function(){
                    $.get( "/api/notification/car/4")
                        .done(function( response ) {
                            console.log("APAGAR TODOS");
                            $.get( "/api/notification/winner/1/"+$scope.ganador.id, {event_id: $scope.param, description: 'Carro'} )
                                .done(function( response ) {
                                    console.log("ENCENDER GANADOR");
                                    $timeout(function(){
                                        $("#myModal").modal();
                                    }, 2000);
                                    $timeout(function(){
                                        $.get( "/api/notification/winner/2/"+$scope.ganador.id )
                                            .done(function( response ) {
                                                console.log("APAGAR GANADOR");
                                            });
                                    }, 10000);

                                });
                        });
                }, 10000);
            });


    }

    // function callAtInterval(){

    //     //$scope.bricks.unshift(genBrick());
    //    consultarImagenes();
    //    //consultarTop();

    // }
    function consultarImagenes(){
        $http.get('/api/photos/'+$scope.param+'/'+$scope.ultimo)
        .then(function(response) {
            if(response.data.records.length > 0) {
                $scope.imagenes.unshift.apply($scope.imagenes, response.data.records);
                $scope.ultimo = response.data.records[response.data.records.length-1].id;
                // console.log("ULTIMO ID RECIBIDO "+$scope.ultimo);
                // console.log(response.data.records[response.data.records.length - 1]);
            }
        });
    }
    function consultarTop(){
        $http.get('/api/top/'+$scope.param)
        .then(function(response) {
            if(response.data.records.length > 0) {
                //console.log('Actualizando TOP');
                $scope.usuarios = response.data.records;

            }
        });
    }
    function genBrick() {
        var height = ~~(Math.random() * 100) + 100;
        var id = ~~(Math.random() * 10000);
        //console.log(height);
        return {
            //src: 'http://lorempixel.com/g/280/' + height + '/?' + id,
            src:'https://s3-us-west-2.amazonaws.com/s.cdpn.io/82/orange-tree.jpg'
        };
    };

    $scope.bricks = [
        genBrick(),
        genBrick(),
        genBrick(),
        genBrick(),
        genBrick()
    ];

    $scope.add = function add() {
        $scope.bricks.unshift(genBrick());

    };

    $scope.remove = function remove() {
        $scope.bricks.splice(
            ~~(Math.random() * $scope.bricks.length),
            1
        )
    };


}]);