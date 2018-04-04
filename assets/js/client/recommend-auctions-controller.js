(function(appAuction) {
    'use strict';

    appAuction.controller('RecommendAuctionsController', ['StakeService',
        function(StakeService) {
            var self = this;
            this.auctions = [];
            var mainSelector = ".product-offers-list-recommend";

            function init(auctions){
                self.auctions = angular.fromJson(auctions);

                $(mainSelector).ready(function(){
                    StakeService.updateAuctions(mainSelector, self.auctions, function(auctions){
                        self.auctions = auctions;
                    });
                });
            }


            this.init = init;
            this.makeStake = StakeService.makeStake;
        }]);
})(window.appAuction);