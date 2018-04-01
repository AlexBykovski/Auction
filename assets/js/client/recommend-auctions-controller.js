(function(appAuction) {
    'use strict';

    appAuction.controller('RecommendAuctionsController', ['StakeService',
        function(StakeService) {
            var self = this;
            this.auctions = [];

            function init(auctions){
                self.auctions = angular.fromJson(auctions);

                $(".product-offers-list").ready(function(){
                    StakeService.updateAuctions(self.auctions, function(auctions){
                        self.auctions = auctions;
                    });
                });
            }


            this.init = init;
            this.makeStake = StakeService.makeStake;
        }]);
})(window.appAuction);