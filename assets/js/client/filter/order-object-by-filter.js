(function(appAuction) {
    'use strict';

    appAuction.filter('orderObjectBy', function() {
        return function(items, field, reverse, returnObject, key) {
            var filtered = [];
            var filteredObject = {};

            angular.forEach(items, function(item) {
                filtered.push(item);
            });

            filtered.sort(function (a, b) {
                return (a[field] > b[field] ? 1 : -1);
            });

            if(reverse) filtered.reverse();

            if(returnObject){
                angular.forEach(filtered, function(item) {
                    filteredObject['"' + item[key] + '"'] = item;
                });

                return filteredObject;
            }

            return filtered;
        };
    });

})(window.appAuction);