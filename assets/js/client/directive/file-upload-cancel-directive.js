(function(appAuction) {
    'use strict';

    //only for upload 1 file
    appAuction.directive("fileUploadCancel",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                var templateCancel = '<sup id="' + attrs.nameContainer + '-reset" style="color: red; font-size: 20px; cursor: pointer;">×</sup>';
                var nameContainer = $('#' + attrs.nameContainer);
                var selectorReset = '#' + attrs.nameContainer + '-reset';
                var elementJquery = $(element);

                elementJquery.on("change", function(event){
                    var files = event.target.files;

                    if(files.length > 0){
                        var description = files[0].name + templateCancel + '<br>';

                        nameContainer.html(description);

                        $(selectorReset).off("click").on("click", function(){
                            elementJquery.wrap('<form>').closest('form').get(0).reset();
                            elementJquery.unwrap();

                            elementJquery.trigger("change");
                        });
                    }
                    else{
                        nameContainer.html("");
                    }
                });
            }
        };
    }]);
    //<sup style="color: red;font-size: 20px;cursor:  pointer;">×</sup>
    //color: red;
    //font-size: 20px;
    //cursor: pointer;
})(window.appAuction);
