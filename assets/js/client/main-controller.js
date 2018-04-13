(function(appAuction) {
    'use strict';

    appAuction.controller('MainController', ['$scope', '$http', '$interval', '$rootScope', 'UpdateService', 'StakeService',
        function($scope, $http, $interval, $rootScope, UpdateService, StakeService) {
        var self = this;
        var categories = [];
        var times = [];
        var allCategories = [];
        var allTimes = [];
        this.currentAuctions = [];

        this.currentAuctions = [];
        var mainSelector = ".product-offers-list-main";

        function init(auctions, categoriesS, timesS, countAllCategoriesS, countAllTimesS){
            self.currentAuctions = angular.fromJson(auctions);
            categories = angular.fromJson(categoriesS);
            times = angular.fromJson(timesS);
            allCategories = angular.fromJson(countAllCategoriesS);
            allTimes = angular.fromJson(countAllTimesS);

            if(categories.length === allCategories.length && categories.indexOf("all") < 0){
                categories.push("all")
            }

            if(times.length === allTimes.length && times.indexOf("all") < 0){
                times.push("all")
            }

            $(mainSelector).ready(function(){
                StakeService.updateAuctions(mainSelector, self.currentAuctions, function(auctions){
                    self.currentAuctions = auctions;
                });
            });
        }

        function isCheckedCategory(category){
            return categories.indexOf(category) > -1;
        }

        function isCheckedTime(time){
            return times.indexOf(time) > -1;
        }

        function checkCategory(category){
            var index = categories.indexOf(category);

            if(index > -1){
                if(category === "all"){
                    return categories = [];
                }

                if(categories.indexOf("all") > -1){
                    categories.splice(categories.indexOf("all"), 1);
                }

                return categories.splice(index, 1);
            }
            else{
                if(category === "all"){
                    categories = allCategories.slice(0);

                    return categories.push(category);
                }

                categories.push(category);

                if(categories.length === allCategories.length){
                    categories.push("all");
                }
            }
        }

        function checkTime(time){
            var index = times.indexOf(time);

            if(index > -1){
                if(time === "all"){
                    return times = [];
                }

                if(times.indexOf("all") > -1){
                    times.splice(times.indexOf("all"), 1);
                }

                return times.splice(index, 1);
            }
            else{
                if(time === "all"){
                    times = allTimes.slice(0);

                    return times.push(time);
                }

                times.push(time);

                if(times.length === allTimes.length){
                    times.push("all");
                }
            }
        }

        this.init = init;
        this.isCheckedCategory = isCheckedCategory;
        this.isCheckedTime = isCheckedTime;
        this.checkCategory = checkCategory;
        this.checkTime = checkTime;
        this.makeStake = StakeService.makeStake;
    }]);
})(window.appAuction);