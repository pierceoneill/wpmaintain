<?php
// Register Particles Box Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_particles',
        'title' => esc_html__('Case Particles V2', 'consultio' ),
        'icon' => 'eicon-barcode',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
            'lib-particles-js',
            'ct-particles-js',
        ],
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'content_section',
                    'label' => esc_html__('Content', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2',
                            ],
                            'default' => 'style-1',
                        ),
                        array(
                            'name' => 'number',
                            'label' => esc_html__('Number', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__('Enter number. Default: 60', 'consultio' ),
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'size',
                            'label' => esc_html__('Size', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__('Enter number. Default: 3', 'consultio' ),
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'size_random',
                            'label' => esc_html__('Size Random', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'true' => 'True',
                                'false' => 'False',
                            ],
                            'default' => 'false',
                        ),
                        array(
                            'name' => 'line_linked',
                            'label' => esc_html__('Line Linked', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'true' => 'True',
                                'false' => 'False',
                            ],
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'move_direction',
                            'label' => esc_html__('Move Direction', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'none'        => esc_html__( 'None', 'consultio' ),
                                'top'        => esc_html__( 'Top', 'consultio' ),
                                'top-right'        => esc_html__( 'Top Right', 'consultio' ),
                                'right'        => esc_html__( 'Right', 'consultio' ),
                                'bottom-right'        => esc_html__( 'Bottom Right', 'consultio' ),
                                'bottom'        => esc_html__( 'Bottom', 'consultio' ),
                                'bottom-left'        => esc_html__( 'Bottom Left', 'consultio' ),
                                'left'        => esc_html__( 'Left', 'consultio' ),
                                'top-left'        => esc_html__( 'Top Left', 'consultio' ),
                            ],
                            'default' => 'none',
                        ),
                        array(
                            'name' => 'particle_color_item',
                            'label' => esc_html__('Colors', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'particle_color',
                                    'label' => esc_html__('Color', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);