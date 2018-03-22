(function(appAuction) {
    'use strict';

    appAuction.controller('SecurityController', ['$scope', '$rootScope', function($scope, $rootScope) {
        function loginClick() {
            $rootScope.$broadcast('open-login-modal');
        }

        function registerClick(){
            openPopup("#register-modal");
        }

        function openPopup(id){
            $.magnificPopup.open({
                items: {
                    src: id
                },

                type: 'inline',

                fixedContentPos: false,
                fixedBgPos: true,

                overflowY: 'auto',

                closeBtnInside: true,
                preloader: false,

                removalDelay: 1000,

                mainClass: 'mfp-zoom-in'
            });
        }

        this.loginClick = loginClick;
        this.registerClick = registerClick;
    }]);
})(window.appAuction);