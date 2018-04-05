(function(appAuction) {
    'use strict';

    appAuction.directive("notifyMessage",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                console.log("here");
                var message = attrs.message;
                var delay = attrs.delay ? attrs.delay : 3000;

                $.notify({message: message},
                    {
                        delay: delay,
                        placement: {
                            from: "top",
                            align: "center"
                        },
                        type: "success",
                    });
            }
        };
    }]);

})(window.appAuction);
