<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_contact_info',
        'title' => esc_html__('Case Contact Info', 'consultio'),
        'icon' => 'eicon-info-circle-o',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'layout_section',
                    'label' => esc_html__('Layout', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'consultio' ),
                            'type' => Case_Theme_Core::LAYOUT_CONTROL,
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_contact_info/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_contact_info/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_contact_info/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_contact_info/layout-image/layout4.jpg'
                                ],
                            ],
                        ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                                'style3' => 'Style 3',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '1',
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
                    'name' => 'section_contact_info',
                    'label' => esc_html__('Contact Info', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'contact_info',
                            'label' => esc_html__('Info List', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'content',
                                    'label' => esc_html__('Content', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                ),
                                array(
                                    'name' => 'icon_type',
                                    'label' => esc_html__('Icon Type', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'icon' => 'Icon',
                                        'image' => 'Image',
                                    ],
                                    'default' => 'icon',
                                ),
                                array(
                                    'name' => 'ct_icon',
                                    'label' => esc_html__('Icon', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'condition' => [
                                        'icon_type' => 'icon',
                                    ],
                                ),
                                array(
                                    'name' => 'icon_image',
                                    'label' => esc_html__( 'Icon Image', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'description' => esc_html__('Select image icon.', 'consultio'),
                                    'condition' => [
                                        'icon_type' => 'image',
                                    ],
                                ),
                                array(
                                    'name' => 'icon_box_color',
                                    'label' => esc_html__('Icon Box Color', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                    'description' => 'Apply layout 2 & 4'
                                ),
                                array(
                                    'name' => 'link',
                                    'label' => esc_html__('Link', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::URL,
                                ),
                            ),
                            'title_field' => '{{{ content }}}',
                        ),
                        array(
                            'name' => 'wg_title',
                            'label' => esc_html__('Wedget Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['3'],
                            ],
                        ),

                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-contact-info .ct-contact-title' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => ['2','4'],
                            ],
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-contact-info .ct-contact-icon i' => 'color: {{VALUE}};',
                            ],
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => ['1','2','4'],
                            ],
                        ),
                        array(
                            'name' => 'icon_gradient_from',
                            'label' => esc_html__('Gradient From Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'icon_gradient_to',
                            'label' => esc_html__('Gradient To Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Content Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-contact-info, {{WRAPPER}} .ct-contact-info1.style2 .ct-contact-content' => 'color: {{VALUE}};',
                            ],
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => ['1','2','4'],
                            ],
                        ),
                        array(
                            'name' => 'content_typography',
                            'label' => esc_html__('Content Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-contact-info',
                            'condition' => [
                                'layout' => ['1','2','4'],
                            ],
                        ),
                        array(
                            'name' => 'item_space',
                            'label' => esc_html__('Item Spacer', 'consultio' ),
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
                                '{{WRAPPER}} .ct-contact-info1 li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);