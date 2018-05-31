(function(appAuction) {
    'use strict';

    appAuction.controller('ForgotPasswordController', ['$scope', '$rootScope', '$sce', '$http', function($scope, $rootScope, $sce, $http) {
        var self = this;
        var isOpen = false;
        var forgotPasswordSelector = "#forgot-password-form";
        this.form = "";


        function showLoginPopup() {
            openPopup();

            request("/forgot-password", null, function (response) {
                setForm(response.data);

                handleForgotPassword();
            });
        }

        function handleForgotPassword(){
            $(forgotPasswordSelector).ready(function(){

                var formEvents = $.data($(this).get(0), 'events');
                var isExistSubmitHandler = !!(formEvents && formEvents.submit);

                if(!isExistSubmitHandler){
                    $(forgotPasswordSelector).off().on("submit", function(e) {
                        e.preventDefault();
                        var data = $(forgotPasswordSelector).serialize();

                        angular.element(forgotPasswordSelector).find("button[type=submit]").prop("disabled", true);

                        request("/forgot-password", data, function (response) {
                            if(response.data.success){
                                if(response.data.isRefresh){
                                    location.href = location.pathname;

                                    return true;
                                }

                                self.loginForm = "";

                                return true;
                            }
                            else{
                                setForm(response.data);
                            }

                             $(forgotPasswordSelector).find("button[type=submit]").prop("disabled", false);
                            handleForgotPassword();
                        });

                        return false;
                    });
                }
            });
        }

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

        function setForm(data){
            self.form = $sce.trustAsHtml(data);
            $("#send-forgot-password-again").show();
        }

        function openPopup(){
            $.magnificPopup.open({
                items: {
                    src: "#forgot-password-modal"
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

        $rootScope.$on('forgot-password-link-click', function(){
            if(!isOpen) {
                showLoginPopup();
            }
        });

        $("body").on("click", "#send-forgot-password-again", function(e){
            var form = $(this).parents("#forgot-password-form");

            $(form).find(".hidden-step-forgot-password").val(1);
            $(this).hide();

            $(form).submit();
        });
    }]);
})(window.appAuction);