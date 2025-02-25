<?php

// Register Contact Form 7 Widget
if(class_exists('WPCF7')) {
    $cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');

    $contact_forms = array();
    if ($cf7) {
        foreach ($cf7 as $cform) {
            $contact_forms[$cform->ID] = $cform->post_title;
        }
    } else {
        $contact_forms[esc_html__('No contact forms found', 'consultio')] = 0;
    }


    ct_add_custom_widget(
        array(
            'name' => 'ct_ctf7',
            'title' => esc_html__('Case Contact Form 7', 'consultio'),
            'icon' => 'eicon-form-horizontal',
            'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
            'scripts' => array(),
            'params' => array(
                'sections' => array(
                    array(
                        'name' => 'source_section',
                        'label' => esc_html__('Source Settings', 'consultio'),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                        'controls' => array(
                            array(
                                'name' => 'ctf7_id',
                                'label' => esc_html__('Select Form', 'consultio'),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => $contact_forms,
                            ),
                            array(
                                'name' => 'style_l1',
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
                                    'style8' => 'Style 8',
                                    'style9' => 'Style 9',
                                    'style10' => 'Style 10',
                                    'style11' => 'Style 11',
                                    'style12' => 'Style 12 (Immigration)',
                                    'style13' => 'Style 13 (Finance)',
                                    'style14' => 'Style 14',
                                    'style15' => 'Style 15',
                                    'style16' => 'Style 16 (Outline White)',
                                ],
                                'default' => 'style1',
                            ),
                            array(
                                'name' => 'title',
                                'label' => esc_html__('Title', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'label_block' => true,
                                'condition' => [
                                    'style_l1' => ['style7', 'style9', 'style11'],
                                ],
                            ),
                            array(
                                'name' => 'description',
                                'label' => esc_html__('Description', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::TEXTAREA,
                                'rows' => 10,
                                'show_label' => false,
                                'condition' => [
                                    'style_l1' => ['style7', 'style9'],
                                ],
                            ),
                            array(
                                'name' => 'image_left',
                                'label' => esc_html__('Image Left', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::MEDIA,
                                'condition' => [
                                    'style_l1' => ['style11'],
                                ],
                            ),
                            array(
                                'name' => 'image_right',
                                'label' => esc_html__('Image Right', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::MEDIA,
                                'condition' => [
                                    'style_l1' => ['style11'],
                                ],
                            ),
                            array(
                                'name' => 'ct_animate',
                                'label' => esc_html__('Case Animate', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => consultio_animate(),
                                'default' => '',
                            ),
                        ),
                    ),
                    array(
                        'name' => 'style_section',
                        'label' => esc_html__('Style Input', 'consultio'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'form_max_width',
                                'label' => esc_html__('Form Max Width', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form' => 'max-width: {{SIZE}}{{UNIT}};',
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
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-style: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'border_width',
                                'label' => esc_html__( 'Border Width', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'selectors' => [
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-color: {{VALUE}} !important;',
                                ],
                                'condition' => [
                                    'border_type!' => '',
                                ],
                            ),
                            array(
                                'name' => 'input_typography',
                                'label' => esc_html__('Input Typography', 'consultio' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)',
                            ),
                            array(
                                'name' => 'input_color',
                                'label' => esc_html__( 'Input Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'color: {{VALUE}} !important;',
                                    '{{WRAPPER}} .ct-contact-form-layout1 .nice-select::after' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'input_bg_color',
                                'label' => esc_html__( 'Input Background Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'background-color: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'input_border_color',
                                'label' => esc_html__( 'Input Border Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-color: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'input_border_radius',
                                'label' => esc_html__('Input Border Radius', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'input_padding',
                                'label' => esc_html__('Input Padding', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'padding-riht: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                                'placeholder' => [
                                    'top' => 'auto',
                                    'right' => '',
                                    'bottom' => 'auto',
                                    'left' => '',
                                ],
                                'allowed_dimensions' => 'horizontal',
                            ),
                            array(
                                'name'         => 'input_box_shadow',
                                'label' => esc_html__( 'Input Box Shadow', 'consultio' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)'
                            ),
                            array(
                                'name'         => 'input_box_shadow_focus',
                                'label' => esc_html__( 'Input Box Shadow Focus', 'consultio' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit):focus'
                            ),
                            array(
                                'name' => 'input_height',
                                'label' => esc_html__('Input Height', 'consultio' ),
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
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control:not(.wpcf7-submit)' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'input_spacer_bottom',
                                'label' => esc_html__('Spacer Bottom', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form .wpcf7-form-control-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'input_spacer_left',
                                'label' => esc_html__('Spacer Left', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form .row [class*="col-"]' => 'padding-left: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .ct-contact-form .row' => 'margin-left: -{{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'input_spacer_right',
                                'label' => esc_html__('Spacer right', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::SLIDER,
                                'control_type' => 'responsive',
                                'size_units' => [ 'px' ],
                                'range' => [
                                    'px' => [
                                        'min' => 0,
                                        'max' => 3000,
                                    ],
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form .row [class*="col-"]' => 'padding-right: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .ct-contact-form .row' => 'margin-right: -{{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'textarea_height',
                                'label' => esc_html__('Textarea Height', 'consultio' ),
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
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control.wpcf7-textarea' => 'height: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                            array(
                                'name' => 'textarea_padding_top',
                                'label' => esc_html__('Textarea Padding Top', 'consultio' ),
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
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-form .wpcf7-form-control.wpcf7-textarea' => 'padding-top: {{SIZE}}{{UNIT}};',
                                ],
                            ),
                        ),
                    ),
                    array(
                        'name' => 'style_section_button',
                        'label' => esc_html__('Style Button', 'consultio'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'btn_color',
                                'label' => esc_html__( 'Button Submit Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} {{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit' => 'color: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'btn_color_hover',
                                'label' => esc_html__( 'Button Submit Color Hover', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} {{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit:hover' => 'color: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'btn_bg_color',
                                'label' => esc_html__( 'Button Submit Background Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} {{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit' => 'background: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'btn_bg_color_hover',
                                'label' => esc_html__( 'Button Submit Background Color Hover', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} {{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit:hover' => 'background: {{VALUE}} !important;',
                                ],
                            ),
                            array(
                                'name' => 'button_typography',
                                'label' => esc_html__('Button Typography', 'consultio' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit',
                            ),
                            array(
                                'name'         => 'button_box_shadow',
                                'label' => esc_html__( 'Button Box Shadow', 'consultio' ),
                                'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                                'control_type' => 'group',
                                'selector'     => '{{SELECTOR}} .ct-contact-form-layout1 .wpcf7-submit'
                            ),
                            array(
                                'name' => 'button_padding',
                                'label' => esc_html__('Button Padding', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                'control_type' => 'responsive',
                            ),
                            array(
                                'name' => 'button_border_radius',
                                'label' => esc_html__('Button Border Radius', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px' ],
                                'selectors' => [
                                    '{{WRAPPER}} .ct-contact-form-layout1 .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                            ),
                        )
                    ), 
                    array(
                        'name' => 'style_section_extra',
                        'label' => esc_html__('Style Extra', 'consultio'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                        'controls' => array(
                            array(
                                'name' => 'label_color',
                                'label' => esc_html__( 'Label Color', 'consultio' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'default' => '',
                                'selectors' => [
                                    '{{SELECTOR}} .ct-contact-form-layout1 .input-filled label' => 'color: {{VALUE}} !important;',
                                ],
                            ),
                        )
                    ),
                ),
            ),
        ),
        get_template_directory() . '/elementor/core/widgets/'
    );
}