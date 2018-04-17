(function(appAuction) {
    'use strict';

    appAuction.controller('AutoStakeController', ['$rootScope', 'StakeService',
        function($rootScope, StakeService) {
            var self = this;
            var isUserLogged = false;
            var auctionId = 0;

            this.isRequestProcessing = false;
            this.isHasAutoStake = false;
            this.countStakes = 0;
            this.errorMessage = "";

            function init(isHasAutoStakeS, isUserLoggedS, auctionIdS){
                self.isHasAutoStake = isHasAutoStakeS;
                isUserLogged = isUserLoggedS;
                auctionId = auctionIdS;
            }

            //@@todo when remove autoStake - 2 request send: need to check
            function createOrCancelAutoStake(){
                //remove autostake
                if(self.isHasAutoStake && !self.isRequestProcessing){
                    self.isRequestProcessing = true;

                    return StakeService.removeAutoStake(auctionId, function(response){
                        self.isRequestProcessing = false;

                        if(response.data.success){
                            self.isHasAutoStake = false;
                            self.countStakes = 0;
                            $(".autostake-settings").hide();
                        }
                    });
                }

                //check autostake
                if(!isUserLogged){
                    $(".autostake-settings").show();

                    return self.errorMessage = "Для создания автоставки вам необходимо авторизоваться";
                }

                if(self.countStakes < 1){
                    $(".autostake-settings").show();

                    return self.errorMessage = "Укажите положительное количество ставок";
                }

                //create autoStake
                if(!self.isRequestProcessing) {
                    self.isRequestProcessing = true;
                    StakeService.createAutoStake(self.countStakes, auctionId, function (response) {
                        self.isRequestProcessing = false;

                        if (response.data.success) {
                            self.isHasAutoStake = true;
                            $(".autostake-settings").hide();

                            return self.errorMessage = "";
                        }
                        else {
                            self.isHasAutoStake = false;

                            return self.errorMessage = response.data.message;
                        }
                    });
                }
            }

            $rootScope.$on('change-autostake-count-stakes', function(event, args){
                self.countStakes = parseInt(args.count);

                if(self.countStakes === 0){
                    self.isHasAutoStake = false;
                    $(".autostake-settings").hide();
                }
            });

            $rootScope.$on('user-logged-in', function(event, args){
                isUserLogged = true;
                self.errorMessage = "";
            });

            this.init = init;
            this.createOrCancelAutoStake = createOrCancelAutoStake;

            document.getElementById("auto_stake_count").addEventListener("keypress", function (evt) {
                if (evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
        }]);
})(window.appAuction);