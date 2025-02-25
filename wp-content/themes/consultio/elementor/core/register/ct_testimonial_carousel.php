<?php
$slides_to_show = range( 1, 10 );
$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

// Register Testimonial List Widget
ct_add_custom_widget(
    array(
        'name' => 'ct_testimonial_carousel',
        'title' => esc_html__('Case Testimonial Carousel', 'consultio'),
        'icon' => 'eicon-testimonial',
        'categories' => array(Case_Theme_Core::CT_CATEGORY_NAME),
        'scripts' => array(
            'jquery-slick',
            'ct-post-carousel-widget-js',
        ),
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
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout4.jpg'
                                ],
                                '5' => [
                                    'label' => esc_html__('Layout 5', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout5.jpg'
                                ],
                                '6' => [
                                    'label' => esc_html__('Layout 6', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout6.jpg'
                                ],
                                '7' => [
                                    'label' => esc_html__('Layout 7', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout7.jpg'
                                ],
                                '8' => [
                                    'label' => esc_html__('Layout 8', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout8.jpg'
                                ],
                                '9' => [
                                    'label' => esc_html__('Layout 9', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout9.jpg'
                                ],
                                '10' => [
                                    'label' => esc_html__('Layout 10', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout10.jpg'
                                ],
                                '11' => [
                                    'label' => esc_html__('Layout 11', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout11.jpg'
                                ],
                                '12' => [
                                    'label' => esc_html__('Layout 12', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout12.jpg'
                                ],
                                '13' => [
                                    'label' => esc_html__('Layout 13', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout13.jpg'
                                ],
                                '14' => [
                                    'label' => esc_html__('Layout 14', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout14.jpg'
                                ],
                                '15' => [
                                    'label' => esc_html__('Layout 15', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout15.jpg'
                                ],
                                '16' => [
                                    'label' => esc_html__('Layout 16', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout16.jpg'
                                ],
                                '17' => [
                                    'label' => esc_html__('Layout 17', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout17.jpg'
                                ],
                                '18' => [
                                    'label' => esc_html__('Layout 18', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout18.jpg'
                                ],
                                '19' => [
                                    'label' => esc_html__('Layout 19', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout19.jpg'
                                ],
                                '20' => [
                                    'label' => esc_html__('Layout 20', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout20.jpg'
                                ],
                                '21' => [
                                    'label' => esc_html__('Layout 21', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout21.jpg'
                                ],
                                '22' => [
                                    'label' => esc_html__('Layout 22', 'consultio' ),
                                    'image' => get_template_directory_uri() . '/elementor/templates/widgets/ct_testimonial_carousel/layout-image/layout22.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'content_list',
                    'label' => esc_html__('Testimonial', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'testimonial',
                            'label' => esc_html__('Add Item', 'consultio'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'image',
                                    'label' => esc_html__('Image', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                ),
                                array(
                                    'name' => 'logo',
                                    'label' => esc_html__('Logo', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'description' => 'Apply some layout.'
                                ),
                                array(
                                    'name' => 'sub_title',
                                    'label' => esc_html__('Sub Title', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'description' => 'Apply some layout.'
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'position',
                                    'label' => esc_html__('Position', 'consultio'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                ),
                                array(
                                    'name' => 'description',
                                    'label' => esc_html__('Description', 'consultio' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'rows' => 10,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'style_section',
                    'label' => esc_html__('Style', 'consultio' ),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array(
                        array(
                            'name' => 'style_l1',
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

                        array(
                            'name' => 'style_l12',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '12',
                            ],
                        ),

                        array(
                            'name' => 'style_l13',
                            'label' => esc_html__('Style', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                                'style2' => 'Style 2',
                            ],
                            'default' => 'style1',
                            'condition' => [
                                'layout' => '13',
                            ],
                        ),
                        array(
                            'name' => 'box_color',
                            'label' => esc_html__('Box Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-testimonial.ct-testimonial-carousel22' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '22',
                            ],
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-testimonial .item--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-testimonial .item--title',
                        ),
                        array(
                            'name' => 'position_color',
                            'label' => esc_html__('Position Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-testimonial .item--position' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'position_typography',
                            'label' => esc_html__('Position Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-testimonial .item--position',
                        ),
                        array(
                            'name' => 'description_color',
                            'label' => esc_html__('Description Color', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .ct-testimonial .item--description' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'description_typography',
                            'label' => esc_html__('Description Typography', 'consultio' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .ct-testimonial .item--description',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_carousel_settings',
                    'label' => esc_html__('Carousel Settings', 'consultio'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array(
                        array(
                            'name' => 'ct_animate',
                            'label' => esc_html__('Case Animate', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => consultio_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'col_xs',
                            'label' => esc_html__('Columns XS Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),
                        array(
                            'name' => 'col_sm',
                            'label' => esc_html__('Columns SM Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),
                        array(
                            'name' => 'col_md',
                            'label' => esc_html__('Columns MD Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),
                        array(
                            'name' => 'col_lg',
                            'label' => esc_html__('Columns LG Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),
                        array(
                            'name' => 'col_xl',
                            'label' => esc_html__('Columns XL Devices', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),

                        array(
                            'name' => 'slides_to_scroll',
                            'label' => esc_html__('Slides to scroll', 'consultio' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                            'condition' => [
                                'layout' => ["1","3","4","5","6","8","9","10","11","12","13","14","15","16","17","19","20","22"],
                            ]
                        ),
                        array(
                            'name' => 'arrows',
                            'label' => esc_html__('Show Arrows', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'dots',
                            'label' => esc_html__('Show Dots', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'pause_on_hover',
                            'label' => esc_html__('Pause on Hover', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'autoplay',
                            'label' => esc_html__('Autoplay', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'autoplay_speed',
                            'label' => esc_html__('Autoplay Speed', 'consultio'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 5000,
                            'condition' => [
                                'autoplay' => 'true'
                            ]
                        ),
                        array(
                            'name' => 'infinite',
                            'label' => esc_html__('Infinite Loop', 'consultio'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'speed',
                            'label' => esc_html__('Animation Speed', 'consultio'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 500,
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elementor/core/widgets/'
);