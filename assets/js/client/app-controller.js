(function(appAuction) {
    'use strict';

    appAuction.controller('AppController', ['$rootScope', 'CurrentUserService', function($rootScope, CurrentUserService) {
        var self = this;

        this.init = function(user){
            if(!user){
                CurrentUserService.setUser(null);
            }
            else{
                CurrentUserService.setUser(angular.fromJson(user));
            }

            var urlParams = new URLSearchParams(window.location.search);

            if(urlParams.has('ref') && urlParams.get('ref')){
                $rootScope.referralCode = urlParams.get('ref');
            }
        };

        this.isUserLoggedIn = function(){
            return CurrentUserService.isUserLoggedIn();
        };

        this.getCurrentUser = function(){
            return CurrentUserService.getUser();
        };

        this.isCurrentUserByUserName = function(username){
            return self.isUserLoggedIn() && self.getCurrentUser().username === username;
        };

        $rootScope.$on('user-logged-in', function(event, args){
            CurrentUserService.setUser(args.user);
        });

        $rootScope.$on('update-user', function(event, args){
            CurrentUserService.setUser(args.user);
        });
    }]);
})(window.appAuction);