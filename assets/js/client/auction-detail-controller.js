(function(appAuction) {
    'use strict';

    appAuction.controller('AuctionDetailController', ['$rootScope', 'StakeService',
        function($rootScope, StakeService) {
            var self = this;
            var mainSelector = ".product-auction-bet-form-with-autostake";
            //var now = moment().subtract( "seconds", 1 );
            //var $fp = $( ".bootstrap-datetimepicker-autostake" );
            //
            //moment.locale('ru');
            //$fp.filthypillow( {
            //    enable24HourTime: true,
            //    minDateTime: function( ) {
            //        return now;
            //    }
            //} );
            //
            //$fp.on( "focus", function( ) {
            //    $fp.filthypillow( "show" );
            //} );
            //$fp.on( "fp:save", function( e, dateObj ) {
            //    $fp.val( dateObj.format( "HH:mm MM.DD.YYYY" ) );
            //    $fp.filthypillow( "hide" );
            //} );

            this.auction = {};

            function init(auction){
                self.auction = angular.fromJson(auction);

                $(mainSelector).ready(function(){
                    StakeService.updateSingleAuction(mainSelector, self.auction, function(auction){
                        self.auction = auction;

                        if(auction["hasAutoStake"]) {
                            $rootScope.$broadcast('change-autostake-count-stakes', {count: auction["autoStakeStakes"]});
                        }
                    });
                });
            }


            this.init = init;
            this.makeStake = StakeService.makeStake;
        }]);
})(window.appAuction);