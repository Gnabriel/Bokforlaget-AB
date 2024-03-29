(function ($) {
    var WidgetElements_ContentHandler = function ($scope, $) {
        var dcecontent = $scope.find('.dce-content');
        var dceunfold = $scope.find('.unfold-btn a');
        var dceunfoldfa = $scope.find('.unfold-btn i.fa');
        var elementSettings = get_Dyncontel_ElementSettings($scope);

        if (elementSettings.enable_unfold) {
            //alert(dcecontent.height());
            var originalHeightUnfold = dcecontent.outerHeight();
            var heightUnfold = elementSettings.height_content.size;
            //alert(heightUnfold+' '+originalHeightUnfold);
            //

            dcecontent.addClass('unfolded');

            if (originalHeightUnfold > heightUnfold) {
                //
                dceunfold.toggle(
                        function () {
                            dcecontent.height(originalHeightUnfold);
                            dceunfoldfa.removeClass('fa-plus-circle').addClass('fa-minus-circle');
                        }, function () {
                    dcecontent.height(heightUnfold);
                    dceunfoldfa.removeClass('fa-minus-circle').addClass('fa-plus-circle');
                }
                );
                /*dceunfold.click(function(){
                 dcecontent.toggleClass('unfold-open');
                 return false;
                 });*/
            } else {
                dcecontent.removeClass('unfolded').addClass('unfold-open');
                dceunfold.remove();
            }
        }
    };

    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-content.default', WidgetElements_ContentHandler);
    });
})(jQuery);
