<?php

// Register Pie Charts Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_line_chart',
        'title' => esc_html__('Case Line Chart', 'consultio'),
        'icon' => 'eicon-time-line',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'scripts' => array(
            'chart-js',
            'ct-linecharts-widget-js',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_line_chart',
                    'label' => esc_html__('Piecharts', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'x_ax',
                            'label' => esc_html__('X-axis values', 'consultio'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'default' => '2020;2019;2018;2017;2016;2015',
                        ),
                        array(
                            'name' => 'values',
                            'label' => esc_html__('Values', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'y_ax',
                                    'label' => esc_html__('Y-axis values', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'border_color',
                                    'label' => esc_html__('Border Color', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                ),
                                array(
                                    'name' => 'bg_color',
                                    'label' => esc_html__('Background Color', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                ),
                            ),
                            'default' => [
                                [
                                    'title' => 'Business',
                                    'y_ax' => '5000;7500;4000;14000;9000;24000',
                                ],
                                [
                                    'title' => 'Finance',
                                    'y_ax' => '12500;11000;22000;17000;27000;26500',
                                ],
                                [
                                    'title' => 'Consulting',
                                    'y_ax' => '16000;19000;28000;24000;32000;36500',
                                ],
                            ],
                            'title_field' => '{{{ title }}}',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);