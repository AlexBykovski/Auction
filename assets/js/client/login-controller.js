(function(appAuction) {
    'use strict';

    appAuction.controller('LoginController', ['$scope', '$rootScope', '$http', '$sce', function($scope, $rootScope, $http, $sce) {
        var self = this;

        function request(url, data, callback) {
            $http({
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                url: url,
                data: data
            }).then(function (response) {
                callback.call($scope, response);
            }, function (response) {
                console.log("error");
            });
        }

        function showLoginPopup() {
            request("/login-user", null, function (response) {
                self.loginForm = $sce.trustAsHtml(response.data);
                openPopup();


                $("#login-form").ready(function(){

                    var formEvents = $.data($(this).get(0), 'events');
                    var isExistSubmitHandler = !!(formEvents && formEvents.submit);
                    console.log(isExistSubmitHandler);

                    if(!isExistSubmitHandler){
                        $(this).submit(function(e) {
                            e.preventDefault();
                            var data = $("#login-form").serialize();

                            angular.element("#login-form").find("button[type=submit]").prop("disabled", true);

                            request("/login-user", data, function (response) {
                                if(response.data.success){
                                    $rootScope.$broadcast('user-logged-in', {user: response.data.user});
                                    closePopup();

                                    return true;
                                }
                                else{
                                    self.loginForm = $sce.trustAsHtml(response.data);
                                }

                                $('#login-form').find("button[type=submit]").prop("disabled", false);
                            });

                            return false;
                        });
                    }
                });

            });
        }

        function openPopup(){
            $.magnificPopup.open({
                items: {
                    src: "#login-modal"
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

        function closePopup(){
            $.magnificPopup.close();
        }

        $rootScope.$on('open-login-modal', function(){
            showLoginPopup();
        });

    }]);
})(window.appAuction);