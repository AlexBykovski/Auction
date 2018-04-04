(function(appAuction) {
    'use strict';

    appAuction.controller('MyAuctionsController', ['StakeService',
        function(StakeService) {
            var self = this;
            var mainSelector = ".user-my-auctions-list";

            this.auctions = [];

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