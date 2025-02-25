<?php
// Register Video Player Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_particle_animate',
        'title' => esc_html__('Case Particle Animate', 'consultio' ),
        'icon' => 'eicon-barcode',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
            'ct-elementor-js',
            'ct-inline-css-js',
        ],
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'content_section',
                    'label' => esc_html__('Source Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'content_list',
                            'label' => esc_html__('List', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'particle',
                                    'label' => esc_html__( 'Particle', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                ),
                                array(
                                    'name' => 'img_type',
                                    'label' => esc_html__('Image Type', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'img' => 'Image',
                                        'bg' => 'Background',
                                    ],
                                    'default' => 'img',
                                ),
                                array(
                                    'name' => 'bg_pos',
                                    'label' => esc_html__('Background Position', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'bg-image' => 'Center Center',
                                        'bg-top-left' => 'Top Left',
                                        'bg-top-right' => 'Top Right',
                                        'bg-top-center' => 'Top Center',
                                        'bg-bottom-left' => 'Bottom Left',
                                        'bg-bottom-right' => 'Bottom Right',
                                        'bg-bottom-center' => 'Bottom Center',
                                    ],
                                    'default' => 'bg-image',
                                    'condition' => [
                                        'img_type' => 'bg',
                                    ],
                                ),
                                array(
                                    'name' => 'particle_animate',
                                    'label' => esc_html__('Animate', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'shape-animate1' => 'Animate 1',
                                        'shape-animate2' => 'Animate 2',
                                        'shape-animate3' => 'Animate 3',
                                        'shape-animate4' => 'Animate 4',
                                        'shape-animate5' => 'Animate 5',
                                        'shape-animate6' => 'Animate 6',
                                        'shape-rotate-3d' => 'Rotate 3D',
                                        'shape-right-left' => 'Loop Right to Left',
                                        'shape-left-right' => 'Loop Left to Right',
                                        'shape-top-bottom' => 'Loop Top to Bottom',
                                        'shape-parallax' => 'Parallax',
                                        'animate-none' => 'None',
                                    ],
                                    'default' => 'shape-animate1',
                                ),
                                array(
                                    'name' => 'top_positioon',
                                    'label' => esc_html__('Top Position', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SLIDER,
                                    'size_units' => [ 'px', '%' ],
                                    'default' => [
                                        'size' => 0,
                                        'unit' => '%',
                                    ],
                                    'range' => [
                                        '%' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                    ],
                                ),
                                array(
                                    'name' => 'left_positioon',
                                    'label' => esc_html__('Left Position', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SLIDER,
                                    'size_units' => [ 'px', '%' ],
                                    'default' => [
                                        'size' => 0,
                                        'unit' => '%',
                                    ],
                                    'range' => [
                                        '%' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                    ],
                                ),
                                array(
                                    'name' => 'ct_animate',
                                    'label' => esc_html__('Case Animate', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => consultio_animate(),
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'ct_animate_delay',
                                    'label' => esc_html__('Animate Delay', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '0',
                                    'description' => 'Enter number. Default 0ms',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'particle_section',
                            'label' => esc_html__('Particle Section', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'outside' => 'Outside Column',
                                'inside' => 'Inside Column',
                            ],
                            'default' => 'outside',
                        ),
                        array(
                            'name' => 'hidde_md',
                            'label' => esc_html__('Hidden Screen Medium', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'hide-md' => 'Yes',
                                'show-all' => 'No',
                            ],
                            'default' => 'hide-md',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);