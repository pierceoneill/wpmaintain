<?php
// Register Icon Box Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_fancy_box',
        'title' => esc_html__('Case Fancy Box', 'consultio' ),
        'icon' => 'eicon-icon-box',
        'categories' => array( Case_Theme_Core::CT_CATEGORY_NAME ),
        'scripts' => [
            'ct-inline-css-js',
        ],
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
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout4.jpg'
                                ],
                                '5' => [
                                    'label' => esc_html__('Layout 5', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout5.jpg'
                                ],
                                '6' => [
                                    'label' => esc_html__('Layout 6', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout6.jpg'
                                ],
                                '7' => [
                                    'label' => esc_html__('Layout 7', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout7.jpg'
                                ],
                                '8' => [
                                    'label' => esc_html__('Layout 8', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout8.jpg'
                                ],
                                '9' => [
                                    'label' => esc_html__('Layout 9', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout9.jpg'
                                ],
                                '10' => [
                                    'label' => esc_html__('Layout 10', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout10.jpg'
                                ],
                                '11' => [
                                    'label' => esc_html__('Layout 11', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout11.jpg'
                                ],
                                '12' => [
                                    'label' => esc_html__('Layout 12', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout12.jpg'
                                ],
                                '13' => [
                                    'label' => esc_html__('Layout 13', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout13.jpg'
                                ],
                                '14' => [
                                    'label' => esc_html__('Layout 14', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout14.jpg'
                                ],
                                '15' => [
                                    'label' => esc_html__('Layout 15', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout15.jpg'
                                ],
                                '16' => [
                                    'label' => esc_html__('Layout 15', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout16.jpg'
                                ],
                                '17' => [
                                    'label' => esc_html__('Layout 17', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout17.jpg'
                                ],
                                '18' => [
                                    'label' => esc_html__('Layout 18', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout18.jpg'
                                ],
                                '19' => [
                                    'label' => esc_html__('Layout 19', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout19.jpg'
                                ],
                                '20' => [
                                    'label' => esc_html__('Layout 20', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout20.jpg'
                                ],
                                '21' => [
                                    'label' => esc_html__('Layout 21', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout21.jpg'
                                ],
                                '22' => [
                                    'label' => esc_html__('Layout 22', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_fancy_box/layout-image/layout22.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
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
                            'name' => 'selected_icon',
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
                            'name' => 'title_text',
                            'label' => esc_html__('Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__('Enter your title', 'consultio' ),
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'description_text',
                            'label' => esc_html__('Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'placeholder' => esc_html__('Enter your description', 'consultio' ),
                            'rows' => 10,
                            'show_label' => false,
                        ),
                        array(
                            'name' => 'list',
                            'label' => esc_html__('List', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                array(
                                    'name' => 'content',
                                    'label' => esc_html__('Content', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ content }}}',
                            'condition' => [
                                'layout' => '5',
                            ],
                        ),

                        array(
                            'name' => 'number',
                            'label' => esc_html__('Number', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['17', '19','22'],
                            ],
                        ),

                        array(
                            'name' => 'link',
                            'label' => esc_html__('Link', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'default' => [
                                'url' => '#',
                            ],
                            'condition' => [
                                'layout' => ['1','5','6','12','14','17'],
                            ],
                        ),

                        array(
                            'name' => 't_width',
                            'label' => esc_html__('Max Width', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),

                        array(
                            'name' => 'btn_text',
                            'label' => esc_html__('Button Text', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['7', '9', '10', '15', '16','18','22'],
                            ],
                        ),
                        array(
                            'name' => 'btn_link',
                            'label' => esc_html__('Button Link', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'default' => [
                                'url' => '#',
                            ],
                            'condition' => [
                                'layout' => ['7', '9', '10', '15', '16','18','22'],
                            ],
                        ),
                        array(
                            'name' => 'box_image',
                            'label' => esc_html__( 'Box Image', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'description' => esc_html__('Select box image.', 'consultio'),
                            'condition' => [
                                'layout' => ['7', '16', '17']
                            ],
                        ),
                        array(
                            'name' => 'style_l6',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2',
                            ],
                            'default' => 'style-1',
                            'condition' => [
                                'layout' => '6',
                            ],
                        ),
                        array(
                            'name' => 'main_color_from_l6',
                            'label' => esc_html__('Main Color Gradient From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => '6',
                                'style_l6' => 'style-2',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box' => '--gradient-color-from: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'main_color_to_l6',
                            'label' => esc_html__('Main Color Gradient To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => '6',
                                'style_l6' => 'style-2',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box' => '--gradient-color-to: {{VALUE}};',
                            ],
                        ),

                        array(
                            'name' => 'style_l9',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '9',
                            ],
                        ),
                        array(
                            'name' => 'style_l21',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '21',
                            ],
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
                            'condition' => [
                                'layout' => '9',
                            ],
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
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
                                'style8' => 'Style 8',
                                'style9' => 'Style 9',
                                'style10' => 'Style 10',
                                'style11' => 'Style 11',
                                'style12' => 'Style 12',
                                'style13' => 'Style 13',
                                'style14' => 'Style 14',
                                'style15' => 'Style 15',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '1',
                            ],
                        ),

                        array(
                            'name' => 'main_color_from',
                            'label' => esc_html__('Main Color From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box' => '--gradient-color-from: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '1',
                                'style' => 'style15',
                            ],
                        ),
                        array(
                            'name' => 'main_color_to',
                            'label' => esc_html__('Main Color To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box' => '--gradient-color-to: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '1',
                                'style' => 'style15',
                            ],
                        ),

                        array(
                            'name' => 'el_color1',
                            'label' => esc_html__('Icon Color Gradient From', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => ['5','20'],
                            ],
                        ),
                        array(
                            'name' => 'el_color2',
                            'label' => esc_html__('Icon Color Gradient To', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => ['5','20'],
                            ],
                        ),

                        array(
                            'name' => 'box_color_hover',
                            'label' => esc_html__('Box Color Hover', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => '5',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box.ct-fancy-box-layout5:before' => 'background-color: {{VALUE}} !important; background-image: none !important;',
                            ],
                        ),

                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box .item--icon i' => 'color: {{VALUE}};background-image: none;text-fill-color: inherit;-webkit-text-fill-color: inherit;',
                                '{{WRAPPER}} .ct-fancy-box .item--icon svg' => 'fill: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => ['1','2'],
                            ],
                        ),

                        array(
                            'name' => 'icon_font_size',
                            'label' => esc_html__('Icon Font Size', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_type' => ['icon'],
                            ],
                        ),

                        array(
                            'name' => 'icon_img_max_height',
                            'label' => esc_html__('Icon Max Height', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon img' => 'max-height: {{SIZE}}{{UNIT}} !important;',
                            ],
                            'condition' => [
                                'icon_type' => ['image'],
                            ],
                        ),

                        array(
                            'name' => 'icon_spacer_top',
                            'label' => esc_html__('Icon Spacer Top', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                                'style' => ['style2'],
                            ],
                        ),

                        array(
                            'name' => 'icon_spacer_right',
                            'label' => esc_html__('Icon Spacer Right', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),

                        array(
                            'name' => 'icon_box_width',
                            'label' => esc_html__('Icon Box Width', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),

                        array(
                            'name' => 'icon_box_height',
                            'label' => esc_html__('Icon Box Height', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),

                        array(
                            'name' => 'icon_space_right',
                            'label' => esc_html__('Icon Right Spacer', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style8'],
                            ],
                        ),

                        array(
                            'name' => 'icon_space_top',
                            'label' => esc_html__('Icon Top Spacer', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--icon' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style8'],
                            ],
                        ),

                        array(
                            'name' => 'border_icon_color',
                            'label' => esc_html__('Icon Border Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box-layout3 .item--icon' => 'border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '3',
                                'style_l3' => 'style1',
                            ],
                        ),
                        array(
                            'name' => 'bg_icon_color',
                            'label' => esc_html__('Icon Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box-layout3 .item--icon' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '3',
                                'style_l3' => 'style1',
                            ],
                        ),

                        array(
                            'name' => 'style_l3',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '3',
                            ],
                        ),
                        array(
                            'name' => 'el_main_color',
                            'label' => esc_html__('Main Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => '15',
                            ],
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box .item--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-fancy-box .item--title',
                        ),
                        array(
                            'name' => 'title_space_top',
                            'label' => esc_html__('Title Top Spacer', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--title' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'title_space_bottom',
                            'label' => esc_html__('Title Bottom Spacer', 'consultio' ),
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
                                '{{WRAPPER}} .ct-fancy-box .item--title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ),

                        array(
                            'name' => 'ct_animate_t',
                            'label' => esc_html__('Case Animate Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                            'condition' => [
                                'layout' => ['19'],
                            ],
                        ),
                        array(
                            'name' => 'ct_animate_delay_t',
                            'label' => esc_html__('Animate Delay Title', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '0',
                            'description' => 'Enter number. Default 0ms',
                            'condition' => [
                                'layout' => ['19'],
                            ],
                        ),

                        array(
                            'name' => 'desc_color',
                            'label' => esc_html__('Description Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box .item--description' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'desc_typography',
                            'label' => esc_html__('Description Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-fancy-box .item--description',
                        ),
                        array(
                            'name' => 'ct_animate_d',
                            'label' => esc_html__('Case Animate Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                            'condition' => [
                                'layout' => ['19'],
                            ],
                        ),
                        array(
                            'name' => 'ct_animate_delay_d',
                            'label' => esc_html__('Animate Delay Description', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '0',
                            'description' => 'Enter number. Default 0ms',
                            'condition' => [
                                'layout' => ['19'],
                            ],
                        ),
                        array(
                            'name' => 'box_bg_color',
                            'label' => esc_html__('Box Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box-layout5 .ct-fancy-box-inner' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .ct-fancy-box-layout2:before' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => ['2','5'],
                            ],
                        ),
                        array(
                            'name' => 'box_bg_color_l8',
                            'label' => esc_html__('Box Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'condition' => [
                                'layout' => '8',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box-layout8' => 'background-color: {{VALUE}};',
                            ],
                        ),

                        
                        array(
                            'name' => 'box_color_l13',
                            'label' => esc_html__('Box Background Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-fancy-box-layout13' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '13',
                            ],
                        ),

                        array(
                            'name'         => 'box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'consultio' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .ct-fancy-box-layout15',
                            'condition' => [
                                'layout' => '15',
                            ],
                        ),

                        array(
                            'name'         => 'box_shadow_hover',
                            'label' => esc_html__( 'Box Shadow', 'consultio' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .ct-fancy-box-layout15:before',
                            'condition' => [
                                'layout' => '15',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);