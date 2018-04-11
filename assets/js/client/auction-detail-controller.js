(function(appAuction) {
    'use strict';

    appAuction.controller('AuctionDetailController', ['StakeService',
        function(StakeService) {
            var self = this;
            var mainSelector = ".product-auction-bet-form-with-autostake";
            var now = moment().subtract( "seconds", 1 );
            var $fp = $( ".bootstrap-datetimepicker-autostake" );
            var isHasAutoStake = false;

            moment.locale('ru');
            $fp.filthypillow( {
                enable24HourTime: true,
                minDateTime: function( ) {
                    return now;
                }
            } );

            $fp.on( "focus", function( ) {
                $fp.filthypillow( "show" );
            } );
            $fp.on( "fp:save", function( e, dateObj ) {
                $fp.val( dateObj.format( "HH:mm MM.DD.YYYY" ) );
                $fp.filthypillow( "hide" );
            } );

            this.auction = {};

            function init(auction, isHasAutoStakeFromServer){
                self.auction = angular.fromJson(auction);
                isHasAutoStake = isHasAutoStakeFromServer;

                $(mainSelector).ready(function(){
                    StakeService.updateSingleAuction(mainSelector, self.auction, function(auction){
                        self.auction = auction;

                        if(auction["hasAutoStake"]){
                            if(isHasAutoStake){
                                $(".count-stakes-autostake").val(auction["autoStakeStakes"]);
                            }
                            else{
                                location.href = location.href;
                            }
                        }
                    });
                });
            }

            function createOrCancelAutostake(){
                if(isHasAutoStake){
                    StakeService.removeAutoStake(self.auction["id"]);
                }
                else{
                    $(".autostake-settings").show();
                }
            }


            this.init = init;
            this.makeStake = StakeService.makeStake;
            this.createOrCancelAutostake = createOrCancelAutostake;
        }]);
})(window.appAuction);