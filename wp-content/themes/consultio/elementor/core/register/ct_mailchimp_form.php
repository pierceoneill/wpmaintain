<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_mailchimp_form',
        'title' => esc_html__('Case Mailchimp Form', 'consultio'),
        'icon' => 'eicon-email-field',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'scripts' => array(),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Color Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
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
                            'selectors' => [
                                '{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields input' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'input_bg_color',
                            'label' => esc_html__('Input Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields input' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'button_bg_color',
                            'label' => esc_html__('Button Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form .mc4wp-form-fields:before' => 'background: {{VALUE}};',
                                '{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form' => '--gradient-color-from: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'button_bg_gradient',
                            'label' => esc_html__('Button Background Gradient', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-mailchimp.ct-mailchimp1 .mc4wp-form' => '--gradient-color-to: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'gradient_type',
                            'label' => esc_html__('Button Gradient Type', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'horizontal' => 'Horizontal',
                                'vertical' => 'Vertical',
                            ],
                            'default' => 'horizontal',
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);