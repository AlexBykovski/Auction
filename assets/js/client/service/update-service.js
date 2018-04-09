(function(appAuction) {
    'use strict';

    appAuction.factory('UpdateService', ['$http', function($http) {
        return {
            updateProducts: function(products, callback) {
                $http({
                    method: 'POST',
                    url: "/get-update-products",
                    data: angular.toJson(products)
                }).then(function (response) {
                    console.log("OK");
                    callback(response.data);
                }, function (response) {
                    console.error("error");
                });
            },
            updateSingleProduct: function(product, callback) {
                $http({
                    method: 'POST',
                    url: "/get-update-single-product",
                    data: angular.toJson(product)
                }).then(function (response) {
                    console.log("OK");
                    callback(response.data);
                }, function (response) {
                    console.error("error");
                });
            }
        };
    }]);

})(window.appAuction);