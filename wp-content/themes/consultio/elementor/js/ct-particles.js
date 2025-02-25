( function( $ ) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCTPartHandler = function( $scope, $ ) {
        
        setTimeout(function(){
            $('.elementor-top-section').each(function () {
                var _el_particle = $(this).find(".ct-particles-js"),
                    _el_particle_remove = $(this).find(".elementor-widget-wrap .ct-particles-js"),
                    _row_particle = _el_particle.parents(".elementor-container");
                _row_particle.before(_el_particle.clone());
                _el_particle_remove.remove();
            });
        }, 100);

        setTimeout(function() {
            $(".ct-particles-js").each(function() {
                particlesJS($(this).attr('id'), {
                  "particles": {
                    "number": {
                        "value": $(this).data('number'),
                    },
                    "color": {
                        "value": $(this).data('color')
                    },
                    "shape": {
                        "type": "circle",
                    },
                    "size": {
                        "value": $(this).data('size'),
                        "random": $(this).data('size-random'),
                    },
                    "line_linked": {
                        "enable": $(this).data('line-linked'),
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": $(this).data('move-direction'),
                        "random": true,
                        "out_mode": "out",
                    }
                  },
                  "retina_detect": true
                });
            });
        }, 800);

    };

    // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/ct_particles.default', WidgetCTPartHandler );
    } );
} )( jQuery );