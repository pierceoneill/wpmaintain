<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_text_effect',
        'title' => esc_html__('Case Text Effect', 'consultio' ),
        'icon' => 'eicon-heading',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'ct_text',
                            'label' => esc_html__('Text', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ text }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'text_style',
                            'label' => esc_html__('Text Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'text-default' => 'Default',
                                'text-outline' => 'Outline',
                            ],
                            'default' => 'text-default',
                        ),
                        array(
                            'name' => 'text_color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-text-effect' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'text_style' => 'text-default',
                            ],
                        ),
                        array(
                            'name' => 'text_outline_color',
                            'label' => esc_html__('Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-text-effect.text-outline' => '-webkit-text-stroke-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'text_style' => 'text-outline',
                            ],
                        ),
                        array(
                            'name' => 'h_el',
                            'label' => esc_html__('Box Height', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-text-effect' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-text-effect',
                        ),
                        array(
                            'name' => 'text_effect',
                            'label' => esc_html__('Text Effect', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => 'None',
                                'ct-slide-to-left' => 'Slide Right To Left',
                                'ct-slide-to-right' => 'Slide Left To Right',
                            ],
                            'default' => '',
                        ),
                        array(
                            'name' => 'effect_speed',
                            'label' => esc_html__('Effect Speed', 'consultio'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'description' => 'Default: 18000 - Unit: ms',
                            'condition' => [
                                'text_effect' => ['ct-slide-to-left', 'ct-slide-to-right'],
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);