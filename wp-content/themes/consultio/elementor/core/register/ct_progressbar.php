<?php

// Register Progress Bar Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_progressbar',
        'title' => esc_html__( 'Case Progress Bar', 'consultio' ),
        'icon' => 'eicon-skill-bar',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => array(
            'progressbar',
            'ct-progressbar-widget-js',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__( 'Source Settings', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'progressbar_list',
                            'label' => esc_html__( 'Progress Bar Lists', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__( 'Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'percent',
                                    'label' => esc_html__( 'Percentage', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SLIDER,
                                    'default' => [
                                        'size' => 50,
                                        'unit' => '%',
                                    ],
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Layout', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '1' => 'Layout 1',
                                '2' => 'Layout 2',
                                '3' => 'Layout 3',
                                '4' => 'Layout 4',
                                '5' => 'Layout 5',
                                '6' => 'Layout 6',
                                '7' => 'Layout 7',
                            ],
                            'default' => '1',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_title',
                    'label' => esc_html__( 'Style', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__( 'Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-progressbar .ct-progress-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'typography',
                            'label' => esc_html__( 'Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-progressbar .ct-progress-title',
                        ),
                        array(
                            'name' => 'percent_color',
                            'label' => esc_html__( 'Percentage Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-progressbar .ct-progress-percentage' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'percent_typography',
                            'label' => esc_html__( 'Percentage Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-progressbar .ct-progress-percentage',
                        ),
                        array(
                            'name' => 'bar_color',
                            'label' => esc_html__( 'Bar Color BG', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-progressbar .ct-progress-holder' => 'background-color: {{VALUE}};',
                            ],
                        ),

                        array(
                            'name' => 'bar_color_from',
                            'label' => esc_html__('Bar Color From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-progressbar' => '--gradient-color-from: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '7',
                            ],
                        ),
                        array(
                            'name' => 'bar_color_to',
                            'label' => esc_html__('Bar Color To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-progressbar' => '--gradient-color-to: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '7',
                            ],
                        ),

                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);