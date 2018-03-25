(function(appAuction) {
    'use strict';

appAuction.factory('CurrentUserService', [function() {
    var currentUser = null;

    return {
        isUserLoggedIn: function() {
            return !!(currentUser && currentUser !== {});
        },
        setUser: function(user) {
            currentUser = user;
        },
        getUser: function(){
            return currentUser;
        },
        removeStakes: function(){
            return currentUser;
        }
    };
}]);

})(window.appAuction);