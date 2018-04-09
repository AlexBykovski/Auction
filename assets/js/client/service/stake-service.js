(function(appAuction) {
    'use strict';

    appAuction.factory('StakeService', ['$http', '$rootScope', '$interval', 'UpdateService',
        function($http, $rootScope, $interval, UpdateService) {
            return {
                updateAuctions: function(mainSelector, auctions, callback){
                    var self = this;

                    var stop = $interval(function() {
                        UpdateService.updateProducts(Object.keys(auctions), function(response){
                            if(!response.success){
                                return console.error("Wrong response");
                            }

                            if(response.user){
                                $rootScope.$broadcast('update-user', {user: response.user});
                            }

                            self.updateTimeCountDown(mainSelector, response.auctions);

                            callback(response.auctions);
                        });
                    }, 1000);
                },
                updateSingleAuction: function(mainSelector, auction, callback){
                    var self = this;

                    var stop = $interval(function() {
                        UpdateService.updateSingleProduct(auction.id, function(response){
                            if(!response.success){
                                return console.error("Wrong response");
                            }

                            if(response.user){
                                $rootScope.$broadcast('update-user', {user: response.user});
                            }

                            var arrayToUpdate = [];
                            arrayToUpdate[response.auction.id] = response.auction;

                            self.updateTimeCountDown(mainSelector, arrayToUpdate);

                            callback(response.auction);
                        });
                    }, 1000);
                },
                updateTimeCountDown: function(mainSelector, auctions){
                    $(mainSelector + " .time-countdown").each(function(index, el){
                        if(auctions[$(el).attr("element-key")] && auctions[$(el).attr("element-key")].isProcessing) {
                            $(this).countdown(auctions[$(el).attr("element-key")].timeEnd, function (event) {
                                $(this).text(
                                    event.strftime('%H:%M:%S')
                                );
                            });
                        }
                        if(auctions[$(el).attr("element-key")] && auctions[$(el).attr("element-key")].isSoon) {
                            $(this).countdown(auctions[$(el).attr("element-key")].startAt, function (event) {
                                var totalHours = event.offset.totalDays * 24 + event.offset.hours;

                                $(this).text(
                                    event.strftime(totalHours + ':%M:%S')
                                );
                            });
                        }
                    });
                },
                makeStake: function(productId){
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
            };
    }]);

})(window.appAuction);