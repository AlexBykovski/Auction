(function(appAuction) {
    'use strict';

    appAuction.directive("showCutImage",['$http', function($http){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                var targetImg = $('#' + attrs.targetImg);

                $(element).on("change", function(event){
                    var files = event.target.files;

                    if(!files.length){
                        return false;
                    }

                    var file = files[0];

                    var formdata = new FormData();

                    formdata.append("image", file);

                    $http({
                        method: 'POST',
                        data: formdata,
                        url: "/office/cut-image",
                        transformRequest: angular.identity,
                        headers: {'Content-Type': undefined}
                    }).then(
                        function successCallback(response) {
                            console.log(response);

                            targetImg.attr("src", "data:image/jpeg;base64," + response.data.result);
                        },
                        function errorCallback(response) {
                            console.error("error");

                        });
                });
            }
        };
    }]);

})(window.appAuction);
