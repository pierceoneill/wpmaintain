<?php
if(class_exists('Newsletter')) :
    ct_add_custom_widget(
        array(
            'name' => 'ct_newsletter',
            'title' => esc_html__('Case Newsletter', 'consultio'),
            'icon' => 'eicon-envelope',
            'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
            'params' => array(
                'sections' => array(
                    array(
                        'name' => 'source_section',
                        'label' => esc_html__('Color Settings', 'consultio'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'button_label',
                                'label' => esc_html__('Button Text', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                            ),
                            array(
                                'name' => 'email_label',
                                'label' => esc_html__('Email Placeholder', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                            ),
                            array(
                                'name' => 'style',
                                'label' => esc_html__('Style', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'style1' => 'Style 1',
                                    'style2' => 'Style 2',
                                    'style3' => 'Style 3',
                                    'style4' => 'Style 4',
                                    'style5' => 'Style 5',
                                    'style6' => 'Style 6',
                                    'style7' => 'Style 7',
                                ],
                                'default' => 'style1',
                            ),
                            array(
                                'name' => 'input_color',
                                'label' => esc_html__('Input Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'condition' => [
                                    'style' => 'style1',
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-newsletter1.style1 .tnp-field-email .tnp-email' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_bg_color',
                                'label' => esc_html__('Input Background Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'condition' => [
                                    'style' => 'style1',
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-newsletter1.style1 .tnp-field-email .tnp-email' => 'background-color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'button_color1',
                                'label' => esc_html__('Button Color 1', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'condition' => [
                                    'style' => ['style1', 'style3'],
                                ],
                            ),
                            array(
                                'name' => 'button_color2',
                                'label' => esc_html__('Button Color 2', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'condition' => [
                                    'style' => ['style1', 'style3'],
                                ],
                            ),
                            array(
                                'name' => 'color_gradient_type',
                                'label' => esc_html__('Gradient Type', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'horizontal' => 'Horizontal',
                                    'vertical' => 'Vertical',
                                ],
                                'default' => 'horizontal',
                                'condition' => [
                                    'style' => ['style1', 'style3'],
                                ],
                            ),

                            array(
                                'name' => 'sub_title',
                                'label' => esc_html__('Sub Title', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                                'condition' => [
                                    'style' => ['style5'],
                                ],
                            ),
                            array(
                                'name' => 'title',
                                'label' => esc_html__('Title', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                                'condition' => [
                                    'style' => ['style5', 'style6', 'style7'],
                                ],
                            ),
                            array(
                                'name' => 'desc',
                                'label' => esc_html__('Description', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXTAREA,
                                'label_block' => true,
                                'condition' => [
                                    'style' => ['style5', 'style6', 'style7'],
                                ],
                            ),
                            array(
                                'name' => 'image_bg_box',
                                'label' => esc_html__('Box Background Image', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::MEDIA,
                                'condition' => [
                                    'style' => ['style5', 'style7'],
                                ],
                            ),
                            array(
                                'name' => 'image_box',
                                'label' => esc_html__('Box Image', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::MEDIA,
                                'condition' => [
                                    'style' => ['style5'],
                                ],
                            ),
                            array(
                                'name' => 'input_border_radius',
                                'label' => esc_html__('Input Radius', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-newsletter .tnp-field-email .tnp-email' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'input_typography',
                                'label' => esc_html__('Input Typography', 'consultio' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .ct-newsletter .tnp-field-email .tnp-email',
                            ),
                            array(
                                'name' => 'btn_border_radius',
                                'label' => esc_html__('Border Radius', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-newsletter .tnp-field-button .tnp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ),
                        ),
                    ),
                ),
            ),
        ),
        get_template_directory() . '/elementor/core/widgets/'
    );
endif;