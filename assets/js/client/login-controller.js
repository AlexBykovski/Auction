(function(appAuction) {
    'use strict';

    appAuction.controller('LoginController', ['$scope', '$rootScope', '$http', '$sce', function($scope, $rootScope, $http, $sce) {
        var self = this;
        var method = "login";
        var loginSelector = "#login-form";
        this.loginForm = "";
        this.loginRegisterForm = "";

        this.init = function(passMethod){
            method = passMethod;
        };

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
                setForm(response.data);

                if(method === "login") {
                    openPopup();
                }


                $(loginSelector).ready(function(){

                    var formEvents = $.data($(this).get(0), 'events');
                    var isExistSubmitHandler = !!(formEvents && formEvents.submit);

                    if(!isExistSubmitHandler){
                        $(loginSelector).submit(function(e) {
                            e.preventDefault();
                            var data = $(loginSelector).serialize();

                            angular.element(loginSelector).find("button[type=submit]").prop("disabled", true);

                            request("/login-user", data, function (response) {
                                if(response.data.success){
                                    $rootScope.$broadcast('user-logged-in', {user: response.data.user});
                                    closePopup();
                                    self.loginForm = "";

                                    return true;
                                }
                                else{
                                    setForm(response.data);
                                }

                                $(loginSelector).find("button[type=submit]").prop("disabled", false);
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

        function setForm(data){
            if(method === "login"){
                self.loginForm = $sce.trustAsHtml(data);
                self.loginRegisterForm = "";
            }
            else{
                self.loginForm = "";
                self.loginRegisterForm = $sce.trustAsHtml(data);
            }
        }

        $rootScope.$on('open-login-modal', function(){
            if(method === "login") {
                showLoginPopup();
            }
        });

        $rootScope.$on('open-registration-modal', function(){
            if(method === "registration") {
                showLoginPopup();
            }
        });

    }]);
})(window.appAuction);