(function(appAuction) {
    'use strict';

    appAuction.controller('RegistrationController', ['$scope', '$rootScope', '$http', '$sce', function($scope, $rootScope, $http, $sce) {
        var self = this;
        var registrationSelector = "#registration-form";
        var baseUrl = "/registration";
        this.registrationForm = "";

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

        function showRegisterPopup() {
            openPopup();

            request(baseUrl, null, function (response) {
                self.registrationForm = $sce.trustAsHtml(response.data);

                handleRegistration();

            });
        }

        function handleRegistration(){
            $(registrationSelector).ready(function(){

                var formEvents = $.data($(this).get(0), 'events');
                var isExistSubmitHandler = !!(formEvents && formEvents.submit);

                if(!isExistSubmitHandler){
                    $(registrationSelector).off().on("submit", function(e) {
                        e.preventDefault();
                        var data = $(registrationSelector).serialize();
                        var url = $rootScope.referralCode ? baseUrl + "?ref=" + $rootScope.referralCode : baseUrl;

                        angular.element(registrationSelector).find("button[type=submit]").prop("disabled", true);

                        request(url, data, function (response) {
                            if(response.data.success){
                                $rootScope.$broadcast('user-logged-in', {user: response.data.user});
                                closePopup();

                                return true;
                            }
                            else{
                                self.registrationForm = $sce.trustAsHtml(response.data);
                            }

                            $(registrationSelector).find("button[type=submit]").prop("disabled", false);

                            handleRegistration();
                        });

                        return false;
                    });
                }
            });
        }

        function openPopup(){
            $.magnificPopup.open({
                items: {
                    src: "#register-modal"
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

        $rootScope.$on('open-registration-modal', function(){
            showRegisterPopup();
        });

    }]);
})(window.appAuction);