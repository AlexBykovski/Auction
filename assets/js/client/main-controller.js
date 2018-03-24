(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$http', function($http) {
        var self = this;
        this.currentAuctions = [];

        function init(currentAuctions){
            self.currentAuctions = angular.fromJson(currentAuctions);
        }

        function makeStake(productId){
            $http({
                method: 'POST',
                url: "/make-manual-stake/" + productId
            }).then(function (response) {
                console.log("OK");
            }, function (response) {
                console.error("error");
            });
        }

        this.init = init;
        this.makeStake = makeStake;
    }]);
})(window.appAuction);