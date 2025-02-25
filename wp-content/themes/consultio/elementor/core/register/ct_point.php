<?php
// Register Point Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_point',
        'title' => esc_html__('Case Point', 'consultio' ),
        'icon' => 'eicon-cursor-move',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'content_section',
                    'label' => esc_html__('Source Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'bg_image',
                            'label' => esc_html__( 'Background Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'item_list',
                            'label' => esc_html__('Items', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'icon',
                                    'label' => esc_html__( 'Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                ),
                                array(
                                    'name' => 'phone',
                                    'label' => esc_html__('Phone', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'email',
                                    'label' => esc_html__('Email', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'address',
                                    'label' => esc_html__('Address', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'top_positioon',
                                    'label' => esc_html__('Top Position', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SLIDER,
                                    'size_units' => [ '%' ],
                                    'default' => [
                                        'size' => 0,
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
                                    'size_units' => [ '%' ],
                                    'default' => [
                                        'size' => 0,
                                    ],
                                    'range' => [
                                        '%' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                    ],
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