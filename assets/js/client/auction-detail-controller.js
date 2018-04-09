(function(appAuction) {
    'use strict';

    appAuction.controller('AuctionDetailController', ['StakeService',
        function(StakeService) {
            var self = this;
            var mainSelector = ".product-auction-bet-form-with-autostake";

            this.auction = {};

            function init(auction){
                self.auction = angular.fromJson(auction);
                console.log(self.auction);

                $(mainSelector).ready(function(){
                    StakeService.updateSingleAuction(mainSelector, self.auction, function(auction){
                        self.auction = auction;
                    });
                });
            }


            this.init = init;
            this.makeStake = StakeService.makeStake;
        }]);
})(window.appAuction);