( function( $ ) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCTIlineHandler = function( $scope, $ ) {
    	
        setTimeout(function(){
            $('.elementor-top-section').each(function () {
                var _el_particle = $(this).find(".ct-particle-animate"),
                    _el_particle_remove = $(this).find(".elementor-widget-wrap .ct-particle-animate"),
                    _row_particle = _el_particle.parents(".elementor-container");
                _row_particle.before(_el_particle.clone());
                _el_particle_remove.remove();
            });
        }, 200);

    };

    // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ct_particle_animate.default', WidgetCTIlineHandler );
    } );
} )( jQuery );