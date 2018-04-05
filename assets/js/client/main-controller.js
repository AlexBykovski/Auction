(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$scope', '$http', '$interval', '$rootScope', 'UpdateService', 'StakeService',
        function($scope, $http, $interval, $rootScope, UpdateService, StakeService) {
        var self = this;
        this.currentAuctions = [];

        this.currentAuctions = [];
        var mainSelector = ".product-offers-list-main";

        function init(auctions){
            self.currentAuctions = angular.fromJson(auctions);

            $(mainSelector).ready(function(){
                StakeService.updateAuctions(mainSelector, self.currentAuctions, function(auctions){
                    self.currentAuctions = auctions;
                });
            });
        }


        this.init = init;
        this.makeStake = StakeService.makeStake;
    }]);
})(window.appAuction);