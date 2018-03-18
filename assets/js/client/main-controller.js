(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$scope', function($scope) {
        $scope.currentAuctions = [];

        function init(currentAuctions){
            $scope.currentAuctions = angular.fromJson(currentAuctions);
        }

        $scope.init = init;
    }]);
})(window.appAuction);