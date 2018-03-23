(function(appAuction) {
    'use strict';

    appAuction.controller('SecurityController', ['$scope', '$rootScope', function($scope, $rootScope) {
        function loginClick() {
            $rootScope.$broadcast('open-login-modal');
        }

        function registerClick(){
            $rootScope.$broadcast('open-registration-modal');
            //openPopup("#register-modal");
        }

        this.loginClick = loginClick;
        this.registerClick = registerClick;
    }]);
})(window.appAuction);