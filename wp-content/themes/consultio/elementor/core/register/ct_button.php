<?php
// Register Button Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_button',
        'title' => esc_html__('Case Button', 'consultio' ),
        'icon' => 'eicon-button',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
            'ct-inline-css-js',
        ],
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
                            'default' => 'btn-default',
                            'options' => [
                                'btn-default' => esc_html__('Default', 'consultio' ),
                                'btn-effect' => esc_html__('Default Effect 1', 'consultio' ),
                                'btn-effect2' => esc_html__('Default Effect 2', 'consultio' ),
                                'btn-primary' => esc_html__('Primary', 'consultio' ),
                                'btn-primary hover-white' => esc_html__('Primary Hover White', 'consultio' ),
                                'btn-secondary' => esc_html__('Secondary', 'consultio' ),
                                'btn-secondary2' => esc_html__('Secondary Hover Two', 'consultio' ),
                                'btn-third' => esc_html__('Third One', 'consultio' ),
                                'btn-third2' => esc_html__('Third Two', 'consultio' ),
                                'btn-white' => esc_html__('White', 'consultio' ),
                                'btn-white2' => esc_html__('White 2', 'consultio' ),
                                'btn-round' => esc_html__('Round 1', 'consultio' ),
                                'btn-round2' => esc_html__('Round 2', 'consultio' ),
                                'btn-round3' => esc_html__('Round 3', 'consultio' ),
                                'text-white' => esc_html__('Text White', 'consultio' ),
                                'line-white' => esc_html__('Line White 1', 'consultio' ),
                                'line-white2' => esc_html__('Line White 2', 'consultio' ),
                                'line-dark1' => esc_html__('Line Dark 1', 'consultio' ),
                                'btn-hover-outline' => esc_html__('Hover Outline', 'consultio' ),
                                'btn-arrow2' => esc_html__('Arrow', 'consultio' ),
                                'btn-outline-primary' => esc_html__('Outline Primary', 'consultio' ),
                                'btn-outline-secondary' => esc_html__('Outline Secondary', 'consultio' ),
                                'btn-half-circle1' => esc_html__('Half Circle 1', 'consultio' ),
                                'rev-btn-animate1' => esc_html__('Line Animate 1', 'consultio' ),
                                'line-animate-left' => esc_html__('Line Animate Left', 'consultio' ),
                            ],
                        ),
                        array(
                            'name' => 'text',
                            'label' => esc_html__('Text', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => esc_html__('Click here', 'consultio'),
                            'placeholder' => esc_html__('Click here', 'consultio'),
                        ),
                        array(
                            'name' => 'link',
                            'label' => esc_html__('Link', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'placeholder' => esc_html__('https://your-link.com', 'consultio' ),
                            'default' => [
                                'url' => '#',
                            ],
                        ),
                        array(
                            'name' => 'align',
                            'label' => esc_html__('Alignment', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'left'    => [
                                    'title' => esc_html__('Case Left', 'consultio' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Case Center', 'consultio' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__('Case Right', 'consultio' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                                'justify' => [
                                    'title' => esc_html__('Case Justified', 'consultio' ),
                                    'icon' => 'fa fa-align-justify',
                                ],
                            ],
                            'prefix_class' => 'elementor-align-',
                            'default' => '',
                            'selectors'         => [
                                '{{WRAPPER}} .ct-button-wrapper' => 'text-align: {{VALUE}}',
                            ],
                        ),
                        array(
                            'name' => 'btn_padding',
                            'label' => esc_html__('Padding', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'control_type' => 'responsive',
                        ),
                        array(
                            'name' => 'btn_border_radius',
                            'label' => esc_html__('Border Radius', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'border_type',
                            'label' => esc_html__( 'Border Type', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__( 'None', 'consultio' ),
                                'solid' => esc_html__( 'Solid', 'consultio' ),
                                'double' => esc_html__( 'Double', 'consultio' ),
                                'dotted' => esc_html__( 'Dotted', 'consultio' ),
                                'dashed' => esc_html__( 'Dashed', 'consultio' ),
                                'groove' => esc_html__( 'Groove', 'consultio' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'border-style: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'border_width',
                            'label' => esc_html__( 'Border Width', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                            'responsive' => true,
                        ),
                        array(
                            'name' => 'border_color',
                            'label' => esc_html__( 'Border Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'border-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                        array(
                            'name' => 'border_color_hover',
                            'label' => esc_html__( 'Border Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn:hover' => 'border-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                        array(
                            'name' => 'typography',
                            'label' => esc_html__('Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-button-wrapper .btn',
                        ),
                        array(
                            'name' => 'btn_block',
                            'label' => esc_html__('Full Width', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'btn-inline-block',
                            'options' => [
                                'btn-inline-block' => 'No',
                                'btn-block' => 'Yes',
                            ],
                        ),
                        array(
                            'name' => 'btn_display',
                            'label' => esc_html__('Display', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'btn--inline',
                            'options' => [
                                'btn--inline' => 'Inline',
                                'btn--flex' => 'Inline Flex',
                            ],
                        ),
                        array(
                            'name' => 'btn_icon',
                            'label' => esc_html__('Icon', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'label_block' => true,
                            'fa4compatibility' => 'icon',
                        ),
                        array(
                            'name' => 'icon_align',
                            'label' => esc_html__('Icon Position', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'left',
                            'options' => [
                                'left' => esc_html__('Before', 'consultio' ),
                                'right' => esc_html__('After', 'consultio' ),
                            ],
                            'condition' => [
                                'btn_icon!' => '',
                            ],
                        ),
                        array(
                            'name' => 'icon_font_size',
                            'label' => esc_html__('Icon Size', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_space_left',
                            'label' => esc_html__('Icon Space', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn .ct-align-icon-right i' => 'margin-left: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_align' => 'right',
                            ],
                        ),
                        array(
                            'name' => 'icon_space_right',
                            'label' => esc_html__('Icon Space', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn .ct-align-icon-left i' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_align' => 'left',
                            ],
                        ),
                        array(
                            'name' => 'btn_bg_color',
                            'label' => esc_html__('Background Color Main', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'background: {{VALUE}} !important;',
                                '{{WRAPPER}} .ct-button-wrapper .btn.btn-white2 i' => 'color: {{VALUE}} !important;',
                                '{{WRAPPER}} .ct-button-wrapper .btn.btn-third i' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'btn_bg_color_gradient',
                            'label' => esc_html__('Background Color Gradient', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'style' => ['btn-default', 'btn-effect', 'btn-effect2'],
                            ],
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn i' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'icon_color_hover',
                            'label' => esc_html__('Icon Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn:hover i' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'btn_color',
                            'label' => esc_html__('Text Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'btn_bg_color_hover',
                            'label' => esc_html__('Background Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn:hover' => 'background: {{VALUE}} !important;',
                                '{{WRAPPER}} .ct-button-wrapper .btn.btn-white2:hover i' => 'color: {{VALUE}} !important;',
                                '{{WRAPPER}} .ct-button-wrapper .btn.btn-third:hover i' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'btn_color_hover',
                            'label' => esc_html__('Text Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-button-wrapper .btn:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name'         => 'box_sh_color',
                            'label' => esc_html__( 'Box Shadow', 'consultio' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .ct-button-wrapper .btn'
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
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);