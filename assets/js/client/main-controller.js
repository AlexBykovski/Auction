(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$scope', '$http', '$interval', '$rootScope', 'UpdateService',
        function($scope, $http, $interval, $rootScope, UpdateService) {
        var self = this;
        this.currentAuctions = [];

        function init(currentAuctions){
            self.currentAuctions = angular.fromJson(currentAuctions);

            $(".product-offers-list").ready(function(){
                $(".time-countdown").each(function(index, el){
                    $(this).countdown(self.currentAuctions[$(el).attr("element-key")].timeEnd, function(event) {
                        $(this).text(
                            event.strftime('%H:%M:%S')
                        );
                    });
                });

                var stop = $interval(function() {
                    UpdateService.updateProducts(Object.keys(self.currentAuctions), function(response){
                        if(!response.success){
                            return console.error("Wrong response");
                        }

                        if(response.user){
                            $rootScope.$broadcast('update-user', {user: response.user});
                        }

                        self.currentAuctions = response.auctions;

                        $(".time-countdown").each(function(index, el){
                            $(this).countdown(self.currentAuctions[$(el).attr("element-key")].timeEnd, function(event) {
                                $(this).text(
                                    event.strftime('%H:%M:%S')
                                );
                            });
                        });

                    });
                }, 1000);
            });
        }

        function makeStake(productId){
            $http({
                method: 'POST',
                url: "/make-manual-stake/" + productId
            }).then(function (response) {
                console.log("OK");
                //WebSocketService.send(productId);
            }, function (response) {
                console.error("error");
            });
        }

        this.init = init;
        this.makeStake = makeStake;
    }]);
})(window.appAuction);