<?php
// Register Button Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_icon_hidden_sidebar',
        'title' => esc_html__('Case Icon Hidden Sidebar', 'consultio' ),
        'icon' => 'eicon-menu-bar',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source Settings', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-icon-hidden-sidebar span' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner .icon-line, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner::before, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner::after' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_color_hover',
                            'label' => esc_html__('Icon Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-icon-hidden-sidebar span:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover .icon-line, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover:before, {{WRAPPER}} .ct-icon-hidden-sidebar.style2 .item--inner:hover:after' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'wg_align',
                            'label' => esc_html__('Alignment', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'flex-start' => [
                                    'title' => esc_html__('Left', 'consultio' ),
                                    'icon' => 'fa fa-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Center', 'consultio' ),
                                    'icon' => 'fa fa-align-center',
                                ],
                                'flex-end' => [
                                    'title' => esc_html__('Right', 'consultio' ),
                                    'icon' => 'fa fa-align-right',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-icon-hidden-sidebar' => 'justify-content: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);