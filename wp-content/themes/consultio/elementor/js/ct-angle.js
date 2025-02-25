( function( $ ) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCTIlineHandler = function( $scope, $ ) {

        $('.elementor-top-section').each(function () {
            var _el_angle = $(this).find(".ct-angle:not(.inner-container)"),
                _el_angle_remove = $(this).find(".elementor-column .ct-angle:not(.inner-container)"),
                _row_angle = _el_angle.parents(".elementor-container");
            _row_angle.before(_el_angle.clone());
            _el_angle_remove.remove();
        });

        $('.ct-angle.inner-container').parents('.elementor-element').addClass('ct-position-inherit');

    };

    // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ct_angle.default', WidgetCTIlineHandler );
    } );
} )( jQuery );