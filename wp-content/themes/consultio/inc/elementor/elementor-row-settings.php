<?php
// Add custom field to section
if(!function_exists('consultio_custom_section_params')){
    add_filter('ct-custom-section/custom-params', 'consultio_custom_section_params'); 
    function consultio_custom_section_params(){
        return array(
            'sections' => array(
                array(
                    'name'     => 'ct_row_settings',
                    'label'    => esc_html__( 'Case Settings', 'consultio' ),
                    'tab'      => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name'    => 'header_fixed_transparent',
                            'label'   => esc_html__( 'Header Fixed Transparent', 'consultio' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'options' => array(
                                'none'        => esc_html__( 'No', 'consultio' ),
                                'transparent'   => esc_html__( 'Yes', 'consultio' ),
                            ),
                            'prefix_class' => 'ct-header-fixed-',
                            'default'      => 'none',
                        ),
                        array(
                            'name'    => 'row_full_max_width',
                            'label'   => esc_html__( 'Row Max Width 1600px', 'consultio' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'options' => array(
                                'none'        => esc_html__( 'No', 'consultio' ),
                                'width'   => esc_html__( 'Yes', 'consultio' ),
                            ),
                            'prefix_class' => 'ct-row-max-',
                            'default'      => 'none',
                        ),
                        array(
                            'name'    => 'row_max_width',
                            'label'   => esc_html__( 'Row Max Width', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                            ],
                            'condition' => [
                                'row_full_max_width' => ['none'],
                            ],
                        ),
                    ),
                ),
            ),
        );
    }
}