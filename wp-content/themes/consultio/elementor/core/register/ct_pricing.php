<?php
ct_add_custom_widget(
    array(
        'name' => 'ct_pricing',
        'title' => esc_html__('Case Pricing', 'consultio'),
        'icon' => 'eicon-settings',
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
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout4.jpg'
                                ],
                                '5' => [
                                    'label' => esc_html__('Layout 5', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout5.jpg'
                                ],
                                '6' => [
                                    'label' => esc_html__('Layout 6', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_pricing/layout-image/layout6.jpg'
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
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '1',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_list',
                    'label' => esc_html__('Content', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__('Enter your title', 'consultio' ),
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'description',
                            'label' => esc_html__('Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'placeholder' => esc_html__('Enter your description', 'consultio' ),
                            'rows' => 10,
                            'show_label' => false,
                            'condition' => [
                                'layout' => ['1','2','6'],
                            ],
                        ),
                        array(
                            'name' => 'recommended',
                            'label' => esc_html__('Recommended', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['1','2'],
                            ],
                        ),
                        array(
                            'name' => 'content_list',
                            'label' => esc_html__('Feature', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'condition' => [
                                'layout' => ['1','3','4','5','6']
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'content',
                                    'label' => esc_html__('Content', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'active',
                                    'label' => esc_html__('Active', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'no' => 'No',
                                        'yes' => 'Yes',
                                    ],
                                    'default' => 'no',
                                ),
                            ),
                            'title_field' => '{{{ content }}}',
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
                            'condition' => [
                                'layout' => ['2','4'],
                            ],
                        ),
                        array(
                            'name' => 'selected_icon',
                            'label' => esc_html__('Icon', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'condition' => [
                                'icon_type' => 'icon',
                                'layout' => ['2','4'],
                            ],
                        ),
                        array(
                            'name' => 'icon_image',
                            'label' => esc_html__( 'Icon Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'description' => esc_html__('Select image icon.', 'consultio'),
                            'condition' => [
                                'icon_type' => 'image',
                                'layout' => ['2','4'],
                            ],
                        ),
                        array(
                            'name' => 'feature_l2',
                            'label' => esc_html__('Feature', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'rows' => 10,
                            'show_label' => false,
                            'condition' => [
                                'layout' => '2',
                            ],
                        ),
                        array(
                            'name' => 'currency',
                            'label' => esc_html__('Currency', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['5','6'],
                            ],
                        ),
                        array(
                            'name' => 'price',
                            'label' => esc_html__('Price', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'price_constant',
                            'label' => esc_html__('Price Constant', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['5'],
                            ],
                        ),
                        array(
                            'name' => 'time',
                            'label' => esc_html__('Time', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['1', '3', '6'],
                            ],
                        ),
                        array(
                            'name' => 'button_text',
                            'label' => esc_html__('Button Text', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                        ),
                        array(
                            'name' => 'button_link',
                            'label' => esc_html__('Button Link', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::URL,
                        ),
                        array(
                            'name' => 'box_bg_color',
                            'label' => esc_html__('Box Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-pricing-layout2' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '2',
                            ],
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Content Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-pricing-layout2 .pricing-title' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-pricing-layout2 .pricing-description' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-pricing-layout2 .pricing-price' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .ct-pricing-layout2 .pricing-feature' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '2',
                            ],
                        ),

                        /* Layout 3 */
                        array(
                            'name' => 'title_color_l3',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-pricing-layout3 .pricing-title' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '3',
                            ],
                        ),

                        array(
                            'name' => 'price_color_l3',
                            'label' => esc_html__('Price Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-pricing-layout3 .pricing-price' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '3',
                            ],
                        ),

                        array(
                            'name' => 'content_color_l3',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-pricing-layout3 .pricing-feature' => 'color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '3',
                            ],
                        ),

                        array(
                            'name' => 'item_highlight',
                            'label' => esc_html__('Item Highlight', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'no' => 'No',
                                'yes' => 'Yes',
                            ],
                            'default' => 'no',
                            'condition' => [
                                'layout' => ['4','6'],
                            ],
                        ),

                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pricing-title',
                        ),

                        array(
                            'name' => 'price_typography',
                            'label' => esc_html__('Price Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pricing-price',
                        ),

                        array(
                            'name' => 'feature_typography',
                            'label' => esc_html__('Feature Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pricing-feature',
                        ),

                        array(
                            'name' => 'button_typography',
                            'label' => esc_html__('Button Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pricing-button a',
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