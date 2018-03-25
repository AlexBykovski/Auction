(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$scope', '$http', 'WebSocketService', function($scope, $http, WebSocketService) {
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
            });
        }

        function makeStake(productId){
            $http({
                method: 'POST',
                url: "/make-manual-stake/" + productId
            }).then(function (response) {
                console.log("OK");
                WebSocketService.send(productId);
            }, function (response) {
                console.error("error");
            });
        }

        this.init = init;
        this.makeStake = makeStake;
    }]);
})(window.appAuction);