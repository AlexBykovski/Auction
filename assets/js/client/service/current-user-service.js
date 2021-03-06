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
        setCountStakes: function(count){
            return currentUser["stakes"];
        }
    };
}]);

})(window.appAuction);