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
        };

        this.isUserLoggedIn = function(){
            return CurrentUserService.isUserLoggedIn();
        };

        this.getCurrentUser = function(){
            return CurrentUserService.getUser();
        };

        $rootScope.$on('user-logged-in', function(event, args){
            CurrentUserService.setUser(args.user);
        });
    }]);
})(window.appAuction);