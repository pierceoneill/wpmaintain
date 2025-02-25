<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_angle',
        'title' => esc_html__('Case Angle Row', 'consultio'),
        'icon' => 'eicon-filter',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'scripts' => [
            'ct-angle-js',
        ],
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'angle_layout',
                            'label' => esc_html__('Layout', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'layout1' => 'Layout 1',
                                'layout2' => 'Layout 2',
                            ],
                            'default' => 'layout1',
                        ),
                        array(
                            'name' => 'angle_color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}' => 'fill: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'angle_height',
                            'label' => esc_html__('Height', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'default' => [
                                'size' => 90,
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                        ),
                        array(
                            'name' => 'angle_position',
                            'label' => esc_html__('Position', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'top' => 'Top',
                                'top-reverse' => 'Top Reverse',
                                'bottom' => 'Bottom',
                            ],
                            'default' => 'bottom',
                        ),
                        array(
                            'name' => 'angle_offset',
                            'label' => esc_html__('Offset', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'default' => [
                                'size' => 0,
                            ],
                        ),
                        array(
                            'name' => 'responsive',
                            'label' => esc_html__('Responsive', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'lg' => 'Default',
                                'md' => 'Hide Tablet',
                                'sm' => 'Hide Mobile',
                            ],
                            'default' => 'lg',
                        ),

                        array(
                            'name' => 'el_container',
                            'label' => esc_html__('Container', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'parent-container' => 'Parent',
                                'inner-container' => 'Inner',
                            ],
                            'default' => 'parent-container',
                        ),

                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);